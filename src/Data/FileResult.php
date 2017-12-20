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
    public function addMessage(Message $message) {
        $this->messages[] = $message;
    }

    /**
     * @param int $verbose
     * @return string
     */
    public function getMessage($verbose = 0)
    {
        if(count($this->messages) === 0 && $verbose < 1) {
            return '';
        }
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