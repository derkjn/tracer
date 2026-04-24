<?php

use League\Container\Container;

if (!function_exists('app')) {
    function app()
    {
        static $container = null;

        if ($container === null) {
            $container = new Container();
        }

        return $container;
    }
}

if (!function_exists('uuid4')) {
    function uuid4()
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(16);
        } else {
            $isStrongCrypto = false;
            $bytes = openssl_random_pseudo_bytes(16, $isStrongCrypto);

            if ($bytes === false || $isStrongCrypto !== true) {
                throw new RuntimeException('Unable to generate a UUID v4.');
            }
        }

        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40);
        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
