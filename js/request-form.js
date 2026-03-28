var trhRequestForm = (function($, flatpickr) {

    /**
     * Form elements.
     */
    var startDateEl = null;
    var startTimeEl = null;
    var endDateEl = null;
    var endTimeEl = null;
    var carEl = null;
    var pickLocEl = null;
    var dropLocEl = null;
    var fnameEl = null;
    var lnameEl = null;
    var emailEl = null;
    var phoneEl = null;
    var notesEl =null;
    var carNameEl = null; // is hidden input
    var pickLocNameEl = null; // is hidden input
    var dropLocNameEl = null; // is hidden input
    var flightNameEl = null; // is hidden input

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
        pickLocEl = $('#trhrf_pick_loc');
        dropLocEl = $('#trhrf_drop_loc');
        fnameEl = $('#trhrf_fname');
        lnameEl = $('#trhrf_lname');
        emailEl = $('#trhrf_email');
        phoneEl = $('#trhrf_phone');
        notesEl = $('#trhrf_notes');
        carNameEl = $('#trhrf_car_name');
        pickLocNameEl = $('#trhrf_pick_loc_name');
        dropLocNameEl = $('#trhrf_drop_loc_name');
        flightNameEl = $('#trhrf_flight');

        form = $('#trh-request-form');

        // set event that sets hidden field with car name
        carEl.on('change', function() {
            carNameEl.val(carEl.find('option:selected').text());
        });

        // set events that sets hidden field with location name
        pickLocEl.on('change', function() {
            pickLocNameEl.val(pickLocEl.find('option:selected').text());
        });

        dropLocEl.on('change', function() {
            dropLocNameEl.val(dropLocEl.find('option:selected').text());
        });

        // Trigger change on load to sync hidden fields if values are pre-selected
        carEl.trigger('change');
        pickLocEl.trigger('change');
        dropLocEl.trigger('change');
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
     * Submit form.
     */
    var submitForm = async function() {

        // all needed vars filled?
        if (startDateEl.val() === '' || startTimeEl.val() === '' || endDateEl.val() === '' 
            || endTimeEl.val() === '' || fnameEl.val() === '' || lnameEl.val() === '' || emailEl.val() === '' || phoneEl.val() === '') {

            alert(trhApp.lang.warn_fill_fields);
            return;
        }

        var btn = form.find(':submit');
        var btnHtml = btn.html();

        btn.html(trhApp.lang.please_wait + '...');

        $('#trh-request-failed-alert').css('display', 'none');
        $('#trh-request-success-alert').css('display', 'none');

        try {
            const response = await fetch(trh_ajax_obj.rest_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': trh_ajax_obj.nonce
                },
                body: JSON.stringify({
                    startDate: startDateEl.val(),
                    startTime: startTimeEl.val(),
                    endDate: endDateEl.val(),
                    endTime: endTimeEl.val(),
                    carId: carEl.val() || '0',
                    pickLocId: pickLocEl.val() || '0',
                    dropLocId: dropLocEl.val() || '0',
                    firstName: fnameEl.val(),
                    lastName: lnameEl.val(),
                    email: emailEl.val(),
                    phone: phoneEl.val(),
                    notes: notesEl.val(),
                    carName: carNameEl.val() || '',
                    pickLocName: pickLocNameEl.val() || '',
                    dropLocName: dropLocNameEl.val() || '',
                    flightNumber: flightNameEl.val() || ''
                })
            });

            const data = await response.json();
            btn.html(btnHtml);

            if (!response.ok) {
                $('#trh-request-failed-alert').css('display', 'block');
                $('.trh-error-alert').html(data.message || 'An error occurred.');
                return;
            }

            $('#trh-request-success-alert').css('display', 'block');
            form[0].reset();
        } catch (err) {
        btn.html(btnHtml);
            $('#trh-request-failed-alert').css('display', 'block');
            $('.trh-error-alert').html('Network error, please try again.');
        }
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