<?php
namespace Oscar\TestApi\ErrorHandler;

use Throwable;
use ErrorException;

class ErrorHandler
{
    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);
        
        $errorResponse = [
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()
        ];

        echo json_encode($errorResponse, JSON_PRETTY_PRINT);
    }
    
    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        // Gestion des erreurs selon le niveau de rapport d'erreurs actuel
        if (error_reporting() !== 0) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }

        // Permet à PHP de gérer l'erreur normalement
        return false;
    }
}
