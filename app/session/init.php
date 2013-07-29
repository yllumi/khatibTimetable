<?php

use PavraSession\Components\Autoload\Autoload;

require 'PavraSession/Components/Autoload/Autoload.php';

$autoloader = new Autoload();
$autoloader->addSearchPath(dirname(__FILE__));

spl_autoload_register(array($autoloader,'defaultLoader'));


