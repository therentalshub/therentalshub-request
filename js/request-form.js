var trhRequestForm = (function($, flatpickr) {

    /**
     * Form elements.
     */
    var startDateEl = null;
    var startTimeEl = null;
    var endDateEl = null;
    var endTimeEl = null;
    var carEl = null;
    var locEl = null;
    var fnameEl = null;
    var lnameEl = null;
    var emailEl = null;
    var phoneEl = null;
    var notesEl =null;
    var carNameEl = null; // is hidden input
    var locNameEl = null; // is hidden input
    var flightNameEl = null; // is hidden input

    /**
     * Box wrapper div for cars select.
     */
    var carWrapEl = null;

    /**
     * Box wrapper div for locations select.
     */
    var locWrapEl = null;

    /**
     * Box wrapper div for flight nr input.
     */
    var flightWrapEl = null;

    /**
     * The form.
     */
    var form = null;

    /**
     * Start date picker.
     */
    var sdCal = null;

    /**
     * Start time picker.
     */
    var stCal = null;

    /**
     * End date picker.
     */
    var edCal = null;

    /**
     * End time picker.
     */
    var etCal = null;

    /**
     * Load form elements
     */
    var loadElements = function() {

        startDateEl = $('#trhrf_start_date');
        startTimeEl = $('#trhrf_start_time');
        endDateEl = $('#trhrf_end_date');
        endTimeEl = $('#trhrf_end_time');
        carEl = $('#trhrf_car');
        locEl = $('#trhrf_loc');
        fnameEl = $('#trhrf_fname');
        lnameEl = $('#trhrf_lname');
        emailEl = $('#trhrf_email');
        phoneEl = $('#trhrf_phone');
        notesEl = $('#trhrf_notes');
        carNameEl = $('#trhrf_car_name');
        locNameEl = $('#trhrf_loc_name');
        flightNameEl = $('#trhrf_flight');
        
        carWrapEl = $('#trhrf_car_wrap');
        locWrapEl = $('#trhrf_location_wrap');
        flightWrapEl = $('#trhrf_flight_wrap');

        form = $('#trh-request-form');

        // set event that sets hidden field with car name
        carEl.on('change', function() {
            carNameEl.val(carEl.find('option:selected').text());
        });

        // set event that sets hidden field with location name
        locEl.on('change', function() {
            locNameEl.val(locEl.find('option:selected').text());
        });
    };

    /**
     * Setup calendars.
     */
    var loadPickers = function() {

        // flatpickr for dates options
        var opts = {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            minDate: new Date().fp_incr(1)
        };
        
        // setup date pickers
        sdCal = startDateEl.flatpickr(opts);
        edCal = endDateEl.flatpickr(opts);

        // end date one day minumum ahead
        var minDays = 1 + trhApp.minDays;
        edCal.set('minDate', new Date().fp_incr(minDays));

        // when start date changes, move end date to minimum
        sdCal.config.onChange.push(function(selectedDates, dateStr, instance) {
            
            var d = new Date(dateStr).fp_incr(minDays);
            edCal.set('minDate', new Date(dateStr).fp_incr(minDays));
            edCal.setDate(d);
        });

        // flatpickr for times options
        opts = {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultDate: trhApp.defaultTime,
            minuteIncrement: 30
        };

        // setup time pickers
        stCal = startTimeEl.flatpickr(opts);
        etCal = endTimeEl.flatpickr(opts);
    }

    /**
     * Load cars.
     */
    var loadCars = function() {

        if (!trhApp.showCars) {
            return;
        }

        carWrapEl.css('display', 'block');

        // get from api
        $.post(trh_ajax_obj.ajax_url, {

            _ajax_nonce: trh_ajax_obj.nonce,
            action: 'therentalshub_get_cars',
        }, function(data) {

            if (Object.hasOwn(data, 'error')) {
                return;
            }

            for (i = 0; i < data.length; i++) {
                
                carEl.append($('<option>', {
                    value: data[i].id,
                    text: data[i].brand + ' ' + data[i].model + ' - ' + data[i].gear
                }));
            }
        });
    };

    /**
     * Load cars.
     */
    var loadLocations = function() {

        if (!trhApp.showLocations) {
            return;
        }

        locWrapEl.css('display', 'block');

        // get from api
        $.post(trh_ajax_obj.ajax_url, {

            _ajax_nonce: trh_ajax_obj.nonce,
            action: 'therentalshub_get_locations',
        }, function(data) {

            if (Object.hasOwn(data, 'error')) {
                return;
            }

            for (i = 0; i < data.length; i++) {
                
                locEl.append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
            }
        });
    };

    /**
     * Show/hide flight number field.
     */
    var loadFlightNr = function() {

        if (!trhApp.showFlightNr) {
            return;
        }

        flightWrapEl.css('display', 'block');
    };

    /**
     * Submit form.
     */
    var submitForm = function() {

        // all needed vars filled?
        if (startDateEl.val() === '' || startTimeEl.val() === '' || endDateEl.val() === '' 
            || endTimeEl.val() === '' || fnameEl.val() === '' || lnameEl.val() === '' || emailEl.val() === '') {

            alert(trhApp.lang.warn_fill_fields);
            return;
        }

        var btn = form.find(':submit');
        var btnHtml = btn.html();

        btn.html(trhApp.lang.please_wait + '...');

        $('#trh-request-failed-alert').css('display', 'none');
        $('#trh-request-success-alert').css('display', 'none');

        // submit request
        $.post(trh_ajax_obj.ajax_url, {

            _ajax_nonce: trh_ajax_obj.nonce,
            action: 'therentalshub_submit_form',
            sd: startDateEl.val(),
            st: startTimeEl.val(),
            ed: endDateEl.val(),
            et: endTimeEl.val(),
            car: carEl.val(),
            loc: locEl.val(),
            fname: fnameEl.val(),
            lname: lnameEl.val(),
            email: emailEl.val(),
            phone: phoneEl.val(),
            notes: notesEl.val(),
            carname: carNameEl.val(),
            locname: locNameEl.val(),
            flightname: flightNameEl.val()
        }, function(data) {

            btn.html(btnHtml);

            if (Object.hasOwn(data, 'error')) {

                $('#trh-request-failed-alert').css('display', 'block');
                $('.trh-error-alert').html(data.error);

                return;
            }

            $('#trh-request-success-alert').css('display', 'block');
        });
    };

    /**
     * Init function.
     */
    var init = function() {

        // load only when on correct page
        if (typeof trhApp === 'undefined') {
            return;
        }

        // load form elements
        loadElements();

        // load datetime pickers
        loadPickers();

        // load cars
        loadCars();

        // load locations
        loadLocations();

        // flight number fiuld
        loadFlightNr();

        // form submit event
        form.on('submit', function(e) {

            e.preventDefault();

            submitForm();
        });
    };

    return {
        init: function() {
            init();
        }
    };
})(jQuery, flatpickr);

jQuery(document).ready(function($) {
    trhRequestForm.init();
});