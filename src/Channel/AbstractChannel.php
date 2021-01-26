<?php
declare(strict_types = 1);

namespace Pickling\Channel;

abstract class AbstractChannel implements ChannelInterface {
  protected string $url = '';

  public function getUrl(): string {
    return $this->url;
  }
}
