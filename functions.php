<?php
if(!defined('ABSPATH')) exit;

// add_action('wp_enqueue_scripts', 'fs_scripts');
// function fs_scripts() {
// 	wp_enqueue_script( 'flatpckr', 'https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.2/flatpickr.min.js', array('jquery'), '1.0.0', true);
// 	wp_enqueue_script( 'script', plugin_dir_url( __FILE__ ).'/assets/js/script.js', array('jquery'), '1.0.0', true);
	// wp_enqueue_script('flatpckr');
	// wp_enqueue_script('script');
// }

add_filter( 'woocommerce_product_data_tabs', 'fs_product_tabs' ); 
function fs_product_tabs( $fs_tabs ) 
{ 
	$fs_tabs['flash-sale-tab'] = array( 
				'label' => __( 'Flash Sale', 'woocommerce' ), 
				'target' => 'flash_sale_options', 
				'class' => array( 'show_if_simple' ), );
				return $fs_tabs; 
} 



add_action ('woocommerce_product_data_panels', 'fs_options_content');
function fs_options_content() {
	global $post;?>
	<div id='flash_sale_options' class='panel woocommerce_options_panel'>
	<div class='options_group'>"
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<style>
		.flatpickr-calendar {
			box-shadow: 0 0 9px 0 #adadad;
		}

		.flatpickr-month, .flatpickr-weekdays, .flatpickr-weekday {
			background-color: #079AF0 !important;
			color: #fff !important;
		}
	</style>
		<?php
		woocommerce_wp_select( array(
			'id'		=> '_status',
			'label'		=> __('Status', 'woocommerce'),
			'options'	=> array(
				'disable' => __('Disable', 'woocommerce'),
				'enable'  => __('Enable', 'woocommerce')
			)
		));

		woocommerce_wp_text_input( array(
			'id'		=> '_timerange',
			'label'		=> __('Time Range', 'woocommerce'),
			'placeholder'=>__('Select Start Date & End Date...', 'woocommerce'),
			'class'		=> 'flatpickr'

		));

		woocommerce_wp_checkbox( array(
			'id'		=> '_product_left',
			'label'		=> __('Show Product Left?', 'woocommerce'),

		));
		?>
		<!-- <label for="timerange">Time Range</label>
		<input type="text" name="timerange" class="flatpickr" placeholder="Select a range" value="" /> -->
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.2/flatpickr.min.js"></script>
		
		<script>
			jQuery(document).ready(function($){
				$('.flatpickr').flatpickr({
					mode: "range",
				    enableTime: true,
    				dateFormat: "M j, Y H:i:S",
    				minDate: "today"
				});

			});
		</script>
	</div>
	</div>

	<?php
}

add_action ('woocommerce_process_product_meta', 'fs_save_fields');
function fs_save_fields( $post_id ) {
	$status = $_POST['_status'];
	$timerange = $_POST['_timerange'];
	$productleft = $_POST['_product_left'];
	update_post_meta($post_id, '_status', esc_attr($status));
	update_post_meta($post_id, '_timerange', esc_attr($timerange));
	update_post_meta($post_id, '_product_left', esc_attr($productleft));
}

