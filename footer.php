<?php
#
# DO NOT EDIT THIS FILE
# ---------------------
# You would lose your changes when you upgrade your site. Use php widgets instead.
#
		# end content


	do_action('main_wrapper_end');

	echo '</div><!-- main_wrapper -->' . "\n";

	echo '<div id="bottom_body_sidebar" class="sidebar" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">' . "\n";

	sem_panels::display('bottom_body_sidebar');

	echo '</div><!-- body_bottom_sidebar -->' . "\n";

	# end body
	
	echo '</div><!-- body_middle -->' . "\n";
	
	echo '<div id="body_bottom" class="body_section"><div class="hidden"></div></div>' . "\n";

	echo '</div><!-- body_wrapper -->' . "\n";
	
	# footer
	
	if ( $active_layout != 'letter') :
		
		sem_panels::display('the_footer');
		
	endif;


do_action('wp_footer');

# end wrapper

echo '</div><!-- wrapper_middle -->' . "\n";

echo '<div id="wrapper_bottom" class="wrapper_section"><div class="hidden"></div></div>' . "\n";

echo '</div><!-- wrapper -->' . "\n";


echo '</div><!-- site_container -->' . "\n";

do_action('after_the_canvas');

do_action('body_close');
?>
</body>
</html>