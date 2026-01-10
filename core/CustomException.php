<?php

class CustomException extends Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class ValidationException extends CustomException {}
class PermissionException extends CustomException {}
class NotFoundException extends CustomException {}