<?php
declare(strict_types = 1);

namespace Pickling\Resource\Feed;

use ArrayAccess;
use Countable;
use Iterator;
use SimpleXMLElement;

class News implements ArrayAccess, Countable, Iterator {
  private string $channel;
  private string $title;
  private string $description;

  private array $list;

  public function __construct(SimpleXMLElement $xml) {
    $this->channel = trim((string)$xml->channel->link);

    $this->title = trim((string)$xml->channel->title);
    $this->description = trim((string)$xml->channel->description);

    foreach ($xml->item as $item) {
      $this->list[] = new Item($item);
    }
  }

  public function getChannel(): string {
    return $this->channel;
  }

  public function getTitle(): string {
    return $this->title;
  }

  public function getDescription(): string {
    return $this->description;
  }

  public function offsetExists($offset): bool {
    return isset($this->list[$offset]);
  }

  public function offsetGet($offset): ?Item {
    return isset($this->list[$offset]) ? $this->list[$offset] : null;
  }

  public function offsetSet($offset, $value): void {
    // no-op as the package feed list must be immutable
  }

  public function offsetUnset($offset): void {
    // no-op as the package feed list must be immutable
  }

  public function count(): int {
    return count($this->list);
  }

  public function current(): Item {
    return current($this->list);
  }

  public function key(): int {
    return (int)key($this->list);
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
