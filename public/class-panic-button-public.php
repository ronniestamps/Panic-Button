<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Panic_Button
 * @subpackage Panic_Button/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Panic_Button
 * @subpackage Panic_Button/public
 * @author     Ronnie Stamps <ronnie@hostwel.net>
 */
class Panic_Button_Public {
	
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $panic_button    The ID of this plugin.
	 */
	private $panic_button;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
	 * Panic Button head settings.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * 
	 */
    protected $panic_button_settings_options;
    protected $escape_method;
    protected $pos;
    protected $time;
    protected $add_url;
    protected $bar_text_color;
    protected $bar_bg_hover;

    
    /**
	 * Panic Button footer settings.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * 
	 */
    protected $instructions;
    protected $modal;
    protected $image;
    protected $bar_bg;
    protected $bar_text;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $panic_button  The name of this plugin.
	 * @param      string    $version   The version of this plugin.
	 */

	
	public function __construct( $panic_button, $version ) {

		$this->panic_button = $panic_button;
		$this->version = $version;
		
		// Hostwel
		$this->panic_button_settings_options = get_option( 'panic_button_settings_option_name' );
        add_action( 'wp_head', array($this,'panic_head_tag'));
		add_action( 'wp_footer', array($this,'panic_footer'));
        add_action('wp_footer', array($this,'cover_tracks'));

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->panic_button, plugin_dir_url( __FILE__ ) . 'css/panic-button-public.css', array(), $this->version, 'all' );
		
		// Hostwel
		wp_enqueue_style( 'panic_popup_css', plugin_dir_url( __FILE__ ).'/css/magnific-popup.css' );
        wp_enqueue_style( $this->panic_button, plugin_dir_url( __FILE__ ) . 'panic_popup_css' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->panic_button, plugin_dir_url( __FILE__ ) . 'js/panic-button-public.js', array( 'jquery' ), $this->version, false );
		
		// Hostwel
		wp_enqueue_script( 'panic_popup', plugin_dir_url( __FILE__ ) . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.0', false,null );
        wp_enqueue_script( $this->panic_button, plugin_dir_url( __FILE__ ) . '/js/jquery.magnific-popup.min.js');

	}
	

    /**
	 * Converts all links to use Javascript replace() so that links 
     * are not added to browser history.
	 *
	 * @since    1.0.0
	 */
    public function cover_tracks() {
    ?>
        <script>
            window.onload = function() {
                let anchors = document.getElementsByTagName("a");
                    for (var i = 0; i < anchors.length; i++) {
                        anchors[i].setAttribute("data-link", anchors[i].href);
                        anchors[i].setAttribute("href", "javascript:void(0);");
                        let link = anchors[i].getAttribute("data-link");
                        anchors[i].setAttribute("onclick", 'window.location.replace("'+link+'");');
                    }
        };
        </script>
    <?php
    }

	public function panic_head_tag() {
		
        $escape_method = $this->panic_button_settings_options['escape_method'];
        $pos = $this->panic_button_settings_options['button_position_8'];
        $time = $this->panic_button_settings_options['time_out_5'];
        $add_url = $this->panic_button_settings_options['address_bar_replacement_url_6'];
		$bar_bg = $this->panic_button_settings_options['standard_button_bg_1'];
		$bar_text = $this->panic_button_settings_options['standard_button_text_1'];
		$bar_text_color = $this->panic_button_settings_options['standard_button_text_color_1'];
		$bar_bg_hover = $this->panic_button_settings_options['standard_button_bg_hover_1'];
		
        if (strpos($add_url,'http://') === false){
            $add_url = 'http://'.$add_url;
        }

        ?>
            <script>
            function navigate(){
                    window.location.replace('<?php print $add_url ?>'); /* OPTION TO ADD URL - "Address bar replacement" */
                return false;
            }
            </script>
        <?php
        if($escape_method == "keyboard" || $escape_method == "all")
        {
            ?>
            <script type="text/javascript">
              let a = [];
              document.body.onkeydown = function(event){
                    setTimeout(clearArray, <?php print $time; ?>); /* OPTION TO CHANGE TIMEOUT - "Fine tune reaction time" */
                    let keyCode = ('which' in event) ? event.which : event.keyCode;
                    if (a.indexOf(keyCode) == -1) a.push(keyCode);
                    if (a.length <= 2) return;
                
                    function clearArray() {
                        a.length = 0;
                    }
                    
                    if (a.length >= 3) {
						a.length = 0;
						navigate();
					}
                return false;
              };  
            </script>
            <?php
        }

        if($pos == 'Bottom')  
        {
            ?>
            <style>
            #panic {
                width: 100%;
                height: 100px;
                position: fixed;
                bottom: 0;
                z-index: 9999;
                background: <?php echo $bar_bg; ?>;
                color: <?php echo $bar_text_color; ?>;
                font-size: 4rem;
                line-height: normal;
                padding: 5px 0 0 20px;
            }

            #panic:hover {
				background: <?php echo $bar_bg_hover; ?>;
                cursor: cell;
            }

