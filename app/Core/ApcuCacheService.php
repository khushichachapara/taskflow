<?php

namespace TaskFlow\Core;

use APCUIterator;

class ApcuCacheService
{
    public function isEnabled(): bool
    {
        return extension_loaded('apcu') && ini_get('apc.enabled');
    }

    //sync apcu cache to file for persistence
    private function syncToFile(): void
    {
        $iterator = new APCUIterator();

        $data = [];

        foreach ($iterator as $item) {
            $success = false;
            $value = apcu_fetch($item['key'], $success);

            if ($success) {

                // old cache format
                if (!is_array($value) || !isset($value['expires_at'])) {
                    $data[$item['key']] = $value;
                    continue;
                }

                // new TTL format
                if ($value['expires_at'] > time()) {
                    $data[$item['key']] = $value['data'];
                }
            }
        }

        file_put_contents(
            dirname(__DIR__, 2) . '/storage/cache/apcu_cache.json',
            json_encode($data, JSON_PRETTY_PRINT)
        );
    }



    public function get(string $key)
    {
        if (!$this->isEnabled()) {
            return false;
        }
        $success = false;
        $payload = apcu_fetch($key, $success);


        if (!$success) {
            return false;
        }

        // check expiry
        if ($payload['expires_at'] < time()) {
            apcu_delete($key);
            return false;
        }

        return $payload['data'];
    }

    public function set(string $key, $value, int $ttl = 600): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $payload = [
            'data' => $value,
            'expires_at' => time() + $ttl
        ];

        $stored = apcu_store($key, $payload, $ttl);

        if ($stored) {
            $this->syncToFile();
        }

        return $stored;
    }



    public function delete(string $key): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        apcu_delete($key);

        $this->syncToFile();

        return true;
    }

    public function clear(): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $cleared = apcu_clear_cache();

        if ($cleared) {
            $this->syncToFile();
        }

        return $cleared;
    }
}
