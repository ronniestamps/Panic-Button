<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://nkdcon.com
 * @package    Panic_Button
 * @subpackage Panic_Button/public
 * @author     Ronnie Stamps <ronnie@nkcon.com>
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $panic_button  The name of this plugin.
	 * @param      string    $version   The version of this plugin.
	 */

	
	public function __construct( $panic_button, $version ) {

		$this->panic_button = $panic_button;
		$this->version = $version;

        // Array of All Options
		$this->panic_button_configuration_options = get_option( 'panic_button_configuration_option_name' );
        
		add_action( 'wp_footer', array( $this,'panic_footer' ) );
        add_action( 'wp_footer', array( $this,'cover_tracks' ) );
        add_action( 'wp_footer', array( $this,'panic_head_tag' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->panic_button, plugin_dir_url( __FILE__ ) . 'css/panic-button-public.css', array(), $this->version, 'all' );
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
		
        $escape_method = $this->panic_button_configuration_options['escape_method'];
        $pos = $this->panic_button_configuration_options['button_position_8'];
        $time = $this->panic_button_configuration_options['time_out_5'];
        $add_url = $this->panic_button_configuration_options['address_bar_replacement_url_6'];
		$bar_bg = $this->panic_button_configuration_options['standard_button_bg_1'];
		$bar_text_color = $this->panic_button_configuration_options['standard_button_text_color_1'];
		$bar_bg_hover = $this->panic_button_configuration_options['standard_button_bg_hover_1'];

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

        if($pos == 'bottom')  
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

            #panicText {
                text-align: center;
            }

            </style>
        <?php
        }

        if($pos == 'top')  
        {
            ?>
            <style>
            #panic {
                width: 100%;
                height: 100px;
                position: fixed;
                top: 0;
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

            #panicText {
                text-align: center;
            }
            </style>
        <?php
        }

        if($pos == 'right')  
        {
            ?>
            <style>
            #panic {
                width: 100px;
                height: 100vh;
                position: fixed;
                right: 0;
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

            #panicText {
                text-align: center;
            }
            </style>
        <?php
        }

        if($pos == 'left')  
        {
            ?>
            <style>
            #panic {
                width: 100px;
                height: 100vh;
                position: fixed;
                left: 0;
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

            #panicText {
                text-align: center;
            }
            </style>
        <?php
        }    
    }

	public function panic_footer() {

        $escape_method = $this->panic_button_configuration_options['escape_method'];      
		$instructions = $this->panic_button_configuration_options['instructions_3'];        
		$modal = $this->panic_button_configuration_options['display_instructions'];        
		$image = $this->panic_button_configuration_options['modal_image_1'];			
		$bar_text = $this->panic_button_configuration_options['standard_button_text_1'];

        if(isset($escape_method) && ($escape_method == "button" || $escape_method == "all"))
        {
            echo '<div id="panic" onclick="navigate();"><div id="panicText">'.$bar_text.'</div></div>';
        } 

        if(isset($modal) && $modal == "modal" && $_SESSION["modal"] != 1)  
        {
            ?>
            <!-- <a class="popup" href="#follow-form">Click</a> Not sure why I did this. -->
            <div id="follow-form" class="white-popup-block mfp-hide" style="background: #FFF;padding: 20px 30px;text-align: left;max-width: 650px;margin: 40px auto;overflow:hidden">
                <?php echo $instructions ; ?>
                <div>
                    <img src="<?php echo $image ?>" />
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
            $_SESSION["modal"] = 1;
        }
    }
}
