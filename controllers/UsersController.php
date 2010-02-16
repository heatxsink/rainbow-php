<?php

class UsersController extends BaseController
{
	/**
	 * Gets the user by id or current user
	 *
	 * @url GET /:id
	 * @url GET /current
	 */
	public function RenderUserAction($id = null)
	{
		if ($id != 'current')
		{
			$user = $id; 
		}
		else
		{
			$user = 42; 
		}
	   
		return sprintf("<html><h1>user_id: %s</h1></html>", $user);
	}
}

?>
