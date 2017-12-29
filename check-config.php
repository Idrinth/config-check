<?php

use De\Idrinth\ConfigCheck\Controller;
use De\Idrinth\ConfigCheck\Data\Config;

require_once 'vendor/autoload.php';

$controller = new Controller(new Config(getcwd(), getopt('vw')));
echo $controller->getText();
die($controller->getCode());