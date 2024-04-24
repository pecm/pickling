<?php
declare(strict_types = 1);

namespace Pickling\Test\Channel;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pickling\Channel\ChannelInterface;
use Pickling\Channel\PearChannel;

#[CoversClass(PearChannel::class)]
final class PearChannelTest extends TestCase {
  public function testGetUrl(): void {
    $pear = new PearChannel();
    $this->assertInstanceOf(ChannelInterface::class, $pear);
    $this->assertSame('https://pear.php.net', $pear->getUrl());
  }
}
