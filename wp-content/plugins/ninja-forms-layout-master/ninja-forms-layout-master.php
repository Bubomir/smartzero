<?php
/*
Plugin Name: Ninja Forms - Layout Master (shared on wplocker.com)
Plugin URI: http://codecanyon.net/item/ninja-forms-layout-master/9372347
Description: Multicolumn layout and form style add-on for Ninja Forms.
Author: Big Tree Island
Author URI: http://bigtreeisland.com
Version: 1.7.0
*/

if ( ! defined( 'ABSPATH' ) ) exit;

include_once "includes/admin-layout.php";
include_once "includes/admin-styles.php";
include_once "includes/frontend-layout.php";
include_once "includes/updates.php";

class Big_Tree_Island_NF_Layout_Master {

	function __construct() {

		// Global Definitions
		define( 'BTI_LAYOUT_MASTER_NINJA_FORMS', 'ninja-forms/ninja-forms.php' );
		define( 'BTI_LAYOUT_MASTER_PLUGIN_URL',  plugins_url( '', __FILE__ ) );

		// Deactivate plugin if Ninja Forms plugin is not present/activated
		add_action( 'admin_init', array( $this, 'activation_check' ) );

		// Initialize plugin classes
		if ( $this->ninja_forms_check() ) {
			$admin_layout    = new Bti_Layout_Master_Admin_Layout();
			$admin_styles    = new Bti_Layout_Master_Admin_Styles();
			$frontend_layout = new Bti_Layout_Master_Frontend_Layout();

			// Plugin updates handler
			$tmp = get_option( 'bti_layout_master_update' );
			if ( ! isset( $tmp ) || 0 != $tmp ) {
				$updates = new Bti_License_Tree(
					plugin_basename( __FILE__ ),
					'ninja-forms-layout-master',
					'1.7.0'
				);
			}

			// Equeue scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		}
	}

	/**
	 * Method to register and equeue JS files for wp-admin pages.
	 *
	 * @since 1.0.0
	 */
	function enqueue_scripts( $hook ) {

		// Register gridster.js
		wp_register_script(
			'bigtreeisland_lm_gridster_js',
			BTI_LAYOUT_MASTER_PLUGIN_URL . '/js/jquery.gridster.min.js',
			array(
				'jquery'
			)
		);

		// Register layout-master.js (plugin specific JS)
		wp_register_script(
			'bigtreeisland_lm_layout_master_js',
			BTI_LAYOUT_MASTER_PLUGIN_URL . '/js/layout-master.js',
			array(
					'jquery',
					'wp-color-picker',
					'bigtreeisland_lm_gridster_js'
			)
		);

		 // Load javascript only under right admin page
		if ( 'toplevel_page_ninja-forms' == $hook ) {
			wp_enqueue_script( 'bigtreeisland_lm_layout_master_js' );
		}
	}

	/**
	 * Method to register and equeue CSS files for wp-admin pages.
	 * @since 1.0.0
	 */
	function enqueue_styles( $hook ) {

		// Register gridster.js stylesheet
		wp_register_style(
			'bigtreeisland_lm_gridster_css',
			BTI_LAYOUT_MASTER_PLUGIN_URL . '/css/jquery.gridster.min.css',
			false,
			false
		);

		// Register plugin specific styleseet
		wp_register_style(
			'bigtreeisland_lm_layout_master_css',
			BTI_LAYOUT_MASTER_PLUGIN_URL . '/css/layout-master.css',
			array(
				'wp-color-picker',
				'bigtreeisland_lm_gridster_css'
			)
		);

		// Load CSS only under right admin page
		if ( 'toplevel_page_ninja-forms' == $hook ) {
			wp_enqueue_style( 'bigtreeisland_lm_layout_master_css' );
		}
	}

	/**
	 * Deactivate Ninja Forms - Layout Master if Ninja Forms is not active
	 *
	 * @since 1.0.0
	 */
	function activation_check() {
		if ( ! $this->ninja_forms_check() ) {
			if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
				deactivate_plugins( plugin_basename( __FILE__ ) );
				add_action( 'admin_notices', array( $this, 'disabled_notice' ) );
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			}
		}
	}

	/**
	 * Method that checks if plugin could be initiated.
	 *
	 * @since 1.0.0
	 */
	function ninja_forms_check() {
		$active_plugins = get_option( 'active_plugins' );
		foreach ( $active_plugins as $plugin ) {
			if ( BTI_LAYOUT_MASTER_NINJA_FORMS == $plugin ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Disabled notice. Will be shown when Ninja Forms is not present
	 * or not activated.
	 *
	 * @since 1.0.0
	 */
	function disabled_notice() {
		?>
			<div class="error">
				<p>
					<?php echo esc_html__( 'Ninja Forms - Layout Master requires Ninja Forms to be present and activated!', 'bti_layout_master' ); ?>
				</p>
			</div>
		<?php
	}

}

$big_tree_island_nf_layout_master = new Big_Tree_Island_NF_Layout_Master();
