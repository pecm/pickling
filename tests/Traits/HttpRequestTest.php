<?php
declare(strict_types = 1);

namespace Pickling\Test\Traits;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Pickling\Traits\HttpRequest;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;
use stdClass;

final class HttpRequestTest extends TestCase {
  private MockClient $httpClient;
  private Psr17Factory $psr17Factory;
  private stdClass $trait;

  protected function setUp(): void {
    $this->httpClient = new MockClient();
    $this->psr17Factory = new Psr17Factory();
    // anonymous class that uses HttpRequest trait to enable testing
    $this->trait = new class(
      $this->httpClient,
      $this->psr17Factory,
      $this->psr17Factory,
    ) extends stdClass {
      use HttpRequest;

      public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
      ) {
        $this->httpClient     = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory  = $streamFactory;
      }

      public function testRequest(): void {
        $this->sendRequest('GET', 'http://localhost/');
      }
    };
  }

  public function testSendRequestWithResponseError(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(500);
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Server Response Status Code: 500');
    $this->trait->testRequest();
  }

  public function testSendRequestWithEmptyResponseBody(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStream(''));
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Response body is empty');
    $this->trait->testRequest();
  }
}
