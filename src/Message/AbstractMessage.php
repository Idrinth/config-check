<?php
namespace De\Idrinth\JsonCheck\Message;

use De\Idrinth\JsonCheck\Message;

abstract class AbstractMessage implements Message
{
    private $message;
    public function __construct($message)
    {
        $this->message = $message;
    }
    public function __toString()
    {
        return $this->toString();
    }
    public function toString($verbose = 0)
    {
        if($verbose === 0) {
            return "";
        }
        if($verbose === 1) {
            return $this->getSymbol();
        }
        return "[{$this->getSymbol()}] $this->message\n";
    }
    abstract protected function getSymbol();
}