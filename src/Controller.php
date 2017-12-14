<?php
namespace De\Idrinth\JsonCheck;

use De\Idrinth\JsonCheck\Service\FileFinder;

class Controller
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $text;

    /**
     * @param string $dir
     * @param array $params
     */
    public function __construct($dir, $params)
    {
        $verbose = !isset($params['v']) ? 0 : is_array($params['v']) ? count($params['v']) : 1;
        $finder = new FileFinder();
        list($this->code, $this->text) = $finder->checkDir($dir)->finish($verbose);
    }

    /**
     * @return int
     */
    public function getCode() {
        return (int) $this->code;
    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }
}