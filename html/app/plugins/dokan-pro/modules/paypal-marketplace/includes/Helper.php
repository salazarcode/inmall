<?php

namespace WeDevs\DokanPro\Modules\PayPalMarketplace;

use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class Helper
 *
 * @package WeDevs\DokanPro\Modules\PayPalMarketplace
 *
 * @since 3.3.0
 */
class Helper {

    /**
     * Get PayPal gateway id
     *
     * @since 3.3.0
     *
     * @return string
     */
    public static function get_gateway_id() {
        // do not change this value ever, otherwise this will cause inconsistancy while retrieving data
        return 'dokan_paypal_marketplace';
    }

    /**
     * Get settings of the gateway
     *
     * @param null $key
     *
     * @since 3.3.0
     *
     * @return mixed|void
     */
    public static function get_settings( $key = null ) {
        $settings = get_option( 'woocommerce_' . static::get_gateway_id() . '_settings', [] );

        if ( $key && isset( $settings[ $key ] ) ) {
            return $settings[ $key ];
        }

        return $settings;
    }

    /**
     * Check whether it's enabled or not
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function get_gateway_title() {
        $settings = static::get_settings();

        return ! empty( $settings['title'] ) ? $settings['title'] : __( 'PayPal Marketplace', 'dokan' );
    }

    /**
     * Get BN Code
     *
     * @since 3.3.3
     *
     * @return bool
     */
    public static function get_bn_code() {
        $settings = static::get_settings();

        return ! empty( $settings['bn_code'] ) ? sanitize_text_field( $settings['bn_code'] ) : 'weDevs_SP_Dokan';
    }

    /**
     * Check whether it's enabled or not
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function is_enabled() {
        $settings = static::get_settings();

        return ! empty( $settings['enabled'] ) && 'yes' === $settings['enabled'];
    }

    /**
     * Check if this gateway is enabled and ready to use
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function is_ready() {
        if ( ! static::is_enabled() ||
            empty( static::get_partner_id() ) ||
            empty( static::get_client_id() ) ||
            empty( static::get_client_secret() ) ) {
            return false;
        }

        return true;
    }

    /**
     * Check if this gateway is enabled and ready to use
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function is_api_ready() {
        if (
            empty( static::get_client_id() ) ||
            empty( static::get_client_secret() ) ) {
            return false;
        }

        return true;
    }

    /**
     * Check if the seller is enabled for receive paypal payment
     *
     * @param $seller_id
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function is_seller_enable_for_receive_payment( $seller_id ) {
        return static::get_seller_merchant_id( $seller_id ) && static::get_seller_enabled_for_received_payment( $seller_id );
    }

    /**
     * Check whether the gateway in test mode or not
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function is_test_mode() {
        $settings = static::get_settings();

        return ! empty( $settings['test_mode'] ) && 'yes' === $settings['test_mode'];
    }

    /**
     * Check whether the test mode is enabled or not
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function is_debug_log_enabled() {
        $settings = static::get_settings();

        return ! empty( $settings['debug'] ) && 'yes' === $settings['debug'];
    }

    /**
     * Check whether Unbranded Credit Card mode is enabled or not
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function is_ucc_mode_allowed() {
        $settings = static::get_settings();

        return ! empty( $settings['ucc_mode'] ) && 'yes' === $settings['ucc_mode'];
    }

    /**
     * Unbranded credit card mode is allowed or not
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function is_ucc_enabled() {
        $wc_base_country = WC()->countries->get_base_country();

        if (
            'smart' === static::get_button_type() &&
            static::is_ucc_mode_allowed() &&
            array_key_exists( $wc_base_country, static::get_advanced_credit_card_debit_card_supported_countries() ) &&
            in_array( get_woocommerce_currency(), static::get_advanced_credit_card_debit_card_supported_currencies( $wc_base_country ), true )
        ) {
            return true;
        }

        return false;
    }

    /**
     * Get advanced credit card debit card supported countries (UCC/Unbranded payments)
     *
     * @see https://developer.paypal.com/docs/business/checkout/reference/currency-availability-advanced-cards/
     *
     * @since 3.3.0
     *
     * @return array
     */
    public static function get_advanced_credit_card_debit_card_supported_countries() {
        $supported_countries = [
            'AU' => 'Australia',
            'CA' => 'Canada',
            'FR' => 'France',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'US' => 'United States',
            'GB' => 'United Kingdom',
        ];

        return apply_filters( 'dokan_paypal_advanced_credit_card_debit_card_supported_countries', $supported_countries );
    }

