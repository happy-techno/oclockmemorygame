<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/joyeux-yohann-36861a97/
 * @since      1.0.0
 *
 * @package    Oclockmemorygame
 * @subpackage Oclockmemorygame/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Oclockmemorygame
 * @subpackage Oclockmemorygame/public
 * @author     Yohann Joyeux <yohann.joyeux@gmail.com>
 */
class Oclockmemorygame_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Ajout de la partie CSS
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/oclockmemorygame-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Ajout de la partie JS
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//Ajout des librairies pour le jeux
		wp_enqueue_script( $this->plugin_name . '-jquery', plugin_dir_url( __FILE__ ) . 'js/jquery-3.6.0.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-oclockmemorygame', plugin_dir_url( __FILE__ ) . 'js/oclockmemorygame-public.js', array( 'jquery' ), $this->version, false );
		
		//Ajout de variable JS depuis PHP pour les WS get and set score
		wp_localize_script($this->plugin_name. '-oclockmemorygame', 'ajax_set_score', home_url().'/wp-json/omg_ws/v1/setscore' );
		wp_localize_script($this->plugin_name. '-oclockmemorygame', 'ajax_get_scores', home_url().'/wp-json/omg_ws/v1/getscores' );			

	}

	/**
	 * On déclare nos routes API pour les échanges en ajax
	 *
	 * @since    1.0.0
	 */
	public function omg_ws_register(){
		//API pour ajouter un score en base
		//.../wp-json/omg_ws/v1/setscore/
		register_rest_route( 'omg_ws/v1', '/setscore', array(
			'methods' => 'GET',
			'callback' => array($this,'omg_ws_setscore'),
		) );
		
		//API pour récupérer la liste des scores en base
		//.../wp-json/omg_ws/v1/getscores/
		register_rest_route( 'omg_ws/v1', '/getscores', array(
			'methods' => 'GET',
			'callback' => array($this,'omg_ws_getscores'),
		) );		

	}

	/**
	 * Function qui enregistre en base un nouveau score passé en paramètre ?scores=XXX
	 *
	 * @since    1.0.0
	 */	
	public function omg_ws_setscore( $data ) {

		global $wpdb;
		$table_name = $wpdb->prefix . 'scores';

		$score = $data->get_param( 'score' );

		error_log('score='.$score);

		$data = array('score' => $score);
		$format = array('%d');
		$wpdb->insert($table_name, $data, $format);

		// Create the response object
		$response = new WP_REST_Response('ok');
		
		// Add a custom status code
		$response->set_status( 200 );
	
		return $response;
	}

	/**
	 * Function qui retourne la liste des top scores dans la limite de 10, triés les plus courts en premier
	 *
	 * @since    1.0.0
	 */	
	public function omg_ws_getscores( $data ) {

		global $wpdb;
		$table_name = $wpdb->prefix . 'scores';
	
		$scores_data = $wpdb->get_results(
			$wpdb->prepare("
				SELECT score
				FROM $table_name 
				ORDER BY score ASC, date DESC
				LIMIT 10
				", ARRAY_A)
		);
		
		// Create the response object
		$response = new WP_REST_Response( $scores_data );
		
		// Add a custom status code
		$response->set_status( 200 );
	
		return $response;
	}	

	/**
	 * Déclaration du shorcode du jeu [omg]
	 *
	 * @since    1.0.0
	 */
	public function omg_register_shortcode() {
		add_shortcode( 'omg', array($this, 'omg_frontend_shortcode') );
	}
	

	/**
	 * Fonction appelée par le shortcode [omg] pour afficher le plateau de jeu dans la page wordpress
	 *
	 */
	public function omg_frontend_shortcode( $atts ) {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-oclockmemorygame-front.php';
		Oclock_Memory_Game::display_game();
	}	

}
