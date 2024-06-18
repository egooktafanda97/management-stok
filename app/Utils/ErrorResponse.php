<?php

namespace App\Utils;

class ErrorResponse
{
    private static $instance;
    private $errors = [];

    private function __construct()
    {
        // Private constructor to prevent instantiation
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ErrorResponse();
        }
        return self::$instance;
    }

    public function setError($errorCode, $errorMessage)
    {
        $this->errors[$errorCode] = $errorMessage;
    }

    public function getError($errorCode)
    {
        return $this->errors[$errorCode] ?? null;
    }

    public function getAllErrors()
    {
        return $this->errors;
    }
}
