<?php
/**
 * The dashboard-specific functionality of the spark-product-ratings plugin.
 *
 * @link 		https://github.com/anisbensaad
 * @since 		1.0.0
 *
 * @package    Spark_Product_Ratings
 * @subpackage Spark_Product_Ratings/admin
 * @author     Anis Bensaad <anis.m.bensaad@gmail.com>
 */

?>
<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

<form method="post" action="options.php">
	<?php 
		settings_fields( 'default-target' );
		do_settings_sections( $this->plugin_name );
		submit_button( 'Save Settings' );
	?>
</form>
