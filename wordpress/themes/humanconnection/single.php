<?php
/**
 * Template used for single posts and other post-types
 * that don't have a specific template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$showTitle = get_field('hc_show_title', $post->ID);
?>
<?php get_header(); ?>

<section id="content" <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php $post_pagination = get_post_meta( $post->ID, 'pyre_post_pagination', true ); ?>
	<?php if ( ( Avada()->settings->get( 'blog_pn_nav' ) && 'no' !== $post_pagination ) || ( ! Avada()->settings->get( 'blog_pn_nav' ) && 'yes' === $post_pagination ) ) : ?>
		<div class="single-navigation clearfix">
			<?php previous_post_link( '%link', esc_attr__( 'Previous', 'Avada' ) ); ?>
			<?php next_post_link( '%link', esc_attr__( 'Next', 'Avada' ) ); ?>
		</div>
	<?php endif; ?>

	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>
			<?php $full_image = ''; ?>


			<?php if ($showTitle === NULL || $showTitle) : ?>
				<?php if ( 'above' === Avada()->settings->get( 'blog_post_title' ) ) : ?>
					<?php if ( 'below_title' === Avada()->settings->get( 'blog_post_meta_position' ) ) : ?>
						<div class="fusion-post-title-meta-wrap">
					<?php endif; ?>
					<?php $title_size = ( false === avada_is_page_title_bar_enabled( $post->ID ) ? '1' : '2' ); ?>
					<?php echo avada_render_post_title( $post->ID, false, '', $title_size ); // WPCS: XSS ok. ?>
					<?php if ( 'below_title' === Avada()->settings->get( 'blog_post_meta_position' ) ) : ?>
						<?php echo avada_render_post_metadata( 'single' ); // WPCS: XSS ok. ?>
						</div>
					<?php endif; ?>
				<?php elseif ( 'disabled' === Avada()->settings->get( 'blog_post_title' ) && Avada()->settings->get( 'disable_date_rich_snippet_pages' ) && Avada()->settings->get( 'disable_rich_snippet_title' ) ) : ?>
					<span class="entry-title" style="display: none;"><?php the_title(); ?></span>
				<?php endif; ?>
			<?php endif; ?>


			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php if ( Avada()->settings->get( 'featured_images_single' ) ) : ?>
					<?php $video = apply_filters( 'privacy_iframe_embed', get_post_meta( $post->ID, 'pyre_video', true ) ); ?>
					<?php if ( 0 < avada_number_of_featured_images() || $video ) : ?>
						<?php
						Avada()->images->set_grid_image_meta(
							array(
								'layout'  => strtolower( 'large' ),
								'columns' => '1',
							)
						);
						?>
						<div class="fusion-flexslider flexslider fusion-flexslider-loading post-slideshow fusion-post-slideshow">
							<ul class="slides">
								<?php if ( $video ) : ?>
									<li>
										<div class="full-video">
											<?php echo $video; // WPCS: XSS ok. ?>
										</div>
									</li>
								<?php endif; ?>
								<?php if ( has_post_thumbnail() && 'yes' != get_post_meta( $post->ID, 'pyre_show_first_featured_image', true ) ) : ?>
									<?php $attachment_data = Avada()->images->get_attachment_data( get_post_thumbnail_id() ); ?>
									<?php if ( is_array( $attachment_data ) ) : ?>
										<li>
											<?php if ( Avada()->settings->get( 'status_lightbox' ) && Avada()->settings->get( 'status_lightbox_single' ) ) : ?>
												<a href="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>" data-title="<?php echo esc_attr( $attachment_data['title_attribute'] ); ?>" data-caption="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>" aria-label="<?php echo esc_attr( $attachment_data['title_attribute'] ); ?>">
													<span class="screen-reader-text"><?php esc_attr_e( 'View Larger Image', 'Avada' ); ?></span>
													<?php echo get_the_post_thumbnail( $post->ID, 'full' ); ?>
												</a>
											<?php else : ?>
												<?php echo get_the_post_thumbnail( $post->ID, 'full' ); ?>
											<?php endif; ?>
										</li>
									<?php endif; ?>
								<?php endif; ?>
								<?php $i = 2; ?>
								<?php while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) : ?>
									<?php $attachment_new_id = fusion_get_featured_image_id( 'featured-image-' . $i, 'post' ); ?>
									<?php if ( $attachment_new_id ) : ?>
										<?php $attachment_data = Avada()->images->get_attachment_data( $attachment_new_id ); ?>
										<?php if ( is_array( $attachment_data ) ) : ?>
											<li>
												<?php if ( Avada()->settings->get( 'status_lightbox' ) && Avada()->settings->get( 'status_lightbox_single' ) ) : ?>
													<a href="<?php echo esc_url_raw( $attachment_data['url'] ); ?>" data-rel="iLightbox[gallery<?php the_ID(); ?>]" title="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>" data-title="<?php echo esc_attr( $attachment_data['title_attribute'] ); ?>" data-caption="<?php echo esc_attr( $attachment_data['caption_attribute'] ); ?>" aria-label="<?php echo esc_attr( $attachment_data['title_attribute'] ); ?>">
														<?php echo wp_get_attachment_image( $attachment_new_id, 'full' ); ?>
													</a>
												<?php else : ?>
													<?php echo wp_get_attachment_image( $attachment_new_id, 'full' ); ?>
												<?php endif; ?>
											</li>
										<?php endif; ?>
									<?php endif; ?>
									<?php $i++; ?>
								<?php endwhile; ?>
							</ul>
						</div>
						<?php Avada()->images->set_grid_image_meta( array() ); ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>



			<?php if ($showTitle === NULL || $showTitle) : ?>
				<?php if ( 'below' === Avada()->settings->get( 'blog_post_title' ) ) : ?>
					<?php if ( 'below_title' === Avada()->settings->get( 'blog_post_meta_position' ) ) : ?>
						<div class="fusion-post-title-meta-wrap">
					<?php endif; ?>
					<?php $title_size = ( false === avada_is_page_title_bar_enabled( $post->ID ) ? '1' : '2' ); ?>
					<?php echo avada_render_post_title( $post->ID, false, '', $title_size ); // WPCS: XSS ok. ?>
					<?php if ( 'below_title' === Avada()->settings->get( 'blog_post_meta_position' ) ) : ?>
						<?php echo avada_render_post_metadata( 'single' ); // WPCS: XSS ok. ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>





			<div class="post-content">
				<?php the_content(); ?>
				<?php fusion_link_pages(); ?>
			</div>

			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php if ( '' === Avada()->settings->get( 'blog_post_meta_position' ) || 'below_article' === Avada()->settings->get( 'blog_post_meta_position' ) || 'disabled' === Avada()->settings->get( 'blog_post_title' ) ) : ?>
					<?php echo avada_render_post_metadata( 'single' ); // WPCS: XSS ok. ?>
				<?php endif; ?>
				<?php do_action( 'avada_before_additional_post_content' ); ?>
				<?php avada_render_social_sharing(); ?>
				<?php $author_info = get_post_meta( $post->ID, 'pyre_author_info', true ); ?>
				<?php if ( ( Avada()->settings->get( 'author_info' ) && 'no' !== $author_info ) || ( ! Avada()->settings->get( 'author_info' ) && 'yes' === $author_info ) ) : ?>
					<section class="about-author">
						<?php ob_start(); ?>
						<?php the_author_posts_link(); ?>
						<?php /* translators: The link. */ ?>
						<?php $title = sprintf( __( 'About the Author: %s', 'Avada' ), ob_get_clean() ); ?>
						<?php $title_size = ( false === avada_is_page_title_bar_enabled( $post->ID ) ? '2' : '3' ); ?>
						<?php Avada()->template->title_template( $title, $title_size ); ?>
						<div class="about-author-container">
							<div class="avatar">
								<?php echo get_avatar( get_the_author_meta( 'email' ), '72' ); ?>
							</div>
							<div class="description">
								<?php the_author_meta( 'description' ); ?>
							</div>
						</div>
					</section>
				<?php endif; ?>
				<?php avada_render_related_posts( get_post_type() ); // Render Related Posts. ?>

				<?php $post_comments = get_post_meta( $post->ID, 'pyre_post_comments', true ); ?>
				<?php if ( ( Avada()->settings->get( 'blog_comments' ) && 'no' !== $post_comments ) || ( ! Avada()->settings->get( 'blog_comments' ) && 'yes' === $post_comments ) ) : ?>
					<?php wp_reset_postdata(); ?>
					<?php comments_template(); ?>
				<?php endif; ?>
				<?php do_action( 'avada_after_additional_post_content' ); ?>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
</section>
<?php do_action( 'avada_after_content' ); ?>
<?php
get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
