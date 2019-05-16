<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.11.2017
 * Time: 01:44
 */

namespace humanconnection\admin;

class AdminHandler {
	public function __construct(){
		if($this->_getCurrentRole() === 'contributor'){
			remove_menu_page( 'index.php' );                    //Dashboard
			remove_menu_page( 'edit.php' );                     //Posts
			remove_menu_page( 'upload.php' );                   //Media
			remove_menu_page( 'edit.php?post_type=page' );      //Pages
			remove_menu_page( 'edit.php?post_type=slide' );     //Fusion Slider
			remove_menu_page( 'edit.php?post_type=avada_faq' ); //FAQ
			remove_menu_page( 'edit.php?post_type=hcmedia' );   //cpt
			remove_menu_page( 'edit-comments.php' );            //Comments
			remove_menu_page( 'wpcf7' );         //CF 7
			remove_menu_page( 'themes.php' );                   //Appearance
			remove_menu_page( 'plugins.php' );                  //Plugins
			remove_menu_page( 'users.php' );                    //Users
			remove_menu_page( 'tools.php' );                    //Tools
			remove_menu_page( 'options-general.php' );          //Settings
		}
	}

	private function _getCurrentRole( $user = null ) {
		$user = $user ? new WP_User( $user ) : wp_get_current_user();
		return $user->roles ? $user->roles[0] : false;
	}
}