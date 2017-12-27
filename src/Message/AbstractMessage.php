<?php

namespace De\Idrinth\ConfigCheck\Message;

use De\Idrinth\ConfigCheck\Message;

abstract class AbstractMessage implements Message
{
    /**
     * @var string
     */
    private $message;

    /**
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param int $verbose
     * @return string
     */
    public function toString($verbose = 0)
    {
        if ($verbose === 0) {
            return "";
        }
        if ($verbose === 1) {
            return $this->getSymbol();
        }
        return "[{$this->getSymbol()}] $this->message\n";
    }

    /**
     * @return string
     */
    abstract protected function getSymbol();
}
