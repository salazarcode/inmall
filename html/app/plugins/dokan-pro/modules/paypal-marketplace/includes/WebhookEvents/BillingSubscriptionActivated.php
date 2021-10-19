<?php

namespace WeDevs\DokanPro\Modules\PayPalMarketplace\WebhookEvents;

use DokanPro\Modules\Subscription\Helper as SubscriptionHelper;
use WeDevs\DokanPro\Modules\PayPalMarketplace\Helper;
use WeDevs\DokanPro\Modules\PayPalMarketplace\Abstracts\WebhookEventHandler;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class BillingSubscriptionActivated
 * @package WeDevs\DokanPro\Payment\PayPal\WebhookEvents
 *
 * @since 3.3.0
 *
 * @author weDevs
 */
//todo: this event is not 100% done and not been tested
class BillingSubscriptionActivated extends WebhookEventHandler {

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
     * Handle billing subscription activated
     *
     * @since 3.3.0
     *
     * @return void
     */
    public function handle() {
        $event           = $this->get_event();
        $subscription_id = sanitize_text_field( $event->resource->id );
        $order_id        = sanitize_text_field( $event->resource->custom_id );
        $order           = wc_get_order( $order_id );

        // check payment gateway used was dokan paypal marketplace
        if ( $order->get_payment_method() !== Helper::get_gateway_id() ) {
            return;
        }

        $vendor_id  = Helper::get_vendor_id_by_subscription( $subscription_id );
        $product_id = get_user_meta( $vendor_id, 'product_package_id', true );

        if ( ! class_exists( SubscriptionHelper::class ) || ! SubscriptionHelper::is_subscription_product( $product_id ) ) {
            return;
        }

        // todo: change this with proper error log and simple return
        if ( ! is_object( $order ) ) {
            wp_send_json_error(
                [
					'status'  => 'error',
					'message' => __( 'Not a valid order!', 'dokan' ),
				]
            );
        }

        //allow if the order is pending
        if ( 'wc-pending' !== $order->get_status() && 'pending' !== $order->get_status() ) {
            wp_send_json_error(
                [
					'status'  => 'error',
					'message' => __( 'Cannot process', 'dokan' ),
				]
            );
        }

        $product_id = null;
        foreach ( $order->get_items( 'line_item' ) as $key => $line_item ) {
            $product_id = $line_item->get_product_id();
        }

        if ( ! Helper::is_vendor_subscription_product( $product_id ) ) {
            return;
        }

        $dokan_subscription = dokan()->subscription->get( $product_id );

        //return if the subscription is non recurring
        if ( ! $dokan_subscription->is_recurring() ) {
            return;
        }

        $vendor_id = $order->get_customer_id();

        update_user_meta( $vendor_id, '_dokan_paypal_subscription_id', $subscription_id );

        $dokan_subscription->activate( $order );

        $order->payment_complete();
    }
}
