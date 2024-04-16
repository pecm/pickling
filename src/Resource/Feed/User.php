<?php
declare(strict_types = 1);

namespace Pickling\Resource\Feed;

use SimpleXMLElement;

final class User extends News {
  private string $userName;

  public function __construct(SimpleXMLElement $xml, string $name) {
    parent::__construct($xml);
    $this->userName = $name;
  }

  public function getUserName(): string {
    return $this->userName;
  }
}
