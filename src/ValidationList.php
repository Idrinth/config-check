<?php
namespace De\Idrinth\JsonCheck;

class ValidationList
{
    private $list = array();
    private $verbose;
    public function __construct($verbose=0)
    {
        $this->verbose = $verbose;
    }
    public function addFile(JsonFileResult $file) {
        $this->list[] = $file;
    }
    public function finish() {
        $code = 0;
        $message = '';
        foreach ($this->list as $file) {
            $code += $file->getErrorNum();
            $message .= "\n".$file->getMessage($this->verbose);
        }
        return array($code, $message);
    }
}