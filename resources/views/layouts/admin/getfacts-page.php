<?php
/*
Template Name: Getfact
*/
get_header(); 

if(isset($_POST['keyword']) && !empty($_POST['keyword'])) {
					$keywords = $_POST['keyword'];
				} else {
					$keywords = '';
				}

?>

<section class="content-block">
  <div class="container">
    <h1 class="mb-lg-5 mb-4 text-uppercase"><?php echo the_title(); ?></h1>
    <div class="row">
      <div class="col-xl-3 col-md-4">
        <aside class="filter-block">
        <div class="d-none d-md-block">
		<form action ="" method="post">
		<input type="text" name="keyword" placeholder="<?php echo SEARCH; ?>" value="<?php echo $keywords; ?>" class="form-control ">
          <p class="big my-top-20"><?php echo REFINE_GETFACT_HEADING; ?></p>
          <div id="accordion">
            <div class="card mb-4">
              <div class="card-header"> <a class="card-link" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><?php echo NEWSPAGE_CHANNEL_HEADING; ?>
			<span class="ds-slider-right-arrow"></span> </a> </div>
              <div id="collapseOne" class="collapse">
                <div class="card-body">
				
				<?php 
					$allchannels = get_field_object('field_5a94f36d2410a');
					if(isset($_POST['channels']) && !empty($_POST['channels'])) {
						$channel_checked = 1;
						} else {
							$channel_checked = 0;
						}
					foreach( $allchannels['choices'] as $k => $v ) { 
					?>
						<div class="custom-control custom-checkbox mr-sm-2">
							<input type="checkbox" name="channels[]" class="custom-control-input" value="<?php echo $k; ?>" <?php if($channel_checked == 1 && in_array($k, $_POST['channels'])) {  ?> checked="checked" <?php } ?> id="<?php echo str_replace(" ","_",$k); ?>">
							<label class="custom-control-label" for="<?php echo str_replace(" ","_",$k); ?>"><?php echo $v; ?></label>
						</div>
				<?php } ?>
				
                </div>
              </div>
            </div>
            <div class="card mb-4">
              <div class="card-header"> <a class="card-link" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"> <?php echo NEWSPAGE_TOPIC_HEADING; ?> 
				<span class="ds-slider-right-arrow"></span> </a>
				</div>
              <div id="collapseTwo" class="collapse">
                <div class="card-body"> 
					<?php 
					$alltopics = get_field_object('field_5a94f5949b45d');
					if(isset($_POST['topics']) && !empty($_POST['topics'])) {
						$topics_checked = 1;
						} else {
							$topics_checked = 0;
						}
					foreach( $alltopics['choices'] as $k => $v ) { ?>
						<div class="custom-control custom-checkbox mr-sm-2">
							<input type="checkbox" name="topics[]" class="custom-control-input" value="<?php echo $k; ?>" <?php if($topics_checked == 1 && in_array($k, $_POST['topics'])) {  ?> checked="checked" <?php } ?> id="<?php echo str_replace(" ","_",$k); ?>">
							<label class="custom-control-label" for="<?php echo str_replace(" ","_",$k); ?>"><?php echo $v; ?></label>
						</div>
					<?php } ?>
				  
				</div>
              </div>
            </div>
            <div class="card  mb-4">
              <div class="card-header"> <a class="card-link" data-toggle="collapse" data-parent="#accordion" href="#collapseThree"> <?php echo NEWSPAGE_ACTIVITY_HEADING; ?> 
				  <span class="ds-slider-right-arrow"></span></a> </div>
              <div id="collapseThree" class="collapse">
                <div class="card-body">
					<?php 
					$allactivities = get_field_object('field_5a94f64052614');
					if(isset($_POST['activities']) && !empty($_POST['activities'])) {
						$activities_checked = 1;
						} else {
							$activities_checked = 0;
						}
					foreach( $allactivities['choices'] as $k => $v ) { ?>
						<div class="custom-control custom-checkbox mr-sm-2">
							<input type="checkbox" name="activities[]" class="custom-control-input" value="<?php echo $k; ?>" <?php if($activities_checked == 1 && in_array($k, $_POST['activities'])) {  ?> checked="checked" <?php } ?> id="<?php echo str_replace(" ","_",$k); ?>">
							<label class="custom-control-label" for="<?php echo str_replace(" ","_",$k); ?>"><?php echo $v; ?></label>
						</div>
					<?php } ?>
				
				</div>
              </div>
            </div>
          </div>			
			<div class="mybtn-group justify-content-between">
				<input type="submit" value="<?php echo GO; ?>" class="btn site-btn">
				<a href="<?php bloginfo('url'); ?>/news" class="btn site-btn"><?php echo CLEAR; ?></a>
			</div>
			</form>
			</div>
        </aside>
      </div>
      <div class="col-xl-9 col-md-8">
		  <div class="news-card-blocks">
	    	<div class="row loadmore-content">
		  	
			<?php 		
			
				
			
				/* get 4 news type post */
				$meta_query = array('relation' => 'OR');
				
				if(isset($_POST['channels']) && !empty($_POST['channels']))
				{	
					$channels = $_POST['channels'];
					$channels_item = '';
					foreach( $channels as $item ){
						$meta_query[] = array(
							'key'     => 'channel',
							'value'   => $item,
							'compare' => 'LIKE',
						);
						$channels_item .= $item.",";
					}

				} else {
					$channels_item = '';
				}
				
				if(isset($_POST['topics']) && !empty($_POST['topics']))
				{	
					$topics = $_POST['topics'];
					$topics_item = '';
					
					foreach( $topics as $topicitem ){
						$meta_query[] = array(
							'key'     => 'topics',
							'value'   => $topicitem,
							'compare' => 'LIKE',
						);
						$topics_item .= $topicitem.",";
					}

				} else {
					$topics_item = '';
				}
				
				if(isset($_POST['activities']) && !empty($_POST['activities']))
				{	
					$activities = $_POST['activities'];
					$activities_item = '';
					foreach( $activities as $activityitem ){
						$meta_query[] = array(
							'key'     => 'two_sides_activities',
							'value'   => $activityitem,
							'compare' => 'LIKE',
						);
						$activities_item .= $activityitem.",";
					}

				} else {
					$activities_item  = '';
				}
				
			
			
				// args total record count
					$args1 = array(
					 
					 'post_type'  => 'post',
					 's' => $keywords,
					 'posts_per_page' => -1,
					 'orderby' => 'ID',
					 'tax_query' => array(

						 array(

							'taxonomy' => 'category',

							'field' => 'term_id',

							'terms' => 12

						  )

						   ),
					 'meta_query' => $meta_query,
					);

			$the_query1 = new WP_Query( $args1 );
				
				/* end */
				
				
				// args
					$args = array(
					 
					 'post_type'  => 'post',
					 'posts_per_page' => MEDIA_PER_PAGE,
					 's' => $keywords,
					 'orderby' => 'ID',
					 'tax_query' => array(

						 array(

							'taxonomy' => 'category',

							'field' => 'term_id',

							'terms' => 12

						  )

						   ),
					 'meta_query' => $meta_query,
					);

			$the_query = new WP_Query( $args );
			
			$total_record = $the_query1->post_count;
			
		
			if ( $the_query->have_posts() ) :
				while ( $the_query->have_posts() ) : $the_query->the_post(); $post_id = get_the_ID();
