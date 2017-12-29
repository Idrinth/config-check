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
        if (!preg_match('/^.+?:\\/\\//', $uri)) {
            return file_get_contents(
                is_file($uri) ?
                $uri :
                $this->root.DIRECTORY_SEPARATOR.$uri
            );
        }
        if (extension_loaded('curl')) {
            return $this->getCurled($uri);
        }
        return file_get_contents($uri);
    }

    /**
     * @param string $uri
     * @return string
     */
    private function getCurled($uri) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
