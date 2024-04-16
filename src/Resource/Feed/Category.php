<?php
declare(strict_types = 1);

namespace Pickling\Resource\Feed;

use SimpleXMLElement;

final class Category extends News {
  private string $categoryName;

  public function __construct(SimpleXMLElement $xml, string $name) {
    parent::__construct($xml);
    $this->categoryName = $name;
  }

  public function getCategoryName(): string {
    return $this->categoryName;
  }
}
