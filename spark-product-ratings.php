<?php

 /**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/anisbensaad
 * @since             1.0.0
 * @package           spark-product-ratings
 *
 * @wordpress-plugin
 * Plugin Name:       Spark Product ratings
 * Plugin URI:        https://github.com/anisbensaad
 * Description:       This is a Wordpress code challenge for the Spark company.
 * Version:           1.0.0
 * Author:            Anis Bensaad
 * Author URI:        https://github.com/anisbensaad
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spark-product-ratings
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'SPARK_PRODUCT_RATINGS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/spark-product-ratings-activator.php
 */
function activate_spark_product_ratings() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/spark-product-ratings-activator.php';
	Spark_Product_Ratings_Activator::activate(SPARK_PRODUCT_RATINGS_VERSION,'spark_product_ratings');
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_spark_product_ratings() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/spark-product-ratings-deactivator.php';
	Spark_Product_Ratings_Deactivator::deactivate();
}

//Using Wordress hooks ( register_activation_hook & register_deactivation_hook ) 
//register_activation_hook( __FILE__, 'activate_spark_product_ratings' );
register_deactivation_hook( __FILE__, 'deactivate_spark_product_ratings' );
add_action( 'init', 'activate_spark_product_ratings' );
/*
 * Warning!: 
 *
 *  its better to use the hook register_activation_hook when we want to create a custom post type because it will be fired only once.
 *	In this case there is a problem with this hook and the custom post type is not created properly, so i choosed this solution, in a real situation i will communicate the  *  issue with the team and maybe i would spend much more time to resolve this problem.
 *   
 *
 */

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-spark-product-ratings.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 		1.0.0
 */
function run_Spark_Product_Ratings() {
	$plugin = new Spark_Product_Ratings();
	$plugin->run();
}
run_Spark_Product_Ratings();