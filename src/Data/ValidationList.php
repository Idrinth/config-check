<?php
namespace De\Idrinth\JsonCheck\Data;

class ValidationList
{
    /**
     * @var JsonFileResult[]
     */
    private $list = array();

    /**
     * @param JsonFileResult $file
     */
    public function addFile(JsonFileResult $file) {
        $this->list[] = $file;
    }

    /**
     * @param int $verbose
     * @param boolean $warningAsError
     * @return [int,string]
     */
    public function finish($verbose=0, $warningAsError=false)
    {
        $code = 0;
        $message = '';
        foreach ($this->list as $file) {
            $code += $file->getErrorNum($warningAsError);
            $message .= $file->getMessage($verbose);
        }
        return array($code, $message);
    }
}