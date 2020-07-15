<?php
/*
Template Name: Home
*/
get_header(); 

global $about_us;


 
?>

<section class="content-block">
  <div class="container">
    <div class="fact-block mb-2">
      <div class="row">
	  <?php $curr_page_id = get_the_ID();
			/* About section on home page */
			$about_quote = get_post_meta($curr_page_id, 'About us quote');
			$about_description = get_post_meta($curr_page_id, 'About us description');
			$about_url = get_post_meta($curr_page_id, 'About us button url');
			$youtube_link = get_post_meta($curr_page_id, 'youtube video link');
			$ready_myths_title = get_post_meta($curr_page_id, 'Ready myths title');
			$ready_myths_url = get_post_meta($curr_page_id, 'Ready myths url');
			/* end about section */
						$args = array(
						  'post_type'   => 'homepage_section',
						  'posts_per_page' => 4,
						  'order' => 'ASC'
						);
						$homesections = get_posts( $args ); 
						//echo "<pre>"; print_r($sliders);
						?>
	  <?php foreach($homesections as $homesection) {  ?>
        <div class="col-lg-3 col-sm-6">
		
			  <figure>
				<figcaption class="mb-3 text-center text-uppercase"><a href="<?php echo get_post_meta( $homesection->ID, 'section_url', true ); ?>" class="h3"><?php echo $homesection->post_title; ?></a></figcaption>
			<a class="roller-over" href="<?php echo get_post_meta( $homesection->ID, 'section_url', true ); ?>"><img class="img-fluid" src="<?php echo wp_get_attachment_url(get_post_meta( $homesection->ID, 'section_image', true )); ?>" alt="" /> </a>	
			  </figure>
		</div>
		<?php } ?>
        </div>
    </div>
    <div class="about-block">
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <article class="mb-md-5 about-home">
            <h3 class="text-uppercase"><?php echo ABOUT_US; ?></h3>
            <blockquote class="h3"><i class="ds-left-quote"></i> <?php echo $about_quote[0]; ?> <i class="ds-right-quote"></i></blockquote>
            <p><?php echo $about_description[0]; ?></p>
          </article>
          <a class="btn site-btn" href="<?php echo $about_url[0]; ?>"><?php echo FIND_OUT_MORE; ?></a> </div>
        <div class="col-lg-3 col-md-6 order-2 order-lg-6">
			<div class="twitter-block">
				<?php if ( is_active_sidebar( 'home-twitter' ) ) : 
						dynamic_sidebar( 'home-twitter' ); 
				endif; ?>
				<script async src="https://platform.twitter.com/widgets.js"></script>
			</div>
			
		  
		  </div>
        <div class="col-lg-6 col-md-12 order-3">
			<div class="video-block embed-responsive embed-responsive-1by1">
			<iframe width="540" height="540" class="embed-responsive-item" src="<?php echo $youtube_link[0]; ?>"></iframe>
				</div>
		  </div>
      </div>
    </div>
  </div>
</section>
<a class="ready display-3 text-center text-white py-5 d-block" href="<?php echo $ready_myths_url[0]; ?>"><?php echo $ready_myths_title[0]; ?></a>

