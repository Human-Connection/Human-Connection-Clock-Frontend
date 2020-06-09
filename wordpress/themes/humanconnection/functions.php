<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!defined('ABSPATH')){
	exit('geek protected.');
}

if(file_exists(dirname(__FILE__).'/vendor/autoload.php')){
	require_once dirname(__FILE__).'/vendor/autoload.php';
}

if(class_exists('humanconnection\\HCTheme')){
	$theme = new \humanconnection\HCTheme();
	include_once($theme::$childThemePath.'/vendor/acf/acf.php');

	add_filter('acf/settings/path', 'setAcfSettingsPath');
	add_filter('acf/settings/dir', 'setAcfSettingsDir');

	include_once($theme::$childThemePath.'/helper/acfinit.php');
}


add_action ( 'manage_pages_custom_column',	'hc_columns',	10,	2	);
add_filter ( 'manage_edit-page_columns',	'hc_page_columns');
function hc_columns( $column, $post_id ) {
	switch ( $column ) {
	case 'modified':
		$m_orig		= get_post_field( 'post_modified', $post_id, 'raw' );
		$m_stamp	= strtotime( $m_orig );
		$modified	= date('d.m.y @ g:i a', $m_stamp );
	       	$modr_id	= get_post_meta( $post_id, '_edit_last', true );
	       	$auth_id	= get_post_field( 'post_author', $post_id, 'raw' );
	       	$user_id	= !empty( $modr_id ) ? $modr_id : $auth_id;
	       	$user_info	= get_userdata( $user_id );

	       	echo '<p class="mod-date">';
	       	echo '<em>'.$modified.'</em><br />';
	       	echo 'by <strong>'.$user_info->display_name.'<strong>';
	       	echo '</p>';
		break;
	// end all case breaks
	}
}
function hc_page_columns( $columns ) {
	$columns['modified']	= 'Last Modified';
	return $columns;
}
