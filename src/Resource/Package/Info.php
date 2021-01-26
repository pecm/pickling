<?php
declare(strict_types = 1);

namespace Pickling\Resource\Package;

use SimpleXMLElement;

/**
 * @link https://pecl.php.net/rest/p/:packageName/info.xml
 * @link http://pear.php.net/dtd/rest.package.xsd
 */
final class Info {
  private string $packageName;
  private string $channel;
  private string $category;
  private string $license;
  private string $licenseUri;
  private string $summary;
  private string $description;
  private string $packageReleasesLocation;
  private string $parentPackage;
  private string $packageReplaceBy;
  private string $channelReplaceBy;

  public function __construct(SimpleXMLElement $xml) {
    $this->packageName             = trim((string)$xml->n);
    $this->channel                 = trim((string)$xml->c);
    $this->category                = trim((string)$xml->ca);
    $this->license                 = trim((string)$xml->l);
    $this->licenseUri              = trim((string)$xml->lu);
    $this->summary                 = trim((string)$xml->s);
    $this->description             = trim((string)$xml->d);
    $this->packageReleasesLocation = trim((string)$xml->r);
    $this->parentPackage           = trim((string)$xml->pa);
    $this->packageReplaceBy        = trim((string)$xml->dp);
    $this->channelReplaceBy        = trim((string)$xml->dc);
  }

  public function getPackageName(): string {
    return $this->packageName;
  }

  public function getChannel(): string {
    return $this->channel;
  }

  public function getCategory(): string {
    return $this->category;
  }

  public function getLicense(): string {
    return $this->license;
  }

  public function getLicenseUri(): string {
    return $this->licenseUri;
  }

  public function getSummary(): string {
    return $this->summary;
  }

  public function getDescription(): string {
    return $this->description;
  }

  public function getPackageReleasesLocation(): string {
    return $this->packageReleasesLocation;
  }

  public function getParentPackage(): string {
    return $this->parentPackage;
  }

  /**
   * package this one is deprecated in favor of
   */
  public function getPackageReplaceBy(): string {
    return $this->packageReplaceBy;
  }

  /**
   * channel of package this one is deprecated in favor of
   */
  public function getChannelReplaceBy(): string {
    return $this->channelReplaceBy;
  }
}
