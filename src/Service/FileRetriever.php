<?php

namespace De\Idrinth\ConfigCheck\Service;

class FileRetriever
{
    public function __construct(private string $root)
    {
    }

    public function get(string $uri): string
    {
        if (str_starts_with($uri, 'cwd://')) {
            return file_get_contents(str_replace('cwd://', $this->root . DIRECTORY_SEPARATOR, $uri));
        }
        if (! preg_match('/^.+?:\\/\\//', $uri)) {
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
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
