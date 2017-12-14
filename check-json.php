<?php
require_once 'vendor/autoload.php';
$controller = new \De\Idrinth\JsonCheck\Controller(getcwd(), getopt('v'));
echo $controller->getText();
die($controller->getCode());