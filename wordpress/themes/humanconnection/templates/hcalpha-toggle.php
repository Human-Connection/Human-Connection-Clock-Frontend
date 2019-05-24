<?php 
	$postId 			 = get_the_ID();
	$gridClasses         = 'fusion-post-grid hcalpha-post-grid post col-lg-12 col-md-12 col-sm-12 ';
    $frontendTitle       = get_field( 'frontend_title');
    $frontendDescription = get_field( 'frontend_description');
    $frontendAnswer      = get_field( 'frontend_answer');
    $status              = get_field( 'status');
    $statusMap			 = [
    	'pending'	=> 'warning',
    	'confirmed' => 'danger',
    	'known'		=> 'info',
    	'fixed'		=> 'success',
    	'intended'	=> 'primary'
    ];
?>
<article id="post-<?php echo md5($postId); ?>" <?php post_class($gridClasses); ?>>
	<div class="alpha-report-title-wrapper">
		<div class="row">
			<div class="col-lg-10 col-md-10 col-sm-12">
    			<h5><a href="#" class="alpha-frontend-title" data-id="<?php echo md5($postId); ?>"><?php echo $frontendTitle; ?></a></h5>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-12">
				<span class="label label-<?php echo $statusMap[$status['value']]; ?>"><?php echo $status['label']; ?></span>
			</div>
		</div>
	</div>
    <div id="report-content-<?php echo md5($postId); ?>" class="alpha-report-wrapper">
	    <div class="report-description">
	    	<div class="panel panel-info">
				<div class="panel-heading"><strong>Beschreibung</strong></div>
				<div class="panel-body">
					<?php echo $frontendDescription; ?>
				</div>
			</div>
	    </div>
	    <?php if($status['value'] !== 'intended'): ?>
		    <div class="report-answer">
		    	<div class="panel panel-info">
					<div class="panel-heading"><strong>Mögliche Lösung</strong></div>
					<div class="panel-body">
						<?php echo $frontendAnswer; ?>
					</div>
				</div>
		    </div>
		<?php endif; ?>
    </div>
</article>