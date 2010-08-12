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

class ContentType {
	
	const APPLICATION_X_JAVASCRIPT = 'application/x-javascript';
	const APPLICATION_JSON = 'application/json';
	const TEXT_PLAIN = 'text/plain';
	const TEXT_HTML = 'text/html; charset=UTF-8';
}

class Rainbow {
	
	public $url;
	public $method;
	public $format;
	
	protected $cache_file = '';
	protected $mode = 'debug';
	protected $route_table = array();
	protected $error_classes = array();
	protected $cached = false;

	public $status_codes = array(
		'100' => 'Continue',
		'200' => 'OK',
		'201' => 'Created',
		'202' => 'Accepted',
		'203' => 'Non-Authoritative Information',
		'204' => 'No Content',
		'205' => 'Reset Content',
		'206' => 'Partial Content',
		'300' => 'Multiple Choices',
		'301' => 'Moved Permanently',
		'302' => 'Found',
		'303' => 'See Other',
		'304' => 'Not Modified',
		'305' => 'Use Proxy',
		'307' => 'Temporary Redirect',
		'400' => 'Bad Request',
		'401' => 'Unauthorized',
		'402' => 'Payment Required',
		'403' => 'Forbidden',
		'404' => 'Not Found',
		'405' => 'Method Not Allowed',
		'406' => 'Not Acceptable',
		'409' => 'Conflict',
		'410' => 'Gone',
		'411' => 'Length Required',
		'412' => 'Precondition Failed',
		'413' => 'Request Entity Too Large',
		'414' => 'Request-URI Too Long',
		'415' => 'Unsupported Media Type',
		'416' => 'Requested Range Not Satisfiable',
		'417' => 'Expectation Failed',
		'500' => 'Internal Server Error',
		'501' => 'Not Implemented',
		'503' => 'Service Unavailable'
	);

	public function	 __construct($mode = 'debug') {
		$this->cache_file = Config::$RAINBOW_ROUTE_TABLE;
		$this->mode = $mode;

		if($mode == 'prod') {
			
			if(function_exists('apc_fetch')) {
				
				$route_table = apc_fetch('route_table');
			}
			elseif(file_exists(dirname(__FILE__) . $this->cache_file)) {
				
				$route_table = unserialize(file_get_contents(dirname(__FILE__) . $this->cache_file));
			}
			
			if($route_table && is_array($route_table)) {
				
				$this->route_table = $route_table;
				$this->cached = true;
			}
		}
	}

	public function	 __destruct() {
		
		if($this->mode == 'prod' && !$this->cached) {
			
			if(function_exists('apc_store')) {
				
				apc_store('route_table', $this->route_table);
			} else {
				
				file_put_contents(dirname(__FILE__) . $this->cache_file, serialize($this->route_table));
			}
		}
	}

	public function FlushCache() {
		
		$this->route_table = array();
		$this->cached = false;
	}

	public function HandleRequests() {
		
		$this->url = $this->GetPath();
		$this->method = $this->GetMethod();
		$this->format = $this->GetFormat();
		
		if ($this->method == 'PUT' || $this->method == 'POST') {
			
			$this->data = $this->GetData();
		}

		$call = $this->FindUrl();
		if ($call) {
			
			$obj = $call[0];
			
			if (is_string($obj)) {
				
				if (class_exists($obj)) {
					
					$obj = new $obj();
				} else {
					
					throw new Exception("Class $obj does not exist");
				}
			}
			
			$obj->service = $this;
			$method = $call[1];
			
			if(count($call) > 2) {
				$params = $call[2];
			} else {
				$params = array();
			}
			
			$result = call_user_func_array(array($obj, $method), $params);
			// the following function is deprecated
			//$result = call_user_method_array($method, $obj, $params);
			
			if($result !== null) {
				
				$this->SendData($result);
			}
		} else {
			
			$this->HandleError(404);
		}
	}

	public function AddClass($class, $base_path = '') {
		
		if (!$this->cached) {
			
			if(is_string($class) && !class_exists($class)) {
				
				throw new Exception('Invalid method or class');
			} elseif(!is_string($class) && !is_object($class)) {
				
				throw new Exception('Invalid method or class; must be a classname or object');
			}
			
			if($base_path[0] == '/') {
				
				$base_path = substr($base_path, 1);
			}
			
			if($base_path[strlen($base_path) - 1] != '/') {
				
				$base_path .= '/';
			}
			
			$this->GenerateMap($class, $base_path);
		}
	}
	
	public function AddErrorClass($class) {
		
		$this->error_classes[] = $class;
	}
	
