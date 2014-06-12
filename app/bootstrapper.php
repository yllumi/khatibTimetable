<?php 

	// set system path
	$system_path = 'app';

	/*
	 * ---------------------------------------------------------------
	 *  Resolve the system path for increased reliability
	 * ---------------------------------------------------------------
	 */
	if (function_exists('realpath') AND @realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}
	
	// ensure there's a trailing slash
	$system_path = rtrim($system_path, '/').'/';

	// Is the sytsem path correct?
	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

	/*
	 * ---------------------------------------------------------------
	 *  Define all necessary path
	 * ---------------------------------------------------------------
	 */

	// system folder
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// config folder
	define('CONFIGPATH', BASEPATH."config/");

	// assets folder
	define('ASSETPATH', BASEPATH."assets/");

	// helpers folder
	define('HELPERPATH', BASEPATH."helpers/");

	/*
	 * ---------------------------------------------------------------
	 *  Load main config file
	 * ---------------------------------------------------------------
	 */

	require(CONFIGPATH.'config.php');
	require(CONFIGPATH.'constants.php');
	require(CONFIGPATH.'autoload.php');
