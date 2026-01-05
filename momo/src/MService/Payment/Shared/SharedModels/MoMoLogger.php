<?php
namespace MService\Payment\Shared\SharedModels;

class MoMoLogger
{
    protected $loggingOff;

    public function __construct(string $name = 'MoMoDefault', bool $loggingOff = false, array $handlers = array(), array $processors = array())
    {
        $this->loggingOff = $loggingOff;
    }

    public function getLoggingOff()
    {
        return $this->loggingOff;
    }

    public function setLoggingOff($loggingOff): void
    {
        $this->loggingOff = $loggingOff;
    }

    public function addRecord($level, $message, array $context = array()) : bool
    {
        if (!$this->loggingOff) {
            error_log("MoMoLogger: " . $message);
        }
        return true;
    }
    
    public function info($message, array $context = array()): void
    {
        $this->addRecord(200, $message, $context);
    }
    
    public function error($message, array $context = array()): void
    {
        $this->addRecord(400, $message, $context);
    }

    public function debug($message, array $context = array()): void
    {
        $this->addRecord(100, $message, $context);
    }
}