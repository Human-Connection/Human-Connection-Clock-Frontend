<?php 
	$gridClasses = 'fusion-post-grid hcmedia-post-grid hcmedia-post-grid post ';
	$videoUrl = get_field('hcmedia_video_url');
	$siteUrl = get_field('hcmedia_site_url');
	$siteTitle = get_field('hcmedia_site_title');
	$siteLinkText = get_field('hcmedia_site_link_text');
	if($videoUrl) {
		$gridClasses .= ' hcmedia-video';
	}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($gridClasses); ?> data-url="<?php echo $videoUrl; ?>">
	<?php if($siteUrl && !$videoUrl) : ?>
	<a href="<?php echo $siteUrl; ?>" title="Weiter zu: <?php echo $siteTitle; ?>" target="_blank">
	<?php endif; ?>
	<div class="image-wrapper">
		<img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php echo get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ); ?>" />
	</div>
	<?php if($siteUrl && !$videoUrl) : ?>
	</a>
	<?php endif; ?>
	<div class="mediaContent">
		<?php the_content(); ?>
		<?php if($siteUrl) : ?>
		<a href="<?php echo $siteUrl; ?>" title="Weiter zu: <?php echo $siteTitle ?>"><?php echo $siteLinkText ?></a>
		<?php endif; ?>
	</div>
</article>