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

class BaseController {
	
	protected static $template_engine = NULL;
	public function __construct() {}
	public function __destruct() {}
	public function __clone()    {}
	
	public function Render($template) {
		
		self::GetTemplateEngine()->display($template);
	}
	
	public function SetParameter($key, $value) {
		
		self::GetTemplateEngine()->assign($key, $value);
	}
	
	public static function GetTemplateEngine() {
		
		if (!self::$template_engine) {
			self::$template_engine = new Smarty();
			self::$template_engine->debugging = RainbowConfig::$SMARTY_DEBUG_MODE;
			self::$template_engine->template_dir = RainbowConfig::$SMARTY_TEMPLATE_DIRECTORY;
			self::$template_engine->compile_dir = RainbowConfig::$SMARTY_COMPILE_DIRECTORY;
			self::$template_engine->cache_dir = RainbowConfig::$SMARTY_CACHE_DIRECTORY;
			self::$template_engine->config_dir = RainbowConfig::$SMARTY_CONFIG_DIRECTORY;
		}
		
		return self::$template_engine;
	}
	
	public function RaiseErrorMissingParam($param) {
		$status_code = 400;
		$this->service->SetStatus($status_code);
		
		$data = $this->RenderJson(
			array(
				"error" => "$param parameter is malformed or missing", 
				"status_code" => $status_code . ' ' . $this->service->status_codes[$status_code]
			)
		);
		
		$this->service->SendData($data);
	}

	public function RaiseErrorUnauthorized($param) {
		$status_code = 401;
		$this->service->SetStatus($status_code);
		
		$data = $this->RenderJson(
			array(
				"error" => "$param parameter is invalid", 
				"status_code" => $status_code . ' ' . $this->service->status_codes[$status_code]
			)
		);
		
		$this->service->SendData($data);
	}

	public function RenderJson($data) {
		$this->service->SetFormat(ContentType::APPLICATION_JSON);
		$return_value = '';
		
		if(rainbow_validate_var($_GET['formatted'])) {
			$this->service->SetFormat(ContentType::APPLICATION_X_JAVASCRIPT);
			$return_value = rainbow_json_encode_pretty($data);
		} else {
			
			$return_value = json_encode($data);
		}
		
		if(rainbow_validate_var($_GET['json_callback'])) {
			
			$json_callback = $_GET['json_callback'];
			$return_value = sprintf("%s(%s);", $json_callback, $return_value);
		}
		
		$this->service->SendData($return_value);
	}
}
