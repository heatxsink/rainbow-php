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

class ApiController extends BaseController {

	private static $data = array(
						array("first_name" => "john", "last_name" => "doe"),
						array("first_name" => "jane", "last_name" => "doe"),
					);
	/**
	 * @url GET /users/all
	 */
	public function RenderUsersAction() {
		
		$this->RenderJson(self::$data);
	}
	
	/**
	 * @url GET /users/:id
	 */
	public function RenderAction($id) {
		$user_data = array();
		
		if(rainbow_validate_var($id)) {
			
			if($id == 0 || $id == 1) {
				
				$user_data = self::$data[$id];
			} else {
				
				$this->RaiseErrorUnauthorized("id");
			}
		} else {
			
			$this->RaiseErrorMissingParam("id");
		}
		
		$this->RenderJson($user_data);
	}
}

?>
