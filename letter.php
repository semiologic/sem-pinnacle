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
<meta name="viewport" content="width=device-width" />
<title><?php
if ( $title = trim(wp_title('&#8211;', false)) ) {
	if ( strpos($title, '&#8211;') === 0 )
		$title = trim(substr($title, strlen('&#8211;')));
	echo $title;
} else {
	bloginfo('description');
}
?></title>
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
<div id="wrapper_top"><div class="hidden"></div></div>
<div id="wrapper_inner">
<?php
# show header
header::letter();
?>
<div class="pad">
<?php
sem_panels::display('before_the_entries');

# show posts
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

?>
<article>
<div class="entry" id="entry-<?php the_ID(); ?>">
<?php
		sem_panels::display('the_entry');
?>
</div>
</article>
<?php
	endwhile;
# or fallback
elseif ( is_404() ) :
	sem_panels::display('the_404');
endif;

sem_panels::display('after_the_entries');
?>
</div>
</div>
<div id="wrapper_bottom"><div class="hidden"></div></div>
</div><!-- wrapper -->
<?php

# show footer
do_action('wp_footer');
?>
</body>
</html>