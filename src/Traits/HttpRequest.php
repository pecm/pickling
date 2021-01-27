<?php
declare(strict_types = 1);

namespace Pickling\Traits;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;

trait HttpRequest {
  /**
   * @var \Psr\Http\Client\ClientInterface
   */
  protected ClientInterface $httpClient;
  /**
   * @var \Psr\Http\Message\RequestFactoryInterface
   */
  protected RequestFactoryInterface $requestFactory;
  /**
   * @var \Psr\Http\Message\StreamFactoryInterface
   */
  protected StreamFactoryInterface $streamFactory;

  final protected function sendRequest(string $method, string $url): string {
    $request  = $this->requestFactory->createRequest($method, $url);
    $response = $this->httpClient->sendRequest($request);
    if ($response->getStatusCode() !== 200) {
      throw new RuntimeException(
        $response->getReasonPhrase() ?? sprintf('Server Response Status Code: %d', $response->getStatusCode())
      );
    }

    $content = $response->getBody()->getContents();
    if ($content === '') {
      throw new RuntimeException('Response body is empty');
    }

    return $content;
  }
}
