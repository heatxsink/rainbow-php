<?php

class RootController
{

        /**
         * Logs in a user with the given username and password POSTed. Though true
         * REST doesn't believe in sessions, it is often desirable for an AJAX server.
         *
         * @url GET login
         */
        public function performLogin()
        {
		return sprintf("<html><h1>login method</h1></html>"); 
	}

	/**
	 * Returns a JSON string object to the browser when hitting the root of the domain
	 *
	 * @url GET /
	 */
	public function catchAll()
	{
		return sprintf("<html><h1>catch all method</h1></html>");
	}



}

?>
