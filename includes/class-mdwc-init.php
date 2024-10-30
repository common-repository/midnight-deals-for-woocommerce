<?php

class MDWC_Init {

    /**
     * @var
     *
     * @since 1.0
     */
    private static $_instance;

    /**
     * Single ton
     * @return MDWC_Init
     *
     * @since 1.0
     */
    public static function get_instance() {

        if( self::$_instance == null ) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    /**
     * MDWC_Init constructor.
     *
     * @since 1.0
     */
    public function __construct() {

        $this->validate();

        add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

    }

    /**
     * Meets requirements
     *
     * @since 1.0
     */
    public function validate() {

        if( !function_exists( 'is_plugin_active' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            $this->init();
        }
        else {
            add_action( 'admin_notices', array( $this, 'missing_wc' ) );
        }

    }

    /**
     * Shows Notice
     *
     * @since 1.0
     */
    public function missing_wc() {

        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php esc_html_e( 'In order to use Midnight Deals for WooCommerce, make sure WooCommerce is installed and active.', 'midnight-wc' ); ?></p>
        </div>
        <?php

    }

    /**
     * Finally initialize the Plugin :)
     *
     * @since 1.0
     */
    private function init() {

        $this->includes();

    }

    /**
     * Includes files
     *
     * @since 1.0
     */
    public function includes() {

        require 'mdwc-functions.php';
        require 'mdwc-product-edit-page.php';
        require 'mdwc-product.php';

    }

    /**
     * Plugin row meta | Action Callback
     *
     * @param $plugin_meta
     * @param $plugin_file
     * @param $plugin_data
     * @param $status 
     * @return array
     *
     * @since 1.0.0
     */
    public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

        if( isset( $plugin_data['slug'] ) && $plugin_data['slug'] == 'midnight-deals-for-woocommerce' ) {

            $plugin_meta[] = sprintf(
                '<a href="%s" style="color: green; font-weight: bold" target="_blank">%s</a>',
                esc_url( 'https://coderpress.co/products/midnight-deals-for-woocommerce/?utm_source=mdwc&utm_medium=plugins-go-pro' ),
                __( 'GO PRO👑' )
            );

            $plugin_meta[] = sprintf(
                '<a href="%s" target="_blank">%s</a>',
                esc_url( 'https://coderpress.co/docs/midnight-deals-for-woocommerce/?utm_source=mdwc&utm_medium=plugins-how-to-setup' ),
                __( 'How to Setup❓' )
            );

        }

        return $plugin_meta;

    }

}