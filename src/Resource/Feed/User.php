<?php
declare(strict_types = 1);

namespace Pickling\Resource\Feed;

use SimpleXMLElement;

final class User extends News {
  public function __construct(SimpleXMLElement $xml, private readonly string $userName) {
    parent::__construct($xml);
  }

  public function getUserName(): string {
    return $this->userName;
  }
}
