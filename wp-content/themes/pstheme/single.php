<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since 1.0.0
 */

get_header();

/* Start the Loop */
?>

<div class="container">
<?php 

while ( have_posts() ) :
	the_post();

	the_title();

	the_content();

endwhile; // End of the loop.

?>

</div>

<?php 

get_footer();
