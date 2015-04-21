<?php
#
# DO NOT EDIT THIS FILE
# ---------------------
# You would lose your changes when you upgrade your site. Use php widgets instead.
#

global $sem_theme_options;

if ( !isset( $active_layout ) )
	$active_layout = apply_filters('active_layout', $sem_theme_options['active_layout']);

# show header
get_header();


			# the loop

			sem_panels::display('before_the_entries');

			if ( have_posts() ) :

				# loop through entries

				while ( have_posts() ) :

					the_post();

					$class = get_post_class();

					// remove hentry so prevent snippets validation error
					if ( is_page() ) {
				        $class = array_diff( $class, array( 'hentry' ) );
				    }

					echo '<article class="entry' . ( $class ? ( ' ' . implode(' ', $class) ) : '' ) . '">' . "\n";

					sem_panels::display('the_entry');

					echo '<div class="spacer"></div>' . "\n"
                        . '</article>'  . "\n";

				endwhile; # have_posts()

			else :

				# fallback

				sem_panels::display('the_404');

			endif; # have_posts()

			sem_panels::display('after_the_entries');


# show footer
get_footer();
