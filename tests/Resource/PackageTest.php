<?php
declare(strict_types = 1);

namespace Pickling\Test\Resource;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Pickling\Channel\PeclChannel;
use Pickling\Resource\Package;
use Pickling\Resource\Package\Info;
use Pickling\Resource\Package\Release;
use Pickling\Resource\Package\ReleaseList;
use Psr\Http\Message\ResponseInterface;
use PsrMock\Psr18\Client as MockClient;

final class PackageTest extends TestCase {
  private MockClient $httpClient;
  private Psr17Factory $psr17Factory;
  private Package $package;

  protected function setUp(): void {
    $this->httpClient = new MockClient();
    $this->psr17Factory = new Psr17Factory();
    $this->package = new Package(
      new PeclChannel(),
      $this->httpClient,
      $this->psr17Factory,
      $this->psr17Factory,
      'mongo'
    );
  }

  public function testPropertyGetters(): void {
    $this->assertSame('mongo', $this->package->getName());
  }

  public function testGetInfo(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $response
      ->method('getBody')
      ->willReturn($this->psr17Factory->createStreamFromFile(__DIR__ . '/../Fixtures/mongo/info.xml'));
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/p/mongo/info.xml', $response);

    $this->assertInstanceOf(Info::class, $this->package->getInfo());
  }

  public function testGetReleaseList(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $response
      ->method('getBody')
      ->willReturn($this->psr17Factory->createStreamFromFile(__DIR__ . '/../Fixtures/mongo/allreleases.xml'));
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/r/mongo/allreleases.xml', $response);

    $this->assertInstanceOf(ReleaseList::class, $this->package->getReleaseList());
  }

  public function testGetLatestVersion(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $stream = $this->psr17Factory->createStream('1.0.1');
    $stream->rewind(); // https://github.com/Nyholm/psr7/issues/99
    $response
      ->method('getBody')
      ->willReturn($stream);
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/r/mongo/latest.txt', $response);

    $this->assertSame('1.0.1', $this->package->getLatestVersion());
  }

  public function testGetStableVersion(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $stream = $this->psr17Factory->createStream('1.0.0');
    $stream->rewind(); // https://github.com/Nyholm/psr7/issues/99
    $response
      ->method('getBody')
      ->willReturn($stream);
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/r/mongo/stable.txt', $response);

    $this->assertSame('1.0.0', $this->package->getStableVersion());
  }

  public function testGetBetaVersion(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $stream = $this->psr17Factory->createStream('0.1.0');
    $stream->rewind(); // https://github.com/Nyholm/psr7/issues/99
    $response
      ->method('getBody')
      ->willReturn($stream);
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/r/mongo/beta.txt', $response);

    $this->assertSame('0.1.0', $this->package->getBetaVersion());
  }

  public function testGetAlphaVersion(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $stream = $this->psr17Factory->createStream('0.0.1');
    $stream->rewind(); // https://github.com/Nyholm/psr7/issues/99
    $response
      ->method('getBody')
      ->willReturn($stream);
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/r/mongo/alpha.txt', $response);

    $this->assertSame('0.0.1', $this->package->getAlphaVersion());
  }

  public function testAtRelease(): void {
    $release = $this->package->at('1.1.1');
    $this->assertInstanceOf(Release::class, $release);
    $this->assertSame('1.1.1', $release->getNumber());
  }

  public function testAtLatestRelease(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $stream = $this->psr17Factory->createStream('1.0.1');
    $stream->rewind(); // https://github.com/Nyholm/psr7/issues/99
    $response
      ->method('getBody')
      ->willReturn($stream);
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/r/mongo/latest.txt', $response);

    $release = $this->package->at('latest');
    $this->assertInstanceOf(Release::class, $release);
    $this->assertSame('1.0.1', $release->getNumber());
  }

  public function testAtStableRelease(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $stream = $this->psr17Factory->createStream('1.0.0');
    $stream->rewind(); // https://github.com/Nyholm/psr7/issues/99
    $response
      ->method('getBody')
      ->willReturn($stream);
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/r/mongo/stable.txt', $response);

    $release = $this->package->at('stable');
    $this->assertInstanceOf(Release::class, $release);
    $this->assertSame('1.0.0', $release->getNumber());
  }

  public function testAtBetaRelease(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $stream = $this->psr17Factory->createStream('0.1.0');
    $stream->rewind(); // https://github.com/Nyholm/psr7/issues/99
    $response
      ->method('getBody')
      ->willReturn($stream);
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/r/mongo/beta.txt', $response);

    $release = $this->package->at('beta');
    $this->assertInstanceOf(Release::class, $release);
    $this->assertSame('0.1.0', $release->getNumber());
  }

  public function testAtAlphaRelease(): void {
    $response = $this->createMock(ResponseInterface::class);
    $response
      ->method('getStatusCode')
      ->willReturn(200);
    $stream = $this->psr17Factory->createStream('0.0.1');
    $stream->rewind(); // https://github.com/Nyholm/psr7/issues/99
    $response
      ->method('getBody')
      ->willReturn($stream);
    $this->httpClient->addResponse('GET', 'https://pecl.php.net/rest/r/mongo/alpha.txt', $response);

    $release = $this->package->at('alpha');
    $this->assertInstanceOf(Release::class, $release);
    $this->assertSame('0.0.1', $release->getNumber());
  }
}
