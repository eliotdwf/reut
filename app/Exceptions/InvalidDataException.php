<?php

namespace App\Exceptions;

use Exception;

class InvalidDataException extends Exception
{
    private $errorMessage;
    private $invalidField;

    public function __construct($errorMessage = "Invalid data provided", $invalidField = null)
    {
        $this->errorMessage = $errorMessage;
        $this->invalidField = $invalidField;
        parent::__construct($this->errorMessage);
    }

    // getters
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
    public function getInvalidField()
    {
        return $this->invalidField;
    }
}
