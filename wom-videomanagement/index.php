<?php
/*
Plugin Name:  WP Youtube Feed
Plugin URI:   https://softalliancetech.com/
Description:  WP Youtube Feed
Version:      1.0
Author:       SAT 
Author URI:   https://softalliancetech.com/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wpb-tutorial
Domain Path:  /languages
*/
add_action( 'wp_enqueue_scripts', 'filter_assets');
function filter_assets(){
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_register_style( 'youtube_custom_css', $plugin_url . "/youtube_custom.css");
	wp_enqueue_style( 'youtube_custom_css' ); 
	
	wp_register_script( 'youtube_custom_js', $plugin_url . "/youtube_custom.js",array('jquery'), null, true );
    wp_enqueue_script( 'youtube_custom_js' );
}
add_shortcode('youtube_feed_0','youtube_feed_method');
function youtube_feed_method(){
	$API_Key    = 'YOU GOOGLE API KEY'; 
	$Channel_ID = 'YOUR CHANNEL ID'; 
	$Max_Results = 20; 
	
	$api_response = file_get_contents('https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$Channel_ID.'&fields=items/statistics/subscriberCount&key='.$API_Key);
	$api_response_decoded = json_decode($api_response, true);
	$z = $api_response_decoded['items'][0]['statistics']['subscriberCount'];
	if(!empty($z)){
		echo "<input type='hidden' value='".$z."' class='youtuesubsc'>";
	}
	
	$apiData = @file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$Channel_ID.'&maxResults='.$Max_Results.'&key='.$API_Key.''); 
	if($apiData){ 
		$videoList = json_decode($apiData); 
	}else{ 
		echo 'Invalid API key or channel ID.'; 
	}

	
		if(!empty($videoList->items)){ 
	     echo '<div class="owl-carousel owl-theme youtubecarousel" style="margin:0px 15px;">';
			foreach($videoList->items as $item){ 
			
				// Embed video 
				if(isset($item->id->videoId)){
					$datecreated = $item->snippet->publishedAt;
					$date=date_create($datecreated);
					$fordate = date_format($date,"F d, Y h:i A");
					echo '<div>';
					echo '<a href="https://www.youtube.com/watch?v='.$item->id->videoId.'" class="youtubepopup" data-lightbox="gallery">';
					echo '<img style="width:100%;" src="'.$item->snippet->thumbnails->medium->url.'" />'; ?>
					<div class="sby_play_btn">
                        <span class="sby_play_btn_bg"></span>
						<svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="youtube" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-youtube fa-w-18"><path fill="black" d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z" class=""></path></svg>
						</div>
					<?php
					
					echo '</a>';
					echo  '<div style="text-align:center;margin-top:10px;padding-bottom: 10px;border-bottom: 2px solid #e5e5e5;">';
					echo $fordate;
					echo '</div>';
	
					echo '</div>';
				} 
			}
		   echo '</div>';		
	}else{ 
		echo '<p class="error">Error</p>'; 
	}
	?>
					<div style="text-align:center;display: flex;justify-content: center;margin-top: 10px;">
						<a href="https://www.youtube.com/channel/UCj8sngIUAbX0-k5fdGgrKQQ/" style="background: #9c090f;display: flex;padding: 7px;border-radius: 2px;color: #fff;width: 140px;justify-content: center;" target="_blank" rel="noopener"><div style="
    margin-right: 5px;
"><span class="dashicons dashicons-youtube"></span></div><div>SUBSCRIBE</div></a>
					</div>	
<?php 
}
?>