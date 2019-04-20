<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 22.11.2017
 * Time: 01:44
 */

namespace humanconnection\util;

class AvadaHelper {
	public function __construct(){
		/* add actions */
		add_action('after_setup_theme', [$this, 'init']);
		add_action('init', [$this, 'removeDefaultPts'], 100);
		add_action('init', [$this, 'removeDefaultImageSizes'], 100);
		add_action('avada_before_header_wrapper', [$this, 'addLangSwitch']);
		add_action('avada_before_header_wrapper', [$this, 'addGithubRibbon']);
		add_action('add_meta_boxes', [$this,'removeMetaBox'], 100);

		/* add filters */
		add_filter('enter_title_here', [$this, 'changeTitleText']);
		add_filter('hcmedia_category_row_actions', [$this, 'removeHCmediaCategoryRowActions']);


		/* Shortcodes */
		add_shortcode('hcmedia', [$this, 'hcMediaShortcode']);
	}

	public function addLangSwitch(){
		$out  = '<div class="hc-top-bar"><div class="fusion-builder-row fusion-row">';
		$out .= wp_nav_menu(['theme_location' => 'top_navigation', 'echo' => false]);
		$out .= '</div>';
		$out .= '</div>';
		
		echo $out;
	}

	public function addGithubRibbon(){
		echo '<div class="ribbon">
		  <a target="_blank" title="Zur Alpha" href="https://alpha.human-connection.org/">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Zur Alpha&nbsp;&nbsp;&nbsp;&nbsp;</a>
		</div>';
	}

	public function init(){
		$lang = get_stylesheet_directory().'/languages';
		load_child_theme_textdomain('Avada', $lang);
	}


	public function removeDefaultPts(){
    	unregister_post_type('avada_portfolio');
	}

	// avada/includes/class-avada-init.php:215 
	public function removeDefaultImageSizes() {
		remove_image_size('blog-large');
		remove_image_size('blog-medium');
		remove_image_size('recent-posts');
		remove_image_size('recent-works-thumbnail');
		remove_image_size('200');
		remove_image_size('400');
		//remove_image_size('600');
		remove_image_size('800');
		remove_image_size('1200');
		remove_image_size('portfolio-full');
		remove_image_size('portfolio-one');
		remove_image_size('portfolio-two');
		remove_image_size('portfolio-three');
		remove_image_size('portfolio-five');
	}

	public function removeMetaBox() {
		remove_meta_box('slugdiv', 'hcngos', 'normal');
		remove_meta_box('slugdiv', 'hcmedia', 'normal');
		remove_meta_box('shariff_metabox', 'hcngos', 'side');
		remove_meta_box('shariff_metabox', 'hcmedia', 'side');
		remove_meta_box('pyre_post_options', 'hcngos', 'advanced');
		remove_meta_box('pyre_post_options', 'hcmedia', 'advanced');
	}

	public function changeTitleText($title){
    	$screen = get_current_screen();
  
    	if ('hcngos' == $screen->post_type) {
        	$title = 'Name der Organisation';
     	}

     	return $title;
	}

	public function removeHCmediaCategoryRowActions( $action ) {
	    unset($action['view']);
	    return $action;
	}

	public function hcMediaShortcode($atts){
	  global $post;
	  extract(
	    shortcode_atts([
	      'posts_per_page'  => -1,
	      'template'        => 'toggle',
	      'category'        => ''
	    ], $atts)
	  );
	  $class = '';
	  if($template == 'toggle') {
	    $class .= ' hcmedia-toggle'; 
	  }
	  $i = 0;
	  $j = 0;
	  $taxonomy = 'hcmedia_category';

	  $terms = get_terms($taxonomy, [
	      'orderby'     => 'slug',
	      'order'       => 'asc',
	      'hide_empty'  => 1,
	      'slug'        => $category

	  ]);
	  $output = '';

	  $output .= '<section id="hcmedia" class="hcmedia-wrapper'.$class.'">';
	  foreach($terms as $term) {
	    $newsPosts = new \WP_Query([
	      'post_type'                  => 'hcmedia',
	      'posts_per_page'             => $posts_per_page,
	      'hcmedia_category'           => $category === '' ? $term->slug : $category
	    ]);

	    if($newsPosts->have_posts()){
	      //$open = $j === 0 ? 'yes' : 'no';

	      $output .= '[fusion_accordion type="toggles" boxed_mode="no" icon_size="20" icon_boxed_mode="yes" icon_alignment="right"][fusion_toggle title="'.$term->name.'" open="no"]';
	      $output .= '<div class="hc-post-container fusion-blog-layout-grid fusion-blog-layout-grid-3 isotope">';
	      while($newsPosts->have_posts()) : $newsPosts->the_post();
	        set_query_var('i', $i);

	        ob_start();
	        get_template_part('templates/hcmedia', $template);
	        $output .= ob_get_clean();
	        $i++;
	      endwhile;
	      $output .= '</div>';
	      $output .= '[/fusion_toggle][/fusion_accordion]';
	      $j++;
	    }
	  }
	  wp_reset_postdata();

	  $output .= '</section>';

	  return do_shortcode($output);
	}
}