<?php

namespace TaskFlow\Core;

use Redis;

class RedisService
{
    private ?Redis $client = null;

    public function __construct()
    {
        $host = $_ENV['REDIS_HOST'] ?? '127.0.0.1';
        $port = (int)($_ENV['REDIS_PORT'] ?? 6379);
        $auth = $_ENV['REDIS_PASSWORD'] ?? '';

        try {
            $this->client = new Redis();
            if (!$this->client->connect($host, $port, 2.5)) {
                $this->client = null;
                return;
            }


            if (!empty($auth)) {
                $this->client->auth($auth);
            }
        } catch (\Throwable $e) {
            // Fallback mode — app will continue without Redis
            $this->client = null;
        }
    }

    // Store value with time to live(TTL)
    public function set(string $key, string $value, int $ttl = 600): void
    {
        if (!$this->client) return;

        $this->client->setex($key, $ttl, $value);
    }

    // Get value
    public function get(string $key): ?string
    {
        if (!$this->client) return null;

        $result = $this->client->get($key);
        return $result !== false ? $result : null;
    }

    // Delete single key , cache invalidation
    public function delete(string $key): void
    {
        if (!$this->client) return;

        $this->client->del($key);
    }



    // Delete keys by pattern (cache invalidation)
    public function deleteByPattern(string $pattern): void
    {
        if (!$this->client) return;

        $keys = $this->client->keys($pattern);

        foreach ($keys as $key) {
            $this->client->del($key);
        }
    }

    // Clear all cache (use carefully)
    public function flush(): void
    {
        if (!$this->client) return;

        $this->client->flushAll();
    }


    // Check if key exists
    public function exists(string $key): bool
    {
        if (!$this->client) return false;

        return (bool) $this->client->exists($key);
    }
    // Get keys by pattern (for small projects)
    public function keys(string $pattern): array
    {
        if (!$this->client) return [];

        return $this->client->keys($pattern);
    }
}
