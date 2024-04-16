<?php
declare(strict_types = 1);

namespace Pickling\Resource\Feed;

use SimpleXMLElement;

final class Category extends News {
  public function __construct(SimpleXMLElement $xml, private readonly string $categoryName) {
    parent::__construct($xml);
  }

  public function getCategoryName(): string {
    return $this->categoryName;
  }
}
