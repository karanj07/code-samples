<?php
/**
 * Template Name: Events Page template
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

get_header(); ?>
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
			<?php echo do_shortcode('[contact-form-7 id="108" title="event-suggest"]'); ?>
		</div>
	</div>
</div>
<input type="button" value="Suggest An Event" class="suggest-form" />
<div id="primary" class=" events-page inner-page inner-list-page">
		<div id="content" role="main">

			<?php 
			
			//"SELECT users.id, users.profile_image FROM users INNER JOIN ".self::$table_name." ON users.id = ".self::$table_name.".user_id";
			
					$query = "SELECT * FROM $wpdb->posts WHERE post_type='event' AND post_status='publish'  ORDER BY post_name ASC LIMIT 12";
					$ngo_posts = $wpdb->get_results($query);
					foreach($ngo_posts as $post){
						$post->from_date = strtotime(get('from_date'));
						$post->to_date = strtotime(get('to_date'));
					}
					function sortByDate($a, $b) {
						return $a->from_date - $b->from_date;
					}

					usort($ngo_posts, 'sortByDate');
					//	wp_reset_query();
					//	query_posts(array('post_type'=>'ngo'));
					foreach($ngo_posts as $post){
						setup_postdata( $post );
			/* $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			query_posts(array('post_type'=>'event', 'posts_per_page' => 10, 'paged' => $paged, 'showposts' => 20));
			
			while ( have_posts() ) : the_post();	 */

			if(time() < strtotime(get('from_date')) || time() < strtotime(get('to_date')) ){
			?>
			
			<div class="event-container">
			<div class="event-header">
				<a href="<?php the_permalink(); ?>" ><h2 class="event-title"><?php the_title(); ?></h2></a>
				<div class="date">
					<i class=" ficon icon-calendar"></i>
					<span class="from"><?php   echo date("F j, Y", strtotime( get('from_date')));?></span>
					<span class="to"><?php $to_date =  get('to_date'); echo !empty( $to_date) ? " - ".date("F j, Y", strtotime($to_date )) :false; ?></span>
				</div>
				<i class=" ficon icon- icon-map-marker"></i>
				<span class="venue">
					<?php if(get('address')){ ?>
					<?php $address = get('address'); if(!empty($address)){echo $address;} ?><?php $city = get('city'); if(!empty($city)){echo ', '.$city;} ?><?php $state = get('state'); if(!empty($state)){echo ', '.$state;} ?>
					<?php }else{ echo "N/A";} ?>	
				</span> 
			</div><!--event-header-->
			 <?php 
			 
			 $thumb_image = get('thumb_image');
			echo  !empty($thumb_image)? '<img src="'.$thumb_image.'" alt="Thumb Image" class="thumb-image"/>': the_post_thumbnail('thumb200');
			 ?>
			 <div class="event-excerpt"><?php the_excerpt(); ?> </div>
			 </div>
			 <?php //get_template_part( 'content', get_post_format() ); ?>
				<?php //comments_template( '', true ); ?>
			<?php 
			}
			//endwhile; // end of the loop. 
			}	 	?>
			<?php twentytwelve_content_nav( 'nav-below' ); ?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>