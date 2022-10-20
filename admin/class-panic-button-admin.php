<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://naked-spectrum.com/plugins/panic-button
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
 * @author     Ronnie Stamps <ronnie@naked-spectrum.com>
 */
class Panic_Button_Admin
{

	protected $panic_button_configuration_options;
	protected $panic_button = 'panic_button'; // Plugin ID
	protected $_plugin_dir; // Plugin directory
	protected $_plugin_url; // Plugin URL

	private $version;

	public function __construct($panic_button, $version)
	{

		$this->panic_button = $panic_button;
		$this->version = $version;

		$this->_plugin_dir = dirname(__FILE__);
		$this->_plugin_url = plugin_dir_url(__FILE__);

		add_action('admin_menu', array($this, 'panic_button_configuration_add_plugin_page'));
		add_filter('plugin_action_links_panic-button/panic-button.php', array($this, 'pb_settings_link'));
		add_action('admin_init', array($this, 'panic_button_configuration_page_init'));
		add_action('admin_footer', array($this, 'media_selector_print_scripts'));

		$defaults = array(
			'escape_method' => 'all',
			'button_position' => 'bottom',
			'precision' => 500,
			'exit_url' => 'https://www.google.com',
			'button_text' => 'EXIT!',
			'button_text_color' => '#ffffff',
			'button_bg' => '#000000',
			'button_bg_hover' => '#333333',
			'instructions' => 'User instructions.',
			'display_instructions' => 'modal',
			'modal_image' => plugin_dir_url(__FILE__) . 'img/panic-button.gif',
		);

		if (!isset($this->settings)) {
			$this->settings = add_option('panic_button_configuration_option_name', $defaults);
			$this->settings = get_option('panic_button_configuration_option_name');
		} else {
			$this->settings = get_option('panic_button_configuration_option_name'); // Array of All Options
		}
	}

	public function panic_button_configuration_add_plugin_page()
	{
		add_options_page(
			'Configuration', // page_title
			'Panic Button', // menu_title
			'manage_options', // capability
			'panic-button-configuration', // menu_slug
			array($this, 'panic_button_configuration_create_admin_page') // function
		);
	}

	function pb_settings_link($links)
	{
		// Build and escape the URL.
		$url = esc_url(add_query_arg(
			'page',
			'panic-button-configuration',
			get_admin_url() . 'options-general.php'
		));
		// Create the link.
		$settings_link = "<a href='$url'>" . __('Settings') . '</a>';
		// Adds the link to the end of the array.
		array_push(
			$links,
			$settings_link
		);
		return $links;
	}

	public function color_picker()
	{
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker-alpha', plugin_dir_url(__FILE__) . '/js/wp-color-picker-alpha.js', array('wp-color-picker'), false, true);
	}

