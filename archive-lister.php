<?php

/*
*  Template Name: Lister 4 columns
*/
?>

<?php
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

function register_admin_style() {
    wp_register_style( 'admin_style', plugins_url('/css/admin-style.css', __FILE__), false, '1.0.0', 'all' );
}
    
function prefix_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'prefix-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
}


/** This function sets the cpt to display in cloumns
*****************************************************************/
add_filter( 'post_class', 'lister_archive_post_class' );

function lister_archive_post_class( $classes ) {
	if( is_singular() )
		return $classes;
 
	$classes[] = 'one-fourth';
	global $wp_query;
	if( 0 == $wp_query->current_post || 0 == $wp_query->current_post % 4 )//to show 2 on page % 2
		$classes[] = 'first lister';
	return $classes;
}

/* The loop
-------------------------------------------------------------------------------->
*/
remove_action('genesis_post_title', 'genesis_do_post_title');//We're not using the title for this post type
remove_action('genesis_loop', 'genesis_do_loop');//remove genesis loop
add_action('genesis_loop', 'lister_loop');//add my_loop
 
function lister_loop() {
	$loop = new WP_Query( array( 'post_type' => 'lister', 'posts_per_page' => 10 ) ); ?> 
		<div id="lister">
		<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="single_lister">
		<?php//use the genesis_get_custom_field template tag to display each custom field value ?>
		<?php the_post_thumbnail();?>
		<h1><?php the_title(); ?></h1>
		<h3><?php echo genesis_get_custom_field('lister_agent'); ?></h3>
		<p class="location"><?php echo genesis_get_custom_field('lister_location'); ?></p>           
		</div><!--end my_loop -->
    <?php endwhile;?>
    <?php
    }
	
	
/* Setting up the look of a Listings
-------------------------------------------------------------------------------->
*/

 
/** Force full width content layout */
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );


/** Removes the content info user, date, edit link */
remove_action( 'genesis_before_post_content', 'genesis_post_info' );



/** Custom post titles */
remove_action( 'genesis_post_title','genesis_do_post_title' );

/** Remove the post content */
//remove_action( 'genesis_post_content', 'genesis_do_post_content' );


/** Remove the post meta function */
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );


/**Move post title under the inage **/
//add_action( 'genesis_after_post_content', 'genesis_do_post_title' );

?>

<?php genesis(); ?>
