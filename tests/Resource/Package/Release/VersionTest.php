<?php
declare(strict_types = 1);

namespace Pickling\Test\Resource\Package\Release;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pickling\Resource\Package\Release\Version;
use SimpleXMLElement;

#[CoversClass(Version::class)]
final class VersionTest extends TestCase {
  public function testPropertyGetters(): void {
    $version = new Version(
      new SimpleXMLElement(
        '<r><v>1.2.3</v><s>stable</s></r>'
      )
    );

    $this->assertSame('1.2.3', $version->getNumber());
    $this->assertSame('stable', $version->getStability());
  }
}