    /**
     * Get branded payment supported countries
     *
     * @since 3.3.0
     *
     * @return array
     */
    public static function get_branded_payment_supported_countries() {
        $supported_countries = [
            'AU' => 'Australia',
            'AT' => 'Austria',
            'BE' => 'Belgium',
            'BG' => 'Bulgaria',
            'CA' => 'Canada',
            'CY' => 'Cyprus',
            'CZ' => 'Czech',
            'DK' => 'Denmark',
            'EE' => 'Estonia',
            'FI' => 'Finland',
            'FR' => 'France',
            'GR' => 'Greece',
            'DE' => 'Germany',
            'HU' => 'Hungary',
            'IE' => 'Ireland',
            'IT' => 'Italy',
            'LV' => 'Latvia',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MT' => 'Malta',
            'NL' => 'Netherlands',
            'NO' => 'Norway',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'RO' => 'Romania',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'ES' => 'Spain',
            'SE' => 'Sweden',
            'US' => 'United States',
            'GB' => 'United Kingdom',
        ];

        return apply_filters( 'dokan_paypal_branded_payment_supported_countries', $supported_countries );
    }

    /**
     * Get Paypal supported currencies except US
     * for advanced credit card debit card
     *
     * @see https://developer.paypal.com/docs/business/checkout/reference/currency-availability-advanced-cards/
     *
     * @since 3.3.0
     *
     * @return array
     */
    public static function get_advanced_credit_card_debit_card_non_us_supported_currencies() {
        return apply_filters(
            'dokan_paypal_supported_currencies', [
                'AUD',
                'CAD',
                'CHF',
                'CZK',
                'DKK',
                'EUR',
                'GBP',
                'HKD',
                'HUF',
                'JPY',
                'NOK',
                'NZD',
                'PLN',
                'SEK',
                'SGD',
                'USD',
            ]
        );
    }

    /**
     * Get US supported currencies for advanced credit card debit card
     *
     * @see https://developer.paypal.com/docs/business/checkout/reference/currency-availability-advanced-cards/
     *
     * @since 3.3.0
     *
     * @return array
     */
    public static function get_advanced_credit_card_debit_card_us_supported_currencies() {
        return apply_filters(
            'dokan_paypal_us_supported_currencies', [
                'AUD',
                'CAD',
                'EUR',
                'GBP',
                'JPY',
                'USD',
            ]
        );
    }

