<?php

class ApiController extends BaseApiController {

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
		
		if(validate_var($id)) {
			
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
