<?php
declare(strict_types = 1);

namespace Pickling\Resource;

use Countable;
use Iterator;
use SimpleXMLElement;

/**
 * @link http://pear.php.net/dtd/rest.allpackages.xsd
 */
final class PackageList implements Countable, Iterator {
  private string $channel;
  /**
   * @var string[]
   */
  private array $list = [];

  public function __construct(SimpleXMLElement $xml) {
    $this->channel = trim((string)$xml->c);
    foreach ($xml->p as $package) {
      $this->list[] = trim((string)$package);
    }
  }

  public function getChannel(): string {
    return $this->channel;
  }

  public function count(): int {
    return count($this->list);
  }

  public function current(): string {
    return current($this->list);
  }

  public function key(): int {
    return key($this->list);
  }

  public function next(): void {
    next($this->list);
  }

  public function rewind(): void {
    reset($this->list);
  }

  public function valid(): bool {
    $key = key($this->list);

    return ($key !== null && $key !== false);
  }
}
