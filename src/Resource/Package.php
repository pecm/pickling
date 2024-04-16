<?php
declare(strict_types = 1);

namespace Pickling\Resource;

use Pickling\Channel\ChannelInterface;
use Pickling\Resource\Package\Info;
use Pickling\Resource\Package\Release;
use Pickling\Resource\Package\ReleaseList;
use Pickling\Traits\HttpRequest;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use SimpleXMLElement;

final class Package {
  use HttpRequest;

  /**
   * @var \Pickling\Channel\ChannelInterface
   */
  private ChannelInterface $channel;

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
    $content = $this->sendRequest(
      'GET',
      sprintf(
        '%s/rest/p/%s/info.xml',
        $this->channel->getUrl(),
        $this->packageName
      )
    );

    return new Info(new SimpleXMLElement($content));
  }

  /**
   * Returns the list of package releases
   *
   * @link https://pecl.php.net/rest/r/:packageName/allreleases.xml
   * @link https://pear.php.net/rest/r/:packageName/allreleases.xml
   */
  public function getReleaseList(): ReleaseList {
    $content = $this->sendRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/allreleases.xml',
        $this->channel->getUrl(),
        $this->packageName
      )
    );

    return new ReleaseList(new SimpleXMLElement($content));
  }

  /**
   * Returns the latest version number
   *
   * @link https://pecl.php.net/rest/r/:packageName/latest.txt
   * @link https://pear.php.net/rest/r/:packageName/latest.txt
   */
  public function getLatestVersion(): string {
    return $this->sendRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/latest.txt',
        $this->channel->getUrl(),
        $this->packageName
      )
    );
  }

  /**
   * Returns the latest stable version number
   *
   * @link https://pecl.php.net/rest/r/:packageName/stable.txt
   * @link https://pear.php.net/rest/r/:packageName/stable.txt
   */
  public function getStableVersion(): string {
    return $this->sendRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/stable.txt',
        $this->channel->getUrl(),
        $this->packageName
      )
    );
  }

  /**
   * Returns the latest beta version number
   *
   * @link https://pecl.php.net/rest/r/:packageName/beta.txt
   * @link https://pear.php.net/rest/r/:packageName/beta.txt
   */
  public function getBetaVersion(): string {
    return $this->sendRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/beta.txt',
        $this->channel->getUrl(),
        $this->packageName
      )
    );
  }

  /**
   * Returns the latest alpha version number
   *
   * @link https://pecl.php.net/rest/r/:packageName/alpha.txt
   * @link https://pear.php.net/rest/r/:packageName/alpha.txt
   */
  public function getAlphaVersion(): string {
    return $this->sendRequest(
      'GET',
      sprintf(
        '%s/rest/r/%s/alpha.txt',
        $this->channel->getUrl(),
        $this->packageName
      )
    );
  }

  /**
   * Selects a specific release for release related operations
   */
  public function at(string $releaseNumber): Release {
    // "magic" version numbers
    switch ($releaseNumber) {
      case 'latest':
        $releaseNumber = $this->getLatestVersion();
        break;
      case 'stable':
        $releaseNumber = $this->getStableVersion();
        break;
      case 'beta':
        $releaseNumber = $this->getBetaVersion();
        break;
      case 'alpha':
        $releaseNumber = $this->getAlphaVersion();
        break;
    }

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
