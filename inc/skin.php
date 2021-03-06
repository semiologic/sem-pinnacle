<?php
/**
 * sem_skin
 *
 * @package Semiologic Pinnacle
 **/


class sem_skin {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  0.5.0
	 * @access private
	 * @var    object
	 */
	private static $instance;


	/**
	 * Returns the instance.
	 *
	 * @since  0.5.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
        add_action('semiologic_page_skin', array($this, 'save_options'), 0);
        add_action('admin_head', array($this, 'admin_head'));
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
    }

    /**
	 * admin_head()
	 *
	 * @return void
	 **/

	function admin_head() {
		echo <<<EOS

<style type="text/css">
#current_option img {
	border: solid 1px #999;
	float: left;
	clear: right;
	margin-right: 0.625em;
}

.current_option_details th {
	text-align: left;
	padding-right: 0.3125em;
}

.available_option {
	text-align: center;
	width: 17.1875em;
}

.available_option img {
	border: solid 1px #ccc;
}

.available_option label {
	cursor: pointer !important;
}

#available_options {
	border-collapse: collapse;
}

#available_options td {
	padding: 0.625em;
	border: solid 1px #ccc;
}

#available_options td.top {
	border-top: none;
}

#available_options td.bottom {
	border-bottom: none;
}

#available_options td.left {
	border-left: none;
}

#available_options td.right {
	border-right: none;
}

</style>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#available_options label").click(function() {
		jQuery(this).closest('td').find('input:radio').attr('checked', 'checked');
		jQuery('#option_picker').trigger('submit');
	});
});
</script>

