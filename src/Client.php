<?php
declare(strict_types = 1);

namespace Pickling;

use Pickling\Channel\ChannelInterface;
use Pickling\Resource\Package;
use Pickling\Resource\PackageList;
use Pickling\Traits\XmlParser;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;
use SimpleXMLElement;

final class Client {
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

  public function __construct(
    ChannelInterface $channel,
    ClientInterface $httpClient,
    RequestFactoryInterface $requestFactory,
    StreamFactoryInterface $streamFactory
  ) {
    $this->channel        = $channel;
    $this->httpClient     = $httpClient;
    $this->requestFactory = $requestFactory;
    $this->streamFactory  = $streamFactory;
  }

  /**
   * Returns the list of packages from the underlying channel
   *
   * @link https://pecl.php.net/rest/p/packages.xml
   * @link https://pear.php.net/rest/p/packages.xml
   */
  public function getPackageList(): PackageList {
    $request = $this->requestFactory->createRequest(
      'GET',
      sprintf(
        '%s/rest/p/packages.xml',
        $this->channel->getUrl()
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

    return new PackageList($this->parseXml($content));
  }

  /**
   * Selects a specific package for package related operations
   */
  public function with(string $packageName): Package {
    return new Package(
      $this->channel,
      $this->httpClient,
      $this->requestFactory,
      $this->streamFactory,
      $packageName
    );
  }
}