    /**
     *
     * @see https://developer.paypal.com/docs/platforms/develop/currency-codes/
     *
     * @since 3.3.0
     *
     * @return array
     */
    public static function get_supported_currencies() {
        $supported_currencies = [
            'AUD' => __( 'Australian dollar', 'dokan' ),
            'BRL' => __( 'Brazilian real', 'dokan' ),
            'CAD' => __( 'Canadian dollar', 'dokan' ),
            'CZK' => __( 'Czech koruna', 'dokan' ),
            'DKK' => __( 'Danish krone', 'dokan' ),
            'EUR' => __( 'Euro', 'dokan' ),
            'HKD' => __( 'Hong Kong dollar', 'dokan' ),
            'HUF' => __( 'Hungarian forint', 'dokan' ),
            'ILS' => __( 'Israeli new shekel', 'dokan' ),
            'JPY' => __( 'Japanese yen', 'dokan' ),
            'MYR' => __( 'Malaysian ringgit', 'dokan' ),
            'MXN' => __( 'Mexican peso', 'dokan' ),
            'TWD' => __( 'New Taiwan dollar', 'dokan' ),
            'NZD' => __( 'New Zealand dollar', 'dokan' ),
            'NOK' => __( 'Norwegian krone', 'dokan' ),
            'PHP' => __( 'Philippine peso', 'dokan' ),
            'PLN' => __( 'Polish złoty', 'dokan' ),
            'GBP' => __( 'Pound sterling', 'dokan' ),
            'RUB' => __( 'Russian ruble', 'dokan' ),
            'SGD' => __( 'Singapore dollar', 'dokan' ),
            'SEK' => __( 'Swedish krona', 'dokan' ),
            'CHF' => __( 'Swiss franc', 'dokan' ),
            'THB' => __( 'Thai baht', 'dokan' ),
            'USD' => __( 'United States dollar', 'dokan' ),
        ];

        return apply_filters( 'dokan_paypal_supported_currencies', $supported_currencies );
    }

    /**
     * Get advanced credit card debit card supported currencies
     *
     * @see https://developer.paypal.com/docs/business/checkout/reference/currency-availability-advanced-cards/
     *
     * @param $country_code
     *
     * @since 3.3.0
     *
     * @return array|bool
     */
    public static function get_advanced_credit_card_debit_card_supported_currencies( $country_code ) {
        $supported_countries = static::get_advanced_credit_card_debit_card_supported_countries();

        if ( ! array_key_exists( $country_code, $supported_countries ) ) {
            return false;
        }

        if ( 'US' === $country_code ) {
            return static::get_advanced_credit_card_debit_card_us_supported_currencies();
        }

        return static::get_advanced_credit_card_debit_card_non_us_supported_currencies();
    }

    /**
     * Get PayPal product type based on country
     *
     * @param $country_code
     *
     * @since 3.3.0
     *
     * @return bool|string
     */
    public static function get_product_type( $country_code ) {
        $ucc_supported_countries        = static::get_advanced_credit_card_debit_card_supported_countries();
        $branded_supported_countries    = static::get_branded_payment_supported_countries();

        if ( ! array_key_exists( $country_code, array_merge( $ucc_supported_countries, $branded_supported_countries ) ) ) {
            return false;
        }

        if ( array_key_exists( $country_code, $ucc_supported_countries ) ) {
            return 'PPCP';
        }

        if ( array_key_exists( $country_code, $branded_supported_countries ) ) {
            return 'EXPRESS_CHECKOUT';
        }
    }

    /**
     * @since 3.3.0
     * @param bool|null $test_mode
     * @return string
     */
    public static function get_seller_merchant_id_key( $test_mode = null ) {
        if ( null === $test_mode ) {
            $test_mode = static::is_test_mode();
        }
        return $test_mode ? '_dokan_paypal_test_merchant_id' : '_dokan_paypal_merchant_id';
    }

    /**
     * @since 3.3.0
     * @param bool|null $test_mode
     * @return string
     */
    public static function get_seller_enabled_for_received_payment_key( $test_mode = null ) {
        if ( null === $test_mode ) {
            $test_mode = static::is_test_mode();
        }
        return $test_mode ? '_dokan_paypal_test_enable_for_receive_payment' : '_dokan_paypal_enable_for_receive_payment';
    }

    /**
     * @since 3.3.0
     * @param bool|null $test_mode
     * @return string
     */
    public static function get_seller_marketplace_settings_key( $test_mode = null ) {
        if ( null === $test_mode ) {
            $test_mode = static::is_test_mode();
        }
        return $test_mode ? '_dokan_paypal_test_marketplace_settings' : '_dokan_paypal_marketplace_settings';
    }

    /**
     * @since 3.3.0
     * @param bool|null $test_mode
     * @return string
     */
    public static function get_seller_payments_receivable_key( $test_mode = null ) {
        if ( null === $test_mode ) {
            $test_mode = static::is_test_mode();
        }
        return $test_mode ? '_dokan_paypal_test_payments_receivable' : '_dokan_paypal_payments_receivable';
    }

