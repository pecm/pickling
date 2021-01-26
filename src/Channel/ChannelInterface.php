<?php
declare(strict_types = 1);

namespace Pickling\Channel;

interface ChannelInterface {
  public function getUrl(): string;
}
