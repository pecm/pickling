<?php
declare(strict_types = 1);

namespace Pickling\Test\Resource\Feed;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Pickling\Resource\Feed\Item;
use Pickling\Resource\Feed\Package;
use SimpleXMLElement;

final class PackageFeedTest extends TestCase {
  #[DataProvider('propertyGettersDataProvider')]
  public function testPropertyGetters(string $file, array $properties): void {
    $content = file_get_contents(__DIR__ . $file);
    $xml = new SimpleXMLElement($content);

    $packageList = new Package($xml, $properties[0]);
    $this->assertSame($properties[0], $packageList->getPackageName());
    $this->assertSame($properties[1], $packageList->getChannel());

    // test package news list countable
    $this->assertSame($properties[2], count($packageList));

    // test package news list iterator
    $this->assertEquals($properties[3], iterator_to_array($packageList));

    // test package news list array access
    $this->assertEquals($properties[3][0], $packageList[0]);
    $this->assertTrue(isset($properties[3][0]));
    $this->assertTrue(isset($packageList[count($packageList) - 1]));
    $this->assertFalse(isset($packageList[count($packageList)]));

    // test package news list array access immutability
    unset($packageList[0]);
    $packageList[1] = '';
    $this->assertEquals($properties[3][0], $packageList[0]);
    $this->assertEquals($properties[3][1], $packageList[1]);
    $this->assertTrue(isset($packageList[0]));
    $this->assertTrue(isset($packageList[count($packageList) - 1]));
    $this->assertFalse(isset($packageList[count($packageList)]));
  }

  public static function propertyGettersDataProvider(): array {
    return [
      [
        '/../../Fixtures/amqp/pkg_amqp.rss',
        [
          'amqp',
          'https://pecl.php.net',
          10,
          [
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>amqp 2.1.2</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=amqp&amp;amp;release=2.1.2</link>' .
              "<description>For a complete list of changes see:\nhttps://github.com/php-amqp/php-amqp/compare/v2.1.1...v2.1.2</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>amqp 2.1.1</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=amqp&amp;amp;release=2.1.1</link>' .
              "<description>For a complete list of changes see:\nhttps://github.com/php-amqp/php-amqp/compare/v2.1.0...v2.1.1</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>amqp 2.1.0</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=amqp&amp;amp;release=2.1.0</link>' .
              "<description>For a complete list of changes see:\nhttps://github.com/php-amqp/php-amqp/compare/v2.0.0...v2.1.0</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>amqp 2.0.0</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=amqp&amp;amp;release=2.0.0</link>' .
              "<description>For a complete list of changes see:\nhttps://github.com/php-amqp/php-amqp/compare/v1.11.0...v2.0.0</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>amqp 2.0.0RC1</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=amqp&amp;amp;release=2.0.0RC1</link>' .
              "<description>For a complete list of changes see:\nhttps://github.com/php-amqp/php-amqp/compare/v2.0.0beta2...v2.0.0RC1</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>amqp 2.0.0beta2</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=amqp&amp;amp;release=2.0.0beta2</link>' .
              "<description>For a complete list of changes see:\nhttps://github.com/php-amqp/php-amqp/compare/v2.0.0beta1...v2.0.0beta2</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>amqp 2.0.0beta1</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=amqp&amp;amp;release=2.0.0beta1</link>' .
              "<description>For a complete list of changes see:\nhttps://github.com/php-amqp/php-amqp/compare/v2.0.0alpha2...v2.0.0beta1</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>amqp 2.0.0alpha2</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=amqp&amp;amp;release=2.0.0alpha2</link>' .
              "<description>For a complete list of changes see:\nhttps://github.com/php-amqp/php-amqp/compare/v2.0.0alpha1...v2.0.0alpha2</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>amqp 2.0.0alpha1</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=amqp&amp;amp;release=2.0.0alpha1</link>' .
              "<description>For a complete list of changes see:\nhttps://github.com/php-amqp/php-amqp/compare/v1.11.0...v2.0.0alpha1</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>amqp 1.11.0</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=amqp&amp;amp;release=1.11.0</link>' .
              "<description>For a complete list of changes see:\nhttps://github.com/php-amqp/php-amqp/compare/v1.11.0RC1...v1.11.0</description>" .
              '</rdf>'
            )),
          ]
        ]
      ],
    ];
  }
}
