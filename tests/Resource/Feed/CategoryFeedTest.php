<?php
declare(strict_types = 1);

namespace Pickling\Test\Resource\Feed;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Pickling\Resource\Feed\Category;
use Pickling\Resource\Feed\Item;
use SimpleXMLElement;

final class CategoryFeedTest extends TestCase {
  #[DataProvider('propertyGettersDataProvider')]
  public function testPropertyGetters(string $file, array $properties): void {
    $content = file_get_contents(__DIR__ . $file);
    $xml = new SimpleXMLElement($content);

    $categoryList = new Category($xml, $properties[0]);
    $this->assertSame($properties[1], $categoryList->getChannel());

    // test category list countable
    $this->assertSame($properties[2], count($categoryList));

    // test category list iterator
    $this->assertEquals($properties[3], iterator_to_array($categoryList));

    // test category list array access
    $this->assertEquals($properties[3][0], $categoryList[0]);
    $this->assertTrue(isset($properties[3][0]));
    $this->assertTrue(isset($categoryList[count($categoryList) - 1]));
    $this->assertFalse(isset($categoryList[count($categoryList)]));

    // test category list array access immutability
    unset($categoryList[0]);
    $categoryList[1] = '';
    $this->assertEquals($properties[3][0], $categoryList[0]);
    $this->assertEquals($properties[3][1], $categoryList[1]);
    $this->assertTrue(isset($categoryList[0]));
    $this->assertTrue(isset($categoryList[count($categoryList) - 1]));
    $this->assertFalse(isset($categoryList[count($categoryList)]));
  }

  public static function propertyGettersDataProvider(): array {
    return [
      [
        '/../../Fixtures/cat_encryption.rss',
        [
          'encryption',
          'https://pecl.php.net',
          4,
          [
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>mcrypt 1.0.7</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=mcrypt&amp;amp;release=1.0.7</link>' .
              "<description>- Make release to advertise PHP 8.3 support, which it already had.</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>PKCS11 1.1.2</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=PKCS11&amp;amp;release=1.1.2</link>' .
              "<description>- Fixed compatibility with PHP 8.2\n- Fix issue where not specifying the object type in openUri would fail</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>scrypt 2.0.1</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=scrypt&amp;amp;release=2.0.1</link>' .
              "<description>Check CPU architecture before attempting to enable SSE (#76)</description>" .
              '</rdf>'
            )),
            new Item(new SimpleXMLElement(
              '<rdf>' .
              '<title>mcrypt 1.0.6</title>' .
              '<link>https://pecl.php.net/package-changelog.php?package=mcrypt&amp;amp;release=1.0.6</link>' .
              "<description>- Make release to advertise PHP 8.2 support, which it already had.</description>" .
              '</rdf>'
            )),
          ]
        ]
      ]
    ];
  }
}
