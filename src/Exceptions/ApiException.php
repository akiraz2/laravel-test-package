<?php


namespace akiraz\LaravelTestPackage\Exceptions;


class ApiException extends \Exception
{
    private $reasons = [];

    public function __construct(array $reasons)
    {
        $this->reasons = $reasons;
        parent::__construct($this->reasons[0]['message']);
    }
}
