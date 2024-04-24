<?php
declare(strict_types = 1);

namespace Pickling\Test;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Pickling\Channel\PeclChannel;
use Pickling\Client;
use Pickling\Resource\CategoryList;
use Pickling\Resource\Package;
use Pickling\Resource\PackageList;
use Psr\Http\Message\ResponseInterface;
use PsrMock\Psr18\Client as MockClient;

final class ClientTest extends TestCase {
  private MockClient $httpClient;
  private Psr17Factory $psr17Factory;
  private Client $peclClient;

  protected function setUp(): void {
    $this->httpClient = new MockClient();
    $this->psr17Factory = new Psr17Factory();
    $this->peclClient = new Client(new PeclChannel(), $this->httpClient, $this->psr17Factory, $this->psr17Factory);
  }

  public function testGetCategoryList(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $response
      ->method('getBody')
      ->willReturn($this->psr17Factory->createStreamFromFile(__DIR__ . '/Fixtures/categories.xml'));
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/c/categories.xml', $response);

    $this->assertInstanceOf(CategoryList::class, $this->peclClient->getCategoryList());
  }

  public function testGetPackageList(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $response
      ->method('getBody')
      ->willReturn($this->psr17Factory->createStreamFromFile(__DIR__ . '/Fixtures/packages.xml'));
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/p/packages.xml', $response);

    $this->assertInstanceOf(PackageList::class, $this->peclClient->getPackageList());
  }

  public function testWithPackage(): void {
    $this->assertInstanceOf(Package::class, $this->peclClient->with(''));
  }
}
