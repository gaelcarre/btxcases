<?php
	ob_start();
	session_start();
	if(!isset($_SESSION['lang']))
		$_SESSION['lang'] = 'FR';
	@define('__CORE_DIR__',dirname(__FILE__),true);
	@define('__INC_DIR__',__CORE_DIR__.'/inc/',true);
	@define('__UTIL_DIR__',__CORE_DIR__.'/util/',true);
	@define('__LIB_DIR__',__CORE_DIR__.'/lib/',true);
	@define('__CLASS_DIR__',__LIB_DIR__.'/class/',true);
	@define('__TPL_DIR__',__CORE_DIR__.'/template/',true);
	@define('__LNG_DIR__',__CORE_DIR__.'/lang/',true);
	@define('__MOD_DIR__',__CORE_DIR__.'/module/',true);
	@define('__PAGE_DIR__',__CORE_DIR__.'/page/',true);
	@define('__CONF_DIR__',__CORE_DIR__.'/conf/',true);
	@define('__MODELE_DIR__',__CORE_DIR__.'/model/',true);
	@define('__IMG_DIR__',__CORE_DIR__.'/img/',true);

?>