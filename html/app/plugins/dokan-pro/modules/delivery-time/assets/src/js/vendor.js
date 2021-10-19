;(function($){
    const Dokan_Handle_Vendor_Delivery_Time = {
        init: function () {
            $( "#vendor-delivery-time-date-picker" ).on( 'change', Dokan_Handle_Vendor_Delivery_Time.set_delivery_time_date_picker );
        },

        init_dashboard_calendar: function () {
            if ( typeof FullCalendar === 'undefined') {
                return;
            }
            let calendarEl = document.getElementById("delivery-time-calendar");
            function handleDatesRender( arg ) {
                console.log( 'viewType:', arg.view.calendar.state.viewType );
            }
            let calendar = new FullCalendar.Calendar( calendarEl, {
                defaultView: 'dayGridMonth',
                datesRender: handleDatesRender,
                defaultDate: new Date(),
                headerToolbar: {
                    start: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                    center: 'title',
                    end: 'today prev,next'
                },
                events: function ( fetchInfo, successCallback, failureCallback ) {
                    let data = {
                        action: 'dokan_get_dashboard_calendar_event',
                        start_date: moment(fetchInfo.start).format('YYYY-MM-DD'),
                        end_date: moment(fetchInfo.end).format('YYYY-MM-DD'),
                        nonce: dokan_delivery_time_calendar_nonce,
                    };
                    jQuery.post( dokan.ajaxurl, data, function( response ) {
                        if ( response.success ) {
                            if ( response.data.calendar_events ) {
                                successCallback( Array.prototype.slice.call( response.data.calendar_events ) );
                            }
                        }
                    } );
                }
            } );

            calendar.render();
        },

        set_delivery_time_date_picker: function () {
            let self = $( "#vendor-delivery-time-date-picker" );
            let vendor_id = self.data( 'vendor_id' );
            let nonce = self.data( 'nonce' );
            let selected_date = self.attr( 'value' );

            if( selected_date ) {
                $( `#vendor-delivery-time-date-picker` ).fadeIn( 400 );
            } else {
                $( `#vendor-delivery-time-date-picker` ).fadeOut( 400 );
            }

            let data = {
                action: 'dokan_get_delivery_time_slot',
                vendor_id: vendor_id,
                nonce: nonce,
                date: selected_date,
            };

            if( data.date ) {
                Dokan_Handle_Vendor_Delivery_Time.get_delivery_time_slots( data );
            }
        },

        set_order_details_delivery_calendar_config: function () {
            if (typeof vendorInfo === 'undefined') {
                return;
            }
            const info = vendorInfo;

            let config = {
                minDate: 'today',
                altInput: true,
                altFormat: dokan_get_i18n_date_format( false ),
                dateFormat: "Y-m-d",
                disable: []
            };

            const allDeliveryDays = [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' ];

            let deliveryDays = Object.keys( info.vendor_delivery_options.delivery_day );
            let deliveryBlockedDaysIndex = [];
            allDeliveryDays.forEach( day => {
                if( ! deliveryDays.includes( day ) ) {
                    deliveryBlockedDaysIndex.push( allDeliveryDays.indexOf( day ) );
                }
            } );

            const vendorVacationDays = info.vendor_vacation_days;
            const preOrderBlockedDates = info.vendor_preorder_blocked_dates;

            config.disable = [
                function( date ) {
                    // return true to disable
                    return ( deliveryBlockedDaysIndex.includes( date.getDay() ) );
                }
            ];

            config.disable = [ ...config.disable,  ...vendorVacationDays, ...preOrderBlockedDates ];

            flatpickr( '#vendor-delivery-time-date-picker', config );
        },

        get_delivery_time_slots: function ( data ) {
            $("#vendor-delivery-time-slot-picker").prop("disabled", true);
            jQuery.post( dokan.ajaxurl, data, function( response ) {
                if ( response.success ) {

                    $( `#vendor-delivery-time-slot-picker option:gt(0)` ).remove();

                    $.each( response.data.vendor_delivery_slots, function( key, value ) {
                        $( `#vendor-delivery-time-slot-picker` )
                            .append( $( "<option></option>" )
                                .attr( "value", key )
                                .text( key ) );
                    } );
                    $("#vendor-delivery-time-slot-picker").prop("disabled", false);
                }
            } );
        },
    };

    jQuery( document ).ready( function ( $ ) {
        Dokan_Handle_Vendor_Delivery_Time.init();
        Dokan_Handle_Vendor_Delivery_Time.set_order_details_delivery_calendar_config();
        Dokan_Handle_Vendor_Delivery_Time.init_dashboard_calendar();
    } );
})(jQuery);
