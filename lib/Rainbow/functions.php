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

function curl_http_get($url) {
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
	$epi_curl = EpiCurl::getInstance();
	$response = $epi_curl->addCurl($ch);
	return $response->data;
}

function curl_http_get_store($url, $filename) {
	$data = curl_http_get($url);
	$fh = fopen($filename, 'w');
	fwrite($fh, $data);
	fclose($fh);
}

function generate_guid() {
	// The field names refer to RFC 4122 section 4.1.2
	return sprintf('%04x%04x%04x%03x4_%04x%04x%04x%04x',
	mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
	mt_rand(0, 65535), // 16 bits for "time_mid"
	mt_rand(0, 4095),  // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
	bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
	   // 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
	   // (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
	   // 8 bits for "clk_seq_low"
	mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node"
	);
}

function validate_var($var) {
	$return_value = false;
	if(isset($var) && strlen(trim($var)) > 0) {
		$return_value = true;
	}
	return $return_value;
}

function json_encode_pretty($input) {
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

?>