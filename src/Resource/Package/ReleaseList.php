<?php
declare(strict_types = 1);

namespace Pickling\Resource\Package;

use ArrayAccess;
use Countable;
use Iterator;
use Pickling\Resource\Package\Release\Version;
use SimpleXMLElement;

/**
 * @link https://pecl.php.net/rest/r/:packageName/allreleases.xml
 * @link http://pear.php.net/dtd/rest.allreleases.xsd
 */
final class ReleaseList implements ArrayAccess, Countable, Iterator {
  private string $packageName;
  private string $channel;
  /**
   * @var \Pickling\Resource\Package\Release\Version[]
   */
  private array $list;

  public function __construct(SimpleXMLElement $xml) {
    $this->packageName = trim((string)$xml->p);
    $this->channel     = trim((string)$xml->c);

    foreach ($xml->r as $release) {
      $this->list[] = new Version($release);
    }
  }

  public function getPackageName(): string {
    return $this->packageName;
  }

  public function getChannel(): string {
    return $this->channel;
  }

  public function offsetExists($offset): bool {
    return isset($this->list[$offset]);
  }

  public function offsetGet($offset): ?Version {
    return isset($this->list[$offset]) ? $this->list[$offset] : null;
  }

  public function offsetSet($offset, $value): void {
    // no-op as the package list must be immutable
  }

  public function offsetUnset($offset): void {
    // no-op as the package list must be immutable
  }

  public function count(): int {
    return count($this->list);
  }

  public function current(): Version {
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
