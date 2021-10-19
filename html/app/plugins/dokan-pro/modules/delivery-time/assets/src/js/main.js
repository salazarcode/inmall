;(function($){
    const Dokan_Handle_Delivery_Time_Slot_Picker = {
        init: function() {
            $('.delivery-time-slot-picker').hide();
            $( ".delivery-time-date-picker" ).on( 'change', Dokan_Handle_Delivery_Time_Slot_Picker.get_delivery_time_slots );
            Dokan_Handle_Delivery_Time_Slot_Picker.init_calendar();
        },

        get_delivery_time_slots: function (e ) {
            e.preventDefault();

            let self = $( e.target );
            let vendor_id = self.data( 'vendor_id' );
            let nonce = self.data( 'nonce' );
            let selected_date = self.attr( 'value' );

            if( selected_date ) {
                $( `#delivery-time-slot-picker-${vendor_id}` ).fadeIn( 400 );
            } else {
                $( `#delivery-time-slot-picker-${vendor_id}` ).fadeOut( 400 );
            }

            $( `#delivery-time-slot-picker-${vendor_id}` ).val( $( `#delivery-time-slot-picker-${vendor_id} option:first` ).val() );

            let data = {
                action: 'dokan_get_delivery_time_slot',
                vendor_id: vendor_id,
                nonce: nonce,
                date: selected_date,
            };

            if( data.date ) {
                Dokan_Handle_Delivery_Time_Slot_Picker.get_delivery_time_slots_data( data );
            }
        },

        get_delivery_time_slots_data: function (data ) {
            $( "#dokan-spinner-overlay" ).fadeIn( 100 );

            jQuery.post( dokan.ajaxurl, data, function( response ) {
                if ( response.success ) {
                    $( "#dokan-spinner-overlay" ).fadeOut( 400 );

                    $( `#delivery-time-slot-picker-${data.vendor_id} option:gt(0)` ).remove();

                    $.each( response.data.vendor_delivery_slots, function( key, value ) {
                        $( `#delivery-time-slot-picker-${data.vendor_id}` )
                            .append( $( "<option></option>" )
                                .attr( "value", key )
                                .text( key ) );
                    } );
                }
            } );
        },

        init_calendar: function () {
            if ( typeof dokan_delivery_time_vendor_infos === 'undefined' ) {
                return;
            }
            const infos = dokan_delivery_time_vendor_infos;

            let config = {
                minDate: 'today',
                altInput: true,
                altFormat: dokan_get_i18n_date_format( false ),
                dateFormat: "Y-m-d",
                disable: []
            };

            const allDeliveryDays = [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' ];

            for ( let [ key, value ] of Object.entries( infos ) ) {
                let deliveryDays = Object.keys( value.vendor_delivery_options.delivery_day );
                let deliveryBlockedDaysIndex = [];
                allDeliveryDays.forEach( day => {
                    if( ! deliveryDays.includes( day ) ) {
                        deliveryBlockedDaysIndex.push( allDeliveryDays.indexOf( day ) );
                    }
                } );

                const vendorVacationDays = value.vendor_vacation_days;
                const preOrderBlockedDates = value.vendor_preorder_blocked_dates;

                config.disable = [
                    function( date ) {
                        // return true to disable
                        return ( deliveryBlockedDaysIndex.includes( date.getDay() ) );
                    }
                ];

                config.disable = [ ...config.disable,  ...vendorVacationDays, ...preOrderBlockedDates ];

                flatpickr( '#delivery-time-date-picker-' + key, config );
            }
        }
    };

    jQuery( document ).ready( function ( $ ) {
        Dokan_Handle_Delivery_Time_Slot_Picker.init();
    } );
})(jQuery);
