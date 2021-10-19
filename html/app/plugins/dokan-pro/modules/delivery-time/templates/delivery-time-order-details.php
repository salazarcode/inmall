<?php
/**
 * Dokan Delivery time wc order details
 *
 * @since 3.3.0
 * @package DokanPro
 */

$delivery_time_date_slot = isset( $delivery_time_date_slot ) ? $delivery_time_date_slot : [];

if ( empty( $delivery_time_date_slot['date'] ) ) {
    return;
}
?>

<div id="dokan-delivery-time-slot-order-details">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" height="20" viewBox="0 0 24 24" stroke="#333333">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div class="main">
        <div>
            <span><strong><?php esc_html_e( 'Delivery Date:', 'dokan' ); ?></strong></span>
            <span><?php echo ' ' . esc_html( $delivery_time_date_slot['date'] ) . ' @'; ?></span>
        </div>
        <div class="slot"><?php echo esc_html( $delivery_time_date_slot['slot'] ); ?></div>
    </div>
</div>
