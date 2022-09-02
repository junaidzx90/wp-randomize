<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Wp_Randomize
 * @subpackage Wp_Randomize/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Randomize
 * @subpackage Wp_Randomize/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Wp_Randomize_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-randomize-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-randomize-admin.js', array( 'jquery' ), $this->version, false );

	}

	function admin_menu_callback(){
		add_menu_page( "WP Randomize", "WP Randomize", "manage_options", "wp-randomize", [$this, "randomize_callback"], "dashicons-image-filter", 45 );
		add_submenu_page( "wp-randomize", "Add group", "Add group", "manage_options", "add-random-group", [$this, "add_new_randomize"], null );
		add_submenu_page( "wp-randomize", "shortcode", "shortcode", "manage_options", "rand-shortcode", [$this, "rand_shortcode"], null );
	}

	function randomize_callback(){
		if(isset($_GET['page']) && $_GET['page'] === 'wp-randomize' && isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])){
			echo '<h3>Edit group</h3><hr>';
			require_once plugin_dir_path( __FILE__ )."partials/add-new-wp-randomize.php";
		}else{
			$reveal = new WP_Randomize_Table();
			?>
			<div class="wrap" id="reveal-table">
				<h3 class="heading3">Random contents</h3>
				<hr>
				<form action="" method="post">
				<?php $reveal->prepare_items(); ?>
				<?php $reveal->display(); ?>
				</form>
			</div>
			<?php
		}
	}

	function add_new_randomize(){
		echo '<h3>New group</h3><hr>';
		require_once plugin_dir_path( __FILE__ )."partials/add-new-wp-randomize.php";
	}

	function rand_shortcode(){
		?>
		<h3>Shortcode</h3>
		<hr>
		<input type="text" readonly value='[randomize cat=""]'>
		<?php
	}

}
