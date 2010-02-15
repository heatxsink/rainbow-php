<?php

class UsersController
{
	/**
	 * Gets the user by id or current user
	 *
	 * @url GET /:id
	 * @url GET /current
	 */
	public function getUser($id = null)
	{
		if ($id != 'current')
		{
			$user = $id; 
		}
		else
		{
			$user = 42; 
		}
	   
		return sprintf("<html><h1>user %s</h1></html>", $user);
	}
}

?>
