<?php
declare(strict_types = 1);

namespace Pickling\Test\Pecl;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Pickling\Channel\Pecl;
use Pickling\Resource\Package;
use Pickling\Resource\Package\Info;
use Pickling\Resource\Package\Release;
use Pickling\Resource\Package\ReleaseList;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

final class PackageTest extends TestCase {
  private MockClient $httpClient;
  private Psr17Factory $psr17Factory;
  private Package $package;

  protected function setUp(): void {
    $this->httpClient = new MockClient();
    $this->psr17Factory = new Psr17Factory();
    $this->package = new Package(new Pecl(), $this->httpClient, $this->psr17Factory, $this->psr17Factory, 'mongo');
  }

  public function testPropertyGetters(): void {
    $this->assertSame('mongo', $this->package->getName());
  }

  public function testGetInfoWithResponseError(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(500);
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Server Response Status Code: 500');
    $this->package->getInfo();
  }

  public function testGetInfoWithEmptyResponseBody(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStream(''));
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Response body is empty');
    $this->package->getInfo();
  }

  public function testGetInfo(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStreamFromFile(__DIR__ . '/../Fixtures/mongo/info.xml'));
    $this->httpClient->addResponse($response);

    $this->assertInstanceOf(Info::class, $this->package->getInfo());
  }

  public function testGetReleaseListWithResponseError(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(500);
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Server Response Status Code: 500');
    $this->package->getReleaseList();
  }

  public function testGetReleaseListWithEmptyResponseBody(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStream(''));
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Response body is empty');
    $this->package->getReleaseList();
  }

  public function testGetReleaseList(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStreamFromFile(__DIR__ . '/../Fixtures/mongo/allreleases.xml'));
    $this->httpClient->addResponse($response);

    $this->assertInstanceOf(ReleaseList::class, $this->package->getReleaseList());
  }

  public function testGetLatestVersionWithResponseError(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(500);
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Server Response Status Code: 500');
    $this->package->getLatestVersion();
  }

  public function testGetLatestVersionWithEmptyResponseBody(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStream(''));
    $this->httpClient->addResponse($response);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Response body is empty');
    $this->package->getLatestVersion();
  }

  public function testGetLatestVersion(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response->method('getStatusCode')->willReturn(200);
    $response->method('getBody')->willReturn($this->psr17Factory->createStream('0.0.0'));
    $this->httpClient->addResponse($response);

    $this->assertSame('0.0.0', $this->package->getLatestVersion());
  }

  public function testAtRelease(): void {
    $this->assertInstanceOf(Release::class, $this->package->at(''));
  }
}