	public function panic_button_configuration_page_init()
	{
		register_setting(
			'panic_button_configuration_option_group', // option_group
			'panic_button_configuration_option_name', // option_name
			array($this, 'panic_button_configuration_sanitize'), // sanitize_callback
		);

		add_settings_section(
			'panic_button_configuration_setting_section', // id
			'Functionality', // title
			array($this, 'panic_button_configuration_functionality_info'), // callback
			'panic-button-configuration-admin' // page
		);

		add_settings_field(
			'escape_method', // id
			'Choose escape method. Use Keyboard as trigger<br><span class="subtext">Pressing 3 or more keys at the same time triggers the escape routine. Use Standard Panic Button as trigger. A graphical element as a button for the user to click on to trigger the escape routine.</span>', // title
			array($this, 'escape_method_callback'), // callback
			'panic-button-configuration-admin', // page
			'panic_button_configuration_setting_section' // section
		);

		add_settings_field(
			'precision', // id
			'Keyboard sensitivity <br><span class="subtext">Fine tune in milliseconds. Use this setting to reduce false triggers due to extremely fast typing. This is usually only needed if you have pages where the user needs to enter text.</span>', // title
			array($this, 'precision_callback'), // callback
			'panic-button-configuration-admin', // page
			'panic_button_configuration_setting_section' // section
		);

		add_settings_field(
			'display_instructions', // id
			'Display instructions as a Browser Alert or in a Modal', // title
			array($this, 'display_instructions_callback'), // callback
			'panic-button-configuration-admin', // page
			'panic_button_configuration_setting_section' // section
		);

		add_settings_field(
			'instructions', // id
			'Instructions to users', // title
			array($this, 'instructions_callback'), // callback
			'panic-button-configuration-admin', // page
			'panic_button_configuration_setting_section' // section
		);

		add_settings_field(
			'exit_url', // id
			'Address bar replacement (URL)', // title
			array($this, 'exit_url_callback'), // callback
			'panic-button-configuration-admin', // page
			'panic_button_configuration_setting_section' // section
		);

		add_settings_section(
			'panic_button_configuration_style_section', // id
			'<hr><br>Style', // title
			array($this, 'panic_button_configuration_style_info'), // callback
			'panic-button-style-admin' // page
		);

		add_settings_field(
			'modal_image', // id
			'Modal Image', // title
			array($this, 'modal_image_callback'), // callback
			'panic-button-style-admin', // page
			'panic_button_configuration_style_section' // section
		);

		add_settings_field(
			'button_position', // id
			'Panic Button Position', // title
			array($this, 'button_position_callback'), // callback
			'panic-button-style-admin', // page
			'panic_button_configuration_style_section' // section
		);

		add_settings_field(
			'button_text', // id
			'Panic Button Text', // title
			array($this, 'button_text_callback'), // callback
			'panic-button-style-admin', // page
			'panic_button_configuration_style_section' // section
		);

		add_settings_field(
			'button_text_color', // id
			'Panic Button Text color', // title
			array($this, 'button_text_color_callback'), // callback
			'panic-button-style-admin', // page
			'panic_button_configuration_style_section' // section
		);

		add_settings_field(
			'button_bg', // id
			'Panic Button background color', // title
			array($this, 'button_bg_callback'), // callback
			'panic-button-style-admin', // page
			'panic_button_configuration_style_section' // section
		);

		add_settings_field(
			'button_bg_hover', // id
			'Panic Button background hover color', // title
			array($this, 'button_bg_hover_callback'), // callback
			'panic-button-style-admin', // page
			'panic_button_configuration_style_section' // section
		);
	}


	public function panic_button_configuration_create_admin_page()
	{
		// check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}

		//Get the active tab from the $_GET param
		$default_tab = null;
		$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

?>

		<div class="wrap">
			<div class="wrapped-column">
				<img src="<?php echo $this->_plugin_url . 'img/logo-512.png'; ?>" class="plugin-thumbnail" />
			</div>

			<div class="left-tabs">
				<nav class="nav-tab-wrapper">
					<a href="?page=panic-button-configuration" class="nav-tab <?php if ($tab === null) : ?>nav-tab-active<?php endif; ?>">Settings</a>
					<a href="?page=panic-button-configuration&tab=support" class="nav-tab <?php if ($tab === 'support') : ?>nav-tab-active<?php endif; ?>">Support</a>
					<a href="?page=panic-button-configuration&tab=about" class="nav-tab <?php if ($tab === 'about') : ?>nav-tab-active<?php endif; ?>">About</a>
				</nav>

				<div class="tab-content">
					<?php switch ($tab):
						default:
							$this->settings_tab();
							break;
						case 'support':
							$this->support_tab();
							break;
						case 'about':
							$this->about_tab();
							break;
					endswitch; ?>
				</div>
			</div>
		</div>
	<?php
	}

	private function settings_tab()
	{
	?>
		<div class="wrap">
			<div class="wrapped-column">
				<form method="post" action="options.php" id="panic-button-form">
					<?php
					settings_fields('panic_button_configuration_option_group');
					do_settings_sections('panic-button-configuration-admin');
					do_settings_sections('panic-button-style-admin');
					submit_button();
					?>
				</form>
			</div>
		</div>
	<?php
	}

	private function support_tab()
	{
	?>
		<div class="wrap">

		</div>
	<?php
	}

	private function about_tab()
	{
	?>
		<div class="wrap">
			<h1>Many Thanks!</h1>
			<p>
				Thank you for choosing Panic Button to protect the privacy for victims of domestic and sexual violence visiting your website. This plugin offers a layer of protection and annonimity for users of Domestic Violence (DV) and Sexual Violence (SV) websites.
			</p>
			<div class="naked-logo">
				<a href="https://naked-spectrum.com" target="_blank">
					<img src="<?php echo $this->_plugin_url . 'img/naked-logo.png'; ?>" />
				</a>
			</div>
		</div>
	<?php
	}

	public function panic_button_configuration_functionality_info()
	{
		echo '<span class="subheading">Settings that will affect behavior.</span>';
	}

	public function panic_button_configuration_style_info()
	{
		echo '<span class="subheading">Settings that will affect style.</span>';
	}


