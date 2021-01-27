<?php
declare(strict_types = 1);

namespace Pickling\Test\Pecl;

use PHPUnit\Framework\TestCase;
use Pickling\Resource\PackageList;
use SimpleXMLElement;

final class PackageListTest extends TestCase {
  public function testPropertyGetters(): void {
    $content = file_get_contents(__DIR__ . '/../Fixtures/packages.xml');
    $this->assertNotEmpty($content);
    $xml = new SimpleXMLElement($content);

    $packageList = new PackageList($xml);
    $this->assertSame('pecl.php.net', $packageList->getChannel());

    // test package list countable
    $this->assertSame(3, count($packageList));

    // test package list iterator
    $this->assertEquals(['ahocorasick', 'amfext', 'amqp'], iterator_to_array($packageList));

    // test package list array access
    $this->assertSame('ahocorasick', $packageList[0]);
    $this->assertTrue(isset($packageList[2]));
    $this->assertFalse(isset($packageList[3]));

    // test package list array access immutability
    unset($packageList[0]);
    $packageList[1] = 'php';
    $this->assertSame('ahocorasick', $packageList[0]);
    $this->assertSame('amfext', $packageList[1]);
    $this->assertTrue(isset($packageList[0]));
    $this->assertTrue(isset($packageList[1]));
    $this->assertTrue(isset($packageList[2]));
  }
}
