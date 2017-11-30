<?php
/**
 * The front page template file
 *
 * This template file defines the front (landing) page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Velocity
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
			endif;

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		<?php
		if (is_front_page()) : ?>
		<nav id="mid-front-page-navigation" class="mid-front-page-navigation">
				<?php
					wp_nav_menu( array(
						'theme_location' => 'mid-front-page',
						'menu_id'        => 'mid-front-page-menu',
						'menu_class'     => 'mid-front-page-menu',
						'walker'         => new Walker_Mid_Front_Page_Menu()
					) );
				?>
			</nav><!-- #mid-front-page-navigation -->
		<?php
		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();

class Walker_Mid_Front_Page_Menu extends Walker {

	// Tell Walker where to inherit it's parent and id values
	var $db_fields = array(
		'parent' => 'menu_item_parent', 
		'id'     => 'db_id' 
	);

	// Note: Menu objects include url and title properties.
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$background_image = get_the_post_thumbnail_url($item->object_id);
		$output .= sprintf( "\n<li><a href='%s'%s%s><h2>%s</h2></a>",
			$item->url,
			( $item->object_id === get_the_ID() ) ? ' class="current"' : '',
			( !empty($background_image) ) ? 'style="background-image:url('.$background_image.');"' : '',
			$item->title
		);
	}

	function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$output .= "</li>\n";
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "\n<ul class=\"sub-menu\">";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "</ul>\n";
	}

}
