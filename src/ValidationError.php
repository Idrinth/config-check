<?php
namespace De\Idrinth\JsonCheck;

class ValidationError
{
    private $message;
    public function __construct($message, $offset=0)
    {
        $this->message = str_pad("", $offset, "  ", STR_PAD_LEFT).$message;
    }
    public function __toString()
    {
        return $this->message;
    }
}