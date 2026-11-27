<?php
declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

final class Database
{
    private static ?Capsule $capsule = null;

    public static function boot(): Capsule
    {
        if (self::$capsule instanceof Capsule) {
            return self::$capsule;
        }

        $configFile = __DIR__ . '/config.php';

        if (!is_file($configFile)) {
            error_log('EAV Store: falta app/Config/config.php');
            throw new RuntimeException('Falta configurar la conexión a la base de datos.');
        }

        require_once $configFile;

        try {
            $capsule = new Capsule();

            $capsule->addConnection([
                'driver' => 'mysql',
                'host' => DB_HOST,
                'port' => DB_PORT,
                'database' => DB_NAME,
                'username' => DB_USER,
                'password' => DB_PASS,
                'charset' => DB_CHARSET,
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => 'InnoDB',
            ]);

            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            self::$capsule = $capsule;

            return self::$capsule;
        } catch (Throwable $exception) {
            error_log('EAV Store: error de conexión: ' . $exception->getMessage());
            throw new RuntimeException('No fue posible conectar con la base de datos.');
        }
    }
}