    /**
     * @since 3.3.0
     * @param bool|null $test_mode
     * @return string
     */
    public static function get_seller_primary_email_confirmed_key( $test_mode = null ) {
        if ( null === $test_mode ) {
            $test_mode = static::is_test_mode();
        }
        return $test_mode ? '_dokan_paypal_test_primary_email_confirmed' : '_dokan_paypal_primary_email_confirmed';
    }

    /**
     * @since 3.3.0
     * @param bool|null $test_mode
     * @return string
     */
    public static function get_seller_enable_for_ucc_key( $test_mode = null ) {
        if ( null === $test_mode ) {
            $test_mode = static::is_test_mode();
        }
        return $test_mode ? '_dokan_paypal_test_enable_for_ucc' : '_dokan_paypal_enable_for_ucc';
    }

    /**
     *
     * @since 3.3.0
     * @param int $seller_id
     * @return string
     */
    public static function get_seller_merchant_id( $seller_id ) {
        return get_user_meta( $seller_id, static::get_seller_merchant_id_key(), true );
    }

    /**
     *
     * @since 3.3.0
     * @param int $seller_id
     * @return string
     */
    public static function get_seller_enabled_for_received_payment( $seller_id ) {
        return get_user_meta( $seller_id, static::get_seller_enabled_for_received_payment_key(), true );
    }

    /**
     * Log PayPal error data with debug id
     *
     * @param int $id
     * @param WP_Error $error
     * @param string $meta_key
     *
     * @param string $context
     *
     * @since 3.3.0
     *
     * @return void
     */
    public static function log_paypal_error( $id, $error, $meta_key, $context = 'post' ) {
        $error_data = $error->get_error_data();

        //store paypal debug id
        if ( isset( $error_data['paypal_debug_id'] ) ) {
            switch ( $context ) {
                case 'post':
                    update_post_meta( $id, "_dokan_paypal_{$meta_key}_debug_id", $error_data['paypal_debug_id'] );
                    break;

                case 'user':
                    update_user_meta( $id, "_dokan_paypal_{$meta_key}_debug_id", $error_data['paypal_debug_id'] );
                    break;
            }
        }

        dokan_log( "[Dokan PayPal Marketplace] $meta_key Error:\n" . print_r( $error, true ), 'error' );
    }

