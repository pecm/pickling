<?php
declare(strict_types = 1);

namespace Pickling;

use Pickling\Channel\ChannelInterface;
use Pickling\Resource\Package;
use Pickling\Resource\PackageList;
use Pickling\Traits\HttpRequest;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;
use SimpleXMLElement;

final class Client {
  use HttpRequest;

  /**
   * @var \Pickling\Channel\ChannelInterface
   */
  private ChannelInterface $channel;

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
    $content = $this->sendRequest(
      'GET',
      sprintf(
        '%s/rest/p/packages.xml',
        $this->channel->getUrl()
      )
    );

    return new PackageList(new SimpleXmlElement($content));
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
