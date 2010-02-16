<?php

/*
 *
 * Copyright (c) 2010 Nicholas Granado
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

include_once('../lib/RESTful.class.php');
include_once('../lib/Smarty2/Smarty.class.php');
include_once('../lib/BaseController.class.php');

// your site controllers should go below here
include_once('../controllers/RootController.php');
include_once('../controllers/UsersController.php');

// do not load our php includes unless we actually use them
spl_autoload_register();

$DEBUG = false;
$restful = null;

if($DEBUG)
{
	$restful = new RESTful('debug');
	$restful->FlushCache();
}
else
{
	$restful = new RESTful('prod');
}

$restful->AddClass('RootController');
$restful->AddClass('UsersController', '/users');
$restful->HandleRequests();
