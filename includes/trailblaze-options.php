<?php
/*-----------------------------------------------------------------------------------*/
/* Trailblaze Sub Menu Page
/*-----------------------------------------------------------------------------------*/

add_action( 'admin_menu', 'wap8_trailblaze_submenu_page', 10 );

/**
 * Trailblaze Sub Menu Page
 *
 * Add a sub menu page, to the Settings menu, for customizing Trailblaze options.
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function wap8_trailblaze_submenu_page() {

	add_submenu_page(
		'options-general.php',                           // parent page to add the menu link to
		__( 'Trailblaze Settings', 'trailblaze' ),       // page title
		__( 'Trailblaze', 'trailblaze' ),                // menu link title
		'manage_options',                                // restrict this page to only those who can manage options
		'wap8-trailblaze-options',                       // unique ID for this menu page
		'wap8_trailblaze_options_cb'                     // callback function to render the page HTML
	);

}

/*-----------------------------------------------------------------------------------*/
/* Trailblaze Options Callback
/*-----------------------------------------------------------------------------------*/

/**
 * Trailblaze Options Callback
 *
 * Render the HTML for the Trailblaze options page.
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function wap8_trailblaze_options_cb() {

	global $submenu;

	$page_data = array();

	foreach ( $submenu['options-general.php'] as $i => $menu_item ) {
		if ( $submenu['options-general.php'][$i][2] == 'wap8-trailblaze-options' )
			$page_data = $submenu['options-general.php'][$i];
	} ?>
<div class="wrap">
	<?php screen_icon();?>

	<h2><?php echo esc_attr( $page_data[3] ); ?></h2>

	<form id="wap8_trailblaze_options" action="options.php" method="post">
		<?php
			settings_fields( '_wap8_trailblaze_settings_group' );
			do_settings_sections( 'wap8-trailblaze-options' );
			submit_button( __( 'Save Settings', 'trailblaze' ) );
		?>
	</form>
</div>	
<?php

}

/*-----------------------------------------------------------------------------------*/
/* Trailblaze Admin Init
/*-----------------------------------------------------------------------------------*/

add_action( 'admin_init', 'wap8_trailblaze_admin_init', 10 );

/**
 * Trailblaze Admin Init
 *
 * Register the Trailblaze settings and add the option sections and fields.
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function wap8_trailblaze_admin_init() {

	// register setting
	register_setting(
		'_wap8_trailblaze_settings_group', // unique option group
		'_wap8_trailblaze_settings',       // unique option name
		'wap8_trailblaze_sanitization_cb'  // callback function to sanitize form inputs
	);

	// add display settings section
	add_settings_section(
		'trailblaze_display_settings_section',         // unique ID for this section
		__( 'Display Settings', 'trailblaze' ),        // section title
		'wap8_trailblaze_display_settings_section_cb', // callback function to render a description for this section
		'wap8-trailblaze-options'                      // page ID to render this section on
	);

	// add settings field for home page link label
	add_settings_field(
		'trailblaze_home',                               // unique ID for this field
		__( 'Home Page Link Label', 'trailblaze' ),      // field title
		'wap8_trailblaze_home_label_field_cb',           // callback function to render this form input
		'wap8-trailblaze-options',                       // page ID to render this form input
		'trailblaze_display_settings_section'            // section ID where this form input should appear
	);

	// add settings field for breadcrumbs separator
	add_settings_field(
		'trailblaze_separator',                           // unique ID for this field
		__( 'Breadcrumbs Separator', 'trailblaze' ),      // field title
		'wap8_trailblaze_separator_field_cb',             // callback function to render this form input
		'wap8-trailblaze-options',                        // page ID to render this form input
		'trailblaze_display_settings_section'             // section ID where this form input should appear
	);

}

/*-----------------------------------------------------------------------------------*/
/* Trailblaze Display Settings Section Callback
/*-----------------------------------------------------------------------------------*/

/**
 * Trailblaze Display Settings Section Callback
 *
 * Insert a simple description of the settings option and template tag usage.
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function wap8_trailblaze_display_settings_section_cb() {

?>
<p><?php _e( 'You can set how your breadcrumbs will be displayed on your website. Please remember that breadcrumbs will not displayed until you have added the <code>wap8_trailblaze()</code> to your theme.', 'trailblaze' ); ?></p>
<?php

}

/*-----------------------------------------------------------------------------------*/
/* Trailblaze Home Label Field Callback
/*-----------------------------------------------------------------------------------*/

