<?php

// 1. customize ACF path
function setAcfSettingsPath($path){
	// update path
	$path = get_stylesheet_directory().'/vendor/acf/';

	// return
	return $path;

}

// 2. customize ACF dir
function setAcfSettingsDir($dir){
	// update path
	$dir = get_stylesheet_directory_uri().'/vendor/acf/';

	// return
	return $dir;
}