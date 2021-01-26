<?php
declare(strict_types = 1);

namespace Pickling\Test;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Pickling\Channel\Pecl;
use Pickling\Client;
use Pickling\Resource\Package;
use Pickling\Resource\PackageList;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

final class ClientTest extends TestCase {
  private MockClient $httpClient;
  private Psr17Factory $psr17Factory;
  private Client $peclClient;

  protected function setUp(): void {
    $this->httpClient = new MockClient();
    $this->psr17Factory = new Psr17Factory();
    $this->peclClient = new Client(new Pecl(), $this->httpClient, $this->psr17Factory, $this->psr17Factory);
  }

  public function testGetPackageListWithResponseError(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(500);
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Server Response Status Code: 500');
    $this->peclClient->getPackageList();
  }

  public function testGetPackageListWithEmptyResponseBody(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStream(''));
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Response body is empty');
    $this->peclClient->getPackageList();
  }

  public function testGetPackageList(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStreamFromFile(__DIR__ . '/Fixtures/packages.xml'));
    $this->httpClient->addResponse($response);

    $this->assertInstanceOf(PackageList::class, $this->peclClient->getPackageList());
  }

  public function testWithPackage(): void {
    $this->assertInstanceOf(Package::class, $this->peclClient->with(''));
  }
}
