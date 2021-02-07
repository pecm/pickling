<?php
declare(strict_types = 1);

namespace Pickling\Resource\Package\Release;

final class Dependencies {
  private string $phpMin;
  private string $phpMax;
  private string $pearMin;
  private string $pearMax;

  /**
   * @link https://pecl.php.net/rest/r/:packageName/deps.:version.txt
   * @link https://pear.php.net/rest/r/:packageName/deps.:version.txt
   */
  public function __construct(string $serialized) {
    $unserialized = unserialize($serialized, ['allowed_classes' => false]);

    $this->phpMin = $unserialized['required']['php']['min'] ?? '';
    $this->phpMax = $unserialized['required']['php']['max'] ?? '';
    $this->pearMin = $unserialized['required']['pearinstaller']['min'] ?? '';
    $this->pearMax = $unserialized['required']['pearinstaller']['max'] ?? '';
  }

  public function getPhpMin(): string {
    return $this->phpMin;
  }

  public function getPhpMax(): string {
    return $this->phpMax;
  }

  public function getPearMin(): string {
    return $this->pearMin;
  }

  public function getPearMax(): string {
    return $this->pearMax;
  }
}
