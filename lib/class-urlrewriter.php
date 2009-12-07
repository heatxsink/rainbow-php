<?php

/*
 *
 * Copyright (c) 2009 Nicholas Granado
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


class UrlRewriter
{
	public static function Transform($path)
	{
		$return_value = '';
		$assets_version = getenv("ASSETS_VERSION");
		$assets_hostname = getenv("ASSETS_HOSTNAME");
	
		if($assets_hostname == '')
		{
			$return_value = $path;
		}
		else
		{
			$filename = basename($path);
			$assets_filename = self::InjectTimestamp($filename, $assets_version);
			$relative_path = str_replace($filename, '', $path);
			$return_value = sprintf("http: *%s%s%s", $assets_hostname, $relative_path, $assets_filename);
		}
		
		return $return_value;
	}
	
	private static function InjectTimestamp($filename, $version)
	{
		$return_value = '';
		$tokens = explode('.', $filename);
		$file_ext = array_pop($tokens);

		if($version == '')
		{
			$return_value = $filename;
		}
		else
		{
			$return_value = sprintf("%s%s.%s", basename($filename, $file_ext), $version, $file_ext);
		}

		return $return_value;
	}
}

?>