<section class="content-block">
  <div class="container">
    <!-- news blog start -->
	<div class="news-card-blocks">
		<h2 class="mb-lg-4"><a href="<?php bloginfo('url'); ?>/news" class="latest-news-head"><?php echo LATEST_NEWS; ?></a></h2>
      <div class="row">
	   <?php 
				/* get 1 featured news */
					   $args = array(
											'post_type'  => 'post',
											'category' => 3,
											'order' => 'DESC',
											'meta_query' => array(
												array(
													'key'   => 'meta-checkbox',
													'value' => 'yes',
												)
											)
										);
					$postslist = get_posts( $args ); 
					$featured_img_url = get_the_post_thumbnail_url($postslist[0]->ID, 'full');
                    $featured_img_url   = aq_resize( $featured_img_url, 540, 304, true ); // Resize & crop img 							
					$post_title5 = $postslist[0]->post_title;
		?>
        <div class="col-lg-6">          
          <section id="news1" class="home-featured-news card mb-4 ie-fix"><a class="roller-over" href="<?php echo get_permalink($postslist[0]->ID); ?>"><img  alt="news" src="<?php echo $featured_img_url; ?>" class="img-fluid"></a>
            <div class="card-body latestnewsh">
              <h3 class="card-title"><a href="<?php echo get_permalink($postslist[0]->ID); ?>"><?php echo strip_tags(substr($postslist[0]->post_title,0,80));  if(mb_strlen($post_title5,'UTF8')>60) { echo "..."; } ?></a> </h3>
              <p class="card-text"><a href="<?php echo get_permalink($postslist[0]->ID); ?>"><?php echo strip_tags(substr($postslist[0]->post_content,0,250)); ?>...</a> </p>
              <a href="<?php bloginfo('url'); ?>/news" class="card-link"><?php echo FEATURED_NEWS; ?></a> </div>
            <!-- card-body --> 
          </section>
        </div><div class="col-lg-6">
          
          <div class="row">
            
			<?php 		/* get 4 news type post */
						$args = array(
								'post_type'  => 'post',
								'category' => 3,
								'order' => 'DESC',
                                                                
								'meta_query' => array(
										array(
											'key'   => 'meta-checkbox',
											'value' => false,
											)
										),
								'posts_per_page' => 4
							);
						$newspostslist = get_posts( $args ); 
						//$category_detail=get_the_category($newspost->ID);
						
					$i = 0;
					foreach($newspostslist as $newspost) { if ($i > 0) { $extracolclass = "d-none d-sm-block"; } else { $extracolclass = ""; } 
					 $featured_img_url = get_the_post_thumbnail_url($newspost->ID, 'thumbnail');
				 	$featured_img_url   = aq_resize( $featured_img_url, 600, 338, true ); // Resize & crop img  
//echo "hi<br>";
                                        if (trim($featured_img_url) == '') {
//echo "hi"; die;
                                    
                                            $featured_img_url = get_template_directory_uri()."/images/default_media_card.jpg";
                                        }
										
										
										
										$post_title = $newspost->post_title;
                                        ?>
					<div class="col-sm-6 <?php echo $extracolclass; ?>">
						<section class="card mb-2 mb-sm-4"> <a class="roller-over" href="<?php echo get_permalink($newspost->ID); ?>"><img alt="news" src="<?php echo $featured_img_url; ?>" class="img-fluid imgmedia-home"></a>
						<div class="card-body">
						  <h4 class="card-title"><a href="<?php echo get_permalink($newspost->ID); ?>"><?php echo strip_tags(substr($newspost->post_title,0,60)); if(mb_strlen($post_title,'UTF8')>60) { echo "..."; }  ?></a> </h4>
						  <a href="<?php bloginfo('url'); ?>/news" class="card-link"><?php echo NEWS; ?></a> </div>
						<!-- card-body --> 
						</section>
					</div>
					<?php $post_title = ''; $i++; } ?>
           </div>
        </div>
      </div>
    </div>
	<!-- news blog end -->
	
    <div class="blog-research">
      <div class="row">
	  <?php 		/* get 2 blog type post */
						$args = array(
								'post_type'  => 'post',
								'category' => 4,
								'order' => 'DESC',
								'posts_per_page' => 2
							);
						$blogpostslist = get_posts( $args ); 
						
			?>
        <div class="col-lg-6"> <br>
          <hr>
          <br>
			     <h2 class="mb-lg-4 text-uppercase"><a href="<?php bloginfo('url'); ?>/blog" class="latest-news-head"><?php echo BLOGS; ?></a></h2>
			
          <div class="row">
		  <?php foreach($blogpostslist as $blogpost) { 
							$featured_img_url = get_the_post_thumbnail_url($blogpost->ID, 'thumbnail');
$featured_img_url   = aq_resize( $featured_img_url, 600, 338, true ); // Resize & crop img 
                                                        
                                if (trim($featured_img_url) == '') {
                                    
                                    $featured_img_url = get_template_directory_uri()."/images/default_media_card.jpg";
                                }
					
								$post_title1 = $blogpost->post_title;
			?>
            <div class="col-sm-6">
         
              <section class="card mb-4 mb-sm-2"><a class="roller-over" href="<?php echo get_permalink($blogpost->ID); ?>"><img  alt="news" src="<?php echo $featured_img_url; ?>" class="img-fluid imgmedia-home"></a>
                <div class="card-body">
                  <h4 class="card-title"><a href="<?php echo get_permalink($blogpost->ID); ?>"><?php echo strip_tags(substr($blogpost->post_title,0,60)); if(mb_strlen($post_title1,'UTF8')>60) { echo "..."; } ?></a></h4>
                  <a class="card-link" href="<?php bloginfo('url'); ?>/blog"><?php echo BLOG; ?></a> </div>
                <!-- card-body --> 
              </section>
            </div>
			<?php $post_title1 = ''; } ?>
            
          </div>
        </div>
        <div class="col-lg-6"> <br>
          <hr>
          <br>
		<h2 class="mb-lg-4"><a href="<?php bloginfo('url'); ?>/reports-and-studies" class="latest-news-head"><?php echo REPORTS_AND_STUDIES; ?></a></h2>
          <div class="row">
		  <?php 		/* get 2 blog type post */
						$args = array(
								'post_type'  => 'post',
								'category' => 9,
								'order' => 'DESC',
								'posts_per_page' => 2
							);
						$researchpostslist = get_posts( $args ); 
						
			?>
			<?php foreach($researchpostslist as $researchpost) { 
							$featured_img_url = get_the_post_thumbnail_url($researchpost->ID, 'thumbnail');
$featured_img_url   = aq_resize( $featured_img_url, 600, 338, true ); // Resize & crop img 
                                                        if (trim($featured_img_url) == '') {
                                    
                                                            $featured_img_url = get_template_directory_uri()."/images/default_media_card.jpg";
                                                        }
								
														$post_title2 = $researchpost->post_title;
						?>
            <div class="col-sm-6">
              <section class="card mb-4 "> <a class="roller-over" href="<?php echo get_permalink($researchpost->ID); ?>"><img  alt="news" src="<?php echo $featured_img_url; ?>" class="img-fluid imgmedia-home"></a>
                <div class="card-body">
                  <h4 class="card-title"><a href="<?php echo get_permalink($researchpost->ID); ?>"><?php echo strip_tags(substr($researchpost->post_title,0,60)); if(mb_strlen($post_title2,'UTF8')>60) { echo "..."; } ?></a></h4>
                  <a class="card-link" href="<?php bloginfo('url'); ?>/reports-and-studies"><?php echo REPORTS_AND_STUDIES; ?></a> </div>
                <!-- card-body --> 
              </section>
            </div>
			<?php $post_title2 = ''; } ?>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<div class="partners-block text-center  mb-5">
	<?php 
            $curr_blog_id = get_current_blog_id();
            $sql = 'SELECT wp_cimy_uef_data.USER_ID, wp_cimy_uef_data.VALUE FROM wp_cimy_uef_data LEFT JOIN wp_usermeta ON wp_usermeta.user_id = wp_cimy_uef_data.USER_ID WHERE wp_cimy_uef_data.VALUE != "" && (wp_usermeta.meta_key = "primary_blog" && wp_usermeta.meta_value = "'.$curr_blog_id.'")';
	    $partnerslogos = $wpdb->get_results($sql);
            if(!empty($partnerslogos)) {
        ?>
    
    <h2 class="mb-4"><?php echo OUR_PARTNERS; ?></h2>
	<div class="container mb-4">
		<div class="owl-carousel partners-carousel">
			
			<?php foreach($partnerslogos as $partner) {
                                $account_status = get_user_meta( $partner->USER_ID, 'account_status', true );
                                if ($account_status == "approved") {
			        $websiteurl = get_user_meta( $partner->USER_ID, 'company_website', true );
				$company_url = '';
				if(esc_attr($websiteurl) != '') {
					$company_url = esc_attr($websiteurl);
					$parsed = parse_url($company_url);
					if (empty($parsed['scheme'])) {
						$company_url = 'http://' . ltrim($company_url, '/');
					}
				} 
			
                            ?>
				<div class="item"><a href="<?php echo $company_url; ?>" target="_blank">
						<img src="<?php echo $partner->VALUE;  ?>" alt="" /></a>
				</div>
                                <?php } 
                                
                                        } ?>
			
		</div>	
	</div>
            <?php } ?>
	<a href="<?php echo get_permalink(180); ?>" class="btn site-btn"><?php echo BECOME_A_MEMBER; ?></a>
</div>

<?php
get_footer();
?>