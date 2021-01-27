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

    $releaseList = new PackageList($xml);
    $this->assertSame('pecl.php.net', $releaseList->getChannel());
    $this->assertSame(3, count($releaseList));
    $this->assertEquals(['ahocorasick', 'amfext', 'amqp'], iterator_to_array($releaseList));
  }
}
