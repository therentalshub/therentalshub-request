
<div class="container">

    <div class="trh-request-form">
    
        <form class="form" method="post" action="" id="trh-request-form">
            <div class="row mb-4">
                <div class="col-sm-8 mb-3 mb-sm-0">
                    <label for="trhrf_start_date"><?=__('Pick-up date', 'therentalshub-request');?> <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_start_date" id="trhrf_start_date" placeholder="<?=__('Select date', 'therentalshub-request');?>..." required/>
                </div>
                <div class="col-sm-4">
                    <label for="trhrf_start_time"><?=__('Pick-up time', 'therentalshub-request');?> <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_start_time" id="trhrf_start_time" placeholder="<?=__('Select time', 'therentalshub-request');?>..." required/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-8 mb-3 mb-sm-0">
                    <label for="trhrf_end_date"><?=__('Drop-off date', 'therentalshub-request');?> <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_end_date" id="trhrf_end_date" placeholder="<?=__('Select date', 'therentalshub-request');?>..." required/>
                </div>
                <div class="col-sm-4">
                    <label for="trhrf_end_time"><?=__('Drop-off time', 'therentalshub-request');?> <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_end_time" id="trhrf_end_time" placeholder="<?=__('Select time', 'therentalshub-request');?>..." required/>
                </div>
            </div>

            <div class="row mb-4" style="display:none" id="trhrf_car_wrap">
                <div class="col">
                    <label for="trhrf_car"><?=__('Choose vehicle', 'therentalshub-request');?></label>
                    <select class="trh-input-control" name="trhrf_car" id="trhrf_car">
                        <option value="0"></option>
                    </select>
                </div>
            </div>

            <div class="row mb-4" style="display:none" id="trhrf_location_wrap">
                <div class="col">
                    <label for="trhrf_loc"><?=__('Choose pick-up location', 'therentalshub-request');?></label>
                    <select class="trh-input-control" name="trhrf_loc" id="trhrf_loc">
                        <option value="0"></option>
                    </select>
                </div>
            </div>

            <div class="row mb-4" style="display:none" id="trhrf_flight_wrap">
                <div class="col">
                    <label for="trhrf_flight"><?=__('Flight number', 'therentalshub-request');?></label>
                    <input type="text" class="trh-input-control" name="trhrf_flight" id="trhrf_flight"/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="trhrf_fname"><?=__('First name', 'therentalshub-request');?> <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_fname" id="trhrf_fname" required/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="trhrf_lname"><?=__('Last name', 'therentalshub-request');?> <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_lname" id="trhrf_lname" required/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="trhrf_email"><?=__('Email address', 'therentalshub-request');?> <span class="trh-required">*</span></label>
                    <input type="text" class="trh-input-control" name="trhrf_email" id="trhrf_email" required/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="trhrf_phone"><?=__('Phone number', 'therentalshub-request');?></label>
                    <input type="text" class="trh-input-control" name="trhrf_phone" id="trhrf_phone"/>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <label for="trhrf_notes"><?=__('Additional notes', 'therentalshub-request');?></label>
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
                        <?=__('Thank you! Your request has been submitted. We will contact you shortly with availability. Please check your inbox for our confirmation email.', 'therentalshub-request');?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <input type="hidden" name="trhrf_car_name" id="trhrf_car_name" value="">
                    <input type="hidden" name="trhrf_loc_name" id="trhrf_loc_name" value="">
                    <button type="submit" class="trh-button"><?=__('Submit booking request', 'therentalshub-request');?></button>
                </div>
            </div>
        </form>

        <script>
            var trhApp = {
                minDays: <?=$trhMinDays;?>,
                defaultTime: "<?=$trhDefaultTime;?>",
                showCars: <?=$trhShowCars;?>,
                carsByGroup: <?=$trhCarsByGroup;?>,
                showLocations: <?=$trhShowLocations;?>,
                showFlightNr: <?=$trhShowFlightNr;?>,
                lang: {
                    warn_fill_fields: "<?=__('Please fill all required (with the asterisk) fields.', 'therentalshub-request');?>",
                    please_wait: "<?=__('Please wait', 'therentalshub-request');?>"
                }
            };
        </script>

    </div>
</div>