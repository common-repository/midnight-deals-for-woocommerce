<?php

/**
 * Apply Midnight Discount
 * 
 * @since 1.0.0
 */
if( !function_exists( 'mdwc_is_deal_valid' ) ):
function mdwc_is_deal_valid( $product_id ) {

    $midnight_deals = get_post_meta( $product_id, 'mdwc_midnight_deals', true );
    $midnight_deals = $midnight_deals == 'yes' ? true : false;

    $start_time = get_post_meta( $product_id, 'mdwc_start_time', true );
    $start_time = $start_time ? gmdate( 'Y-m-d H:i:s', strtotime( $start_time ) ) : false;

    $end_time = get_post_meta( $product_id, 'mdwc_end_time', true );
    $end_time = $end_time ? gmdate( 'Y-m-d H:i:s', strtotime( $end_time ) ) : false;

    $current_time = gmdate('Y-m-d H:i:s');


    if( 
        $midnight_deals 
        &&
        $start_time
        &&
        $end_time
        &&
        $start_time <= $current_time
        &&
        $end_time >= $current_time 
    ) {
        return true;
    }

    return false;

}
endif;