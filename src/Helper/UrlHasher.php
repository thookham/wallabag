<?php

namespace Wallabag\Helper;

/**
 * Hash URLs for privacy and performance.
 */
class UrlHasher
{
    /**
     * Hash the given url using the given algorithm.
     * Hashed url are faster to be retrieved in the database than the real url.
     *
     * @param string $algorithm
     *
     * @return string
     */
    public static function hashUrl(string $url, $algorithm = 'sha1')
    {
        $url = urldecode($url);

        // Normalize URL: remove common tracking parameters
        $parsed = parse_url($url);
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
            $trackingParams = [
                'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
                'ref', 'fbclid', 'gclid', '_ga', '_gl',
            ];
            foreach ($trackingParams as $param) {
                unset($query[$param]);
            }
            $parsed['query'] = http_build_query($query);
            $url = (isset($parsed['scheme']) ? $parsed['scheme'] . '://' : '') .
                   (isset($parsed['host']) ? $parsed['host'] : '') .
                   (isset($parsed['port']) ? ':' . $parsed['port'] : '') .
                   (isset($parsed['path']) ? $parsed['path'] : '') .
                   ('' !== $parsed['query'] ? '?' . $parsed['query'] : '') .
                   (isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '');
        }

        return hash($algorithm, $url);
    }
}
