<?php
declare(strict_types = 1);

namespace Pickling\Resource\Feed;

use SimpleXMLElement;

class Item {
  private string $title;
  private string $link;
  private string $description;

  public function __construct(SimpleXMLElement $xml) {
    $this->title = trim((string)$xml->title);
    $this->link = trim((string)$xml->link);
    $this->description = trim((string)$xml->description);
  }

  public function getTitle(): string {
    return $this->title;
  }

  public function getLink(): string {
    return $this->link;
  }

  public function getDescription(): string {
    return $this->description;
  }
}
