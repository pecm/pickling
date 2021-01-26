<?php
declare(strict_types = 1);

namespace Pickling\Resource\Package\Release;

use SimpleXMLElement;

final class Version {
  private string $number;
  private string $stability;

  public function __construct(SimpleXMLElement $xml) {
    $this->number    = trim((string)$xml->v);
    $this->stability = trim((string)$xml->s);
  }

  public function getNumber(): string {
    return $this->number;
  }

  public function getStability(): string {
    return $this->stability;
  }
}
