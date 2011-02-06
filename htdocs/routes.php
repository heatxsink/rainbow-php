<?php
/*
 *
 * Copyright (c) 2011 Nicholas Granado
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

include_once('../lib/Smarty2/Smarty.class.php');
include_once('../lib/Rainbow/functions.php');
include_once('../lib/Rainbow/RainbowConfig.class.php');
include_once('../lib/Rainbow/Rainbow.class.php');
include_once('../lib/Rainbow/BaseController.class.php');

// application controllers
include_once('../controllers/RootController.php');
include_once('../controllers/UsersController.php');
include_once('../controllers/ApiController.php');

date_default_timezone_set(RainbowConfig::$RAINBOW_DEFAULT_TIMEZONE);

$rainbow = null;

if(RainbowConfig::$RAINBOW_DEBUG_MODE) {
	$rainbow = new Rainbow('debug');
	$rainbow->FlushCache();
} else {
	error_reporting(E_ALL ^ E_NOTICE);
	$rainbow = new Rainbow('prod');
}

$rainbow->AddClass('RootController', '/');
$rainbow->AddClass('UsersController', '/users/');
$rainbow->AddClass('ApiController', '/api/1/');
$rainbow->HandleRequests();
