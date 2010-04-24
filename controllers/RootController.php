<?php

class RootController extends BaseController {
	
	/**
	 * This method renders the root of the site.
	 *
	 * @url GET /
	 */
	public function RenderRootAction() {
		
		return self::RenderTemplate('index.html');
	}
	
	/**
	 * This method renders the login action of the site.
	 *
	 * @url GET login
	 */
	public function RenderLoginAction() {
		
		return "<html><h1>login action</h1></html>";
	}
}

?>
