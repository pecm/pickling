<?php
declare(strict_types = 1);

namespace Pickling\Traits;

use RuntimeException;
use SimpleXMLElement;

trait XmlParser {
  protected function parseXml(string $content): SimpleXMLElement {
    $useErrors = \libxml_use_internal_errors();
    \libxml_use_internal_errors(true);
    $xml = new SimpleXMLElement($content);
    if ($xml === false) {
      $xmlError = \libxml_get_last_error();
      \libxml_clear_errors();
      throw RuntimeException($xmlError->message, $xmlError->code);
    }

    \libxml_use_internal_errors($useErrors);

    return $xml;
  }
}
