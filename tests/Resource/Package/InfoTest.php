<?php
declare(strict_types = 1);

namespace Pickling\Test\Resource\Package;

use PHPUnit\Framework\TestCase;
use Pickling\Resource\Package\Info;
use SimpleXMLElement;

final class InfoTest extends TestCase {
  /**
   * @dataProvider propertyGettersDataProvider
   */
  public function testPropertyGetters(string $file, array $properties): void {
    $content = file_get_contents(__DIR__ . $file);
    $xml = new SimpleXMLElement($content);

    $info = new Info($xml);
    $this->assertSame($properties[0], $info->getPackageName());
    $this->assertSame($properties[1], $info->getChannel());
    $this->assertSame($properties[2], $info->getCategory());
    $this->assertSame($properties[3], $info->getLicense());
    $this->assertSame($properties[4], $info->getLicenseUri());
    $this->assertSame($properties[5], $info->getSummary());
    $this->assertSame($properties[6], $info->getDescription());
    $this->assertSame($properties[7], $info->getPackageReleasesLocation());
    $this->assertSame($properties[8], $info->getParentPackage());
    $this->assertSame($properties[9], $info->getPackageReplaceBy());
    $this->assertSame($properties[10], $info->getChannelReplaceBy());
  }

  public function propertyGettersDataProvider(): array {
    return [
      [
        '/../../Fixtures/amqp/info.xml',
        [
          'amqp',
          'pecl.php.net',
          'Networking',
          'PHP License',
          '',
          'Communicate with any AMQP compliant server',
          'This extension can communicate with any AMQP spec 0-9-1 compatible server, such as RabbitMQ, OpenAMQP and Qpid, giving you the ability to create and delete exchanges and queues, as well as publish to any exchange and consume from any queue.',
          '',
          '',
          '',
          ''
        ]
      ],
      [
        '/../../Fixtures/mongo/info.xml',
        [
          'mongo',
          'pecl.php.net',
          'Database',
          'Apache License',
          '',
          'MongoDB database driver',
          'This package provides an interface for communicating with the MongoDB database in PHP.',
          '',
          '',
          'mongodb',
          ''
        ]
      ],
      [
        '/../../Fixtures/mongodb/info.xml',
        [
          'mongodb',
          'pecl.php.net',
          'Database',
          'Apache License',
          '',
          'MongoDB driver for PHP',
          <<<EOL
The purpose of this driver is to provide exceptionally thin glue between MongoDB
and PHP, implementing only fundamental and performance-critical components
necessary to build a fully-functional MongoDB driver.
EOL,
          '',
          '',
          '',
          ''
        ]
      ],
      [
        '/../../Fixtures/parallel/info.xml',
        [
          'parallel',
          'pecl.php.net',
          'PHP',
          'PHP License',
          '',
          'Parallel concurrency API',
          'A succinct parallel concurrency API for PHP 7.',
          '',
          '',
          '',
          ''
        ]
      ]
    ];
  }
}