    /**
     * Get user id by merchant id
     *
     * @param $merchant_id
     *
     * @since 3.3.0
     *
     * @return int
     */
    public static function get_user_id_by_merchant_id( $merchant_id ) {
        global $wpdb;

        $user_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT `user_id` FROM $wpdb->usermeta WHERE `meta_key` = %s AND `meta_value`= %s",
                static::get_seller_merchant_id_key(),
                $merchant_id
            )
        );

        return absint( $user_id );
    }

    /**
     * Get Percentage of from a price
     *
     * @param $price
     * @param $extra_amount
     *
     * @since 3.3.0
     *
     * @return float|int
     */
    public static function get_percentage( $price, $extra_amount ) {
        $percentage = ( $extra_amount * 100 ) / $price;

        return $percentage;
    }

    /**
     * Get list of supported webhook events
     *
     * @since 3.3.0
     *
     * @return array
     */
    public static function get_supported_webhook_events() {
        return apply_filters(
            'dokan_paypal_supported_webhook_events', [
                'MERCHANT.ONBOARDING.COMPLETED'       => 'MerchantOnboardingCompleted',
                'CUSTOMER.MERCHANT-INTEGRATION.CAPABILITY-UPDATED' => 'CustomerMerchantIntegrationCapabilityUpdated',
                'CUSTOMER.MERCHANT-INTEGRATION.SELLER-EMAIL-CONFIRMED' => 'CustomerMerchantIntegrationSellerEmailConfirmed',
                'MERCHANT.PARTNER-CONSENT.REVOKED'    => 'MerchantPartnerConsentRevoked',
                'CHECKOUT.ORDER.APPROVED'             => 'CheckoutOrderApproved',
                'CHECKOUT.ORDER.COMPLETED'            => 'CheckoutOrderCompleted',
                'PAYMENT.REFERENCED-PAYOUT-ITEM.COMPLETED' => 'PaymentReferencedPayoutItemCompleted',
                'PAYMENT.CAPTURE.REFUNDED'            => 'PaymentCaptureRefunded',
                'PAYMENT.CAPTURE.REVERSED'            => 'PaymentCaptureRefunded',
                //'BILLING.SUBSCRIPTION.ACTIVATED'      => 'BillingSubscriptionActivated',
                //'BILLING.SUBSCRIPTION.CANCELLED'      => 'BillingSubscriptionCancelled',
                //'BILLING.SUBSCRIPTION.PAYMENT.FAILED' => 'BillingSubscriptionCancelled',
                //'PAYMENT.SALE.COMPLETED'              => 'PaymentSaleCompleted',
            ]
        );
    }

    /**
     * Get webhook events for notification
     *
     * @since 3.3.0
     *
     * @return array
     */
    public static function get_webhook_events_for_notification() {
        $events = array_keys( static::get_supported_webhook_events() );

        return array_map(
            function ( $event ) {
                return [ 'name' => $event ];
            }, $events
        );
    }

    /**
     * Get PayPal client id
     *
     * @since 3.3.0
     *
     * @return string
     */
    public static function get_client_id() {
        $key      = static::is_test_mode() ? 'test_app_user' : 'app_user';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
    }

    /**
     * Get PayPal client secret key
     *
     * @since 3.3.0
     *
     * @return string
     */
    public static function get_client_secret() {
        $key      = static::is_test_mode() ? 'test_app_pass' : 'app_pass';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
    }

    /**
     * Get Paypal partner id
     *
     * @since 3.3.0
     *
     * @return string
     */
    public static function get_partner_id() {
        $key      = 'partner_id';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
    }

    /**
     * Get client id
     *
     * @since 3.3.0
     *
     * @return string
     */
    public static function get_button_type() {
        $key      = 'button_type';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
    }

    /**
     * Get Cart item quantity exceeded error message
     *
     * @since 3.3.0
     *
     * @return string
     */
    public static function get_max_quantity_error_message() {
        $key      = 'max_error';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
    }

    /**
     * Get Payment Action (capture or authorize)
     *
     * @since 3.3.0
     *
     * @return string
     */
    public static function get_disbursement_mode() {
        $key      = 'disbursement_mode';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? $settings[ $key ] : 'INSTANT';
    }

    /**
     * Get disbersement delay period
     *
     * @since 3.3.0
     *
     * @return int
     */
    public static function get_disbursement_delay_period() {
        $key      = 'disbursement_delay_period';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? (int) $settings[ $key ] : 0;
    }

    /**
     * Get marketplace logo url
     *
     * @since 3.3.0
     *
     * @return string
     */
    public static function get_marketplace_logo() {
        $key      = 'marketplace_logo';
        $settings = static::get_settings();

        return ! empty( $settings[ $key ] ) ? esc_url_raw( $settings[ $key ] ) : esc_url_raw( esc_url_raw( DOKAN_PLUGIN_ASSEST . '/images/dokan-logo.png' ) );
    }

    /**
     * Check if non-connected sellers sees notice on their dashboard to connect their PayPal account
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function display_notice_on_vendor_dashboard() {
        $key      = 'display_notice_on_vendor_dashboard';
        $settings = self::get_settings();

        return ! empty( $settings[ $key ] ) && 'yes' === $settings[ $key ];
    }

    /**
     * Check if non-connected sellers gets announcement to connect their PayPal account
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function display_announcement_to_non_connected_sellers() {
        $key      = 'display_notice_to_non_connected_sellers';
        $settings = self::get_settings();

        return ! empty( $settings[ $key ] ) && 'yes' === $settings[ $key ];
    }

    /**
     * Get Connect announcement interval
     *
     * @since DOKAN_PRO_SiNCE
     *
     * @return int
     */
    public static function non_connected_sellers_display_notice_intervals() {
        $key      = 'display_notice_interval';
        $settings = self::get_settings();

        return ! empty( $settings[ $key ] ) ? absint( $settings[ $key ] ) : 7;
    }

    /**
     * Get webhook key
     *
     * @since 3.3.0
     *
     * @return string
     */
    public static function get_webhook_key() {
        return static::is_test_mode() ? 'dokan_paypal_marketplace_test_webhook' : 'dokan_paypal_marketplace_webhook';
    }

    /**
     * Get human readable error message
     *
     * @since 3.3.0
     * @param WP_Error $error
     * @return mixed|string
     */
    public static function get_error_message( WP_Error $error ) {
        $error_message = $error->get_error_message();
        if ( is_array( $error_message ) && isset( $error_message['details']['description'] ) ) {
            $error_message = $error_message['details']['description'];
        } elseif ( is_array( $error_message ) && isset( $error_message['message'] ) ) {
            $error_message = $error_message['message'];
        }
        return $error_message;
    }

    /**
     * Check wheter subscription module is enabled or not
     *
     * @since 3.3.0
     *
     * @return bool
     */
    public static function has_vendor_subscription_module() {
        // don't confused with product_subscription, id for vendor subscription module is product_subscription
        return function_exists( 'dokan_pro' ) && dokan_pro()->module->is_active( 'product_subscription' );
    }

    /**
     * Get subscription product from an order
     * @param \WC_Order $order
     * @since 3.3.0
     * @return \WC_Product|bool|null
     */
    public static function get_vendor_subscription_product_by_order( $order ) {
        foreach ( $order->get_items() as $item ) {
            $product = $item->get_product();

            if ( 'product_pack' === $product->get_type() ) {
                return $product;
            }
        }

        return null;
    }

    /**
     * Check if the order is a subscription order
     *
     * @param \WC_Order $order
     * @since 3.3.0
     *
     * @return bool
     **/
    public static function is_vendor_subscription_order( $order ) {
        if ( ! static::has_vendor_subscription_module() ) {
            return false;
        }

        $product = static::get_vendor_subscription_product_by_order( $order );

        return $product ? true : false;
    }

    /**
     * Check if the order is a subscription order
     *
     * @param \WC_Product|int $product
     * @since 3.3.0
     *
     * @return bool
     **/
    public static function is_vendor_subscription_product( $product ) {
        if ( is_int( $product ) ) {
            $product = wc_get_product( $product );
        }

        if ( ! $product instanceof \WC_Product ) {
            return false;
        }

        if ( ! self::has_vendor_subscription_module() ) {
            return false;
        }

        if ( 'product_pack' === $product->get_type() ) {
            return true;
        }

        return false;
    }

    /**
     * Get vendor id by subscriptoin id
     *
     * @since 3.3.0
     *
     * @param string $subscription_id
     *
     * @return int
     */
    public static function get_vendor_id_by_subscription( $subscription_id ) {
        global $wpdb;

        $vendor_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT `user_id` FROM $wpdb->usermeta WHERE `meta_key` = %s AND `meta_value`= %s",
                '_dokan_paypal_subscription_id',
                $subscription_id
            )
        );

        return absint( $vendor_id );
    }

    /**
     * Include module template
     *
     * @since 3.3.0
     *
     * @param string $name template file name
     * @param array  $args
     *
     * @return void
     */
    public static function get_template( $name, $args = [] ) {
        //todo: sanitize file name
        dokan_get_template( "$name.php", $args, '', trailingslashit( DOKAN_PAYPAL_MP_TEMPLATE_PATH ) );
    }
}
