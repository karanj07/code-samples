<?php
/**
 * Template Name: List NGO Page template
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); 
?>
<!--<script src="<?php bloginfo('template_directory'); ?>/js/jquery-animate-css-rotate-scale.js"></script>-->	

<script src="<?php bloginfo('template_directory'); ?>/js/jquery.quicksand.js"></script>

<script>
$(function(){

	$(".suggest-form").on("click", function(){ $(".pop-form-container").height($(window).height()); $(".pop-form-container").show(); });
	$(".form-close").on("click", function(){ $(".pop-form-container").hide(); });
});
</script>
<div class="pop-form-container popup-container" >
	<div id="form-box" class="pop-box" >
		<div class="form">
			<a href="#" class="popup-close form-close" title="close form"></a>
			<?php echo do_shortcode('[contact-form-7 id="109" title="ngo-suggest"]'); ?>
		</div>
	</div>
</div>
<input type="button" value="Suggest An NGO" class="suggest-form" />
<div id="primary" class=" ngo-all inner-page inner-list-page">
		<div id="content" role="main">                                       
                                        <div class="content-pad">
                                        	<!-- Category Filter -->
                                            <dl class="group">
                                                <dd>
                                                    <ul class="filter group"> 
														<li class="current all"><a href="#"><span>All</span></a></li> 
														<?php $categories = get_categories( $args ); 
														foreach($categories as $category){
														$removecat = array("uncategorized");
															if(!in_array($category->slug, $removecat)){
																echo '<li class="cat-item-'.$category->cat_ID.'"><a href="#"><span>'.$category->cat_name.'</span></a></li>';
															}
														}
														/* echo '<pre>';
														print_r($categories); 
														echo '</pre>';
														die; */
														?> 
                                                    </ul> 
													<script>
													$("ul.filter li a").attr("href", '#');
													$("ul.filter li").removeClass("cat-item");
													</script>
                                                </dd>
                                            </dl>
											<br />
                                        	<div class="clearfix"></div>
                                            <!-- Portfolio Items -->
                                            <ul class="portfolio group"> 
                                                <?php
													$query = "SELECT * FROM $wpdb->posts WHERE post_type='ngo' AND post_status='publish' ORDER BY post_date DESC  ";//ORDER BY post_name ASC 
													$ngo_posts = $wpdb->get_results($query);

													//	wp_reset_query();
													//	query_posts(array('post_type'=>'ngo'));
													$i=1;
													foreach($ngo_posts as $post){
														setup_postdata( $post );
														$category = get_the_category(); 

												?>
													<li class="item" data-id="id-<?php echo $i; ?>" data-type="cat-item-<?php echo $category[0]->cat_ID; ?> cat-item-<?php echo $category[1]->cat_ID; ?>">
														<div class="event-container">
														 <a href="<?php the_permalink(); ?>" ><h2 class="event-title"><?php the_title(); ?></h2></a> 
														 <?php 	
															if(has_post_thumbnail()){
																echo the_post_thumbnail('thumb200');
															}else{ 
														?>
																<img class="attachment-ngo-all-thumb wp-post-image" height="100" width="200" src="<?php bloginfo('template_directory'); ?>/images/default_thumb.jpg">
														<?php } ?>
														<div class="post-categories">
														
														<?php 
														if($category){
															foreach($category as $cat){
																echo '<span>'.$cat->name.'</span>';
															} 
														}else{echo '<span>uncategorised</span>'; } ?>
														</div>
														<?php	//echo the_category();																 
															$thumb_image = get('thumb_image');
															echo  !empty($thumb_image)? '<img src="'.$thumb_image.'" alt="Thumb Image" class="thumb-image"/>':false
														 ?>
														 <div class="event-excerpt"><?php echo excerpt(40); ?></div>
														 </div>
													 </li>
													<?php $i++; } // end of the loop. ?>
                                                <div class="clearfix"></div>
                                            </ul>
                                            <div class="clearfix">&nbsp;</div>
                                        </div>
                                    </div>
<script>
$(document).ready(function(){
	// Clone portfolio items to get a second collection for Quicksand plugin
	var $portfolioClone = $(".portfolio").clone();
	
	// Attempt to call Quicksand on every click event handler
	$(".filter a").click(function(e){
		
		$(".filter li").removeClass("current");	
		
		// Get the class attribute value of the clicked link
		var $filterClass = $(this).parent().attr("class");

		if ( $filterClass == "all" ) {
			var $filteredPortfolio = $portfolioClone.find("li");
		} else {
			var $filteredPortfolio = $portfolioClone.find("li[data-type~=" + $filterClass + "]");
		}
		
		// Call quicksand
		$(".portfolio").quicksand( $filteredPortfolio, { 
			duration: 800, 
			easing: 'easeInOutQuad' ,
		//	useScaling: true
		}, function(){
			
			// Blur newly cloned portfolio items on mouse over and apply prettyPhoto
			$(".portfolio a").hover( function(){ 
				$(this).children("img").animate({ opacity: 0.75 }, "fast"); 
			}, function(){ 
				$(this).children("img").animate({ opacity: 1.0 }, "slow"); 
			}); 
			
		});


		$(this).parent().addClass("current");

		// Prevent the browser jump to the link anchor
		e.preventDefault();
	})
});
									</script>	


<?php get_footer(); ?>