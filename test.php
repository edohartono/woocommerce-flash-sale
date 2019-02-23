<?php
echo "page loaded";
			//Looping product list
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => '_status',
				'value' => 'enable'
			)
		),
	);

	$loop = new WP_Query($args);
	if($loop->have_posts()){
		while($loop->have_posts()): $loop->the_post();?>
			
			
<?php}?>