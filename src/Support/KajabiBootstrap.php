<?php

declare(strict_types=1);

namespace PaymentEngine\Kajabi\Support;

final class KajabiBootstrap
{
    public static function registerAutoload(): void
    {
        self::requirePhpSdk();

        spl_autoload_register(static function (string $class): void {
            $prefixes = [
                'PaymentEngine\\Kajabi\\' => dirname(__DIR__) . DIRECTORY_SEPARATOR,
                'Remita\\Kajabi\\' => dirname(__DIR__) . DIRECTORY_SEPARATOR,
            ];

            foreach ($prefixes as $prefix => $baseDir) {
                if (!str_starts_with($class, $prefix)) {
                    continue;
                }

                $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
                $path = $baseDir . $relative . '.php';

                if (file_exists($path)) {
                    require_once $path;
                }

                return;
            }
        });
    }

    private static function requirePhpSdk(): void
    {
        $candidates = [
            dirname(__DIR__, 2) . '/vendor/payment-engine-sdk/index.php',
            dirname(__DIR__, 4) . '/developer-tools/server-side-sdks/php/src/index.php',
            dirname(__DIR__, 5) . '/developer-tools/server-side-sdks/php/src/index.php',
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                require_once $candidate;
                return;
            }
        }

        throw new \RuntimeException('Remita Checkout PHP SDK bootstrap file could not be found for Kajabi.');
    }
}
