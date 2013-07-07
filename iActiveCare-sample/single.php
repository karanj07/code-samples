<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); 
?>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
  <script>
$(function(){
	var coordinates = [];
	var venue = $('.box-container .address').text();
	var geo = new google.maps.Geocoder;
	function getLatLong(address){
     
      geo.geocode({'address':address}, function(results, status){
              if (status == google.maps.GeocoderStatus.OK) {
			  
			  <?php 
				$query = "SELECT `meta_value` FROM wp_postmeta WHERE post_id=".get_the_ID()." AND meta_key='_wp_geo_latitude'";
				$latitude = $wpdb->get_row($query);
				$latitude = $latitude->meta_value;

				$query = "SELECT `meta_value` FROM wp_postmeta WHERE post_id=".get_the_ID()." AND meta_key='_wp_geo_longitude'";
				$longitude = $wpdb->get_row($query);
				$longitude = $longitude->meta_value;
			  ?>
				  var lat = <?php echo isset($latitude)? $latitude: "results[0].geometry.location.lat()"; ?>; 
				  var lng =  <?php echo isset($longitude)? $longitude: "results[0].geometry.location.lng()"; ?>;
				  console.log(lat);
				  console.log(lng);
					var mapOptions = {
						center: new google.maps.LatLng(lat,lng),
						zoom: 9,
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						streetViewControl: false,
						mapTypeControl: false
					};
					var mapOptions2 = {
						center: new google.maps.LatLng(lat,lng),
						zoom: 14,
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						streetViewControl: false,
						mapTypeControl: false
					};

					var map = new google.maps.Map(document.getElementById('gmap'), mapOptions);

					var map2 = new google.maps.Map(document.getElementById('pmap'), mapOptions2);

				/* 	var styles = [
					 {
										featureType: 'water',
										elementType: 'all',
										stylers: [
											{ hue: '#c1c1c1' },
											{ saturation: -100 },
											{ lightness: 0 },
											{ visibility: 'on' }
										]
									},{
										featureType: 'poi',
										elementType: 'all',
										stylers: [
											{ hue: '#c4c4c4' },
											{ saturation: -100 },
											{ lightness: -1 },
											{ visibility: 'on' }
										]
									},{
										featureType: 'landscape',
										elementType: 'all',
										stylers: [
											{ hue: '#f2f2f2' },
											{ saturation: -100 },
											{ lightness: 54 },
											{ visibility: 'on' }
										]
									},{
										featureType: 'road',
										elementType: 'all',
										stylers: [
											{ hue: '#c4c4c4' },
											{ saturation: -100 },
											{ lightness: 36 },
											{ visibility: 'on' }
										]
									},{
										featureType: 'road.highway',
										elementType: 'all',
										stylers: [
											{ hue: '#939393' },
											{ saturation: -100 },
											{ lightness: -10 },
											{ visibility: 'on' }
										]
									},{
										featureType: 'transit',
										elementType: 'all',
										stylers: [
											{ visibility: 'simplified' }
										]
									},{
										featureType: 'landscape.natural',
										elementType: 'all',
										stylers: [
											{ hue: '#e4e4e4' },
											{ saturation: -100 },
											{ lightness: -6 },
											{ visibility: 'on' }
										]
									}
					];

					map.setOptions({styles: styles});
					map2.setOptions({styles: styles});  */

					var markerOptions = {
						position: new google.maps.LatLng(lat, lng),
						//icon: '<?php bloginfo('template_directory'); ?>/images/map-icons/event-icon.png'
					};
					var marker = new google.maps.Marker(markerOptions);
					var marker2 = new google.maps.Marker(markerOptions);
					marker.setMap(map);
					marker2.setMap(map2);
				  return true;
              } else {
                return false;
              }

       });
  }
  getLatLong(venue); 
 


	//console.log(address);




	$("#gmap").on("click", function(){
		$(".pop-map-container").height($(window).height()); $(".pop-map-container").show();
	});
	$(".map-close").on("click", function(){ $(".pop-map-container").height(0); $(".pop-map-container").hide();});

});


</script>

<div class="pop-map-container popup-container" >
	<div id="pop-box" class="pop-box map-box"  >
		<a href="#" class="popup-close map-close" title="close map"></a>
		<div id="pmap"></div>
	</div>
</div>

<script>
var wh = $(window).height();
var bh = $(".map-box").height();
var diff = (wh-bh)/2;
$(".map-box").css("top", diff);
 </script>
