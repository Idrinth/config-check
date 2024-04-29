<?php

namespace De\Idrinth\ConfigCheck\Service;

class RetrievalFakes
{
    private static $fakeExtensionLoaded = null;
    private static $fakeFileGetContents = null;
    private static $fakeCurlExec = null;
    private static $wasCalled = [];

    /**
     * @param string|boolean $filedata
     * @param string|boolean $urldata
     * @param boolean $isExtensionLoaded
     */
    public function __construct($filedata = null, $urldata = null, $isExtensionLoaded = null)
    {
        $this->setFakeCurlExec($urldata);
        $this->setFakeExtensionLoaded($isExtensionLoaded);
        $this->setFakeFileGetContents($filedata);
    }

    /**
     * @param boolean $expectedReturn
     */
    public function setFakeExtensionLoaded($expectedReturn)
    {
        self::$fakeExtensionLoaded = $expectedReturn;
    }

    /**
     * @param string|boolean $expectedReturn
     */
    public function setFakeFileGetContents($expectedReturn)
    {
        self::$fakeFileGetContents = $expectedReturn;
    }

    /**
     * @param string|boolean $expectedReturn
     */
    public function setFakeCurlExec($expectedReturn)
    {
        self::$fakeCurlExec = $expectedReturn;
    }

    /**
     * @return array Function -> param -> number
     */
    public function getCalled()
    {
        $values = self::$wasCalled;
        self::$wasCalled = [];
        return $values;
    }
    /**
     * no more replacing
     */
    public function reset()
    {
        self::$fakeCurlExec = null;
        self::$fakeFileGetContents = null;
        self::$fakeExtensionLoaded = null;
        self::$wasCalled = [];
    }

    /**
     * no more replacing
     */
    public function __destruct()
    {
        $this->reset();
    }

    /**
     * increments call-count for a method with a specific param
     * @param type $function
     * @param type $param
     */
    private static function incrementCall($function, $param)
    {
        self::$wasCalled[$function] = isset(self::$wasCalled[$function]) ?
            self::$wasCalled[$function] :
            [];
        self::$wasCalled[$function][$param] = isset(self::$wasCalled[$function][$param]) ?
            self::$wasCalled[$function][$param] + 1 :
            1;
    }

    /**
     * @param string $name
     * @return boolean
     */
    public static function extensionLoaded($name)
    {
        self::incrementCall(__FUNCTION__, $name);
        if (self::$fakeExtensionLoaded !== null) {
            return self::$fakeExtensionLoaded;
        }
        return \extension_loaded($name);
    }

    /**
     * @param resource $curl
     * @return boolean|string
     */
    public static function curlExec($curl)
    {
        self::incrementCall(__FUNCTION__, "resource");
        if (self::$fakeCurlExec !== null) {
            return self::$fakeCurlExec;
        }
        return \curl_exec($curl);
    }

    /**
     * @param string $file
     * @return string|boolean
     */
    public static function fileGetContents($file)
    {
        self::incrementCall(__FUNCTION__, realpath($file) ?: $file);
        if (self::$fakeFileGetContents !== null) {
            return self::$fakeFileGetContents;
        }
        return \file_get_contents($file);
    }
}

namespace De\Idrinth\ConfigCheck\Service;

/**
 * replaces the global function in this specific namespace
 * @param string $name
 * @return boolean
 */
function extension_loaded($name)
{
    return \De\Idrinth\ConfigCheck\Service\RetrievalFakes::extensionLoaded($name);
}

/**
 * replaces the global function in this specific namespace
 * @param string $file
 * @return string
 */
function file_get_contents($file)
{
    return \De\Idrinth\ConfigCheck\Service\RetrievalFakes::fileGetContents($file);
}

/**
 * replaces the global function in this specific namespace
 * @param resource $curl
 * @return boolean|string
 */
function curl_exec($curl)
{
    return \De\Idrinth\ConfigCheck\Service\RetrievalFakes::curlExec($curl);
}