	public function HandleError($status_code) {
		
		$method = "handle$status_code";
		
		foreach($this->error_classes as $class) {
			
			if(is_object($class)) {
				
				$reflection = new ReflectionObject($class);
			} elseif(class_exists($class)) {
				
				$reflection = new ReflectionClass($class);
			}
			
			if($reflection->hasMethod($method)) {
				
				$obj = is_string($class) ? new $class() : $class;
				$obj->$method();
				return;
			}
		}
		
		$this->SetStatus($status_code);
		$this->SendData($status_code . ' ' . $this->status_codes[$status_code]);
	}

	protected function FindUrl() {
		
		$urls = $this->route_table[$this->method];

		if(!$urls) {

			return null;
		}
		
		foreach($urls as $url => $call) {
			
			if(!strstr($url, ':')) {
				
				if ($url == $this->url) {
					
					return $call;
				}
			} else {
				
				$regex = preg_replace('/\\\:([^\/]+)/', '(?P<$1>[^/]+)', preg_quote($url));
				
				if(preg_match(":^$regex$:", $this->url, $matches)) {
					
					$args = $call[2];
					$params = array();
					
					foreach($matches as $arg => $match) {

						if(isset($args[$arg])) {
							
							$params[$args[$arg]] = $match;
						}
					}
					
					ksort($params);
					$call[2] = $params;
					return $call;
				}
			}
		}
	}

	protected function GenerateMap($class, $base_path = '') {
		
		if(is_object($class)) {
			
			$reflection = new ReflectionObject($class);
		} elseif(class_exists($class)) {
			
			$reflection = new ReflectionClass($class);
		}

		$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

		foreach($methods as $method) {
			
			$doc = $method->getDocComment();
			
			if(preg_match_all('/@url\s+(GET|POST|PUT|DELETE|HEAD|OPTIONS)[ \t]*\/?(\S*)/s', $doc, $matches, PREG_SET_ORDER)) {
				
				$params = $method->getParameters();
				
				foreach($matches as $match) {
					
					$http_method = $match[1];
					$url = $base_path . $match[2];
					
					// if there's a trailing slash take it off!
					if($url[strlen($url) - 1] == '/') {
						
						$url = substr($url, 0, -1);
					}
					
					// if there's a slash up front take it off!
					if($url[0] == '/') {
						
						$url = substr($url, 1, strlen($url));
					}
					
					$call = array($class, $method->getName());
					
					if(strstr($url, ':')) {
						
						$args = array();
						foreach($params as $param) {
							
							$args[$param->getName()] = $param->getPosition();
						}
						
						$call[] = $args;
					}

					$this->route_table[$http_method][$url] = $call;
				}
			}
		}
	}

	public function GetQueryParams() {
		
		$query_params = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		$params = explode('&', $query_params);
		
		$return_value = array();
		foreach($params as $param) {
			
			$x = explode('=', $param);
			$return_value[$x[0]] = $x[1];
		}
		
		return $return_value;
	}

	public function GetPath() {
		
		$path = substr($_SERVER['REQUEST_URI'], 1);
		
		$position = strpos($path, "?");
		
		if($position) {
			
			$path = substr($path, 0, $position);
		}
		
		if($path[strlen($path) - 1] == '/') {
			
			$path = substr($path, 0, -1);
		}
		
		return $path;
	}
	
	public function GetMethod() {
		
		$method = $_SERVER['REQUEST_METHOD'];
		
		if($method == 'POST' && $_GET['method'] == 'PUT') {
			
			$method = 'PUT';
		} elseif($method == 'POST' && $_GET['method'] == 'DELETE') {
			
			$method = 'DELETE';
		}
		
		return $method;
	}
	
	public function SetFormat($content_type) {
		
		$this->format = $content_type;
	}
	
	public function GetFormat() {
		$format = ContentType::TEXT_HTML;
		$accept = explode(',', $_SERVER['HTTP_ACCEPT']);
		
		if(in_array(ContentType::TEXT_PLAIN, $accept)) {
			
			$format = ContentType::TEXT_PLAIN;
		} elseif(in_array(ContentType::APPLICATION_JSON, $accept)) {
			
			$format = ContentType::APPLICATION_JSON;
		}
		
		return $format;
	}
	
	public function GetData() {
		$data = file_get_contents('php://input');
		$data = json_decode($data);
		return $data;
	}
	

	public function SendData($data) {
		header('x-rainbow-php-version: 0.80');
		header('Content-Type: ' . $this->format);
		echo $data;
	}

	public function SetStatus($code) {
		$http_error_code = $code . " " . $this->status_codes[strval($code)];
		header("{$_SERVER['SERVER_PROTOCOL']} $http_error_code");
	}
}

?>