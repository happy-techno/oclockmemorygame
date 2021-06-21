<?php

/**
 * The file that defines the core plugin class
 *
 * @link       https://www.linkedin.com/in/joyeux-yohann-36861a97/
 * @since      1.0.0
 *
 * @package    Oclockmemorygame
 * @subpackage Oclockmemorygame/includes
 */

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Oclockmemorygame
 * @subpackage Oclockmemorygame/includes
 * @author     Yohann Joyeux <yohann.joyeux@gmail.com>
 */
class Oclockmemorygame {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Oclockmemorygame_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'OCLOCKMEMORYGAME_VERSION' ) ) {
			$this->version = OCLOCKMEMORYGAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'oclockmemorygame';

		$this->load_dependencies();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Oclockmemorygame_Loader. Orchestrates the hooks of the plugin.
	 * - Oclockmemorygame_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-oclockmemorygame-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-oclockmemorygame-public.php';

		$this->loader = new Oclockmemorygame_Loader();

	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Oclockmemorygame_Public( $this->get_plugin_name(), $this->get_version() );

		//Déclaration css/js (statics ressources)
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		//Déclaration du shortcode (frontend)
		$this->loader->add_action( 'init', $plugin_public, 'omg_register_shortcode' );

		//Déclaration des API Wordpress pour la partie backend du jeu
		$this->loader->add_action( 'rest_api_init', $plugin_public, 'omg_ws_register' );		

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Oclockmemorygame_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
