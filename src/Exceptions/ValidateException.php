<?php

namespace Kingdarkness\Goship\Exceptions;

class ValidateException extends \Exception
{
    /**
    * @var array
    */
    public $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }
}