/**
 * Trailblaze Home Label Field Callback
 *
 * The callback function to render the Home Label input field.
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function wap8_trailblaze_home_label_field_cb() {

	$options = wp_parse_args(
		get_option(
			'_wap8_trailblaze_settings' ),
			array(
				'trailblaze_home' => '',
			)
		);

	echo "<input type='text' id='trailblaze_home' name='_wap8_trailblaze_settings[trailblaze_home]' class='regular-text' value='{$options['trailblaze_home']}' />";
	echo "<p class='description'>" . __( 'If left blank, the label will default to <strong>Home</strong>. No HTML allowed.', 'trailblaze' ) . "</p>";

}

/*-----------------------------------------------------------------------------------*/
/* Get Trailblaze Separator Items
/*-----------------------------------------------------------------------------------*/

/**
 * Get Trailblaze Separator Items
 *
 * A helper function to get the list of breadcrumbs separator items.
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function wap8_get_trailblaze_separator_items() {

	$items = array(
		'double-right-angled-quote' => array(
			'label'  => __( 'Double Right Angled Quote', 'trailblaze' ),
			'entity' => '&#187;',
		),
		'single-right-angled-quote' => array(
			'label'  => __( 'Single Right Angled Quote', 'trailblaze' ),
			'entity' => '&#8250;',
		),
		'right-arrow' => array(
			'label'  => __( 'Right Arrow', 'trailblaze' ),
			'entity' => '&#8594;',
		),
		'large-list-dot' => array(
			'label'  => __( 'Large List Dot', 'trailblaze' ),
			'entity' => '&#149;',
		),
		'medium-list-dot' => array(
			'label'  => __( 'Medium List Dot', 'trailblaze' ),
			'entity' => '&#183;',
		),
		'vertical-bar' => array(
			'label'  => __( 'Vertical Bar', 'trailblaze' ),
			'entity' => '&#124;',
		),
		'broken-vertical-bar' => array(
			'label'  => __( 'Broken Vertical Bar', 'trailblaze' ),
			'entity' => '&#166;',
		),
		'forward-slash' => array(
			'label'  => __( 'Forward Slash', 'trailblaze' ),
			'entity' => '&#047;',
		),
	);

	return $items;

}

/*-----------------------------------------------------------------------------------*/
/* Convert Trailblaze Separator Items
/*-----------------------------------------------------------------------------------*/

/**
 * Convert Trailblaze Separator Items
 *
 * A helper function to convert the list of breadcrumbs separator items entity codes
 * for display.
 *
 * @param $key
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function wap8_convert_trailblaze_separator_items( $key ) {

	$items = wap8_get_trailblaze_separator_items();
	return ( isset( $items[ $key ] ) ) ? $items[ $key ]['entity'] : '';

}

/*-----------------------------------------------------------------------------------*/
/* Trailblaze Separator Field Callback
/*-----------------------------------------------------------------------------------*/

/**
 * Trailblaze Separator Field Callback
 *
 * The callback function to render the Breadcrumbs Separator radio buttons.
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function wap8_trailblaze_separator_field_cb() {

	$options = wp_parse_args(
		get_option(
			'_wap8_trailblaze_settings' ),
			array(
				'trailblaze_separator' => '',
			)
		);

	$items = wap8_get_trailblaze_separator_items();

	foreach ( $items as $key => $value ) {
		$checked = $options['trailblaze_separator'];
		echo "<label><input name='_wap8_trailblaze_settings[trailblaze_separator]' value='" . $key . "' " . checked( $checked, $key, false ) . " type='radio' /> <span>" . $value['label'] . "</span></label><br />";
	}

}

/*-----------------------------------------------------------------------------------*/
/* Trailblaze Sanitization Callback
/*-----------------------------------------------------------------------------------*/

/**
 * Trailblaze Sanitization Callback
 *
 * Sanitize field inputs before saving to the database.
 *
 * @param $input
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Heavy Heavy <@heavyheavyco>
 *
 */

function wap8_trailblaze_sanitization_cb( $input ) {

	$input['trailblaze_home'] = wp_strip_all_tags( $input['trailblaze_home'] ); // home page label

	// ensure 'trailblaze_separator' always exists after the first save.
	$input['trailblaze_separator'] = ( empty( $input['trailblaze_separator'] ) ) ? '' : $input['trailblaze_separator'];

	return $input;

}