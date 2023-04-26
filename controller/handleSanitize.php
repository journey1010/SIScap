<?php 

class handleSanitize {

    protected function handlerError (Throwable $e): void
    {
        $errorMessage = date("Y-m-d H:i:s") . " : " . $e->getMessage() . "\n";
        error_log($errorMessage, 3, _ROOT_PATH . '/log/error.log' );
        return;
    }

    protected function SanitizeVarInput (string $var ): string
    {
        $varSanitize = filter_var($var, FILTER_SANITIZE_SPECIAL_CHARS);
        $varSanitize = htmlspecialchars($var, ENT_QUOTES, "UTF-8");
        return $varSanitize;
    }
}