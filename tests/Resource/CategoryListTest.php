<?php
declare(strict_types = 1);

namespace Pickling\Test\Resource;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pickling\Resource\CategoryList;
use SimpleXMLElement;

#[CoversClass(CategoryList::class)]
final class CategoryListTest extends TestCase {
  public function testPropertyGetters(): void {
    $content = file_get_contents(__DIR__ . '/../Fixtures/categories.xml');
    $this->assertNotEmpty($content);
    $xml = new SimpleXMLElement($content);

    $categoryList = new CategoryList($xml);
    $this->assertSame('pecl.php.net', $categoryList->getChannel());

    // test package list countable
    $this->assertSame(4, count($categoryList));

    // test category list iterator
    $this->assertEquals(['Audio', 'Authentication', 'Benchmarking', 'Caching'], iterator_to_array($categoryList));

    // test category list array access
    $this->assertSame('Audio', $categoryList[0]);
    $this->assertTrue(isset($categoryList[2]));
    $this->assertFalse(isset($categoryList[4]));

    // test category list array access immutability
    unset($categoryList[0]);
    $categoryList[1] = 'php';
    $this->assertSame('Audio', $categoryList[0]);
    $this->assertSame('Authentication', $categoryList[1]);
    $this->assertTrue(isset($categoryList[0]));
    $this->assertTrue(isset($categoryList[1]));
    $this->assertTrue(isset($categoryList[2]));
  }
}
