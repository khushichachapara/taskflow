<?php

namespace TaskFlow\Core;

class ApcuCacheService
{
    public function isEnabled(): bool
    {
        return extension_loaded('apcu_fetch') && ini_get('apc.enabled');
    }

    public function get(string $key)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return apcu_fetch($key);
    }

    public function set(string $key, $value, int $ttl = 300): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return apcu_store($key, $value, $ttl);
    }

    public function delete(string $key): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return apcu_delete($key);
    }

    public function clear(): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return apcu_clear_cache();
    }
}