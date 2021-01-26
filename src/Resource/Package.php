<?php
declare(strict_types = 1);

namespace Pickling\Resource;

use Pickling\Channel\ChannelInterface;
use Pickling\Resource\Package\Info;
use Pickling\Resource\Package\Release;
use Pickling\Resource\Package\ReleaseList;
use Pickling\Traits\XmlParser;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;
use SimpleXMLElement;

final class Package {
  use XmlParser;

  /**
   * @var \Pickling\Channel\ChannelInterface
   */
  private ChannelInterface $channel;
  /**
   * @var \Psr\Http\Client\ClientInterface
   */
  private ClientInterface $httpClient;
  /**
   * @var \Psr\Http\Message\RequestFactoryInterface
   */
  private RequestFactoryInterface $requestFactory;
  /**
   * @var \Psr\Http\Message\StreamFactoryInterface
   */
  private StreamFactoryInterface $streamFactory;

  private string $packageName;

  public function __construct(
    ChannelInterface $channel,
    ClientInterface $httpClient,
    RequestFactoryInterface $requestFactory,
    StreamFactoryInterface $streamFactory,
    string $packageName
  ) {
    $this->channel        = $channel;
    $this->httpClient     = $httpClient;
    $this->requestFactory = $requestFactory;
    $this->streamFactory  = $streamFactory;
    $this->packageName    = $packageName;
  }

  public function getName(): string {
    return $this->packageName;
  }

  /**
   * Returns the package information
   *
   * @link https://pecl.php.net/rest/p/:packageName/info.xml
   * @link https://pear.php.net/rest/p/:packageName/info.xml
   */
  public function getInfo(): Info {
    $request = $this->requestFactory->createRequest(
      'GET',
      sprintf(
        '%s/rest/p/%s/info.xml',
        $this->channel->getUrl(),
        $this->packageName
      )
    );
    $response = $this->httpClient->sendRequest($request);
    if ($response->getStatusCode() !== 200) {
      throw new RuntimeException(
        $response->getReasonPhrase() ?? sprintf('Server Response Status Code: %d', $response->getStatusCode())
      );
    }

    $content = $response->getBody()->getContents();
    if ($content === '') {
      throw new RuntimeException('Response body is empty');
    }

    return new Info($this->parseXml($content));
  }

  /**
   * Returns the list of package releases
   *
   * @link https://pecl.php.net/rest/r/:packageName/allreleases.xml
   * @link https://pear.php.net/rest/r/:packageName/allreleases.xml
   */
  public function getReleaseList(): ReleaseList {
    $request = $this->requestFactory->createRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/allreleases.xml',
        $this->channel->getUrl(),
        $this->packageName
      )
    );
    $response = $this->httpClient->sendRequest($request);
    if ($response->getStatusCode() !== 200) {
      throw new RuntimeException(
        $response->getReasonPhrase() ?? sprintf('Server Response Status Code: %d', $response->getStatusCode())
      );
    }

    $content = $response->getBody()->getContents();
    if ($content === '') {
      throw new RuntimeException('Response body is empty');
    }

    return new ReleaseList($this->parseXml($content));
  }

  /**
   * Returns the latest version number
   */
  public function getLatestVersion(): string {
    $request = $this->requestFactory->createRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/latest.txt',
        $this->channel->getUrl(),
        $this->packageName
      )
    );
    $response = $this->httpClient->sendRequest($request);
    if ($response->getStatusCode() !== 200) {
      throw new RuntimeException(
        $response->getReasonPhrase() ?? sprintf('Server Response Status Code: %d', $response->getStatusCode())
      );
    }

    $content = $response->getBody()->getContents();
    if ($content === '') {
      throw new RuntimeException('Response body is empty');
    }

    return $content;
  }

  /**
   * Selects a specific release for release related operations
   */
  public function at(string $releaseNumber): Release {
    return new Release(
      $this->channel,
      $this->httpClient,
      $this->requestFactory,
      $this->streamFactory,
      $this->packageName,
      $releaseNumber
    );
  }
}
