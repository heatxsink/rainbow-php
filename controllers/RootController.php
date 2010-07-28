<?php

class RootController extends BaseController {
	
	/**
	 * This method renders the root of the site.
	 *
	 * @url GET /
	 */
	public function RenderRootAction() {
		
		self::SetParameter('page_title', 'Sample Site');
		self::SetParameter('content', 'Your content goes here.');
		self::Render('index.html');
	}
	
	/**
	 * This method renders the login action of the site.
	 *
	 * @url GET /login
	 */
	public function RenderLoginAction() {
		
		return "<html><h1>login action</h1></html>";
	}
}

?>