	public function panic_button_configuration_sanitize($input)
	{
		$sanitary_values = array();
		if (isset($input['escape_method'])) {
			$sanitary_values['escape_method'] = $input['escape_method'];
		}

		if (isset($input['display_instructions'])) {
			$sanitary_values['display_instructions'] = $input['display_instructions'];
		}

		if (isset($input['instructions'])) {
			$sanitary_values['instructions'] = esc_textarea($input['instructions']);
		}

		if (isset($input['precision'])) {
			$sanitary_values['precision'] = sanitize_text_field($input['precision']);
		}

		if (isset($input['exit_url'])) {
			$sanitary_values['exit_url'] = sanitize_text_field($input['exit_url']);
		}

		if (isset($input['modal_image'])) {
			$sanitary_values['modal_image'] = sanitize_text_field($input['modal_image']);
		}

		if (isset($input['button_position'])) {
			$sanitary_values['button_position'] = $input['button_position'];
		}

		if (isset($input['button_text'])) {
			$sanitary_values['button_text'] = $input['button_text'];
		}

		if (isset($input['button_text_color'])) {
			$sanitary_values['button_text_color'] = $input['button_text_color'];
		}

		if (isset($input['button_bg'])) {
			$sanitary_values['button_bg'] = $input['button_bg'];
		}

		if (isset($input['button_bg_hover'])) {
			$sanitary_values['button_bg_hover'] = $input['button_bg_hover'];
		}

		return $sanitary_values;
	}

	public function escape_method_callback()
	{
	?>

		<select id="escape_method" name="panic_button_configuration_option_name[escape_method]">
			<?php $selected = (isset($this->settings['escape_method']) && $this->settings['escape_method'] === 'all') ? 'selected' : ''; ?>
			<option <?php echo $selected; ?> value="all">All Escape Methods</option>
			<?php $selected = (isset($this->settings['escape_method']) && $this->settings['escape_method'] === 'keyboard') ? 'selected' : ''; ?>
			<option <?php echo $selected; ?> value="keyboard">Keyboard Only</option>
			<?php $selected = (isset($this->settings['escape_method']) && $this->settings['escape_method'] === 'button') ? 'selected' : ''; ?>
			<option <?php echo $selected; ?> value="button">Button Only</option>
		</select>

	<?php

	}

	public function display_instructions_callback()
	{
	?>

		<select id="display_instructions" name="panic_button_configuration_option_name[display_instructions]">
			<?php $selected = (isset($this->settings['display_instructions']) && $this->settings['display_instructions'] === 'alert') ? 'selected' : ''; ?>
			<option <?php echo $selected; ?> value="alert">Browser Alert</option>
			<?php $selected = (isset($this->settings['display_instructions']) && $this->settings['display_instructions'] === 'modal') ? 'selected' : ''; ?>
			<option <?php echo $selected; ?> value="modal">Modal</option>
		</select>

	<?php
	}

	public function instructions_callback()
	{
	?>

		<textarea class="regular-text" rows="5" name="panic_button_configuration_option_name[instructions]" id="instructions">Instructions to users on how to use either as a button, the keyboard, or both.</textarea>

	<?php
	}

	public function precision_callback()
	{
	?>
		<input class="regular-text" type="range" min="0" max="2000" name="panic_button_configuration_option_name[precision]" id="precision" value="<?php echo $this->settings['precision']; ?>" oninput="range_weight_disp.value = precision.value"><output id="range_weight_disp"></output>

	<?php
	}

	public function exit_url_callback()
	{
	?>
		<input class="regular-text" type="text" name="panic_button_configuration_option_name[exit_url]" id="exit_url" value="<?php echo $this->settings['exit_url']; ?>">

	<?php
	}

	public function modal_image_callback()
	{
		printf(
			'<img src="%s" id="image-preview" name="panic_button_configuration_option_name[modal_image]"><br>
			<input id="upload_image_button" type="button" class="button" value="Upload Image" />',
			isset($this->settings['modal_image']) ? esc_attr($this->settings['modal_image']) : $this->_plugin_url . '/img/panic-button.gif'
		);
		echo '<input id="clear_image_button" type="button" class="button" value="Reset Image" />';
		printf(
			'<input class="regular-text" type="hidden" name="panic_button_configuration_option_name[modal_image]" id="modal_image" value="%s">',
			isset($this->settings['modal_image']) ? esc_attr($this->settings['modal_image']) : $this->_plugin_url . '/img/panic-button.gif'
		);
	}

