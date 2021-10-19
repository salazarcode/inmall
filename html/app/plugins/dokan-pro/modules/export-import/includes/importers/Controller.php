<?php
/**
 * Dokan Product CSV Import Controller
 *
 * @since 3.3.3
 * @package WeDevs\DokanPro
 * @author WeDevs
 */

namespace WeDevs\DokanPro\Modules\ExIm\Importers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WC_Product_CSV_Importer_Controller' ) ) {
    include_once WC_ABSPATH . 'includes/admin/importers/class-wc-product-csv-importer-controller.php';
}

/**
 * Class Controller
 *
 * Dokan product CSV importer controller.
 *
 * @since 3.3.3
 * @package WeDevs\DokanPro\Modules\ExIm\Importers
 */
class Controller extends \WC_Product_CSV_Importer_Controller {

    /**
     * Output header view with dokan template hooks.
     * This override the WC Import screen header.
     *
     * @since 3.3.3
     * @return void
     */
    protected function output_header() {
        include dirname( __FILE__ ) . '/views/html-csv-import-header.php';
    }

    /**
     * Output footer view with dokan template hooks.
     * This override the WC Import screen footer.
     *
     * @since 3.3.3
     * @return void
     */
    protected function output_footer() {
        include dirname( __FILE__ ) . '/views/html-csv-import-footer.php';
    }

    /**
     * Done step.
     *
     * Checking the nonce and displaying custom done screen
     * with the product import message and show product
     * button pointed to the vendor product page.
     *
     * @since 3.3.3
     * @return void
     */
    protected function done() {
        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'woocommerce-csv-importer' ) ) {
            return;
        }

        $imported  = isset( $_REQUEST['products-imported'] ) ? absint( wp_unslash( $_REQUEST['products-imported'] ) ) : 0;
        $updated   = isset( $_REQUEST['products-updated'] ) ? absint( wp_unslash( $_REQUEST['products-updated'] ) ) : 0;
        $failed    = isset( $_REQUEST['products-failed'] ) ? absint( wp_unslash( $_REQUEST['products-failed'] ) ) : 0;
        $skipped   = isset( $_REQUEST['products-skipped'] ) ? absint( wp_unslash( $_REQUEST['products-skipped'] ) ) : 0;
        $file_name = isset( $_REQUEST['file-name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['file-name'] ) ) : '';
        $errors    = array_filter( (array) get_user_option( 'product_import_error_log' ) );

        include dirname( __FILE__ ) . '/views/html-csv-import-done.php';
    }
}
