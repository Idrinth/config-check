<?php

namespace De\Idrinth\ConfigCheck\Data;

class ValidationList
{
    /**
     * @var FileResult[]
     */
    private array $list = [];

    public function addFile(FileResult $file): void
    {
        $this->list[] = $file;
    }

    /**
     * @param int $verbose
     * @param boolean $warningAsError
     * @return array [int,string]
     */
    public function finish(int $verbose = 0, bool $warningAsError = false): array
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
            "\nConfig Check: " . ($valid == $total ? "OK" : "Failed") . "\n$valid/$total OK\n\n$message\n"
        );
    }
}
