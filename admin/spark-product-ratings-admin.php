<?php
/**
 * The dashboard-specific functionality of the spark-product-ratings plugin.
 *
 * @link 		https://github.com/anisbensaad
 * @since 		1.0.0
 *
 * @package    Spark_Product_Ratings
 * @subpackage Spark_Product_Ratings/includes
 * @author     Anis Bensaad <anis.m.bensaad@gmail.com>
 */

class Spark_Product_Ratings_Admin{

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
		add_action('add_meta_boxes_product',array($this,'create_metaboxes'));
		add_action('save_post_product',array($this,'save_rating'));

	}

	/**
	 * Creates a new custom post type
	 * This method is called on plugin activation, so its needs to be static.
	 *
	 * @uses 	register_post_type()
	 */
	public static function new_cpt_product() {
		$cap_type 	= 'post';
		$plural 	= 'Products';
		$single 	= 'Product';
		$cpt_name 	= 'product';
		$opts['can_export']								= TRUE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= '';
		$opts['exclude_from_search']					= FALSE;
		$opts['has_archive']							= FALSE;
		$opts['hierarchical']							= FALSE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-businessman';
		$opts['menu_position']							= 25;
		$opts['public']									= TRUE;
		$opts['publicly_querable']						= TRUE;
		$opts['query_var']								= TRUE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array( 'title', 'thumbnail' );
		$opts['taxonomies']								= array();
		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";
		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'spark-product-ratings' );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'spark-product-ratings' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'spark-product-ratings' );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'spark-product-ratings' );
		$opts['labels']['menu_name']					= esc_html__( $plural, 'spark-product-ratings' );
		$opts['labels']['name']							= esc_html__( $plural, 'spark-product-ratings' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'spark-product-ratings' );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", 'spark-product-ratings' );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", 'spark-product-ratings' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'spark-product-ratings' );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent {$plural} :", 'spark-product-ratings' );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", 'spark-product-ratings' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'spark-product-ratings' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'spark-product-ratings' );
		$opts['rewrite']['ep_mask']						= EP_PERMALINK;
		$opts['rewrite']['feeds']						= FALSE;
		$opts['rewrite']['pages']						= TRUE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $plural ), 'spark-product-ratings' );
		$opts['rewrite']['with_front']					= FALSE;
		$opts = apply_filters( 'spark-product-ratings-cpt-options', $opts );
		register_post_type( strtolower( $cpt_name ), $opts );
	} // new_cpt_product()


	
	public function create_metaboxes(){
		//we use add_metabox() to create the field rating for the custom post type products
		add_meta_box('rating', 'Rating', array( $this, 'display_rating' ), 'product');
	}

	public function display_rating($post){
		// we fetch the rating value with get_post_meta
	  $rating = get_post_meta($post->ID,'rating',true);
	  wp_nonce_field( '_rating_nonce', 'rating_nonce' ); ?>

		<p>
			<label for="rating"><?php _e( 'Rate this product', 'spark-product-ratings' ); ?></label><br>
			<select name="rating" id="rating">
				<option <?php echo ($rating === '0' ) ? 'selected' : '' ?>>0</option>
				<option <?php echo ($rating === '1' ) ? 'selected' : '' ?>>1</option>
				<option <?php echo ($rating === '2' ) ? 'selected' : '' ?>>2</option>
				<option <?php echo ($rating === '3' ) ? 'selected' : '' ?>>3</option>
				<option <?php echo ($rating === '4' ) ? 'selected' : '' ?>>4</option>
				<option <?php echo ($rating === '5' ) ? 'selected' : '' ?>>5</option>
			</select>
		</p><?php
	}

	public function save_rating($post_id ){
	  //Saving our meta box rating
	  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	  if ( ! isset( $_POST['rating_nonce'] ) || ! wp_verify_nonce( $_POST['rating_nonce'], '_rating_nonce' ) ) return;
	  if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	  if ( isset( $_POST['rating'] ) )
		update_post_meta( $post_id, 'rating', esc_attr( $_POST['rating'] ) );
	}


	/**
	 * Creates a new taxonomy for a custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public static function new_taxonomy_type() {
		$plural 	= 'Target groups';
		$single 	= 'Target group';
		$tax_name 	= 'target_group';
		$opts['hierarchical']							= TRUE;
		//$opts['meta_box_cb'] 							= '';
		$opts['public']									= TRUE;
		$opts['query_var']								= $tax_name;
		$opts['show_admin_column'] 						= FALSE;
		$opts['show_in_nav_menus']						= TRUE;
		$opts['show_tag_cloud'] 						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['sort'] 									= '';
		//$opts['update_count_callback'] 					= '';
		$opts['capabilities']['assign_terms'] 			= 'edit_posts';
		$opts['capabilities']['delete_terms'] 			= 'manage_categories';
		$opts['capabilities']['edit_terms'] 			= 'manage_categories';
		$opts['capabilities']['manage_terms'] 			= 'manage_categories';
		$opts['labels']['add_new_item'] 				= esc_html__( "Add New {$single}", 'spark-product-ratings' );
		$opts['labels']['add_or_remove_items'] 			= esc_html__( "Add or remove {$plural}", 'spark-product-ratings' );
		$opts['labels']['all_items'] 					= esc_html__( $plural, 'spark-product-ratings' );
		$opts['labels']['choose_from_most_used'] 		= esc_html__( "Choose from most used {$plural}", 'spark-product-ratings' );
		$opts['labels']['edit_item'] 					= esc_html__( "Edit {$single}" , 'spark-product-ratings');
		$opts['labels']['menu_name'] 					= esc_html__( $plural, 'spark-product-ratings' );
		$opts['labels']['name'] 						= esc_html__( $plural, 'spark-product-ratings' );
		$opts['labels']['new_item_name'] 				= esc_html__( "New {$single} Name", 'spark-product-ratings' );
		$opts['labels']['not_found'] 					= esc_html__( "No {$plural} Found", 'spark-product-ratings' );
		$opts['labels']['parent_item'] 					= esc_html__( "Parent {$single}", 'spark-product-ratings' );
		$opts['labels']['parent_item_colon'] 			= esc_html__( "Parent {$single}:", 'spark-product-ratings' );
		$opts['labels']['popular_items'] 				= esc_html__( "Popular {$plural}", 'spark-product-ratings' );
		$opts['labels']['search_items'] 				= esc_html__( "Search {$plural}", 'spark-product-ratings' );
		$opts['labels']['separate_items_with_commas'] 	= esc_html__( "Separate {$plural} with commas", 'spark-product-ratings' );
		$opts['labels']['singular_name'] 				= esc_html__( $single, 'spark-product-ratings' );
		$opts['labels']['update_item'] 					= esc_html__( "Update {$single}", 'spark-product-ratings' );
		$opts['labels']['view_item'] 					= esc_html__( "View {$single}", 'spark-product-ratings' );
		$opts['rewrite']['ep_mask']						= EP_NONE;
		$opts['rewrite']['hierarchical']				= FALSE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $tax_name ), 'spark-product-ratings' );
		$opts['rewrite']['with_front']					= FALSE;
		$opts = apply_filters( 'spark-product-ratings-taxonomy-options', $opts );
		register_taxonomy( $tax_name, 'product', $opts );
	} // new_taxonomy_type()

}