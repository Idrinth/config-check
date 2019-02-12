<?php

namespace De\Idrinth\ConfigCheck\Service;

class FileRetriever
{
    /**
     * @var string
     */
    private $root;

    /**
     * @param string $root
     */
    public function __construct($root)
    {
        $this->root = $root;
    }

    /**
     * @param string $uri
     * @return string
     */
    public function get($uri)
    {
        if (strpos($uri, 'cwd://') === 0) {
            return file_get_contents(str_replace('cwd://', $this->root . DIRECTORY_SEPARATOR, $uri));
        }
        if (!preg_match('/^.+?:\\/\\//', $uri)) {
            return $this->getFromFilesystem($uri);
        }
        return extension_loaded('curl') ? $this->getCurled($uri) : file_get_contents($uri);
    }

    /**
     * @param string $uri
     * @return string
     */
    private function getFromFilesystem(string $uri): string
    {
        $uri = str_replace('/', DIRECTORY_SEPARATOR, $uri);
        return file_get_contents(
            is_file($uri) ?
            $uri :
            $this->root . DIRECTORY_SEPARATOR . $uri
        );
    }

    /**
     * @param string $uri
     * @return string
     */
    private function getCurled($uri)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