	public function media_selector_print_scripts()
	{

		$my_saved_attachment_post_id = get_option('modal_image', 0);
	?>
		<script type='text/javascript'>
			jQuery(document).ready(function($) {

				// Uploading files
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
				var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this

				jQuery('#upload_image_button').on('click', function(event) {

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if (file_frame) {
						// Set the post ID to what we want
						file_frame.uploader.uploader.param('post_id', set_to_post_id);
						// Open frame
						file_frame.open();
						return;
					} else {
						// Set the wp.media post id so the uploader grabs the ID we want when initialised
						wp.media.model.settings.post.id = set_to_post_id;
					}

					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
						title: 'Select an image to upload',
						button: {
							text: 'Use this image',
						},
						multiple: false // Set to true to allow multiple files to be selected
					});

					// When an image is selected, run a callback.
					file_frame.on('select', function() {
						// We set multiple to false so only get one image from the uploader
						attachment = file_frame.state().get('selection').first().toJSON();

						// Do something with attachment.id and/or attachment.url here
						$('#image-preview').attr('src', attachment.url).css('width', 'auto');
						$('#modal_image').attr('value', attachment.url);

						// Restore the main post ID
						wp.media.model.settings.post.id = wp_media_post_id;
					});

					// Finally, open the modal
					file_frame.open();
				});

				jQuery('#clear_image_button').on('click', function(event) {
					$('#modal_image').attr('value', '');
					$('#image-preview').attr('src', '');
				});

				// Restore the main ID when the add media button is pressed
				jQuery('a.add_media').on('click', function() {
					wp.media.model.settings.post.id = wp_media_post_id;
				});
			});
		</script>
	<?php
	}

	public function button_position_callback()
	{
	?> <select name="panic_button_configuration_option_name[button_position]" id="button_position">
			<?php $selected = (isset($this->settings['button_position']) && $this->settings['button_position'] === 'top') ? 'selected' : ''; ?>
			<option <?php echo $selected; ?> value="top">Top</option>
			<?php $selected = (isset($this->settings['button_position']) && $this->settings['button_position'] === 'bottom') ? 'selected' : ''; ?>
			<option <?php echo $selected; ?> value="bottom">Bottom</option>
			<?php $selected = (isset($this->settings['button_position']) && $this->settings['button_position'] === 'left') ? 'selected' : ''; ?>
			<option <?php echo $selected; ?> value="left">Left</option>
			<?php $selected = (isset($this->settings['button_position']) && $this->settings['button_position'] === 'right') ? 'selected' : ''; ?>
			<option <?php echo $selected; ?> value="right">Right</option>
		</select> <?php
				}

				public function button_text_callback()
				{
					printf(
						'<input class="regular-text" type="text" name="panic_button_configuration_option_name[button_text]" id="button_text" value="%s">',
						isset($this->settings['button_text']) ? esc_attr($this->settings['button_text']) : ''
					);
				}

				public function button_text_color_callback()
				{
					printf(
						'<input type="text" class="color-picker" name="panic_button_configuration_option_name[button_text_color]" id="button_text_color" value="%s" data-alpha="true"/>',
						isset($this->settings['button_text_color']) ? esc_attr($this->settings['button_text_color']) : ''
					);
				}

				public function button_bg_callback()
				{
					printf(
						'<input type="text" class="color-picker" name="panic_button_configuration_option_name[button_bg]" id="button_bg" value="%s" data-alpha="true"/>',
						isset($this->settings['button_bg']) ? esc_attr($this->settings['button_bg']) : ''
					);
				}

				public function button_bg_hover_callback()
				{
					printf(
						'<input type="text" class="color-picker" name="panic_button_configuration_option_name[button_bg_hover]" id="button_bg_hover" value="%s" data-alpha="true"/>',
						isset($this->settings['button_bg_hover']) ? esc_attr($this->settings['button_bg_hover']) : ''
					);
				}


				/**
				 * Register the stylesheets for the admin area.
				 *
				 * @since    1.0.0
				 */
				public function enqueue_styles()
				{

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

					wp_enqueue_style($this->panic_button, plugin_dir_url(__FILE__) . 'css/panic-button-admin.css', array(), $this->version, 'all');

					// Hostwel
					wp_enqueue_style('thickbox');
				}

				/**
				 * Register the JavaScript for the admin area.
				 *
				 * @since    1.0.0
				 */
				public function enqueue_scripts()
				{

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
					wp_enqueue_media();
					wp_enqueue_script($this->panic_button, plugin_dir_url(__FILE__) . 'js/panic-button-admin.js', array('jquery'), $this->version, false);

					// Hostwel
					//wp_enqueue_script('jquery');    



				}
			}