class fs_widget extends WP_Widget {
  /**
  * To create the Flash Sale all four methods will be 
  * nested inside this single instance of the WP_Widget class.
  **/
  public function __construct() {

    $widget_options = array( 
      'classname' => 'flash_sale_widget',
      'description' => 'This is an Flash Sale',
    );
    parent::__construct( 'flash_sale_widget', 'Flash Sale', $widget_options );
  }
  public function widget( $args, $instance ) {
  $title = apply_filters( 'widget_title', $instance[ 'title' ] );
  $blog_title = get_bloginfo( 'name' );
  $tagline = get_bloginfo( 'description' );
  echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; ?>

	<!-- content here --><?php
	
 $args1 = array(
 	'post_type' => 'product',
 	'meta_query' => array(
 		array(
 			'key' => '_status',
 			'value' => 'enable'
 		)
 	),
 );
  // The Query
$custom_query = new WP_Query( $args1 );

if ( $custom_query->have_posts() ) { 

  while ( $custom_query->have_posts() ) { $custom_query->the_post(); 

  	global $product;
  	?>
	<style>
		.flash-sale-container {
			width: 100%;
			border-radius: 10px;
			border: 2px solid #E7D916;
			position: relative;
			font-family: Lato, sans-serif;
		}

		.flash-sale-container .img{
			width: 100%;
			position: relative;
		}

		.flash-sale-container .img img {
			display: block;
			width: 95%;
			height: auto;
			margin: auto;
		}

		.flash-sale-container h2 {
			color: #000;
			width: 100%;
			text-align: center;
			font-weight: bold;
			font-size: 2.3vmax;
			height: 4.6vw;
			overflow: hidden;
		}

		.flash-sale-container .count {
			position: relative;
			width: 100%;
			height: 7vw;
			margin-bottom: 1vw;
		}

		.flash-sale-container .price {
		  display: block;
		  width: 100%;
		  height: 5vw;
		  overflow: hidden;
		  position: relative;
		  margin-bottom: 1vw;
		  padding-left: 2vw;
		  padding-right: 2vw;

		}

		.flash-sale-container .price .reg-price {
		  color: #666666;
		  position: absolute;
		  text-decoration: line-through;
		}

		.flash-sale-container .price .sale-price {
		  text-decoration: none;
		  font-size: 2vmax;
		  color: #FF0000;
		  position: absolute;
		  font-weight: bold;
		  bottom:0;
		}

		.flash-sale-container .price .atc-circle {
		  background-color: rgb(250, 218, 10);
		  border-radius: 50%;
		  width: 3.5vmax;
		  height: 3.5vmax;
		  position: absolute;
		  right: 0;
		  line-height: 3.5vmax;
		  display: inline-block;
		  text-align: center;
		  vertical-align: middle !important;
		  color: #fff;
		  bottom: 0;
		  transition: .3s;

		}

		.flash-sale-container .price .atc-circle i {
		  vertical-align: middle;
		}

       
       	 .countdown-container {
       	 	width: 100%;
       	 	margin: auto;
       	 }


		.countdown-container section {
			margin: auto !important;
			display: block;
			width: 21.5vw;
			position: relative;
		}
		.countdown-container div {
			width: 5vw;
			height: 5vw;
			display: inline-block;
			background: rgb(250, 218, 10);
			color: #fff;
			float: left;
			margin-right: .5vw;
			border-radius: 50%;
		}

		.countdown-container div:last-child {
			margin-right: 0;
		}

		.countdown-container span {
			width: 5vw;
			display: inline-block;
			color: #000;
			float: left;
			margin-right: .5vw;
			text-align: center;
		}

		.countdown-container span:last-child {
			margin-right: 0;
		}

		.countdown-container p {
       	 	width: 100%;
       	 	font-weight: bold;
       	 	text-align: center;
       	 	font-size: 3vmax;
       	 	line-height: 5vw;
       	 	margin: 0;
       	 	vertical-align: middle !important;
       	 }

       	 
       	 
	</style>
   <div class="flash-sale-container">
   		<span style="margin:2vw;"><?= wc_get_product_category_list($product->get_id()) ?></span>
       <a href="<?php the_permalink(); ?>">
       	<div class="img">
       		<?php the_post_thumbnail();?>
       	</div>
           <?php the_title('<h2>', '</h2>');?>
       </a>
       
      	<div class="count" id="count">
      		<div class="countdown-container" id="countdown-container">
      		<section>
				<div id="days">
					
				</div>
				<div id="hours">
					
				</div>
				<div id="minutes">
					
				</div>
				<div id="seconds">
					
				</div>
			</section>

				<section>
					<span>day</span>

					<span>hour</span>

					<span>min</span>

					<span>sec</span>
				</section>
			</div>
      	</div>

      	<div class="price">
      		<?php echo $product->get_price_html();?>
      		<i class="fa fa-arrow-right atc-circle" area-hidden="true"></i>
      	</div>
   </div>

   	<?php $datetime = get_post_meta(get_the_ID(), '_timerange', true); ?>
   <script type="text/javascript">

   		var datetime = "<?php echo $datetime;?>";

   		var fixDate = datetime.slice(-21);
   		var endDate = new Date(fixDate).getTime();
   		var x = setInterval(function(){
   		var startDate = new Date(). getTime();
   		var distance = endDate - startDate;
   		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
   		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
   		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
   		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
  //  		var countdownHTML = days + "d " + hours + "h "
  // + minutes + "m " + seconds + "s ";
  		//ADDING HTML
   		// document.getElementById("count").innerHTML = countdownHTML;
   		document.getElementById("days").innerHTML = "<p>" + days + "</p>";
   		document.getElementById("hours").innerHTML = "<p>" + hours + "</p>";
   		document.getElementById("minutes").innerHTML = "<p>" + minutes + "</p>";
   		document.getElementById("seconds").innerHTML = "<p>" + seconds + "</p>";
   		// Ternary operator if outdate
  		if (distance < 0) {
  			clearInterval(x);

  			document.getElementById("count").innerHTML = "<div style='color: #FF0000;font-weight:bold;font-size:3vw;text-align:center;'>EXPIRED</div>";
  		}
   		}, 1000);
   </script>
<?php 
 }

} else {
  echo "post not found";
}
/* Restore original Post Data */
wp_reset_postdata();
echo $args['after_widget'];
}

public function form( $instance ) {
  $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
    <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
  </p><?php 
}

public function update( $new_instance, $old_instance ) {
  $instance = $old_instance;
  $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
  return $instance;
}

}

function jpen_register_flash_sale_widget() { 
  register_widget( 'fs_widget' );
}
add_action( 'widgets_init', 'jpen_register_flash_sale_widget' );

function flash_sale_submenu() {
	add_submenu_page('woocommerce', 'Flash Sale', 'Flash Sale', 'manage_options', 'flash-sale', 'flash_sale_submenu_cb');
}

function flash_sale_submenu_cb() {?>
	<h2>Flash Sale</h2>
	<style>
		.container {
			width: 100%;
			background: #FFFFFF;
			border-radius: 0 0 10px 0 #adadad;
		}
	</style>
	<div class="container">
		<div class="flash-sale-list">

		</div>
	</div>
	<?php
}

add_action('admin_menu', 'flash_sale_submenu');


?>