#!/usr/bin/php
<?php

use De\Idrinth\ConfigCheck\Controller;
use De\Idrinth\ConfigCheck\Data\Config;

require_once is_file('vendor/autoload.php') ? 'vendor/autoload.php' : dirname(dirname(__DIR__)).'autoload.php';

$controller = new Controller(new Config(getcwd(), getopt('vw')));
echo $controller->getText();
die($controller->getCode());
