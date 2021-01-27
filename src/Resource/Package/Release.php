<?php
declare(strict_types = 1);

namespace Pickling\Resource\Package;

use Pickling\Channel\ChannelInterface;
use Pickling\Resource\Package\Release\Info;
use Pickling\Resource\Package\Release\Manifest;
use Pickling\Traits\HttpRequest;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use SimpleXMLElement;

final class Release {
  use HttpRequest;

  /**
   * @var \Pickling\Channel\ChannelInterface
   */
  private ChannelInterface $channel;

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
  public function getManifest(): Manifest {
    $content = $this->sendRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/package.%s.xml',
        $this->channel->getUrl(),
        $this->packageName,
        $this->releaseNumber
      )
    );

    return new Manifest(new SimpleXMLElement($content));
  }

  /**
   * Returns the release information
   *
   * @link https://pecl.php.net/rest/r/:packageName/:version.xml
   * @link https://pear.php.net/rest/r/:packageName/:version.xml
   */
  public function getInfo(): Info {
    $content = $this->sendRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/%s.xml',
        $this->channel->getUrl(),
        $this->packageName,
        $this->releaseNumber
      )
    );

    return new Info(new SimpleXMLElement($content));
  }
}
