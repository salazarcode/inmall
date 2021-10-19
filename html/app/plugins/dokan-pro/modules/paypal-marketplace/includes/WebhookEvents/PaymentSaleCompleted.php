<?php

namespace WeDevs\DokanPro\Modules\PayPalMarketplace\WebhookEvents;

use WeDevs\DokanPro\Modules\PayPalMarketplace\Abstracts\WebhookEventHandler;
use DokanPro\Modules\Subscription\Helper as SubscriptionHelper;
use WeDevs\DokanPro\Modules\PayPalMarketplace\Helper;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class PaymentSaleCompleted
 * @package WeDevs\DokanPro\Payment\PayPal\WebhookEvents
 *
 * @since 3.3.0
 *
 * @author weDevs
 */
class PaymentSaleCompleted extends WebhookEventHandler {

    /**
     * CheckoutOrderApproved constructor.
     *
     * @param $event
     *
     * @since 3.3.0
     */
    public function __construct( $event ) {
        $this->set_event( $event );
    }

    /**
     * Handle payment sale
     *
     * @since 3.3.0
     *
     * @return void
     */
    public function handle() {
        try {
            // this hook is used for subscription payment only
            /**
             * This webhook will be called by both vendor subscription and normal product
             * if product is vendor subscription, then payment gateway fee payer will be admin
             * todo: check and rewrite this hook
             */
            $event    = $this->get_event();
            $order_id = sanitize_text_field( $event->resource->custom_id );

            $order = wc_get_order( $order_id );

            if ( ! is_object( $order ) ) {
                wp_send_json_error(
                    [
						'status'  => 'error',
						'message' => __( 'Not a valid order!', 'dokan' ),
					]
                );
            }

            $paypal_fee_data                = $event->resource->transaction_fee;
            $paypal_processing_fee_currency = $paypal_fee_data->currency;
            $paypal_processing_fee          = $paypal_fee_data->value;

            $order->add_order_note(
                /* translators: %s: paypal processing fee */
                sprintf( __( 'PayPal processing fee is %s', 'dokan' ), $paypal_processing_fee )
            );

            update_post_meta( $order->get_id(), 'dokan_gateway_fee', $paypal_processing_fee );
            update_post_meta( $order->get_id(), 'dokan_gateway_fee_paid_by', 'seller' );
            update_post_meta( $order->get_id(), '_dokan_paypal_payment_processing_fee', $paypal_processing_fee );
            update_post_meta( $order->get_id(), '_dokan_paypal_payment_processing_currency', $paypal_processing_fee_currency );
        } catch ( \Exception $e ) {
            dokan_log( "[Dokan PayPal Marketplace] PaymentSaleCompleted Error:\n" . print_r( $e->getMessage(), true ), 'error' );
        }
    }
}
