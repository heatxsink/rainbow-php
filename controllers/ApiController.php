<?php

class ApiController extends BaseApiController {

	/**
	 * @url GET /users/all
	 */
	public function RenderUsersAction() {
		$data = array(
						array("first_name" => "john", "last_name" => "doe"),
						array("first_name" => "jane", "last_name" => "doe"),
					);
		
		self::RenderJson($data);
	}

	/**
	 * @url GET /users/:id
	 */
	public function RenderAction($id) {
		$data = array(
						array("first_name" => "john", "last_name" => "doe"),
						array("first_name" => "jane", "last_name" => "doe"),
					);
		
		if(validate_var($id)) {
			if($id == 0 || $id == 1) {
				$data = $data[$id];
			} else {
				self::RaiseErrorUnauthorized("id");
			}
		} else {
			self::RaiseErrorMissingParam("id");
		}
		
		self::RenderJson($data);
	}
}

?>
