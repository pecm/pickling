<?php
declare(strict_types = 1);

namespace Pickling\Test\Channel;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pickling\Channel\ChannelInterface;
use Pickling\Channel\PeclChannel;

#[CoversClass(PeclChannel::class)]
final class PeclChannelTest extends TestCase {
  public function testGetUrl(): void {
    $pecl = new PeclChannel();
    $this->assertInstanceOf(ChannelInterface::class, $pecl);
    $this->assertSame('https://pecl.php.net', $pecl->getUrl());
  }
}
