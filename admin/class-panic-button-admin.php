<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://hostwel.net
 * @since      1.0.0
 *
 * @package    Panic_Button
 * @subpackage Panic_Button/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Panic_Button
 * @subpackage Panic_Button/admin
 * @author     Ronnie Stamps <ronnie@hostwel.net>
 */
class Panic_Button_Admin {
	
	protected $panic_button_settings_options;
	protected $panic_button = 'panic_button'; // Plugin ID
    protected $_plugin_dir; // Plugin directory
    protected $_plugin_url; // Plugin URL
	private $version;

	
	public function __construct( $panic_button, $version ) {

		$this->panic_button = $panic_button;
		$this->version = $version;
		
		/// Hostwel
		add_action( 'admin_menu', array( $this, 'panic_button_settings_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'panic_button_settings_page_init' ) );
		$this->_plugin_dir = dirname(__FILE__);
        $this->_plugin_url = plugin_dir_url( __FILE__ ); //get_site_url(null, 'wp-content/plugins/' . basename($this->_plugin_dir));
		
		add_action( 'admin_footer', array( $this, 'media_selector_print_scripts' ) );
		
		
	}
	
	
	// Hostwel
	
	public function panic_button_settings_add_plugin_page() {
		add_options_page(
			'Panic Button Settings', // page_title
			'Panic Button Settings', // menu_title
			'manage_options', // capability
			'panic-button-settings', // menu_slug
			array( $this, 'panic_button_settings_create_admin_page' ) // function
		);
	}
	
	public function color_picker() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker-alpha', plugin_dir_url( __FILE__ ).'/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), false, true );

	}
	
	public function panic_button_settings_page_init() {
		register_setting(
			'panic_button_settings_option_group', // option_group
			'panic_button_settings_option_name', // option_name
			array( $this, 'panic_button_settings_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'panic_button_settings_setting_section', // id
			'<hr>Functionality', // title
			array( $this, 'panic_button_settings_functionality_info' ), // callback
			'panic-button-settings-admin' // page
		);

		add_settings_field(
			'escape_method', // id
			'Choose escape method. Use Keyboard as trigger<br><span class="subtext">Pressing 3 or more keys at the same time triggers the escape routine. Use Standard Panic Button as trigger. A graphical element as a button for the user to click on to trigger the escape routine.</span>', // title
			array( $this, 'escape_method_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_setting_section' // section
		);
		
		add_settings_field(
			'time_out_5', // id
              'Keyboard sensitivity <br><span class="subtext">Fine tune in milliseconds. Use this setting to reduce false triggers due to extremely fast typing. This is usually only needed if you have pages where the user needs to enter text.</span>', // title
			array( $this, 'time_out_5_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_setting_section' // section
		);

		add_settings_field(
			'display_modal_on_page_2', // id
			'Display instructions in a Modal', // title
			array( $this, 'display_modal_on_page_2_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_setting_section' // section
		);

		add_settings_field(
			'instructions_3', // id
			'Modal instructions', // title
			array( $this, 'instructions_3_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_setting_section' // section
		);

		add_settings_field(
			'address_bar_replacement_url_6', // id
			'Address bar replacement (URL)', // title
			array( $this, 'address_bar_replacement_url_6_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_setting_section' // section
		);
		
		add_settings_section(
			'panic_button_settings_style_section', // id
			'<hr>Style', // title
			array( $this, 'panic_button_settings_style_info' ), // callback
			'panic-button-settings-admin' // page
		);
		
		add_settings_field(
			'modal_image_1', // id
			'Modal Image', // title
			array( $this, 'modal_image_1_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_style_section' // section
		);
			
		add_settings_field(
			'button_position_8', // id
			'Panic Button Position', // title
			array( $this, 'button_position_8_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_style_section' // section
		);
		
		add_settings_field(
			'standard_button_text_1', // id
			'Panic Button Text', // title
			array( $this, 'standard_button_text_1_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_style_section' // section
		);
		
		add_settings_field(
			'standard_button_text_color_1', // id
			'Panic Button Text color', // title
			array( $this, 'standard_button_text_color_1_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_style_section' // section
		);
		
		add_settings_field(
			'standard_button_bg_1', // id
			'Panic Button background color', // title
			array( $this, 'standard_button_bg_1_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_style_section' // section
		);
		
		add_settings_field(
			'standard_button_bg_hover_1', // id
			'Panic Button background hover color', // title
			array( $this, 'standard_button_bg_hover_1_callback' ), // callback
			'panic-button-settings-admin', // page
			'panic_button_settings_style_section' // section
		);
	}
	
	
	public function panic_button_settings_create_admin_page() {
		$this->panic_button_settings_options = get_option( 'panic_button_settings_option_name' );
?>

		<div class="wrap">
			<img src="<?php echo $this->_plugin_url.'/img/logo-512.png'; ?>" class="plugin-thumbnail" />
			<form method="post" action="options.php" id="panic-button-form">
				<?php
					settings_fields( 'panic_button_settings_option_group' );
					do_settings_sections( 'panic-button-settings-admin' );
					wp_enqueue_media();
					submit_button();
				?>
			</form>
		</div>
	<?php 
	}
	

	
	public function panic_button_settings_functionality_info() {
		echo '<span class="subheading">Settings that will affect behavior.</span>';
	}
	
	public function panic_button_settings_style_info() {
		echo '<span class="subheading">Settings that will affect style.</span>';
	}
	
	
	public function panic_button_settings_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['escape_method'] ) ) {
			$sanitary_values['escape_method'] = $input['escape_method'];
		}

		if ( isset( $input['display_modal_on_page_2'] ) ) {
			$sanitary_values['display_modal_on_page_2'] = $input['display_modal_on_page_2'];
		}

		if ( isset( $input['instructions_3'] ) ) {
			$sanitary_values['instructions_3'] = esc_textarea( $input['instructions_3'] );
		}

		if ( isset( $input['time_out_5'] ) ) {
			$sanitary_values['time_out_5'] = sanitize_text_field( $input['time_out_5'] );
		}

		if ( isset( $input['address_bar_replacement_url_6'] ) ) {
			$sanitary_values['address_bar_replacement_url_6'] = sanitize_text_field( $input['address_bar_replacement_url_6'] );
		}
				
		if ( isset( $input['modal_image_1'] ) ) {
			$sanitary_values['modal_image_1'] = sanitize_text_field($input['modal_image_1']);
		}

		if ( isset( $input['button_position_8'] ) ) {
			$sanitary_values['button_position_8'] = $input['button_position_8'];
		}
		
		if ( isset( $input['standard_button_text_1'] ) ) {
			$sanitary_values['standard_button_text_1'] = $input['standard_button_text_1'];
		}
		
		if ( isset( $input['standard_button_text_color_1'] ) ) {
			$sanitary_values['standard_button_text_color_1'] = $input['standard_button_text_color_1'];
		}
		
		if ( isset( $input['standard_button_bg_1'] ) ) {
			$sanitary_values['standard_button_bg_1'] = $input['standard_button_bg_1'];
		}
		
		if ( isset( $input['standard_button_bg_hover_1'] ) ) {
			$sanitary_values['standard_button_bg_hover_1'] = $input['standard_button_bg_hover_1'];
		}

		return $sanitary_values;
	}
	
	public function escape_method_callback() { ?>

		<select id="escape_method" name="panic_button_settings_option_name[escape_method]">
			<?php $selected = (isset( $this->panic_button_settings_options['escape_method'] ) && $this->panic_button_settings_options['escape_method'] === 'all') ? 'selected' : '' ; ?>
			<option <?php echo $selected; ?> value="all">All Escape Methods</option>
			<?php $selected = (isset( $this->panic_button_settings_options['escape_method'] ) && $this->panic_button_settings_options['escape_method'] === 'keyboard') ? 'selected' : '' ; ?>
			<option <?php echo $selected; ?> value="keyboard">Keyboard Only</option>
			<?php $selected = (isset( $this->panic_button_settings_options['escape_method'] ) && $this->panic_button_settings_options['escape_method'] === 'button') ? 'selected' : '' ; ?>
			<option <?php echo $selected; ?> value="button">Button Only</option>
		</select>		
	
	<?php
	}

	public function display_modal_on_page_2_callback() {
		printf(
			'<input type="checkbox" name="panic_button_settings_option_name[display_modal_on_page_2]" id="display_modal_on_page_2" value="display_modal_on_page_2">',
			( isset( $this->panic_button_settings_options['display_modal_on_page_2'] ) && $this->panic_button_settings_options['display_modal_on_page_2'] === 'display_modal_on_page_2' ) ? 'checked' : ''
		);
	}

	public function instructions_3_callback() {
		printf(
			'<textarea class="regular-text" rows="5" name="panic_button_settings_option_name[instructions_3]" id="instructions_3">%s</textarea>',
			isset( $this->panic_button_settings_options['instructions_3'] ) ? esc_attr( $this->panic_button_settings_options['instructions_3']) : ''
		);
	}

	public function time_out_5_callback() {
		printf(
			'<input class="regular-text" type="range" min="0" max="2000" name="panic_button_settings_option_name[time_out_5]" id="time_out_5" value="500" oninput="range_weight_disp.value = time_out_5.value"><output  id="range_weight_disp"></output>',
			isset( $this->panic_button_settings_options['time_out_5'] ) ? esc_attr( $this->panic_button_settings_options['time_out_5']) : ''
		);
	}

	public function address_bar_replacement_url_6_callback() {
		printf(
			'<input class="regular-text" type="text" name="panic_button_settings_option_name[address_bar_replacement_url_6]" id="address_bar_replacement_url_6" value="%s">',
			isset( $this->panic_button_settings_options['address_bar_replacement_url_6'] ) ? esc_attr( $this->panic_button_settings_options['address_bar_replacement_url_6']) : ''
		);
	}
	
	public function modal_image_1_callback() {		
		printf(
		'<img src="%s" id="image-preview" name="'.$this->panic_button_settings_options["modal_image_1"].'"><br>
			<input id="upload_image_button" type="button" class="button" value="Upload Image" />',isset( $this->panic_button_settings_options['modal_image_1'] ) ? esc_attr( $this->panic_button_settings_options['modal_image_1']) : $this->_plugin_url.'/img/panic-button.gif'
			);
		echo '<input id="clear_image_button" type="button" class="button" value="Reset Image" />';
		printf(
		'<input class="regular-text" type="hidden" name="panic_button_settings_option_name[modal_image_1]" id="modal_image_1" value="%s">',
			isset( $this->panic_button_settings_options['modal_image_1'] ) ? esc_attr( $this->panic_button_settings_options['modal_image_1']) : $this->_plugin_url.'/img/panic-button.gif'
			);
	
	}

	public function media_selector_print_scripts() {

	$my_saved_attachment_post_id = get_option( 'modal_image_1', 0 );
		
		?><script type='text/javascript'>

		jQuery( document ).ready( function( $ ) {

			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $my_saved_attachment_post_id ?>; // Set this

			jQuery('#upload_image_button').on('click', function( event ){

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}

				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});

				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();

					// Do something with attachment.id and/or attachment.url here
					$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( '#modal_image_1' ).attr( 'value', attachment.url );

					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});

					// Finally, open the modal
					file_frame.open();
			});
			
			jQuery('#clear_image_button').on('click', function( event ){
				$( '#modal_image_1' ).attr( 'value', '' );
				$( '#image-preview' ).attr( 'src', '' );
			});
			
			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});

	</script><?php

}

	public function button_position_8_callback() {
		?> <select name="panic_button_settings_option_name[button_position_8]" id="button_position_8">
			<?php $selected = (isset( $this->panic_button_settings_options['button_position_8'] ) && $this->panic_button_settings_options['button_position_8'] === 'Top') ? 'selected' : '' ; ?>
			<option <?php echo $selected; ?>>Top</option>
			<?php $selected = (isset( $this->panic_button_settings_options['button_position_8'] ) && $this->panic_button_settings_options['button_position_8'] === 'Bottom') ? 'selected' : '' ; ?>
			<option <?php echo $selected; ?>>Bottom</option>
			<?php $selected = (isset( $this->panic_button_settings_options['button_position_8'] ) && $this->panic_button_settings_options['button_position_8'] === 'Left') ? 'selected' : '' ; ?>
			<option <?php echo $selected; ?>>Left</option>
			<?php $selected = (isset( $this->panic_button_settings_options['button_position_8'] ) && $this->panic_button_settings_options['button_position_8'] === 'Right') ? 'selected' : '' ; ?>
			<option <?php echo $selected; ?>>Right</option>
		</select> <?php
	}
	
	public function standard_button_text_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="panic_button_settings_option_name[standard_button_text_1]" id="standard_button_text_1" value="%s">',
			isset( $this->panic_button_settings_options['standard_button_text_1'] ) ? esc_attr( $this->panic_button_settings_options['standard_button_text_1']) : ''
		);
	}
	
	public function standard_button_text_color_1_callback() {
		printf(
			'<input type="text" class="color-picker" name="panic_button_settings_option_name[standard_button_text_color_1]" id="standard_button_text_color_1" value="%s" data-alpha="true"/>',
			isset( $this->panic_button_settings_options['standard_button_text_color_1'] ) ? esc_attr( $this->panic_button_settings_options['standard_button_text_color_1']) : ''
		);
	}
	
	public function standard_button_bg_1_callback() {
		printf(
			'<input type="text" class="color-picker" name="panic_button_settings_option_name[standard_button_bg_1]" id="standard_button_bg_1" value="%s" data-alpha="true"/>',
			isset( $this->panic_button_settings_options['standard_button_bg_1'] ) ? esc_attr( $this->panic_button_settings_options['standard_button_bg_1']) : ''
		);
	}
	
	public function standard_button_bg_hover_1_callback() {
		printf(
			'<input type="text" class="color-picker" name="panic_button_settings_option_name[standard_button_bg_hover_1]" id="standard_button_bg_hover_1" value="%s" data-alpha="true"/>',
			isset( $this->panic_button_settings_options['standard_button_bg_hover_1'] ) ? esc_attr( $this->panic_button_settings_options['standard_button_bg_hover_1']) : ''
		);
	}
	
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Panic_Button_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Panic_Button_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->panic_button, plugin_dir_url( __FILE__ ) . 'css/panic-button-admin.css', array(), $this->version, 'all' );
		
		// Hostwel
		wp_enqueue_style('thickbox');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Panic_Button_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Panic_Button_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->panic_button, plugin_dir_url( __FILE__ ) . 'js/panic-button-admin.js', array( 'jquery' ), $this->version, false );
		
		// Hostwel
			//wp_enqueue_script('jquery');    
  


	}

}
