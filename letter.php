<?php
#
# DO NOT EDIT THIS FILE
# ---------------------
# You would lose your changes when you upgrade your site. Use php widgets instead.
#


/*
Template Name: Sales Letter
*/

add_filter('active_layout', array('sem_template', 'force_letter'));
remove_action('wp_footer', array('sem_template', 'display_credits'), 5);

# show header
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php wp_title( '&#8211;', true, 'right'); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php
    if ( is_singular() && get_option( 'thread_comments' ) )
   		wp_enqueue_script( 'comment-reply' );

    do_action('wp_head');
?>
</head>
<body class="<?php echo implode(' ', get_body_class(array('skin', 'custom'))); ?>">

<div id="wrapper">
<div id="wrapper_top" class="wrapper_section"><div class="hidden"></div></div>
<div id="wrapper_middle" class="wrapper_section">
<?php
# show header
header::letter();
?>
<?php
/*echo '<main id="main" class="main" role="main" itemprop="mainContentOfPage">' . "\n";

echo '<div class="main_content' . ( is_archive() || is_404() || is_search() ? ' entry' : '' )  . '">' . "\n";
*/
sem_panels::display('before_the_entries');

# show posts
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

	$class = get_post_class();

	// remove hentry so prevent snippets validation error
	if ( is_page() ) {
        $class = array_diff( $class, array( 'hentry' ) );
    }

	echo '<article>'
	    . '<div class="entry' . ( $class ? ( ' ' . implode(' ', $class) ) : '' ) . '">' . "\n";

			sem_panels::display('the_entry');

	echo '<div class="spacer"></div>' . "\n"
		. '</div>' . '<!-- entry -->' . "\n"
	                   . '</article>'  . "\n";


	endwhile;
# or fallback
elseif ( is_404() ) :
	sem_panels::display('the_404');
endif;

sem_panels::display('after_the_entries');
/*echo '</div><!-- main_content -->' . "\n";

echo '</main><!-- main -->' . "\n";
*/
?>
</div>
<div id="wrapper_bottom" class="wrapper_section"><div class="hidden"></div></div>
</div><!-- wrapper -->
<?php

# show footer
do_action('wp_footer');
?>
</body>
</html>