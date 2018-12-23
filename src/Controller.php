<?php

namespace De\Idrinth\ConfigCheck;

use De\Idrinth\ConfigCheck\Data\Config;
use De\Idrinth\ConfigCheck\Data\SchemaStore\Json;
use De\Idrinth\ConfigCheck\Data\SchemaStore\Xml;
use De\Idrinth\ConfigCheck\Data\ValidationList;
use De\Idrinth\ConfigCheck\Service\FileFinder;
use De\Idrinth\ConfigCheck\Service\FileRetriever;
use De\Idrinth\ConfigCheck\Service\IniFileValidator;
use De\Idrinth\ConfigCheck\Service\JsonFileValidator;
use De\Idrinth\ConfigCheck\Service\XmlFileValidator;
use De\Idrinth\ConfigCheck\Service\YamlFileValidator;

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
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $validators = $this->getValidators($config);
        $validator = new ValidateFileList(new FileFinder(), $config->getRootDir(), $validators);
        $data = new ValidationList();
        foreach (array('yaml', 'ini', 'xml', 'json') as $type) {
            if ($config->isEnabled($type)) {
                foreach ($config->getExtensions($type) as $extension) {
                    $validator->process($extension, $type, $data, $config->getBlacklist($type));
                }
            }
        }
        list($this->code, $this->text) = $data->finish(
            $config->getVerbosity(),
            $config->hasWarningsAsErrors()
        );
    }

    /**
     * @param Config $config
     * @return FileValidator[]
     */
    private function getValidators(Config $config)
    {
        $fileRetriever = new FileRetriever($config->getRootDir());
        return array(
            'yml' => new YamlFileValidator(new Json($fileRetriever, $config->getMapping('yaml'))),
            'ini' => new IniFileValidator(new Json($fileRetriever, $config->getMapping('ini'))),
            'xml' => new XmlFileValidator(new Xml($fileRetriever, $config->getMapping('xml'))),
            'json' => new JsonFileValidator(new Json($fileRetriever, $config->getMapping('json'))),
        );
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return (int) $this->code;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
