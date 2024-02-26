
<div class="container">

    <div class="trh-request-form">
    
        <form class="form" method="post" action="" id="trh-request-form">
            <div class="row mb-4">
                <div class="col-sm-8 mb-3 mb-sm-0">
                    <label for="trhrf_start_date">Pick-up date <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_start_date" id="trhrf_start_date" placeholder="Select date..." required/>
                </div>
                <div class="col-sm-4">
                    <label for="trhrf_start_time">Pick-up time <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_start_time" id="trhrf_start_time" placeholder="Select time..." required/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-8 mb-3 mb-sm-0">
                    <label for="trhrf_end_date">Drop-off date <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_end_date" id="trhrf_end_date" placeholder="Select date..." required/>
                </div>
                <div class="col-sm-4">
                    <label for="trhrf_end_time">Drop-off time <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_end_time" id="trhrf_end_time" placeholder="Select time..." required/>
                </div>
            </div>

            <div class="row mb-4" style="display:none" id="trhrf_car_wrap">
                <div class="col">
                    <label for="trhrf_car">Choose vehicle</label>
                    <select class="trh-input-control" name="trhrf_car" id="trhrf_car">
                        <option value="0"></option>
                    </select>
                </div>
            </div>

            <div class="row mb-4" style="display:none" id="trhrf_location_wrap">
                <div class="col">
                    <label for="trhrf_loc">Choose pick-up location</label>
                    <select class="trh-input-control" name="trhrf_loc" id="trhrf_loc">
                        <option value="0"></option>
                    </select>
                </div>
            </div>

            <div class="row mb-4" style="display:none" id="trhrf_flight_wrap">
                <div class="col">
                    <label for="trhrf_flight">Flight number</label>
                    <input type="text" class="trh-input-control" name="trhrf_flight" id="trhrf_flight"/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="trhrf_fname">First name <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_fname" id="trhrf_fname" required/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="trhrf_lname">Last name <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_lname" id="trhrf_lname" required/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="trhrf_email">Email address <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_email" id="trhrf_email" required/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="trhrf_phone">Phone number</label>
                    <input type="text" class="trh-input-control" name="trhrf_phone" id="trhrf_phone"/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="trhrf_notes">Additional notes</label>
                    <textarea class="trh-input-control" rows="5" name="trhrf_notes" id="trhrf_notes"></textarea>
                </div>
            </div>

            <div class="row mb-4" id="trh-request-failed-alert" role="alert" style="display:none">
                <div class="col">
                    <div class="trh-alert trh-error-alert"></div>
                </div>
            </div>

            <div class="row mb-4" id="trh-request-success-alert" role="alert" style="display:none">
                <div class="col">
                    <div class="trh-alert trh-success-alert">
                        <?=esc_html_e('Thank you! Your request has been submitted. We will contact you shortly with availability. Please check your inbox for our confirmation email.', 'trh');?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <input type="hidden" name="trhrf_car_name" id="trhrf_car_name" value="">
                    <input type="hidden" name="trhrf_loc_name" id="trhrf_loc_name" value="">
                    <button type="submit" class="trh-button">Submit booking request</button>
                </div>
            </div>
        </form>

        <script>
            var trhApp = {
                minDays: <?=$trhMinDays;?>,
                defaultTime: "<?=$trhDefaultTime;?>",
                showCars: <?=$trhShowCars;?>,
                showLocations: <?=$trhShowLocations;?>,
                showFlightNr: <?=$trhShowFlightNr;?>,
            };
        </script>

    </div>
</div>