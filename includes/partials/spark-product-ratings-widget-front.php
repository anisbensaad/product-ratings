<?php 
if ( ! empty( $instance['title'] ) ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
}
if ( ! empty( $title ) ) {
	echo $before_title . $title . $after_title;
}
if(isset($_GET['target']) && $_GET['target'] !=''){
	$target = $_GET['target'];
}


if(isset($target) && term_exists( $target, 'target_group' )){
	$args = array(
		'tax_query' => array(
			array(
				'taxonomy' => 'target_group',
				'field'    => 'slug',
				'terms'    => $target,
			),
		),
		'post_type' => 'product',
		'posts_per_page' => 5,
		'post_status'    => 'publish',
		'orderby'   => 'meta_value_num',
		'meta_key'  => 'rating',
		'order'     => 'DESC'
	);
}else{
	$default_target = get_option('select-default-target');
	$args = array(
		'tax_query' => array(
			array(
				'taxonomy' => 'target_group',
				'field'    => 'term_id',
				'terms'    => $default_target,
			),
		),
		'post_type' => 'product',
		'posts_per_page' => 5,
		'post_status'    => 'publish',
		'orderby'   => 'meta_value_num',
		'meta_key'  => 'rating',
		'order'     => 'DESC'
	);	
}
$query = new WP_Query( $args );
if ( $query->have_posts() ) :
    while ( $query->have_posts() ) : $query->the_post();
    	$rating = get_post_meta( $query->post->ID, 'rating', true ); ?>

    	<div class="product-cpt">
	    <?php if ( has_post_thumbnail() ) {the_post_thumbnail('small', array('class' => 'product-img'));} 
	
	    	for($i=1;$i<6;$i++){
	    		if($i <= $rating){
	    			echo '<i class="fa fa-star checked" aria-hidden="true"></i>';
	    		}else{
	    			echo '<i class="fa fa-star" aria-hidden="true"></i>';
	    		}
	    	}
	    ?>
		<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
		<hr>
	<?php endwhile;?>
<?php endif;?>	
<div class="spark-product-ratings-widget"><?php