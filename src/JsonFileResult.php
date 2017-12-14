<?php
namespace De\Idrinth\JsonCheck;

class JsonFileResult
{
    private $path;
    private $messages = array();
    public function __construct($path, $verbose=0)
    {
        $this->path = $path;
        $this->verbose = $verbose;
    }
    public function addMessage(Message $message) {
        $this->messages[] = $message;
    }
    public function getMessage($verbose)
    {
        $content = "$this->path\n";
        foreach($this->messages as $message) {
            $short = ($message instanceof ErrorMessage?"✓":"✗");
            $content.= $verbose==0 ? "" : $verbose==1 ? $short : "[$short] $message\n";
        }
        return $content;
    }
    public function getErrorNum()
    {
        $amount = 0;
        foreach($this->messages as $message) {
            if($message instanceof ErrorMessage) {
                $amount++;
            }
        }
        return $amount;
    }
}