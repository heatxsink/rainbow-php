<?php
/*
 *
 * Copyright (c) 2012 Nicholas Granado
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

class RainbowConfig {

	public static $RAINBOW_DEBUG_MODE = false;
	public static $RAINBOW_ROUTE_TABLE = '../../cache/route_table.apc';
	// $RAINBOW_ROUTE_TABLE_PREFIX is useful when you have more than one 
	// verison of a rainbow-php app is running on a server.
	public static $RAINBOW_ROUTE_TABLE_PREFIX = '--';
	public static $RAINBOW_DEFAULT_TIMEZONE = 'America/Los_Angeles';
	public static $SMARTY_DEBUG_MODE = false;
	public static $SMARTY_TEMPLATE_DIRECTORY = '../views/smarty/templates';
	public static $SMARTY_COMPILE_DIRECTORY = '../views/smarty/templates_c';
	public static $SMARTY_CACHE_DIRECTORY = '../views/smarty/cache';
	public static $SMARTY_CONFIG_DIRECTORY = '../views/smarty/configs';
}

?>