EOS;
	} # admin_head()
	

	/**
	 * scripts()
	 *
	 * @return void
	 **/

	function scripts() {
		wp_enqueue_script('jquery');
	} # scripts()

	/**
	 * save_options()
	 *
	 * @return void
	 **/
	
	function save_options() {
		if ( !$_POST || !current_user_can('switch_themes') )
			return;
		
		check_admin_referer('sem_skin');

		global $sem_theme_options;
		global $sem_stock_skins;

		$sem_theme_options['active_skin'] = preg_replace("/[^a-z0-9_-]/i", "", $_POST['skin']);
		$custom_skin = !in_array( strtolower($sem_theme_options['active_skin']), $sem_stock_skins );
		$sem_theme_options['skin_data'] = sem_template::get_skin_data($sem_theme_options['active_skin'],
			$custom_skin ? sem_content_path . '/skins' : sem_path . '/skins' );

		if ( current_user_can('unfiltered_html') )
			$sem_theme_options['credits'] = stripslashes($_POST['credits']);
		
		write_sem_options( $sem_theme_options);
		delete_transient('sem_header');
		
		echo '<div class="updated fade">'
			. '<p><strong>'
			. __('Settings saved.', 'sem-pinnacle')
			. '</strong></p>'
			. '</div>' . "\n";
	} # save_options()
	
	
	/**
	 * edit_options()
	 *
	 * @return void
	 **/
	
	static function edit_options() {
		echo '<div class="wrap">' . "\n";
		echo '<form method="post" action="" id="option_picker">' . "\n";
		
		wp_nonce_field('sem_skin');
		
		global $sem_theme_options;
		global $sem_stock_skins;

		$skins = sem_skin::get_skins();
		$fonts = sem_font::get_fonts();
		
		echo '<h2>' . __('Manage Skin', 'sem-pinnacle') . '</h2>' . "\n";
		
		echo '<h3>' . __('Current Skin &amp; Font', 'sem-pinnacle') . '</h3>' . "\n";
		
		$details = $skins[$sem_theme_options['active_skin']];
		$custom_skin = !in_array( $sem_theme_options['active_skin'], $sem_stock_skins );
		$screenshot = ($custom_skin ? sem_content_url : sem_url) . '/skins/' . $sem_theme_options['active_skin'] . '/screenshot.png';
		$title = __('%1$s v.%2$s by %3$s', 'sem-pinnacle');
		$name = $details['uri']
			? ( '<a href="' . esc_url($details['uri']) . '"'
				. ' title="' . esc_attr(__('Visit the skin\' page', 'sem-pinnacle')) . '">'
				. $details['name']
				. '</a>' )
			: $details['name'];
		$author = $details['author_uri']
			? ( '<a href="' . esc_url($details['author_uri']) . '"'
				. ' title="' . esc_attr(__('Visit the skin authors\' site', 'sem-pinnacle')) . '">'
				. $details['author_name']
				. '</a>' )
			: $details['author_name'];
		
		echo '<div id="current_option">' . "\n";
		
		echo '<img src="' . esc_url($screenshot) . '" alt="" />' . "\n";
		
		echo '<h4>' . sprintf($title, $name, $details['version'], $author) . '</h4>';
		
		if ( $details['description'] ) {
			echo wpautop(apply_filters('widget_text', $details['description']));
		}
		
		if ( $details['tags'] ) {
			echo '<p>'
				. sprintf(__('Tags: %s', 'sem-pinnacle'), implode(',', $details['tags']))
				. '</p>' . "\n";
		}

		$theme_credits = sem_template::get_theme_credits();
		$skin_credits = sem_template::get_skin_credits();
		$credits = sprintf($sem_theme_options['credits'], $theme_credits, $skin_credits['skin_name'], $skin_credits['skin_author']);
		
		if ( empty($credits) ) {
			$credits = "Left blank";
		}

		if ($credits) {
			echo '<p>'
				. sprintf(__('Credits: %s', 'sem-pinnacle'), $credits)
				. '</p>' . "\n";
		}

		$font = '<span class="' . esc_attr($sem_theme_options['active_font']) . '">'
			. $fonts[$sem_theme_options['active_font']]
			. '</span>';

		echo '<p>'
			. sprintf(__('Font Family: %s.', 'sem-pinnacle'), $font)
			. '&nbsp;&nbsp;'
			.  __( 'To select a new font, visit the Semiologic <a href="' . admin_url('admin.php?page=font') . '">font</a> page.', 'sem-pinnacle')
			. '</p>' . "\n";

		echo '<div style="clear: both;"></div>' . "\n";
		
		echo '</div>' . "\n";
		
		echo '<h3>' . __('Available Skins', 'sem-pinnacle') . '</h3>' . "\n";
		
		echo '<p class="hide-if-no-js">'
			. __('Click on a skin below to activate it immediately.', 'sem-pinnacle')
			. '</p>' . "\n";
		
		echo '<table id="available_options" cellspacing="0" cellpadding="0">' . "\n";
		
		$row_size = 6;
		$num_rows = ceil(count($skins) / $row_size);
		
		$i = 0;
		
		foreach ( $skins as $skin => $details ) {
			if ( $i && !( $i % $row_size ) )
				echo '</tr>' . "\n";
			
			if ( !( $i % $row_size ) )
				echo '<tr>' . "\n";
			
			$classes = array('available_option');
			if ( ceil(( $i + 1 ) / $row_size) == 1 )
				$classes[] = 'top';
			if ( ceil(( $i + 1 ) / $row_size) == $num_rows )
				$classes[] = 'bottom';
			if ( !( $i % $row_size ) )
				$classes[] = 'left';
			elseif ( !( ( $i + 1 ) % $row_size ) )
				$classes[] = 'right';
			
			$i++;
			
			echo '<td class="' . implode(' ', $classes) . '">' . "\n";

			$custom_skin = !in_array( $skin, $sem_stock_skins );
			$screenshot = ($custom_skin ? sem_content_url : sem_url) . '/skins/' . $skin . '/screenshot.png';
			$title = __('%1$s v.%2$s', 'sem-pinnacle');
			$name = $details['uri']
				? ( '<a href="' . esc_url($details['uri']) . '"'
					. ' title="' . esc_attr(__('Visit the skin\'s page', 'sem-pinnacle')) . '">'
					. $details['name']
					. '</a>' )
				: $details['name'];
			$author = $details['author_uri']
				? ( '<a href="' . esc_url($details['author_uri']) . '"'
					. ' title="' . esc_attr(__('Visit the skin author\'s site', 'sem-pinnacle')) . '">'
					. $details['author_name']
					. '</a>' )
				: $details['author_name'];
			
			echo '<p>'
				. '<label for="skin-' . $skin . '">'
				. '<img src="' . esc_url($screenshot) . '" alt="" width="160" height="120" />'
				. '</label>'
				. '</p>' . "\n"
				. '<h4>'
				. '<span class="hide-if-js">'
				. '<input type="radio" name="skin" value="' . $skin . '" id="skin-' . $skin . '"'
					. checked($sem_theme_options['active_skin'], $skin, false)
					. ' />' . '&nbsp;' . "\n"
				. '</span>'
				. sprintf($title, $name, $details['version']) . '<br />'
				. sprintf(__('By %s', 'sem-pinnacle'), $author)
				. '</h4>' . "\n";
			
			echo '</td>' . "\n";
		}
		
		while ( $i % $row_size ) {
			$classes = array('available_option');
			if ( ceil(( $i + 1 ) / $row_size) == 1 )
				$classes[] = 'top';
			if ( ceil(( $i + 1 ) / $row_size) == $num_rows )
				$classes[] = 'bottom';
			if ( !( $i % $row_size ) )
				$classes[] = 'left';
			elseif ( !( ( $i + 1 ) % $row_size ) )
				$classes[] = 'right';
			
			$i++;
			
			echo '<td class="' . implode(' ', $classes) . '">&nbsp;</td>' . "\n";
		}
		
		echo '</tr>' . "\n";
			
		
		echo '</table>' . "\n";
		
		echo '<p class="submit hide-if-js">'
			. '<input type="submit" value="' . esc_attr(__('Save Changes', 'sem-pinnacle')) . '" />'
			. '</p>' . "\n";

		echo '<h3>' . __('Designer Credits', 'sem-pinnacle') . '</h3>' . "\n";
		
		echo '<p>'
			. '<label for="sem_credits">'
			. '<code>'
			. htmlspecialchars(__('Made with %1$s &bull; %2$s skin by %3$s', 'sem-pinnacle'), ENT_COMPAT, get_option('blog_charset'))
			. '</code>'
            . '<br />' . "\n"
            . '<code>' . __('%1$s - Theme Name, %2$s - Skin Name, %3$s - Skin Author', 'sem-pinnacle') . '</code>'
			. '</label>'
			. '<br />' . "\n"
			. '<textarea id="sem_credits" name="credits" class="widefat" cols="50" rows="3">'
			. htmlspecialchars($sem_theme_options['credits'], ENT_COMPAT, get_option('blog_charset'))
			. '</textarea>'
			. '</p>' . "\n";
		
		echo '<div class="submit">'
			. '<input type="submit" value="' . esc_attr(__('Save Changes', 'sem-pinnacle')) . '" />'
			. '</div>' . "\n";
		
		echo '</form>' . "\n";
		echo '</div>' . "\n";
	} # edit_options()
	
	/**
	 * get_skins()
	 *
	 * @return array $skins
	 **/

	static function get_skins() {
		$skins = array();

		$skin_dirs = array( sem_path . '/skins',  sem_content_path . '/skins' );

		foreach( $skin_dirs as $skin_dir ) {
			$handle = @opendir( $skin_dir );

			if ( !$handle )
				continue;

			while ( ($skin = readdir($handle) ) !== false ) {
				if ( in_array($skin, array('.', '..')) )
					continue;

				$skin_location = $skin_dir;
				$file = $skin_location . "/$skin/skin.css";
				if ( !is_file($file) || !is_readable($file) )
					continue;

				$skins[$skin] = sem_template::get_skin_data( $skin, $skin_location );
			}
		}

		uasort($skins, array('sem_skin', 'sort'));
		
		return $skins;		
	} # get_skins()
	
	
	/**
	 * sort()
	 *
	 * @param array $a
	 * @param array $b
	 * @return int
	 **/

	static function sort($a, $b) {
		return strnatcasecmp($a['name'], $b['name']);
	} # sort()
} # sem_skin

//$sem_skin = new sem_skin();
sem_skin::get_instance();