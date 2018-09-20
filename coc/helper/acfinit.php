<?php

// 1. customize ACF path
function cocSetAcfSettingsPath($path){
	// update path
	$path = plugins_url('/vendor/acf/', dirname(__FILE__));

	// return
	return $path;

}

// 2. customize ACF dir
function cocSetAcfSettingsDir($dir){
	// update path
	$path = plugins_url('/vendor/acf/', dirname(__FILE__));

	// return
	return $dir;
}