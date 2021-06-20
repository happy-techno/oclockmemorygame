<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.linkedin.com/in/joyeux-yohann-36861a97/
 * @since      1.0.0
 *
 * @package    Oclockmemorygame
 * @subpackage Oclockmemorygame/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Oclockmemorygame
 * @subpackage Oclockmemorygame/includes
 * @author     Yohann Joyeux <yohann.joyeux@gmail.com>
 */
class Oclockmemorygame_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'scores';

		$scores_sql = "
			CREATE TABLE `wp_scores` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`score` int(11) NOT NULL,
				PRIMARY KEY (`id`),
				KEY `score` (`score`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;		
		";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta($scores_sql);

	}

}
