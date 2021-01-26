<?php
declare(strict_types = 1);

namespace Pickling\Resource\Package\Release;

use SimpleXMLElement;

/**
 * @link https://pecl.php.net/rest/r/:packageName/package.:version.xml
 * @link http://pear.php.net/dtd/package-2.0.xsd
 */
final class Manifest {
  private string $packageName;
  private string $channel;
  // extends?
  private string $summary;
  private string $description;


  private string $license;
  private string $licenseUri;

  public function __construct(SimpleXMLElement $xml) {
    $this->packageName = trim((string)$xml->name);
    $this->channel     = trim((string)$xml->channel);
    $this->summary     = trim((string)$xml->summary);
    $this->description = trim((string)$xml->description);
    $this->license     = trim((string)$xml->license);
    $this->licenseUri  = trim((string)($xml->license['uri'] ?? ''));
  }

  public function getPackageName(): string {
    return $this->packageName;
  }

  public function getChannel(): string {
    return $this->channel;
  }

  public function getSummary(): string {
    return $this->summary;
  }

  public function getDescription(): string {
    return $this->description;
  }

  public function getLicense(): string {
    return $this->license;
  }

  public function getLicenseUri(): string {
    return $this->licenseUri;
  }
}
