<?php
namespace De\Idrinth\ConfigCheck\Data;

class ValidationList
{
    /**
     * @var FileResult[]
     */
    private $list = array();

    /**
     * @param FileResult $file
     */
    public function addFile(FileResult $file) {
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