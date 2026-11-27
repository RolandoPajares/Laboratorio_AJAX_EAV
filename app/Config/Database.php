<?php
declare(strict_types=1);

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) return self::$connection;
        $configFile = __DIR__ . '/config.php';
        if (!is_file($configFile)) {
            error_log('EAV Store: falta app/Config/config.php');
            throw new RuntimeException('Falta configurar la conexión a la base de datos.');
        }
        require_once $configFile;
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);
        try {
            self::$connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            return self::$connection;
        } catch (PDOException $exception) {
            error_log('EAV Store: error de conexión: ' . $exception->getMessage());
            throw new RuntimeException('No fue posible conectar con la base de datos.');
        }
    }
}
