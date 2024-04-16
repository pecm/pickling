<?php
declare(strict_types = 1);

namespace Pickling\Resource\Feed;

use SimpleXMLElement;

final class Package extends News {
  private string $packageName;

  public function __construct(SimpleXMLElement $xml, string $name) {
    parent::__construct($xml);
    $this->packageName = $name;
  }

  public function getPackageName(): string {
    return $this->packageName;
  }
}
