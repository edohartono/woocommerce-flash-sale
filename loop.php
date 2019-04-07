
			//Looping product list
			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
			);

			$loop = new WP_Query($args);
			if($loop->have_posts()){
			while($loop->have_posts()) {
				$title= get_the_title();
				echo $title;
			
}}?>

