<?php
declare(strict_types = 1);

namespace Pickling\Test\Resource\Package\Release;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Pickling\Resource\Package\Release\Manifest;
use SimpleXMLElement;

final class ManifestTest extends TestCase {
  #[DataProvider('propertyGettersDataProvider')]
  public function testPropertyGetters(string $file, array $properties): void {
    $content = file_get_contents(__DIR__ . $file);
    $xml = new SimpleXMLElement($content);

    $manifest = new Manifest($xml);
    $this->assertSame($properties[0], $manifest->getPackageName());
    $this->assertSame($properties[1], $manifest->getChannel());
  }

  public static function propertyGettersDataProvider(): array {
    return [
      [
        '/../../../Fixtures/amqp/package.1.10.2.xml',
        [
          'amqp',
          'pecl.php.net'
        ]
      ],
      [
        '/../../../Fixtures/mongo/package.1.6.16.xml',
        [
          'mongo',
          'pecl.php.net'
        ]
      ],
      [
        '/../../../Fixtures/mongodb/package.1.9.0.xml',
        [
          'mongodb',
          'pecl.php.net'
        ]
      ],
      [
        '/../../../Fixtures/parallel/package.0.8.0.xml',
        [
          'parallel',
          'pecl.php.net'
        ]
      ]
    ];
  }
}
