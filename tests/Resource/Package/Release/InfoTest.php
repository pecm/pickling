<?php
declare(strict_types = 1);

namespace Pickling\Test\Resource\Package\Release;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Pickling\Resource\Package\Release\Info;
use SimpleXMLElement;

final class InfoTest extends TestCase {
  #[DataProvider('propertyGettersDataProvider')]
  public function testPropertyGetters(string $file, array $properties): void {
    $content = file_get_contents(__DIR__ . $file);
    $xml = new SimpleXMLElement($content);

    $info = new Info($xml);
    $this->assertSame($properties[0], $info->getPackageName());
    $this->assertSame($properties[1], $info->getChannel());
    $this->assertSame($properties[2], $info->getVersion());
    $this->assertSame($properties[3], $info->getStability());
    $this->assertSame($properties[4], $info->getLicense());
    $this->assertSame($properties[5], $info->getReleasingMaintainer());
    $this->assertSame($properties[6], $info->getSummary());
    $this->assertSame($properties[7], $info->getDescription());
    $this->assertSame($properties[8], $info->getReleaseDate());
    $this->assertSame($properties[9], $info->getReleaseNotes());
    $this->assertSame($properties[10], $info->getReleaseSize());
    $this->assertSame($properties[11], $info->getDownloadUri());
    $this->assertSame($properties[12], $info->getPackageLink());
  }

  public static function propertyGettersDataProvider(): array {
    return [
      [
        '/../../../Fixtures/amqp/1.10.2.xml',
        [
          'amqp',
          'pecl.php.net',
          '1.10.2',
          'stable',
          'PHP License',
          'lstrojny',
          'Communicate with any AMQP compliant server',
          'This extension can communicate with any AMQP spec 0-9-1 compatible server, such as RabbitMQ, OpenAMQP and Qpid, giving you the ability to create and delete exchanges and queues, as well as publish to any exchange and consume from any queue.',
          '2020-04-05 15:41:28',
          <<<EOL
- Windows build: avoid variable lengths arrays (Christoph M. Becker) (https://github.com/pdezwart/php-amqp/issues/368)

For a complete list of changes see:
https://github.com/pdezwart/php-amqp/compare/v1.10.1...v1.10.2
EOL,
          107350,
          'https://pecl.php.net/get/amqp-1.10.2',
          ''
        ]
      ],
      [
        '/../../../Fixtures/mongo/1.6.16.xml',
        [
          'mongo',
          'pecl.php.net',
          '1.6.16',
          'stable',
          'Apache License',
          'jmikola',
          'MongoDB database driver',
          'This package provides an interface for communicating with the MongoDB database in PHP.',
          '2017-09-05 13:42:06',
          "** Bug\n    * [PHP-1529] - undefined symbol: php_mongo_asn1_time_to_time_t",
          210341,
          'http://pecl.php.net/get/mongo-1.6.16',
          ''
        ]
      ],
      [
        '/../../../Fixtures/mongodb/1.9.0.xml',
        [
          'mongodb',
          'pecl.php.net',
          '1.9.0',
          'stable',
          'Apache License',
          'alcaeus',
          'MongoDB driver for PHP',
          <<<EOL
The purpose of this driver is to provide exceptionally thin glue between MongoDB
and PHP, implementing only fundamental and performance-critical components
necessary to build a fully-functional MongoDB driver.
EOL,
          '2020-11-25 12:18:18',
          <<<EOL
** Epic
    * [PHPC-1631] - Support PHP 8

** New Feature
    * [PHPC-1691] - Iterator implementation for MongoDB\Driver\Cursor

** Bug
    * [PHPC-1167] - executeBulkWrite() may leave dangling session pointer on BulkWrite object
    * [PHPC-1693] - Fix MongoDB\BSON\Regex::__construct() arginfo
    * [PHPC-1697] - Fix MongoDB\Driver\Command::__construct() arginfo
    * [PHPC-1698] - prep_tagsets may leak in convert_to_object
    * [PHPC-1700] - prep_tagsets may leak if calling method errors

** Improvement
    * [PHPC-479] - Print mongoc and libbson versions during configure
    * [PHPC-1699] - Ensure all zpp errors are proxied by InvalidArgumentException
    * [PHPC-1704] - Improve checks for built-in PHP extensions for Windows builds
    * [PHPC-1706] - AIX platforms shouldn't try linking with libresolv

** Task
    * [PHPC-169] - Test read and write concern inheritance
    * [PHPC-1652] - Add timestamp test with high-order bit set that's not 2^32-1
    * [PHPC-1653] - Resync BSON corpus spec tests
    * [PHPC-1655] - Add a bson corpus test with invalid type for \$code when \$scope is also present
    * [PHPC-1660] - Always refer to explicit version in PECL example for non-stable release notes
    * [PHPC-1689] - Allow driver to compile with PHP 8
    * [PHPC-1692] - Test suite fixes for PHP 8
    * [PHPC-1694] - Add PHP 8 nightly to Travis CI build matrix
    * [PHPC-1695] - Add PHP 8 to AppVeyor build matrix
EOL,
          1300408,
          'https://pecl.php.net/get/mongodb-1.9.0',
          ''
        ]
      ],
      [
        '/../../../Fixtures/parallel/0.8.0.xml',
        [
          'parallel',
          'pecl.php.net',
          '0.8.0',
          'beta',
          'PHP License',
          'remi',
          'Parallel concurrency API',
          'A succinct parallel concurrency API for PHP 7.',
          '2019-02-18 13:40:38',
          '- initial pecl release',
          23080,
          'https://pecl.php.net/get/parallel-0.8.0',
          ''
        ]
      ]
    ];
  }
}
