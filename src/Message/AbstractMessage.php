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
     * @var int
     */
    private $minVerbosity;

    /**
     * @param string $message
     * @param int $minVerbosity
     */
    public function __construct($message, $minVerbosity)
    {
        $this->message = trim($message, "\n ");
        $this->minVerbosity = $minVerbosity;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString(1);
    }

    /**
     * @param int $verbose
     * @return string
     */
    public function toString($verbose = 0)
    {
        if ($verbose < $this->minVerbosity) {
            return "";
        }
        if ($verbose === $this->minVerbosity) {
            return $this->getSymbol();
        }
        return "  [{$this->getSymbol()}] $this->message\n";
    }

    /**
     * @return string
     */
    abstract protected function getSymbol();
}
