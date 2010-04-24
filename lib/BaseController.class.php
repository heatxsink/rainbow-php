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

class BaseController {
	
	protected static $smarty = NULL;
	protected static $DEBUG = false;
	public function __contruct() {}
	public function __destruct() {}
	public function __clone()    {}
	
	public static function RenderTemplate($template) {
		
		self::GetSmarty()->display($template);
	}
	
	public static function GetSmarty() {
		
		if (!self::$smarty) {
			
			self::$smarty = new Smarty();
			self::$smarty->debugging = self::$DEBUG;
			self::$smarty->template_dir = '../smarty/templates';
			self::$smarty->compile_dir = '../smarty/templates_c';
			self::$smarty->cache_dir = '../smarty/cache';
			self::$smarty->config_dir = '../smarty/configs';
		}
		
		return self::$smarty;
	}

}