<?php
namespace De\Idrinth\JsonCheck\Data;

use De\Idrinth\JsonCheck\Message;

class JsonFileResult
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
    public function addMessage(Message $message) {
        $this->messages[] = $message;
    }

    /**
     * @param int $verbose
     * @return string
     */
    public function getMessage($verbose = 0)
    {
        $content = "\n$this->path\n";
        foreach($this->messages as $message) {
            $content .= $message->toString($verbose);
        }
        return $content;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }

    /**
     * @param boolean $warningsAsErrors
     * @return int
     */
    public function getErrorNum($warningsAsErrors = false)
    {
        $amount = 0;
        foreach($this->messages as $message) {
            if($message->isFailure($warningsAsErrors)) {
                $amount++;
            }
        }
        return $amount;
    }
}