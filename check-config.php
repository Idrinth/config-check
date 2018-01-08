#!/usr/bin/env php
<?php

use De\Idrinth\ConfigCheck\Controller;
use De\Idrinth\ConfigCheck\Data\Config;

$vendor = str_replace('/', DIRECTORY_SEPARATOR, '/vendor/autoload.php');
require_once is_file(__DIR__.$vendor) ? __DIR__.$vendor : dirname(dirname(__DIR__)).$vendor;

$controller = new Controller(new Config(getcwd(), getopt('vw')));
echo $controller->getText();
die($controller->getCode());
