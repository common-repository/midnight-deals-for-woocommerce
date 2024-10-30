<?php 

if( !class_exists( 'MDWC_Product_Edit_Page' ) ):
class MDWC_Product_Edit_Page {

    /**
     * @var
     *
     * @since 1.0.0
     */
    private static $_instance;

    /**
     * Single ton
     * @return MDWC_Product_Edit_Page
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

        add_action( 'woocommerce_product_options_pricing', array( $this, 'add_settings' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_settings' ) );

    }

    /**
     * Add settings | Action Callback
     * 
     * @since 1.0.0
     */
    public function add_settings() {

        $current_time = gmdate( 'h:i A' );
        $post_id = get_the_ID();
        $midnight_deals = get_post_meta( $post_id, 'mdwc_midnight_deals', true );
        $midnight_deals = $midnight_deals == 'yes' ? 'checked' : '';
        $start_time = get_post_meta( $post_id, 'mdwc_start_time', true );
        $end_time = get_post_meta( $post_id, 'mdwc_end_time', true );
        $discount_type = get_post_meta( $post_id, 'mdwc_discount_type', true );
        $discount = get_post_meta( $post_id, 'mdwc_discount', true );

        $discount_types = array(
            'percentage'    => __( 'Percentage', 'midnight-wc' ),
            'fixed'         => __( 'Fixed', 'midnight-wc' )
        );

        wp_nonce_field( 'mdwc-nonce', 'mdwc_nonce' );

        ?>
        <p class="form-field">
		    <label for="mdwc-midnight-deals"><?php esc_html_e( '🌙 Midnight Deals', 'midnight-wc' ); ?></label>
            <input type="checkbox" name="mdwc-midnight_deals" id="mdwc-midnight-deals" value="yes" <?php echo esc_attr( $midnight_deals ); ?> class="checkbox" />
            <span class="description"><?php esc_html_e( 'Enable Midnight Deals', 'midnight-wc' ); ?></span>
        </p>
        <p class="form-field">
		    <label for="mdwc-current-time"><?php esc_html_e( '🕛 Current time', 'midnight-wc' ); ?></label>
            <input type="text" class="short" name="mdwc_current_time" id="mdwc-current-time" value="<?php echo esc_attr( $current_time ); ?>" readonly />
            <span class="description"><?php esc_html_e( 'Your website based on Server time, this is your current server time, set your deals\' time according to it.', 'midnight-wc' ); ?></span>
        </p>
        <p class="form-field">
		    <label for="mdwc-start-time"><?php esc_html_e( '🕛 Start time', 'midnight-wc' ); ?></label>
            <input type="time" class="short" name="mdwc_start_time" id="mdwc-start-time" value="<?php echo esc_attr( $start_time ); ?>" /> 
        </p>
        <p class="form-field">
		    <label for="mdwc-end-time"><?php esc_html_e( '🕕 End time', 'midnight-wc' ); ?></label>
            <input type="time" class="short" name="mdwc_end_time" id="mdwc-end-time" value="<?php echo esc_attr( $end_time ); ?>" /> 
        </p>
        <p class="form-field">
		    <label for="mdwc-discount-type"><?php esc_html_e( '🎫 Discount type', 'midnight-wc' ); ?></label>
            <select name="mdwc_discount_type" class="short" id="mdwc-discount-type">
                <?php
                foreach( $discount_types as $key => $value ) {

                    printf( 
                        '<option value="%s" %s>%s</option>', 
                        esc_attr( $key ), 
                        selected( $discount_type, $key, false ),
                        esc_html( $value ) 
                    );
                    
                }
                ?>
            </select> 
        </p>
        <p class="form-field">
            <label for="mdwc-discount"><?php esc_html_e( '📈 Discount', 'midnight-wc' ); ?></label>
            <input type="text" class="short wc_input_price" name="mdwc_discount" id="mdwc-discount" value="<?php echo esc_attr( $discount ); ?>" />
            <span class="description"><?php esc_html_e( 'Enter discount amount or percent.', 'midnight-wc' ); ?></span>
        </p>
        <?php

    }

    /**
     * Save settings | Action Callback
     * 
     * @param int $post_id
     * 
     * @since 1.0.0
     */
    public function save_settings( $post_id ) {
        
        if ( 
            ! isset( $_POST['mdwc_nonce'] ) 
            ||
            ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['mdwc_nonce'] ) ) , 'mdwc-nonce' )
        ) {
            
            die( 'Security check.' );
            
        }

        $midnight_deals = isset( $_POST['mdwc-midnight_deals'] ) ? 'yes' : 'no';
        $start_time = isset( $_POST['mdwc_start_time'] ) ? sanitize_text_field( wp_unslash( $_POST['mdwc_start_time'] ) ) : '';
        $end_time = isset( $_POST['mdwc_end_time'] ) ? sanitize_text_field( wp_unslash( $_POST['mdwc_end_time'] ) ) : '';
        $discount_type = isset( $_POST['mdwc_discount_type'] ) ? sanitize_text_field( wp_unslash( $_POST['mdwc_discount_type'] ) ) : '';
        $discount = isset( $_POST['mdwc_discount'] ) ? sanitize_text_field( wp_unslash( $_POST['mdwc_discount'] ) ) : '';

        update_post_meta( $post_id, 'mdwc_midnight_deals', $midnight_deals );
        update_post_meta( $post_id, 'mdwc_start_time', $start_time );
        update_post_meta( $post_id, 'mdwc_end_time', $end_time );
        update_post_meta( $post_id, 'mdwc_discount_type', $discount_type );
        update_post_meta( $post_id, 'mdwc_discount', $discount );

    }

}

MDWC_Product_Edit_Page::get_instance();

endif;