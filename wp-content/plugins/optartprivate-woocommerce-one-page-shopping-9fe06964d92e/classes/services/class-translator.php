<?php
namespace OptArt\WoocommerceOnePageShopping\Classes\Services;

/**
 * Class separates the translation used in the extension from code layer
 */
class translator
{
    /**
     * Contains key => value pairs of translation index and test
     *
     * @var array
     */
    private $translations = null;

    /**
     * Constructor sets the translations on place
     */
    public function __construct()
    {
        // admin settings page
        $this->add( 'one.page.shopping', __( 'One page shopping', 'woocommerce-one-page-shopping' ) );
        $this->add( 'ops.settings', __( 'One page shopping settings', 'woocommerce-one-page-shopping' ) );
        $this->add( 'save.changes', __( 'Save changes', 'woocommerce-one-page-shopping' ) );
        $this->add( 'product.settings', __( 'Product settings', 'woocommerce-one-page-shopping' ) );
        $this->add( 'shop.page.settings', __( 'Shop page settings', 'woocommerce-one-page-shopping' ) );
        $this->add( 'cat.page.settings', __( 'Category settings', 'woocommerce-one-page-shopping' ) );
        $this->add( 'settings.saved', __( 'One page shopping settings saved', 'woocommerce-one-page-shopping' ) );

        // setting_provider service
        $this->add( 'plugin.scope', __( 'Plugin scope', 'woocommerce-one-page-shopping' ) );
        $this->add( 'enabled.for.all', __( 'Enable for all products', 'woocommerce-one-page-shopping' ) );
        $this->add( 'enabled.for.all.cat', __( 'Enable for all categories', 'woocommerce-one-page-shopping' ) );
        $this->add( 'disabled.for.all', __( 'Disable for all products', 'woocommerce-one-page-shopping' ) );
        $this->add( 'disabled.for.all.cat', __( 'Disable for all categories', 'woocommerce-one-page-shopping' ) );
        $this->add( 'fixed.products', __( 'Fixed products', 'woocommerce-one-page-shopping' ) );
        $this->add( 'fixed.categories', __( 'Fixed categories', 'woocommerce-one-page-shopping' ) );
        $this->add( 'display.cart', __( 'Display cart', 'woocommerce-one-page-shopping' ) );
        $this->add( 'display.checkout', __( 'Display checkout', 'woocommerce-one-page-shopping' ) );
        $this->add( 'automatic.add-to-cart', __( 'Automatically add to cart on visit (simple products only)', 'woocommerce-one-page-shopping' ) );
        $this->add( 'yes', __( 'Yes', 'woocommerce-one-page-shopping' ) );
        $this->add( 'no', __( 'No', 'woocommerce-one-page-shopping' ) );

        // product_settings page
        $this->add( 'enabled', __( 'Enabled', 'woocommerce-one-page-shopping' ) );
        $this->add( 'disabled', __( 'Disabled', 'woocommerce-one-page-shopping' ) );
        $this->add( 'enable.for.product', __( 'Enable for this product', 'woocommerce-one-page-shopping' ) );
        $this->add( 'settings.unavailable', __( 'OPS settings are not available for this product. Go to "WooCommerce" > "One page shopping" tab to see possibilities.', 'woocommerce-one-page-shopping' ) );

        // category_settings page
        $this->add( 'cat.cart.desc', __( 'Use this setting to handle displaying the cart for One Page Shopping plugin.', 'woocommerce-one-page-shopping' ) );
        $this->add( 'cat.checkout.desc', __( 'Use this setting to handle displaying the checkout for One Page Shopping plugin.', 'woocommerce-one-page-shopping' ) );
        $this->add( 'ops', __( 'One Page Shopping (OPS)', 'woocommerce-one-page-shopping' ) );
        $this->add( 'ops.cat.desc', __( 'Enable or disable One Page Shopping plugin for this category page.', 'woocommerce-one-page-shopping' ) );
    }

    /**
     * Method adds the translation entry into an array
     *
     * @param string $identifier
     * @param string $translation - this should be a value returned by __() function
     * @throws \Exception
     */
    private function add($identifier, $translation)
    {
        if (isset($this->translations[$identifier])) {
            throw new \Exception('Translation identifier "'.$identifier.'" already exists');
        }

        $this->translations[$identifier] = $translation;
    }

    /**
     * Makes it possible to get the translation from a main set using translation index/key
     *
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public function get_translation($key)
    {
        if (!isset($this->translations[$key])) {
            throw new \Exception('There\'s no translation for given key: '.$key);
        }

        return $this->translations[$key];
    }
}
