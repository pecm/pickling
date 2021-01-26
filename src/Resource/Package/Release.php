<?php
declare(strict_types = 1);

namespace Pickling\Resource\Package;

use Pickling\Channel\ChannelInterface;
use Pickling\Resource\Package;
use Pickling\Traits\XmlParser;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;
use SimpleXMLElement;

final class Release {
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
  private string $releaseNumber;

  public function __construct(
    ChannelInterface $channel,
    ClientInterface $httpClient,
    RequestFactoryInterface $requestFactory,
    StreamFactoryInterface $streamFactory,
    string $packageName,
    string $releaseNumber
  ) {
    $this->channel        = $channel;
    $this->httpClient     = $httpClient;
    $this->requestFactory = $requestFactory;
    $this->streamFactory  = $streamFactory;
    $this->packageName    = $packageName;
    $this->releaseNumber  = $releaseNumber;
  }

  public function getPackageName(): string {
    return $this->packageName;
  }

  public function getNumber(): string {
    return $this->releaseNumber;
  }

  /**
   * Returns the package manifest (package.xml) of a specific release
   *
   * @link https://pecl.php.net/rest/r/:packageName/package.:version.xml
   * @link https://pear.php.net/rest/r/:packageName/package.:version.xml
   */
  public function getManifest(): Release\Manifest {
    $request = $this->requestFactory->createRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/package.%s.xml',
        $this->channel->getUrl(),
        $this->packageName,
        $this->releaseNumber
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

    return new Release\Manifest($this->parseXml($content));
  }

  /**
   * Returns the release information
   *
   * @link https://pecl.php.net/rest/r/:packageName/:version.xml
   * @link https://pear.php.net/rest/r/:packageName/:version.xml
   */
  public function getInfo(): Release\Info {
    $request = $this->requestFactory->createRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/%s.xml',
        $this->channel->getUrl(),
        $this->packageName,
        $this->releaseNumber
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

    return new Release\Info($this->parseXml($content));
  }
}
