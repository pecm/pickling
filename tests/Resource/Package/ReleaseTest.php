<?php
declare(strict_types = 1);

namespace Pickling\Test\Resource\Package;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Pickling\Channel\Pecl;
use Pickling\Resource\Package\Release;
use Pickling\Resource\Package\Release\Info;
use Pickling\Resource\Package\Release\Manifest;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

final class ReleaseTest extends TestCase {
  private MockClient $httpClient;
  private Psr17Factory $psr17Factory;
  private Release $release;

  protected function setUp(): void {
    $this->httpClient = new MockClient();
    $this->psr17Factory = new Psr17Factory();
    $this->release = new Release(
      new Pecl(),
      $this->httpClient,
      $this->psr17Factory,
      $this->psr17Factory,
      'mongo',
      '0.0.0'
    );
  }

  public function testPropertyGetters(): void {
    $this->assertSame('mongo', $this->release->getPackageName());
    $this->assertSame('0.0.0', $this->release->getNumber());
  }

  public function testGetManifestWithResponseError(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(500);
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Server Response Status Code: 500');
    $this->release->getManifest();
  }

  public function testGetManifestWithEmptyResponseBody(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStream(''));
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Response body is empty');
    $this->release->getManifest();
  }

  public function testGetManifest(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStreamFromFile(__DIR__ . '/../../Fixtures/mongo/package.1.6.16.xml'));
    $this->httpClient->addResponse($response);

    $this->assertInstanceOf(Manifest::class, $this->release->getManifest());
  }

  public function testGetInfoWithResponseError(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(500);
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Server Response Status Code: 500');
    $this->release->getInfo();
  }

  public function testGetInfoWithEmptyResponseBody(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStream(''));
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Response body is empty');
    $this->release->getInfo();
  }

  public function testGetInfo(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStreamFromFile(__DIR__ . '/../../Fixtures/mongo/1.6.16.xml'));
    $this->httpClient->addResponse($response);

    $this->assertInstanceOf(Info::class, $this->release->getInfo());
  }
}
