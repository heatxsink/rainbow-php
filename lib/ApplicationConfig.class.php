<?php
putenv("DEVELOPER_MODE=0");

class ApplicationConfig {
	
	public static function IsDeveloperMode()
	{
		$developer_mode = (bool) getenv("DEVELOPER_MODE");
		return $developer_mode;
	}
}

?>