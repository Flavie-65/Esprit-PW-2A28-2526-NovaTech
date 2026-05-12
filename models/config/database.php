<?php

declare(strict_types=1);

/**
 * PDO connection (singleton). XAMPP defaults: host 127.0.0.1, user root, empty password.
 *
 * Database name: set environment variable GESTION_DB_NAME to match the database you
 * created in phpMyAdmin (recommended). Otherwise the app tries, in order:
 *   1) GESTION_DB_NAME (if set and non-empty)
 *   2) gestion_equipement (matches comments in sql/*.sql)
 *   3) gestion_dequipement (common slug variant for this project folder)
 *
 * PDO singleton only. Call from managers via BaseManager::getPdo() (not from controllers).
 */

/**
 * User-facing failure page when MySQL cannot be reached or no candidate database exists.
 * Does not print exception stack traces.
 */
function db_render_connection_failure(?PDOException $cause = null): void
{
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=UTF-8');
        http_response_code(503);
    }
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Service indisponible</title></head><body>';
    echo '<h1>Base de données indisponible</h1>';
    echo '<p>La connexion MySQL a échoué ou la base de données n’existe pas encore.</p>';
    echo '<ul>';
    echo '<li>Créez une base dans phpMyAdmin (par ex. <strong>gestion_equipement</strong>, comme dans <code>sql/equipment.sql</code>), ou</li>';
    echo '<li>Définissez la variable d’environnement <code>GESTION_DB_NAME</code> avec le nom exact de votre base, ou</li>';
    echo '<li>Ajustez hôte / utilisateur / mot de passe en tête de <code>models/config/database.php</code>.</li>';
    echo '</ul>';
    if (getenv('GESTION_DB_DEBUG') === '1' && $cause instanceof PDOException) {
        echo '<p><small>' . htmlspecialchars($cause->getMessage(), ENT_QUOTES, 'UTF-8') . '</small></p>';
    }
    echo '</body></html>';
    exit;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = '127.0.0.1';
    $port = '3306';
    $charset = 'utf8mb4';
    $user = 'root';
    $pass = '';

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $names = [];
    $env = getenv('GESTION_DB_NAME');
    if (is_string($env) && $env !== '') {
        $names[] = $env;
    }
    $names[] = 'gestion_equipement';
    $names[] = 'gestion_dequipement';
    $names = array_values(array_unique($names));

    $last = null;
    foreach ($names as $name) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $host,
            $port,
            $name,
            $charset
        );
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (PDOException $e) {
            $last = $e;
            $driverCode = isset($e->errorInfo[1]) ? (int) $e->errorInfo[1] : 0;
            if ($driverCode === 1049) {
                continue;
            }
            db_render_connection_failure($e);
        }
    }

    db_render_connection_failure($last instanceof PDOException ? $last : null);
}
