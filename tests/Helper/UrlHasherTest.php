<?php

namespace Tests\Wallabag\Helper;

use PHPUnit\Framework\TestCase;
use Wallabag\Helper\UrlHasher;

class UrlHasherTest extends TestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testHashUrlNormalization(string $url1, string $url2, bool $shouldBeEqual)
    {
        $hash1 = UrlHasher::hashUrl($url1);
        $hash2 = UrlHasher::hashUrl($url2);

        if ($shouldBeEqual) {
            $this->assertEquals($hash1, $hash2, "Hash for '$url1' and '$url2' should be equal");
        } else {
            $this->assertNotEquals($hash1, $hash2, "Hash for '$url1' and '$url2' should not be equal");
        }
    }

    public function urlProvider(): array
    {
        return [
            ['https://example.com', 'https://example.com/', false], // Trail slash matters in some systems, depends on parse_url
            ['https://example.com?utm_source=test', 'https://example.com', true],
            ['https://example.com?utm_source=test&foo=bar', 'https://example.com?foo=bar', true],
            ['https://example.com?ref=123&utm_medium=email', 'https://example.com', true],
            ['https://example.com?fbclid=abc', 'https://example.com', true],
            ['https://example.com?gclid=xyz', 'https://example.com', true],
            ['https://example.com?_ga=1.2.3', 'https://example.com', true],
            ['https://example.com?real=parameter', 'https://example.com', false],
            ['https://example.com?real=parameter&utm_source=test', 'https://example.com?real=parameter', true],
        ];
    }
}
