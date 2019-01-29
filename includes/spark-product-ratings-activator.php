<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Spark_Product_Ratings
 * @subpackage Spark_Product_Ratings/includes
 * @author     Anis Bensaad <anis.m.bensaad@gmail.com>
 */
class Spark_Product_Ratings_Activator {

	/**
	 * 	
	 * Declare custom post type, taxonomies, and plugin settings
	 * Flushes rewrite rules afterwards
	 *
	 * @since    1.0.0
	 */

	public static function activate($version, $plugin_name) {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/spark-product-ratings-admin.php';

		$plugin_admin = new Spark_Product_Ratings_Admin( $version, $plugin_name) ;
		if(!post_type_exists('products')){
			$plugin_admin::new_cpt_product();
			flush_rewrite_rules();
		}
		if(!taxonomy_exists('target_group')){
			$plugin_admin::new_taxonomy_type();
			flush_rewrite_rules();
		}

	} // activate()

} // class
