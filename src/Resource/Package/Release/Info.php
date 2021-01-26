<?php
declare(strict_types = 1);

namespace Pickling\Resource\Package\Release;

use SimpleXMLElement;

/**
 * @link https://pecl.php.net/rest/r/:packageName/:version.xml
 * @link https://pear.php.net/dtd/rest.release.xsd
 */
final class Info {
  private string $packageName;
  private string $channel;
  private string $version;
  private string $stability;
  private string $license;
  private string $releasingMaintainer;
  private string $summary;
  private string $description;
  private string $releaseDate;
  private string $releaseNotes;
  private int $releaseSize;
  private string $downloadUri;
  private string $packageLink;

  public function __construct(SimpleXMLElement $xml) {
    $this->packageName         = trim((string)$xml->p);
    $this->channel             = trim((string)$xml->c);
    $this->version             = trim((string)$xml->v);
    $this->stability           = trim((string)$xml->st);
    $this->license             = trim((string)$xml->l);
    $this->releasingMaintainer = trim((string)$xml->m);
    $this->summary             = trim((string)$xml->s);
    $this->description         = trim((string)$xml->d);
    $this->releaseDate         = trim((string)$xml->da);
    $this->releaseNotes        = trim((string)$xml->n);
    $this->releaseSize         = (int)$xml->f;
    $this->downloadUri         = trim((string)$xml->g);
    $this->packageLink         = trim((string)$xml->x);
  }

  public function getPackageName(): string {
    return $this->packageName;
  }

  public function getChannel(): string {
    return $this->channel;
  }

  public function getVersion(): string {
    return $this->version;
  }

  public function getStability(): string {
    return $this->stability;
  }

  public function getLicense(): string {
    return $this->license;
  }

  public function getReleasingMaintainer(): string {
    return $this->releasingMaintainer;
  }

  public function getSummary(): string {
    return $this->summary;
  }

  public function getDescription(): string {
    return $this->description;
  }

  public function getReleaseDate(): string {
    return $this->releaseDate;
  }

  public function getReleaseNotes(): string {
    return $this->releaseNotes;
  }

  /**
   * release size in bytes (compressed tgz)
   */
  public function getReleaseSize(): int {
    return $this->releaseSize;
  }

  /**
   * partial URI to download (no .tgz/.tar extension)
   */
  public function getDownloadUri(): string {
    return $this->downloadUri;
  }

  /**
   * link to extracted package.xml
   */
  public function getPackageLink(): string {
    return $this->packageLink;
  }
}
