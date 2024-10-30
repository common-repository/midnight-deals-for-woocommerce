<?php 

if( !class_exists( 'MDWC_Product' ) ):
class MDWC_Product {

    /**
     * @var
     *
     * @since 1.0.0
     */
    private static $_instance;

    /**
     * Single ton
     * @return MDWC_Product
     *
     * @since 1.0.0
     */
    public static function get_instance() {

        if( self::$_instance == null ) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }
    
    /**
     * __construct
     *
     * @return void
     * 
     * @since 1.0.0
     */
    public function __construct() {

        // Simple Product | Add Variation Cost
		add_filter( 'woocommerce_product_get_price', array( $this, 'apply_midnight_discount' ), 99, 2 );
		add_filter( 'woocommerce_product_get_regular_price', array( $this, 'apply_midnight_discount' ), 99, 2 );

		// Variable Product | Add Variation Cost
		add_filter( 'woocommerce_product_variation_get_regular_price', 'apply_midnight_discount', 99, 2 );
		add_filter( 'woocommerce_product_variation_get_price', 'apply_midnight_discount' , 99, 2 );

        // Display Discount HTML on Shop & Single Product Page
        add_filter( 'woocommerce_get_price_html', array( $this, 'apply_discount_html' ), 99, 2 );

        add_filter( 'post_class', array( $this, 'add_class_to_shop_loop' ), 10, 3 );

        // Midnight Deals Badge
        add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'add_midnight_deal_badge' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'add_midnight_deal_notice_product_page' ) );

    }

    /**
	 * Apply Midnight Discount | Filter Callback
	 * 
	 * @since 1.0.0
	 */
	public function apply_midnight_discount( $cost, $product ) {

		$product_id = $product->get_id();
        
        if( mdwc_is_deal_valid( $product_id ) ) {

            $discount_type = get_post_meta( $product_id, 'mdwc_discount_type', true );
            $discount = get_post_meta( $product_id, 'mdwc_discount', true );

            if( $discount_type == 'fixed' ) {

                $cost = $cost - $discount;

            }
            else {

                $cost = $cost - ( $cost * $discount / 100 );

            }

        }

		return $cost;

	}

    /**
     * Apply Discount HTML | Filter Callback
     * 
     * @param string $price
     * @param WC_Product $product
     * 
     * @since 1.0.0
     */
    public function apply_discount_html( $cost, $product ) {

        $product_id = $product->get_id();
        $actual_cost = $product->get_price();
        $currency_symbol = get_woocommerce_currency_symbol();
        
        if( mdwc_is_deal_valid( $product_id ) ) {

            $discount_type = get_post_meta( $product_id, 'mdwc_discount_type', true );
            $discount = get_post_meta( $product_id, 'mdwc_discount', true );

            if( $discount_type == 'fixed' ) {

                $cost = $actual_cost + $discount;

            }
            else {

                $cost = $actual_cost + ( $actual_cost * $discount / 100 );

            }

            $cost = "<del>{$currency_symbol}{$cost}</del> <ins> {$currency_symbol}{$actual_cost} </ins>";

        }

        return $cost;

    }

    /**
     * Add Midnight Deal Badge | Action Callback
     * 
     * @since 1.0.0
     */
    public function add_midnight_deal_badge() {

        global $product;
        
        if( $product instanceof WC_Product ) {

            $product_id = $product->get_id();

            if( mdwc_is_deal_valid( $product_id ) ) {

                ?>
                <div class="wc-block-components-product-sale-badge mdwc-midnight-deals-badge">
                    <span aria-hidden="true"><?php esc_html_e( '🌙 Midnight Deal', 'midnight-wc' ); ?></span>
                </div>
                <?php

            }

        }

    }

    /**
     * Admin Enqueue Scripts | Action Callback
     * 
     * @since 1.0.0
     */
    public function admin_enqueue_scripts() {

        wp_enqueue_style( 'mdwc-front', MDWC_PLUGIN_URL . 'assets/css/front-style.css', array( 'dashicons' ), MDWC_VERSION );

        $product_id = get_the_ID();
        
        if( mdwc_is_deal_valid( $product_id ) ) {

            $custom_css = '';

            wp_add_inline_style( 'mdwc-front', $custom_css );

        }

    }

    /**
     * Add Class to Shop Loop | Filter Callback
     * 
     * @param array $classes
     * @param string $class
     * @param int $post_id
     * 
     * @since 1.0.0
     */
    public function add_class_to_shop_loop( $classes, $class, $post_id ) {

        if( in_array( "post-{$post_id}", $classes ) && mdwc_is_deal_valid( $post_id ) ) {

            $classes[] = 'mdwc-midnight-deal-product';

        }

        return $classes;

    }

    public function add_midnight_deal_notice_product_page() {

        $product_id = get_the_ID();

        if( mdwc_is_deal_valid( $product_id ) ) {

            $discount_type = get_post_meta( $product_id, 'mdwc_discount_type', true );
            $discount = get_post_meta( $product_id, 'mdwc_discount', true );
            
            ?>
            <div class="mdwc-midnight-deal-product-page">
                <p><?php printf( 
                    '<b>%s</b> %s',
                    esc_html__( '🌙 Midnight Deal', 'midnight-wc' ),
                    esc_html__( 'is active on this product.', 'midnight-wc' )
                 ); ?></p>
            </div>
            <?php
        }

    }

}

MDWC_Product::get_instance();

endif;