<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class DevelopmentServerTest extends TestCase
{
    private string $publicDirectory;

    private string $serverUrl;

    private ?Process $server = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->publicDirectory = sys_get_temp_dir().'/shop-nqt-server-'.bin2hex(random_bytes(8));
        mkdir($this->publicDirectory);
        file_put_contents($this->publicDirectory.'/index.php', '<?php header("Content-Type: text/html; charset=UTF-8"); echo str_repeat("Laravel local homepage ", 1000);');
        file_put_contents($this->publicDirectory.'/asset.js', 'export const loaded = true;');

        $socket = stream_socket_server('tcp://127.0.0.1:0');
        $this->assertIsResource($socket);
        $address = stream_socket_get_name($socket, false);
        fclose($socket);

        $this->serverUrl = 'http://'.$address;
        $this->server = new Process([
            PHP_BINARY, '-S', $address, dirname(__DIR__, 2).'/server.php',
        ], $this->publicDirectory);
        $this->server->setTimeout(5);
        $this->server->start();
        $this->assertTrue($this->server->waitUntil(fn (string $type, string $output): bool => str_contains($output, 'Development Server')));
    }

    protected function tearDown(): void
    {
        $this->server?->stop();
        unlink($this->publicDirectory.'/index.php');
        unlink($this->publicDirectory.'/asset.js');
        rmdir($this->publicDirectory);

        parent::tearDown();
    }

    #[RequiresPhpExtension('zlib')]
    public function test_homepage_is_compressed_when_gzip_is_accepted(): void
    {
        $response = $this->request('/', 'gzip');

        $this->assertSame('gzip', $response['headers']['content-encoding']);
        $this->assertSame('Accept-Encoding', $response['headers']['vary']);
        $this->assertSame(str_repeat('Laravel local homepage ', 1000), gzdecode($response['body']));
    }

    #[RequiresPhpExtension('zlib')]
    public function test_homepage_is_not_compressed_when_gzip_has_zero_quality(): void
    {
        $response = $this->request('/', 'gzip;q=0');

        $this->assertArrayNotHasKey('content-encoding', $response['headers']);
        $this->assertSame('Accept-Encoding', $response['headers']['vary']);
        $this->assertSame(str_repeat('Laravel local homepage ', 1000), $response['body']);
    }

    public function test_static_assets_are_served_with_the_original_content_and_mime_type(): void
    {
        $response = $this->request('/asset.js', 'gzip');

        $this->assertSame('export const loaded = true;', $response['body']);
        $this->assertStringContainsString('javascript', $response['headers']['content-type']);
        $this->assertArrayNotHasKey('content-encoding', $response['headers']);
    }

    public function test_other_application_routes_reach_the_front_controller(): void
    {
        $response = $this->request('/shop/example', 'gzip');

        $this->assertSame(str_repeat('Laravel local homepage ', 1000), $response['body']);
        $this->assertArrayNotHasKey('content-encoding', $response['headers']);
    }

    /**
     * @return array{headers: array<string, string>, body: string}
     */
    private function request(string $path, string $encoding): array
    {
        $body = file_get_contents($this->serverUrl.$path, false, stream_context_create([
            'http' => [
                'header' => 'Accept-Encoding: '.$encoding,
                'timeout' => 2,
            ],
        ]));
        $this->assertIsString($body);

        $headers = [];

        foreach ($http_response_header as $header) {
            $parts = explode(':', $header, 2);

            if (count($parts) === 2) {
                $headers[strtolower($parts[0])] = trim($parts[1]);
            }
        }

        return ['headers' => $headers, 'body' => $body];
    }
}
