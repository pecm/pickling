<?php
declare(strict_types = 1);

namespace Pickling;

use Pickling\Channel\ChannelInterface;
use Pickling\Resource\CategoryList;
use Pickling\Resource\Feed\Category as CategoryFeed;
use Pickling\Resource\Feed\News as LatestFeed;
use Pickling\Resource\Feed\Package as PackageFeed;
use Pickling\Resource\Feed\User as UserFeed;
use Pickling\Resource\Package;
use Pickling\Resource\PackageList;
use Pickling\Traits\HttpRequest;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
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

  public function getCategoryList(): CategoryList {
    $content = $this->sendRequest(
      'GET',
      sprintf(
        '%s/rest/c/categories.xml',
        $this->channel->getUrl()
      )
    );

    return new CategoryList(new SimpleXMLElement($content));
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

  public function getLatestFeed(): LatestFeed {
    $rss = $this->sendRequest(
      'GET',
      sprintf(
        '%s/feeds/latest.rss',
        $this->channel->getUrl()
      )
    );

    return new LatestFeed(new SimpleXMLElement($rss));
  }

  public function getCategoryFeed(string $categoryName): CategoryFeed {
    $rss = $this->sendRequest(
      'GET',
      sprintf(
        '%s/feeds/cat_%s.rss',
        $this->channel->getUrl(),
        $categoryName
      )
    );

    return new CategoryFeed(new SimpleXMLElement($rss), $categoryName);
  }

  public function getPackageFeed(string $packageName): PackageFeed {
    $rss = $this->sendRequest(
      'GET',
      sprintf(
        '%s/feeds/pkg_%s.rss',
        $this->channel->getUrl(),
        $packageName
      )
    );

    return new PackageFeed(new SimpleXMLElement($rss), $packageName);
  }

  public function getUserFeed(string $userName): UserFeed {
    $rss = $this->sendRequest(
      'GET',
      sprintf(
        '%s/feeds/user_%s.rss',
        $this->channel->getUrl(),
        $userName
      )
    );

    return new UserFeed(new SimpleXMLElement($rss), $userName);
  }
}
