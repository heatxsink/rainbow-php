<?php
include_once('../lib/RESTful.class.php');
include_once('../controllers/RootController.php');
include_once('../controllers/UsersController.php');
spl_autoload_register(); // don't load our classes unless we use them

$restful = new RESTful('prod');
$restful->FlushCache();
$restful->AddClass('RootController');
$restful->AddClass('UsersController', '/users');

$restful->HandleRequests();
