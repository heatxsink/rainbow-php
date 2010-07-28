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
	
	protected static $template_engine = NULL;
	public function __contruct() {}
	public function __destruct() {}
	public function __clone()    {}
	
	public static function Render($template) {
		
		self::GetTemplateEngine()->display($template);
	}
	
	public static function SetParameter($key, $value) {
		
		self::GetTemplateEngine()->assign($key, $value);
	}
	
	private static function GetTemplateEngine() {
		
		if (!self::$template_engine) {
			self::$template_engine = new Smarty();
			self::$template_engine->debugging = Config::$SMARTY_DEBUG_MODE;
			self::$template_engine->template_dir = Config::$SMARTY_TEMPLATE_DIRECTORY;
			self::$template_engine->compile_dir = Config::$SMARTY_COMPILE_DIRECTORY;
			self::$template_engine->cache_dir = Config::$SMARTY_CACHE_DIRECTORY;
			self::$template_engine->config_dir = Config::$SMARTY_CONFIG_DIRECTORY;
		}
		
		return self::$template_engine;
	}

}