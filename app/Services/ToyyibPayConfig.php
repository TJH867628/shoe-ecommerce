<?php

namespace App\Services;

class ToyyibPayConfig
{
    private static ?ToyyibPayConfig $instance = null;

    private string $secretKey = '2syf8kwx-ouwa-49cl-nzll-w4mkkeg4edix';
    private string $categoryCode = '3a4pwn1b';
    private string $baseUrl = 'https://dev.toyyibpay.com';

    private function __construct()
    {
    }

    public static function getInstance(): ToyyibPayConfig
    {
        if (self::$instance === null) {
            self::$instance = new ToyyibPayConfig();
        }

        return self::$instance;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getCategoryCode(): string
    {
        return $this->categoryCode;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
