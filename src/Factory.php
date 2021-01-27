<?php
declare(strict_types = 1);

namespace Pickling;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Pickling\Channel\ChannelInterface;
use Pickling\Channel\PearChannel;
use Pickling\Channel\PeclChannel;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class Factory {
  public static function create(
    ChannelInterface $channel,
    ClientInterface $httpClient = null,
    RequestFactoryInterface $requestFactory = null,
    StreamFactoryInterface $streamFactory = null
  ): Client {
    return new Client(
      $channel,
      $httpClient ?: Psr18ClientDiscovery::find(),
      $requestFactory ?: Psr17FactoryDiscovery::findRequestFactory(),
      $streamFactory ?: Psr17FactoryDiscovery::findStreamFactory()
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
