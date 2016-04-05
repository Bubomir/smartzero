<?php
namespace OptArt\WoocommerceOnePageShopping\Classes\WpPages;
use OptArt\WoocommerceOnePageShopping\Classes\Services\setting_provider;

/**
 * Functionalities on admin setting page
 * Class Bsb_Admin_Settings
 */
class admin_settings extends common
{
    /**
     * @var setting_provider
     */
    private $setting_provider;

    /**
     * Run the hooks!
     */
    public function _run()
    {
        add_action( 'admin_menu', array( $this, 'admin_menu_item' ) );
        add_action( 'admin_init', array( $this, 'register_plugin_options' ) );

        $this->setting_provider = new setting_provider( $this->get_translator(), self::get_plugin_identifier() );
    }

    /**
     * Returns the path to the templates in admin setting page
     * @return string
     */
    public function get_template_path()
    {
        return 'templates/admin-settings';
    }

    /**
     * Adding the submenu to WooCommerce menu
     */
    public function admin_menu_item()
    {
        add_submenu_page(
            'woocommerce',
            $this->get_translation( 'one.page.shopping' ),
            $this->get_translation( 'one.page.shopping' ),
            'manage_options',
            self::get_plugin_identifier(),
            array( $this, 'display_admin_settings' )
        );
    }

    /**
     * Render the template with settings
     */
    public function display_admin_settings()
    {
        $this->render_template( 'admin-settings.php', array(
            'plugin_identifier' => self::get_plugin_identifier(),
            'translator' => $this->get_translator()
        ) );
    }

    /**
     * Adds the new section on the setting page
     * @param string $id
     * @param string $label
     * @return $this
     */
    private function add_section( $id, $label )
    {
        add_settings_section(
            $id,
            $label,
            function() {},
            self::get_plugin_identifier()
        );

        return $this;
    }

    /**
     * Registering the settings, sections and field for plugin settings page
     */
    public function register_plugin_options()
    {
        register_setting(
            self::get_plugin_identifier(),
            self::get_plugin_identifier(),
            array( $this, 'validate_settings' )
        );

        $product_section_id = self::get_plugin_identifier() . '_product';
        $shop_section_id = self::get_plugin_identifier() . '_shop';
        $cat_section_id = self::get_plugin_identifier() . '_cat';

        $this
            ->add_section( $product_section_id, $this->get_translation( 'product.settings' ) )
            ->add_section( $shop_section_id, $this->get_translation( 'shop.page.settings' ) )
            ->add_section( $cat_section_id, $this->get_translation( 'cat.page.settings' ) );

        $this
            ->add_settings_radio( 'plugin-scope', $product_section_id )
            ->add_settings_radio( 'display-cart', $product_section_id )
            ->add_settings_radio( 'display-checkout', $product_section_id )
            ->add_settings_radio( 'automatically-add-to-cart', $product_section_id )
            ->add_settings_radio( 'shop-page', $shop_section_id )
            ->add_settings_radio( 'shop-display-cart', $shop_section_id )
            ->add_settings_radio( 'shop-display-checkout', $shop_section_id )
            ->add_settings_radio( 'cat-plugin-scope', $cat_section_id )
            ->add_settings_radio( 'cat-display-cart', $cat_section_id )
            ->add_settings_radio( 'cat-display-checkout', $cat_section_id );
    }

    /**
     * Add radio input into the settings page
     * @param string $setting_id
     * @param string $section_id
     * @return $this
     * @throws \Exception
     */
    private function add_settings_radio( $setting_id, $section_id )
    {
        $setting = $this->setting_provider->get( $setting_id );
        $options = array();
        foreach ( $setting->get_all_values() as $value ) {

            $options[$value->get_identifier()] = $value->get_description();
        }

        add_settings_field(
            $setting->get_identifier(),
            $setting->get_label(),
            array( $this, 'render_settings_radio' ),
            self::get_plugin_identifier(),
            $section_id,
            array(
                'field_id' => $setting->get_identifier(),
                'options' => $options,
                'stored_value' => $setting->get_stored_value()
            )
        );

        return $this;
    }

    /**
     * Validate (and return store value) the user (editor) input
     * @param array $input
     * @return array
     */
    public function validate_settings( $input )
    {
        add_settings_error(
            self::get_plugin_identifier(),
            self::get_plugin_identifier(),
            $this->get_translation( 'settings.saved' ),
            'updated'
        );

        return $input;
    }

    /**
     * Rendering the radio input
     * @param array $params
     */
    public function render_settings_radio( array $params )
    {
        $this->render_template( 'radio.php', array(
            'name' => $params['field_id'],
            'plugin_identifier' => self::get_plugin_identifier(),
            'options' => $params['options'],
            'checked' => $params['stored_value'],
            'desc_tip' => 'test'
        ) );
    }
}
