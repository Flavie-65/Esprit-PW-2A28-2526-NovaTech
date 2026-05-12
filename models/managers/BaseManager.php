<?php

declare(strict_types=1);

/**
 * Base for all managers: sole access to PDO for persistence.
 * Controllers and entities must not call db() directly.
 */
abstract class BaseManager
{
    protected function getPdo(): PDO
    {
        require_once __DIR__ . '/../config/database.php';

        return db();
    }
}
