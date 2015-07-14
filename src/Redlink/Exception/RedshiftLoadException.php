<?php

namespace Redlink\Exception;

class RedshiftLoadException extends \Exception
{
    protected $driverCode;

    public function __construct($message, $sqlCode, $driverCode, \Exception $previous = null)
    {
        parent::__construct($message, $sqlCode, $previous);

        $this->driverCode = $driverCode;
    }

    public function getDriverCode()
    {
        return $this->driverCode;
    }
}