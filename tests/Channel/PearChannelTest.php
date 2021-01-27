<?php
declare(strict_types = 1);

namespace Pickling\Test\Channel;

use PHPUnit\Framework\TestCase;
use Pickling\Channel\ChannelInterface;
use Pickling\Channel\PearChannel;

final class PearChannelTest extends TestCase {
  public function testGetUrl(): void {
    $pear = new PearChannel();
    $this->assertInstanceOf(ChannelInterface::class, $pear);
    $this->assertSame('https://pear.php.net', $pear->getUrl());
  }
}
