<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Wp_Randomize
 * @subpackage Wp_Randomize/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Randomize
 * @subpackage Wp_Randomize/includes
 * @author     Developer Junayed <admin@easeare.com>
 */
class Wp_Randomize_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$wp_randomize = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wp_randomize` (
			`ID` INT NOT NULL AUTO_INCREMENT,
			`title` VARCHAR(255) NOT NULL,
			`category` INT NOT NULL,
			`fields` LONGTEXT NOT NULL,
			`date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`ID`)) ENGINE = InnoDB";
		dbDelta($wp_randomize);
	}

}
