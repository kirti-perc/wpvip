<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();

?>
<?php 
/* Start the Loop */
while ( have_posts() ) :
	the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( ! is_front_page() ) : ?>
		<div class="entry-header alignwide">
			<div class="container">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</div>
		</div>	
	<?php endif; ?>
	<div class="entry-content">
		<div class="container">
		<?php
		the_content();		
		?>
	     </div>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->

<?php endwhile; ?>
<?php get_footer(); ?>
