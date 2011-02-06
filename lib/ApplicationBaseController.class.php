<?php

class ApplicationBaseController extends BaseController{
	
	public function Render($template) {
		//
		//  If you want html minify'ing just un-comment this block.
		//
		//if (!ApplicationConfig::IsDeveloperMode()) {
		//	self::$template_engine->load_filter('output', 'html_minify');
		//}
		//
		self::$template_engine->assign('developer_mode', ApplicationConfig::IsDeveloperMode());
		$this->GetTemplateEngine()->display($template);
	}
}