<?php

class RootController extends BaseController {
	
	/**
	 * This method renders the root of the site.
	 *
	 * @url GET /
	 */
	public function RenderRootAction() {
		
		$this->SetParameter('page_title', 'Sample Site');
		$this->SetParameter('content', 'Your content goes here.');
		$this->Render('index.html');
	}
	
	/**
	 * This method renders the login action of the site.
	 *
	 * @url GET /login
	 */
	public function RenderLoginAction() {
		
		return "<html><h1>this is the login action</h1></html>";
	}
}

?>
