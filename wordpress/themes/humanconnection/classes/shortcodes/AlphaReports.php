<?php
/**
 * Human connection Theme shortcode
 *
 * AlphaReports - renders a searchable list of issues in frontend
 *
 */

namespace humanconnection\shortcodes;

use humanconnection\cpts\CptAlpha;

class AlphaReports {
	public function __construct(){}

	public function init(){
		add_shortcode(strtolower(__CLASS__), [$this, 'renderShortcode']);
	}

	public function renderShortcode($atts, $content){
		$args = shortcode_atts([
			'posts_per_page'  => -1,
			'template'        => 'toggle',
			'category'        => ''
		], $atts);

		$class = '';
		$template = $args['template'];
		$posts_per_page = $args['posts_per_page'];
		$category = $args['category'];

		if($template == 'toggle') {
			$class .= ' hcalpha-toggle';
		}

		$taxonomy = 'hcalpha_category';

		$terms = get_terms($taxonomy, [
			'orderby'     => 'slug',
			'order'       => 'asc',
			'hide_empty'  => 1,
			'slug'        => $category

		]);

		$out = '';
		$out .= '<section id="hcalpha" class="hcalpha hcalpha-wrapper'.$class.'">';

			/*
			$out .= '<div class="search-alpha">';
				$out .= '<div class="input-group">';
				$out .= '<input type="text" class="form-control" placeholder="Durchsuchen" value="" id="alphaSearchVal">';
				$out .= '</div>';
			$out .= '</div>';
			*/
		foreach($terms as $term) {
			$qry = new \WP_Query( [
				'post_type'        => CptAlpha::CPT_NAME,
				'posts_per_page'   => $posts_per_page,
				'hcalpha_category' => $category === '' ? $term->slug : $category
			] );
			if ( $qry->have_posts() ) {
				$i   = 0;
				$out .= '<div class="search-grid-items alphareports-view">';
				$out .= '[fusion_accordion type="toggles" boxed_mode="no" icon_size="20" icon_boxed_mode="yes" icon_alignment="right"][fusion_toggle title="' . $term->name . '" open="no"]';
				$out .= '<div class="hc-post-container fusion-blog-layout-grid fusion-blog-layout-grid-12 isotope">';
				while ( $qry->have_posts() ):
					$qry->the_post();
					set_query_var( 'i', $i );

					ob_start();
					get_template_part( 'templates/hcalpha', $template );
					$out .= ob_get_clean();
					$i ++;
				endwhile;
				$out .= '[/fusion_toggle][/fusion_accordion]';
				$out .= '</div>';
				$out .= '</div>';
				wp_reset_postdata();
			}
		}
		$out .= '</section>';

		return do_shortcode($out);
	}
}