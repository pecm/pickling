# Pickling [![Maintainability](https://api.codeclimate.com/v1/badges/59811840be37c5b2e444/maintainability)](https://codeclimate.com/github/pecm/pickling/maintainability) [![Total Downloads](https://poser.pugx.org/pecm/pickling/downloads)](//packagist.org/packages/pecm/pickling)

Pickling is a simple yet powerful client for [PHP Extension Community Library](https://pecl.php.net/) and [PHP Extension
and Application Repository](https://pear.php.net/) REST APIs.

## Installation

At the core, Pickling works out-of-the-box with many HTTP Clients through [HTTPlug Discovery](https://packagist.org/packages/php-http/discovery).

In other words, it requires at least one package for each of the following implementations:

1. [psr/http-client-implementation](https://packagist.org/providers/psr/http-client-implementation)
2. [psr/http-factory-implementation](https://packagist.org/providers/psr/http-factory-implementation)
3. [psr/http-message-implementation](https://packagist.org/providers/psr/http-message-implementation)

To add Pickling to composer:

```bash
composer require pecm/pickling
```

A general suggestion for HTTP Client, using [kriswallsmith/buzz](https://packagist.org/packages/kriswallsmith/buzz)
and [nyholm/psr7](https://packagist.org/packages/nyholm/psr7):

```bash
composer require kriswallsmith/buzz nyholm/psr7
```

## Usage

More usage examples beyond the simple ones below can be found on the [examples/](examples/) folder.

**Factory Instantiation**

```php
// pecl client with standard options
$peclClient = Pickling\Factory::createPecl();

// pear client with standard options
$pearClient = Pickling\Factory::createPear();
```

**Customized Instantiation**

```php
// pecl client
$peclClient = new Pickling\Client(
  // a class that implements Pickling\Channel\ChannelInterface
  new Pickling\Channel\Pecl(),
  // a class that implements Psr\Http\Client\ClientInterface
  new Http\Client\Socket\Client(),
  // a class that implements Psr\Http\Message\RequestFactoryInterface
  new Nyholm\Psr7\Factory\Psr17Factory(),
  // a class that implements Psr\Http\Message\StreamFactoryInterface
  new Nyholm\Psr7\Factory\Psr17Factory()
);

// pear client
$pearClient = new Pickling\Client(
  // a class that implements Pickling\Channel\ChannelInterface
  new Pickling\Channel\Pear(),
  // a class that implements Psr\Http\Client\ClientInterface
  new Http\Client\Socket\Client(),
  // a class that implements Psr\Http\Message\RequestFactoryInterface
  new Nyholm\Psr7\Factory\Psr17Factory(),
  // a class that implements Psr\Http\Message\StreamFactoryInterface
  new Nyholm\Psr7\Factory\Psr17Factory()
);
```

### Client

**Get Package List**

```php
$peclClient->getPackageList();

Pickling\Resource\PackageList Object
(
  [channel:Pickling\Resource\PackageList:private] => "pecl.php.net"
  [list:Pickling\Resource\PackageList:private] => Array
  (
    [0] => "ahocorasick"
    // ...
    [408] => "zstd"
  )
)
```

### Package

**Get Package Release List**

```php
$peclClient->with('amqp')->getReleaseList();

Pickling\Resource\Package\ReleaseList Object
(
  [packageName:Pickling\Resource\Package\ReleaseList:private] => "amqp"
  [channel:Pickling\Resource\Package\ReleaseList:private] => "pecl.php.net"
  [list:Pickling\Resource\Package\ReleaseList:private] => Array
  (
    [0] => Pickling\Resource\Package\Release\Version Object
    (
      [number:Pickling\Resource\Package\Release\Version:private] => "1.10.2"
      [stability:Pickling\Resource\Package\Release\Version:private] => "stable"
    )
    // ...
    [41] => Pickling\Resource\Package\Release\Version Object
    (
      [number:Pickling\Resource\Package\Release\Version:private] => "0.1.0"
      [stability:Pickling\Resource\Package\Release\Version:private] => "beta"
    )
  )
)
```

**Get Package Latest Release**

```php
$peclClient->with('amqp')->getLatestVersion();

"1.10.2"
```

**Get Package Info**

```php
$peclClient->with('amqp')->getInfo();

Pickling\Resource\Package\Info Object
(
  [packageName:Pickling\Resource\Package\Info:private] => "amqp"
  [channel:Pickling\Resource\Package\Info:private] => "pecl.php.net"
  [category:Pickling\Resource\Package\Info:private] => "Networking"
  [license:Pickling\Resource\Package\Info:private] => "PHP License"
  [licenseUri:Pickling\Resource\Package\Info:private] => ""
  [summary:Pickling\Resource\Package\Info:private] => "Communicate with any AMQP compliant server"
  [description:Pickling\Resource\Package\Info:private] => "This extension can communicate with any AMQP spec 0-9-1 compatible server, such as RabbitMQ, OpenAMQP and Qpid, giving you the ability to create and delete exchanges and queues, as well as publish to any exchange and consume from any queue."
  [packageReleasesLocation:Pickling\Resource\Package\Info:private] => ""
  [parentPackage:Pickling\Resource\Package\Info:private] => ""
  [packageReplaceBy:Pickling\Resource\Package\Info:private] => ""
  [channelReplaceBy:Pickling\Resource\Package\Info:private] => ""
)
```

### Package Release

**Get Release Info**

```php
$peclClient->with('amqp')->at('1.10.2')->getInfo();

Pickling\Resource\Package\Release\Info Object
(
  [packageName:Pickling\Resource\Package\Release\Info:private] => "amqp"
  [channel:Pickling\Resource\Package\Release\Info:private] => "pecl.php.net"
  [version:Pickling\Resource\Package\Release\Info:private] => "1.10.2"
  [stability:Pickling\Resource\Package\Release\Info:private] => "stable"
  [license:Pickling\Resource\Package\Release\Info:private] => "PHP License"
  [releasingMaintainer:Pickling\Resource\Package\Release\Info:private] => "lstrojny"
  [summary:Pickling\Resource\Package\Release\Info:private] => "Communicate with any AMQP compliant server"
  [description:Pickling\Resource\Package\Release\Info:private] => "This extension can communicate with any AMQP spec 0-9-1 compatible server, such as RabbitMQ, OpenAMQP and Qpid, giving you the ability to create and delete exchanges and queues, as well as publish to any exchange and consume from any queue."
  [releaseDate:Pickling\Resource\Package\Release\Info:private] => "2020-04-05 15:41:28"
  [releaseNotes:Pickling\Resource\Package\Release\Info:private] => "- Windows build: avoid variable lengths arrays (Christoph M. Becker) (https://github.com/pdezwart/php-amqp/issues/368)

For a complete list of changes see:
https://github.com/pdezwart/php-amqp/compare/v1.10.1...v1.10.2"
  [releaseSize:Pickling\Resource\Package\Release\Info:private] => 107350
  [downloadUri:Pickling\Resource\Package\Release\Info:private] => "https://pecl.php.net/get/amqp-1.10.2"
  [packageLink:Pickling\Resource\Package\Release\Info:private] => ""
)
```

## License

This library is licensed under the [MIT License](LICENSE).
