<?php
declare(strict_types = 1);

namespace Pickling;

use Pickling\Channel\ChannelInterface;
use Pickling\Channel\PearChannel;
use Pickling\Channel\PeclChannel;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use PsrDiscovery\Discover;

final class Factory {
  public static function create(
    ChannelInterface $channel,
    ClientInterface $httpClient = null,
    RequestFactoryInterface $requestFactory = null,
    StreamFactoryInterface $streamFactory = null
  ): Client {
    return new Client(
      $channel,
      $httpClient ?: Discover::httpClient(),
      $requestFactory ?: Discover::httpRequestFactory(),
      $streamFactory ?: Discover::httpStreamFactory()
    );
  }
  public static function createPear(
    ClientInterface $httpClient = null,
    RequestFactoryInterface $requestFactory = null,
    StreamFactoryInterface $streamFactory = null
  ): Client {
    return self::create(
      new PearChannel(),
      $httpClient,
      $requestFactory,
      $streamFactory
    );
  }

  public static function createPecl(
    ClientInterface $httpClient = null,
    RequestFactoryInterface $requestFactory = null,
    StreamFactoryInterface $streamFactory = null
  ): Client {
    return self::create(
      new PeclChannel(),
      $httpClient,
      $requestFactory,
      $streamFactory
    );
  }
}
