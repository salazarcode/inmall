jQuery(function($) {
    const Dokan_Handle_Delivery_Time_Admin_Meta_Box = {
        init: function () {
            Dokan_Handle_Delivery_Time_Admin_Meta_Box.set_admin_delivery_time_slots( $( "#dokan-delivery-admin-date-picker" ) );

            $( "#dokan-delivery-admin-date-picker" ).datepicker( "option",  { dateFormat: dokan_get_i18n_date_format(), altFormat: 'yy-mm-dd', altField: "#dokan_delivery_date_input", minDate: 0,  } ).on( 'change', function( e ) {
                e.preventDefault();

                Dokan_Handle_Delivery_Time_Admin_Meta_Box.set_admin_delivery_time_slots( this );
            } );
        },

        set_admin_delivery_time_slots: function ( context ) {
            $( context ).attr( 'value', $( '#dokan-delivery-admin-date-picker' ).val() );

            let self = $( context );
            let vendor_id = self.data( 'vendor_id' );
            let selected_date = $( '#dokan_delivery_date_input' ).val();
            let nonce = self.data( 'nonce' );

            let data = {
                action: 'dokan_get_delivery_time_slot',
                vendor_id: vendor_id,
                nonce: nonce,
                date: selected_date,
            };

            if( data.date ) {
                Dokan_Handle_Delivery_Time_Admin_Meta_Box.get_admin_delivery_time_slots( data );
            }
        },

        get_admin_delivery_time_slots: function ( data ) {
            $("#dokan-delivery-admin-time-slot-picker").prop("disabled", true);
            jQuery.post( dokan.ajaxurl, data, function( response ) {
                if ( response.success ) {

                    $( `#dokan-delivery-admin-time-slot-picker option:gt(0)` ).remove();

                    $.each( response.data.vendor_delivery_slots, function( key, value ) {
                        $( `#dokan-delivery-admin-time-slot-picker` )
                            .append( $( "<option></option>" )
                                .attr( "value", key )
                                .text( key ) );
                    } );

                    $("#dokan-delivery-admin-time-slot-picker").prop("disabled", false);
                }
            } );
        }
    };

    jQuery( document ).ready( function ( $ ) {
        Dokan_Handle_Delivery_Time_Admin_Meta_Box.init();
    } );
} );
