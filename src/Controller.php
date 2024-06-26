<?php

namespace De\Idrinth\ConfigCheck;

use De\Idrinth\ConfigCheck\Data\Config;
use De\Idrinth\ConfigCheck\Data\SchemaStore\Json;
use De\Idrinth\ConfigCheck\Data\SchemaStore\Xml;
use De\Idrinth\ConfigCheck\Data\ValidationList;
use De\Idrinth\ConfigCheck\Service\FileFinder;
use De\Idrinth\ConfigCheck\Service\FileRetriever;
use De\Idrinth\ConfigCheck\Service\FileValidator;
use De\Idrinth\ConfigCheck\Service\IniFileValidator;
use De\Idrinth\ConfigCheck\Service\JsonFileValidator;
use De\Idrinth\ConfigCheck\Service\XmlFileValidator;
use De\Idrinth\ConfigCheck\Service\YamlFileValidator;
use JsonSchema\Validator;

class Controller
{
    private int $code;
    private string $text;

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
     * @return array<string, FileValidator>
     */
    private function getValidators(Config $config): array
    {
        $fileRetriever = new FileRetriever($config->getRootDir());
        return array(
            'yaml' => new YamlFileValidator(new Json($fileRetriever, $config->getMapping('yaml')), new Validator()),
            'ini' => new IniFileValidator(new Json($fileRetriever, $config->getMapping('ini'))),
            'xml' => new XmlFileValidator(new Xml($fileRetriever, $config->getMapping('xml'))),
            'json' => new JsonFileValidator(new Json($fileRetriever, $config->getMapping('json')), new Validator()),
        );
    }

    public function getCode(): int
    {
        return (int) $this->code;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
