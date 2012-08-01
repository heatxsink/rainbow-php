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

function rainbow_curl_http_get($url) {
	$ssl = false;
	$ch = curl_init($url);
	$headers = array("User-Agent: rainbow-php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	if ($ssl) {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	}
	$response = curl_exec($ch);
	return $response;
}

function rainbow_curl_http_get_store($url, $filename) {
	$data = rainbow_curl_http_get($url);
	$fh = fopen($filename, 'w');
	fwrite($fh, $data);
	fclose($fh);
}

function rainbow_generate_guid() {
	return sprintf('%04x%04x%04x%03x4_%04x%04x%04x%04x',
		mt_rand(0, 65535), mt_rand(0, 65535),
		mt_rand(0, 65535),
		mt_rand(0, 4095),
		bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
		mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)
	);
}

function rainbow_validate_var($var) {
	$return_value = false;
	if(isset($var) && strlen(trim($var)) > 0) {
		$return_value = true;
	}
	return $return_value;
}

function rainbow_validate_email_address($email) {
	$qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
	$dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
	$atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
		'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
	$quoted_pair = '\\x5c[\\x00-\\x7f]';
	$domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";
	$quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";
	$domain_ref = $atom;
	$sub_domain = "($domain_ref|$domain_literal)";
	$word = "($atom|$quoted_string)";
	$domain = "$sub_domain(\\x2e$sub_domain)*";
	$local_part = "$word(\\x2e$word)*";
	$addr_spec = "$local_part\\x40$domain";

	return preg_match("!^$addr_spec$!", $email);
}

function rainbow_get_client_ip_address() {
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$ip_tokens = split('\.', $ip_address);
	$ip_decimal = ($ip_tokens[3] + $ip_tokens[2] * 256 + $ip_tokens[1] * 256 * 256 + $ip_tokens[0] * 256 * 256 * 256);
	return $ip_decimal;
}

function rainbow_get_unix_timestamp() {
	$unix_time = time();
	$gmt_mysql_time_string = gmdate("Y-m-d H:i:s", $unix_time);
	return $gmt_mysql_time_string;
}

function rainbow_json_encode_pretty($input) {
	$tab = "  ";
	$return_value = "";
	$indent_level = 0;
	$in_string = false;
	$json = json_encode($input);
	$len = strlen($json);

	for ($c = 0; $c < $len; $c++)
	{
		$char = $json[$c];
		switch ($char)
		{
			case '{':
			case '[':
				if (!$in_string) {
					$return_value .= $char . "\n" . str_repeat($tab, $indent_level + 1);
					$indent_level++;
				}
				else
				{
					$return_value .= $char;
				}
				break;
			case '}':
			case ']':
				if (!$in_string) {
					$indent_level--;
					$return_value .= "\n" . str_repeat($tab, $indent_level) . $char;
				}
				else
				{
					$return_value .= $char;
				}
				break;
			case ',':
				if (!$in_string) {
					$return_value .= ",\n" . str_repeat($tab, $indent_level);
				}
				else
				{
					$return_value .= $char;
				}
				break;
			case ':':
				if (!$in_string) {
					$return_value .= ": ";
				}
				else
				{
					$return_value .= $char;
				}
				break;
			case '"':
				$in_string = !$in_string;
			default:
				$return_value .= $char;
				break;
		}
	}
	return $return_value;
}

function rainbow_is_ipad_request() {
	return strstr($_SERVER['HTTP_USER_AGENT'], 'iPad');
}

function rainbow_is_iphone_request() {
	return strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone');
}

function rainbow_is_android_request() {
	return strstr($_SERVER['HTTP_USER_AGENT'], 'Android');
}

function rainbow_is_webos_request() {
	return strstr($_SERVER['HTTP_USER_AGENT'], 'webOS');
}

function rainbow_is_ios_request() {
	return strstr($_SERVER['HTTP_USER_AGENT'], 'iPad') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPod') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone');
}

?>
