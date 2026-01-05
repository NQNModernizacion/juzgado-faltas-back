<?php

namespace App\Utils;

use RuntimeException;

trait ReadOnlyModel
{
    public function save(array $options = [])
    {
        throw new RuntimeException(static::class . ' es read-only.');
    }

    public function delete()
    {
        throw new RuntimeException(static::class . ' es read-only.');
    }

    public function update(array $attributes = [], array $options = [])
    {
        throw new RuntimeException(static::class . ' es read-only.');
    }

    public static function create(array $attributes = [])
    {
        throw new RuntimeException(static::class . ' es read-only.');
    }
}
