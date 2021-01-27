<?php
declare(strict_types = 1);

namespace Pickling\Resource\Package;

use Countable;
use Iterator;
use Pickling\Resource\Package\Release\Version;
use SimpleXMLElement;

/**
 * @link https://pecl.php.net/rest/r/:packageName/allreleases.xml
 * @link http://pear.php.net/dtd/rest.allreleases.xsd
 */
final class ReleaseList implements Countable, Iterator {
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
