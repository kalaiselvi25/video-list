<?php
/*
Plugin Name:  Sherman Video management
Plugin URI:   https://softalliancetech.com/
Description:  Sherman Video management
Version:      1.0
Author:       SAT 
Author URI:   https://softalliancetech.com/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wpb-tutorial
Domain Path:  /languages
*/
add_action( 'wp_enqueue_scripts', 'videomanagement_assets');
function videomanagement_assets(){
	$plugin_url = plugin_dir_url( __FILE__ );
	wp_register_style( 'wom-videomanagement_css', $plugin_url . "/wom-videomanagement.css");
	wp_enqueue_style( 'wom-videomanagement_css' ); 

	
}
function wizardfrm(){
?>
<script>
var wizajax="<?php echo admin_url( 'admin-ajax.php' ) ?>";
</script>
<?php 
}
add_action('wp_head','wizardfrm');
function custom_post_type_wom_video() {
    $labels = array(
        'name' => 'Sherman Video',
        'singular_name' => 'Sherman Video',
        'menu_name' => 'Sherman Video',
        'all_items' => 'All Videos',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Video',
        'edit_item' => 'Edit Video',
        'new_item' => 'New Video',
        'view_item' => 'View Video',
        'search_items' => 'Search Video',
        'not_found' => 'No Video found',
        'not_found_in_trash' => 'No Video found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-book',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive' => true,
    );

    register_post_type('wom_video', $args);
}
add_action('init', 'custom_post_type_wom_video');

add_shortcode('sherman-videos','wom_video_method');


add_action('wp_ajax_custom_ajax_action', 'custom_ajax_action');
add_action('wp_ajax_nopriv_custom_ajax_action', 'custom_ajax_action'); 

function custom_ajax_action(){
	$html = '';
	$plugin_url = plugin_dir_url( __FILE__ );
$args = array(
    'post_type' => 'wom_video',
    'posts_per_page' => 15, // Number of posts per page
    'paged' => $_GET['offset'] ? $_GET['offset'] : 1, // Current page number
);

// Initialize the query
$custom_query = new WP_Query($args);
		
	$html .= '<div class="wom_video_container col-12">';
	$html .= '<div class="row">';
	if ($custom_query->have_posts()) {
        
		while ($custom_query->have_posts()) {
			$custom_query->the_post();
			$video = get_field('video');
			if(empty($video)){
				$video = [];
				$video_url = get_field('video_link');
				if(isset($video_url) && $video_url != ""){
					$video['url'] = $video_url;
					
				}
				
			}
			
			$thumbnail = get_field('thumbnail');	
			
			$html .= '<div class="wom_video_item">';
			
			
				
				if($thumbnail){
					$imgsrc = $thumbnail['url'];
				}
				else{
					$imgsrc = $plugin_url.'/placeholder-image.png';
				}
				

             

				/*Free videos*/
		       $html .= the_content();
			
		

			$html .= '</div>';
			 
		}
		/*Display form in popup */
				$html .= '<div id="popup-content" class="mfp-hide" >';
				
				$html .= do_shortcode('[[elementor-template id="1890"]]');
				$html .= '<div class="video-container">
					<iframe id="video-iframe" class="mfp-iframe" width="900" height="550" frameborder="0" allowfullscreen></iframe>
				</div>';
				$html .= '</div>';
		 $html .= '</div>';

		
		wp_reset_postdata();
	} else {
		$html .=  '';
	}
	$html .= '</div>';

	echo $html;
	die();
}
function wom_video_method(){
?>

 <style>
 
 </style>
<script type="text/javascript">
		jQuery(document).ready(function($) {
			
			
			/* ajax pagination */
    var offset = 1;
    var loading = false;

    function loadMoreItems() {
        if (loading) return;
        loading = true;
        $("#load-more").text("Loading...");
		$("#load-more").show();

        $.ajax({
            url: wizajax,
            type: "GET",
			data: {
                action: 'custom_ajax_action', 
                offset: offset,
            },
            success: function(data) {
				
                if (data.length > 64) {
                    $("#content-container").append(data);
                    offset += 1; // Adjust this value based on your pagination logic
                } else {
                    $("#load-more").text("No more items to load");
                    $("#load-more").prop("disabled", true);
                }
				$("#load-more").hide();
				
                loading = false;
            }
        });
    }

    // Load more items when the "Load More" button is clicked
    $("#load-more").click(function() {
        loadMoreItems();
    });

    // Load more items when the user scrolls to the bottom of the page
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300) {
            loadMoreItems();
        }
    });

    // Initial load
    loadMoreItems();
		});
		</script>
<?php
$html = '';
$html .= '<div id="content-container">
</div>
<button id="load-more">Load More</button>
';
return $html;
}