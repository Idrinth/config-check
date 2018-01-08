<?php

namespace De\Idrinth\ConfigCheck\Data;

use De\Idrinth\ConfigCheck\Message;

class FileResult
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Message[]
     */
    private $messages = array();

    /**
     * @param type $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param Message $message
     */
    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
    }

    /**
     * @param int $verbose
     * @param boolean $warningsAsErrors
     * @return string
     */
    public function getMessage($verbose = 0, $warningsAsErrors = false)
    {
        if ($verbose < 1) {
            return '';
        }
        if (count($this->messages) === 0 && $verbose < 2) {
            return '';
        }
        $errors = $this->getErrorNum($warningsAsErrors);
        if ($errors === 0 && $verbose < 3) {
            return '';
        }
        $content = "\n[".($errors>0?'F':'X')."] $this->path\n";
        foreach ($this->messages as $message) {
            $content .= $message->toString($verbose);
        }
        return $content;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage(1);
    }

    /**
     * @param boolean $warningsAsErrors
     * @return int
     */
    public function getErrorNum($warningsAsErrors = false)
    {
        $amount = 0;
        foreach ($this->messages as $message) {
            if ($message->isFailure($warningsAsErrors)) {
                $amount++;
            }
        }
        return $amount;
    }
}
