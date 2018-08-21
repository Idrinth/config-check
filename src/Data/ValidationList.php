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
    public function addFile(FileResult $file)
    {
        $this->list[] = $file;
    }

    /**
     * @param int $verbose
     * @param boolean $warningAsError
     * @return [int,string]
     */
    public function finish($verbose = 0, $warningAsError = false)
    {
        $code = 0;
        $message = '';
        $valid = $total = count($this->list);
        foreach ($this->list as $file) {
            $errors = $file->getErrorNum($warningAsError);
            if ($errors > 0) {
                $valid--;
            }
            $code += $errors;
            $message .= $file->getMessage($verbose, $warningAsError);
        }
        return array(
            $code,
            "\nConfig Check: ".($valid === $total ? "OK" : "Failed")."\n$valid/$total OK\n\n$message\n"
        );
    }
}
