<?php
#
# DO NOT EDIT THIS FILE
# ---------------------
# You would lose your changes when you upgrade your site. Use php widgets instead.
#

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
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
<!--[if lt IE 9]>
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php
    if ( is_singular() && get_option( 'thread_comments' ) )
  		wp_enqueue_script( 'comment-reply' );

    do_action('wp_head');
?>
</head>

<body class="<?php echo implode(' ', get_body_class(array('skin', 'custom'))); ?>" itemscope itemtype="http://schema.org/WebPage">
<?php
do_action('body_open');

do_action('before_the_canvas');

# canvas
echo '<div id="site_container">' . "\n";

# wrapper
echo '<div id="wrapper" class="hfeed">' . "\n";

echo '<div id="wrapper_top"><div class="hidden"></div></div>' . "\n";

echo '<div id="wrapper_inner">' . "\n";

	
	# header
	
	if ( $active_layout != 'letter') :
		
		sem_panels::display('the_header');
		
	endif;

	
	# body
	
	echo '<div id="body" class="wrapper">' . "\n";

	echo '<div id="body_top"><div class="hidden"></div></div>' . "\n";

	echo '<div id="body_inner" class="wrapper_item">' . "\n";
		
		switch ( $active_layout) :
		
		case 'sms':

			# sidebar wrapper for sms layout
		
			echo '<div id="sidebar_wrapper">' . "\n";
			
			break;

		endswitch;

			
		# content
		
		echo '<div id="main" class="main pad" role="main" itemprop="mainContentOfPage">' . "\n";

		echo '<div class="main_content' . ( is_archive() || is_404() || is_search() ? ' entry' : '' )  . '">' . "\n";
?>