            #panicLeft {
                float: left;
            }
            #panicRight {
                float: right;
            }
            </style>
        <?php
        }

        if($pos == 'Top')  
        {
            ?>
            <style>
            #panic {
                width: 100%;
                height: 100px;
                position: fixed;
                bottom: 0;
                z-index: 9999;
                background: <?php echo $bar_bg; ?>;
                color: <?php echo $bar_text_color; ?>;
                font-size: 4rem;
                line-height: normal;
                padding: 5px 0 0 20px;
            }

            #panic:hover {
                background: <?php echo $bar_bg_hover; ?>;
                cursor: cell;
            }

            #panicLeft {
                float: left;
            }
            #panicRight {
                float: right;
            }
            </style>
        <?php
        }

        if($pos == 'Right')  
        {
            ?>
            <style>
            #panic {
                width: 100%;
                height: 100px;
                position: fixed;
                bottom: 0;
                z-index: 9999;
                background: <?php echo $bar_bg; ?>;
                color: <?php echo $bar_text_color; ?>;
                font-size: 4rem;
                line-height: normal;
                padding: 5px 0 0 20px;
            }

            #panic:hover {
                background: <?php echo $bar_bg_hover; ?>;
                cursor: cell;
            }

            #panicLeft {
                float: left;
            }
            #panicRight {
                float: right;
            }
            </style>
        <?php
        }

        if($pos == 'Left')  
        {
            ?>
            <style>
            #panic {
                width: 100%;
                height: 100px;
                position: fixed;
                bottom: 0;
                z-index: 9999;
                background: <?php echo $bar_bg; ?>;
                color: <?php echo $bar_text_color; ?>;
                font-size: 4rem;
                line-height: normal;
                padding: 5px 0 0 20px;
            }

            #panic:hover {
                background: <?php echo $bar_bg_hover; ?>;
                cursor: cell;
            }

            #panicLeft {
                float: left;
            }
            #panicRight {
                float: right;
            }
            </style>
        <?php
        }    
    }

	public function panic_footer() {

        $escape_method = $this->panic_button_settings_options['escape_method'];      
		$instructions = $this->panic_button_settings_options['instructions_3'];        
		$modal = $this->panic_button_settings_options['display_modal_on_page_2'];        
		$image = $this->panic_button_settings_options['modal_image_1'] != '' ? $this->panic_button_settings_options['modal_image_1'] : plugin_dir_url( __FILE__ ).'img/panic-button.gif';		
		$bar_bg = $this->panic_button_settings_options['standard_button_bg_1'];		
		$bar_text = $this->panic_button_settings_options['standard_button_text_1'];
		$bar_text_color = $this->panic_button_settings_options['standard_button_text_color_1'];
		$bar_bg_hover = $this->panic_button_settings_options['standard_button_bg_hover_1'];

        if(isset($escape_method) && ($escape_method == "button" || $escape_method == "all"))
        {
            echo '<div id="panic" onclick="navigate();"><div id="panicLeft">'.$bar_text.'</div><div id="panicRight">'.$bar_text.'</div></div>';
        } 

        if(isset($modal))  
        {  
            ?>
            <a class="popup" href="#follow-form">Click</a>
            <div id="follow-form" class="white-popup-block mfp-hide" style="background: #FFF;padding: 20px 30px;text-align: left;max-width: 650px;margin: 40px auto;overflow:hidden">
                <?php print $instructions ; ?>
                <div>
                    <img src="<?php print $image ?>" />
                </div>
                <div>
                    <button id="ack">Got It!</button>
                </div>
            </div>
            <script>
            jQuery(document).ready(function () {
            setTimeout(function() {
             if (jQuery('#follow-form').length) {
               jQuery.magnificPopup.open({
                items: {
                    src: '#follow-form' 
                },
                type: 'inline',
                closeOnBgClick: false,
                closeMarkup: '<button title="%title%" class="mfp-close" style="display:none">Close</button>', 
                  });
               }
             }, 1000);
            });

            jQuery('#ack').click(function(){
                //This will close popup dialog opened using $.magnificPopup.open()
                jQuery.magnificPopup.close();
            });
            </script>
            <?php
        }
    }
}
