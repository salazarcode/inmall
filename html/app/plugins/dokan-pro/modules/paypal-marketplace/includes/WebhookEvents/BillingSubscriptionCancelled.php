<?php

namespace WeDevs\DokanPro\Modules\PayPalMarketplace\WebhookEvents;

use WeDevs\DokanPro\Modules\PayPalMarketplace\Abstracts\WebhookEventHandler;
use DokanPro\Modules\Subscription\Helper as SubscriptionHelper;
use WeDevs\DokanPro\Modules\PayPalMarketplace\Helper;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class BillingSubscriptionCancelled
 * @package WeDevs\DokanPro\Payment\PayPal\WebhookEvents
 *
 * @since 3.3.0
 *
 * @author weDevs
 */
// todo: this event is not 100% completed and tested
class BillingSubscriptionCancelled extends WebhookEventHandler {

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
     * Handle billing subscription failed
     *
     * @since 3.3.0
     *
     * @return void
     */
    public function handle() {
        try {
            $event           = $this->get_event();
            $subscription_id = sanitize_text_field( $event->resource->id );
            $order_id        = sanitize_text_field( $event->resource->custom_id );
            $vendor_id       = Helper::get_vendor_id_by_subscription( $subscription_id );
            $product_id      = get_user_meta( $vendor_id, 'product_package_id', true );

            if ( ! class_exists( SubscriptionHelper::class ) || ! SubscriptionHelper::is_subscription_product( $product_id ) ) {
                return;
            }

            do_action( 'dps_cancel_recurring_subscription', $order_id, $vendor_id, true, 'billing_subscription' );
        } catch ( \Exception $e ) {
            dokan_log( "[Dokan PayPal Marketplace] Billing Subscription Cancelled/Payment Failed Error:\n" . print_r( $e->getMessage(), true ), 'error' );
        }
    }
}
