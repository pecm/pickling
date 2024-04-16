<?php
declare(strict_types = 1);

namespace Pickling\Resource\Feed;

use SimpleXMLElement;

final class Package extends News {
  public function __construct(SimpleXMLElement $xml, private readonly string $packageName) {
    parent::__construct($xml);
  }

  public function getPackageName(): string {
    return $this->packageName;
  }
}