$postThumbnail = get_the_post_thumbnail_url($post_id, 'thumbnail');
$postThumbnail   = aq_resize( $postThumbnail, 600, 338, true );
if ($postThumbnail == '') {
                                    
                                    $postThumbnail = get_template_directory_uri()."/images/default_media_card.jpg";
                                }
								$news_title = get_the_title();
				?>
					<div class="col-xl-4 col-sm-6">
							<section class="card mb-4 ie-fix"> <a class="roller-over" href="<?php echo get_permalink($post_id); ?>"><img class="img-fluid" src="<?php echo $postThumbnail ?>" alt="<?php echo the_title(); ?> "></a>
							  <div class="card-body">
								<h4 class="card-title"><a href="<?php echo get_permalink($post_id); ?>"><?php echo strip_tags(substr($news_title, 0, 60)); if(mb_strlen($news_title,'UTF8')>60) { echo "..."; }  ?></a></h4>
								<a class="card-link" href="<?php bloginfo('url'); ?>/get-the-facts"><?php echo GETFACT; ?></a> </div>
							  <!-- card-body --> 
							</section>  
						</div>
				<?php endwhile; 
	
				else:
				?><div class="no_result_found"><?php echo NO_RESULT_FOUND; ?></div>
				<?php
				endif;
				?>	
			</div>
			<?php 
			
			if($total_record > MEDIA_PER_PAGE) { ?>
			<div class="text-center mt-1 mt-lg-4">
			<input type="button" value="<?php echo LOAD_MORE; ?>" class="btn site-btn loadmore_news" offset="<?php echo MEDIA_PER_PAGE; ?>" ajaxurl="<?php bloginfo('url'); ?>/searchmedia?category=12&keywords=<?php echo $keywords; ?>&channels=<?php echo rtrim($channels_item, ","); ?>&topics=<?php echo rtrim($topics_item, ","); ?>&activities=<?php echo rtrim($activities_item, ","); ?>">
			</div>
			<?php } ?>
	  </div>
    </div>
  </div>
</section>

<?php
get_footer();
?>