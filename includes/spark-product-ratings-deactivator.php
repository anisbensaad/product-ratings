<?php

/**
 * Fired during plugin deactivation
 *
 * @since      1.0.0
 * @package    Spark_Product_Ratings
 * @subpackage Spark_Product_Ratings/includes
 * @author     Anis Bensaad <anis.m.bensaad@gmail.com>
 */

class Spark_Product_Ratings_Deactivator {

	public static function deactivate() {
		/**
		 * This is optionnal but it's nice to have
		 * This method is called on plugin deactivation, so its needs to be static.
		 *
		 * @uses 	unregister_post_type() => introduced to Wordpress since 4.5.0
		 */

		$post_type = 'product';
		unregister_post_type( $post_type );
	}

}