<?php if(is_singular('event')){ ?>
	<div id="primary" class="single-event-page inner-page single-inner-page">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<div class="featured-image-container"><?php echo the_post_thumbnail('featured960'); ?></div>
				<div class="page-content">
					<div class="left-content left-column">
					
						<div class="date">
							<i class=" ficon icon-calendar"></i>
							<span class="from"><?php echo date("F j, Y", strtotime( get('from_date'))); ?></span>
							<span class="to"><?php $to_date = get('to_date'); echo !empty( $to_date) ? " - ".date("F j, Y", strtotime( $to_date)) :false; ?></span>
						</div>
						<h2 class="page-title"><?php echo the_title(); ?></h2>
						
						<div class="content-text">	
							<?php echo the_content(); ?>
						</div>
						<?php 
						
						
						
						?>
						<nav class="nav-single">
							<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
							<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
							<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
						</nav><!-- .nav-single -->
						
						<?php comments_template( '', true ); ?>
					</div>
				
					<div class="sidebar right-column">
						<div class="box-container venue">
						<h4 class="box-title">Venue</h4>
						<p><i class="ficon icon-map-marker" style="margin-bottom:20px;"></i>
						<?php if(get('address') || get('city')){ ?>
							<span class="address">
							<?php $address = get('address'); if(!empty($address)){echo $address;} ?></span><?php $city = get('city'); if(!empty($city)){echo ', '.$city;} ?><?php $state = get('state'); if(!empty($state)){echo ', '.$state;} ?></p>
							<div id="gmap"></div>
							<?php if(empty($latitude)){ ?><p><i>please note that the above map only shows the approximate location and not the exact coordinates of the given address</i></p> <?php } ?>
						<?php }else{ echo "no venue information available";} ?>
						</div>
						
						<?php if(get_group('sponsors')){
							$sponsors = get('sponsors_image');
						?>
						<div class="box-container sponsored-by">
							<h4 class="box-title">Sponsored By</h4>
							<?php for($i=1; $i<11; $i++) { echo get('sponsors_image', 1, $i)?'<img src="'.get('sponsors_image', 1, $i).'"/>':false; }?>
						</div>
						<?php } ?>
						
						<?php if(get('more_info')){ ?>
						<div class="box-container more-info">
							<h4 class="box-title">More Info</h4>
							<?php echo get('more_info'); ?>
						</div>
						<?php } ?>
					</div>
				</div><!--page-content-->
			<?php endwhile; // end of the loop. ?>
			
		</div><!-- #content -->
	</div><!-- #primary -->

	
	
	
<?php }elseif(is_singular('ngo')){ ?>
	<div id="primary" class="single-ngo-page inner-page single-inner-page">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
			<div class="featured-image-container"><?php echo the_post_thumbnail('featured960'); ?></div>
			<div class="page-content">
				<div class="left-content left-column">
					<?php if(get('volunteer')){ ?><p class="founded strong-text">Founded on - <?php echo get('about_founded'); ?></p><?php } ?>
					<h2 class="page-title"><?php echo the_title(); ?></h2>
					<div class="mission strong-text"><?php echo get('about_mission'); ?></div>
					<div class="content-text">	
						<?php echo the_content(); ?>
					</div>
					
					<?php if(get('other_events_and_news')){ ?>
					<div class="box-container news-events">
						<h4 class="box-title">News And Events</h4>
						<?php echo get('other_events_and_news'); ?>
					</div>
					<?php } ?>
					
					<?php if(get('other_volunteer')){ ?>
					<div class="box-container volunteer">
						<h4 class="box-title">Volunteer</h4>
						<?php echo get('other_volunteer'); ?>
					</div>
					<?php } ?>
				</div>
			
				<div class="sidebar right-column">
					<div class="box-container contact">
						<h4 class="box-title">Contact</h4>
						<?php if(get_group('contact')){ ?>
						<?php if(get('contact_phone')){ ?><p class="phone"><strong>Phone: </strong><?php echo get('contact_phone'); ?><p>		<?php } ?>
						<?php if(get('contact_fax')){ ?><p class="fax"><strong>Fax: </strong><?php echo get('contact_fax'); ?><p>				<?php } ?>
						<?php if(get('contact_email')){ ?><p class="email"><strong>Email: </strong><?php echo get('contact_email'); ?><p>		<?php } ?>
						<?php if(get('contact_website')){ ?><p class="website"><strong>Website: </strong><?php echo get('contact_website'); ?><p>	<?php } ?>
						<?php if(get('contact_address')){ ?>
							<p class="address"><strong>Address: </strong><?php echo get('contact_address'); ?><?php echo ', '.get('contact_city'); ?><?php $state = get('contact_state'); if(!empty($state)){echo ', '.$state;} ?><p>
							<div id="gmap"></div>
							<?php if(empty($latitude)){ ?><p><i>please note that the above map only shows the approximate location and not the exact coordinates of the given address</i></p> <?php } ?>
						<?php } ?>
						<?php }else{ echo "no contact information available"; } ?>
					</div>
					<?php if(get('gallery')){ ?>
					<div class="box-container gallery ">
						<h4 class="box-title">Gallery</h4>
						<p class="gallery"><?php echo do_shortcode(get('gallery')); ?><?php// echo get('gallery'); ?><p>
					</div>
					<?php } ?>
					<?php if(get_group('members')){ ?>
					<div class="box-container members">
						<h4 class="box-title">Members</h4>
						<?php if(get_group('members')){
						for($i=1; $i<6; $i++) {
						if(get('members_name', $i)){
						?>
						<div class="single-member">
							<span class="member-name"> <img width="60" src="<?php  echo get('members_image', $i)? get('members_image', $i):bloginfo('template_directory')."/images/default_user.jpg"; ?> "/></span>
							<div class="member-info">
								<p class="member-name"><?php  echo get('members_name', $i) ?></p>
								<p class="member-position"><i><?php echo get('members_position', $i) ?></i></p>
							</div>
						</div>	
						<?php 
								}else{return false;}
							}
						} ?>
					</div>
					<?php } ?>
				</div>
			</div><!--page-content-->
			
			
				<?php //get_template_part( 'content', 'page' ); ?>
				<?php //comments_template( '', true ); ?>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
	
<?php }else{ ?>



<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>

				<nav class="nav-single">
					<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'twentytwelve' ) . '</span> %title' ); ?></span>
					<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'twentytwelve' ) . '</span>' ); ?></span>
				</nav><!-- .nav-single -->

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>
<?php } ?>
<?php get_footer(); ?>