<?php
require_once 'vendor/autoload.php';
$controller = new \De\Idrinth\ConfigCheck\Controller(getcwd(), getopt('v'), new De\Idrinth\ConfigCheck\Data\Config());
echo $controller->getText();
die($controller->getCode());