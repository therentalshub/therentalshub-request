<?php
/**
 * Plugin Name: TheRentalsHub Request
 * Plugin URI: https://www.therentalshub.com
 * Description: Capture booking requests
 * Version: 1.1.8
 * Requires PHP: 8.0
 * Author: The Rentals Hub
 * License: MIT
 * Text Domain: therentalshub-request
 * Domain Path: /languages
 */

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

if (!defined('ABSPATH')) {
    exit;
}

 /**
  * Globals.
  */
const TRHBR_PLUGIN_VERSION = '2.0.0';
const TRHBR_ENVIRONMENT = 'prod';
const TRHBR_PLUGIN_NAME = 'therentalshub-request';
const TRHBR_API_ENDPOINT_DEV = 'http://web-api.vpn.therentalshub.com/requests';
const TRHBR_API_ENDPOINT_PROD = 'https://web-api.therentalshub.com/requests';

/**
 * Updater.
 */
require_once plugin_dir_path(__FILE__).'plugin-update-checker/plugin-update-checker.php';

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/therentalshub/therentalshub-request', 
    __FILE__, 
    TRHBR_PLUGIN_NAME
);

/**
 * Translations loading.
 */
function trh_load_textdomain() {
	load_plugin_textdomain(TRHBR_PLUGIN_NAME, false, dirname(plugin_basename( __FILE__ )).'/languages'); 
}

add_action('init', 'trh_load_textdomain');

/**
 * Register API routes.
 */
add_action('rest_api_init', function () {
   
   register_rest_route('rental/v1', '/booking/submit', [
      'methods'  => 'POST',
      'callback' => 'rest_therentalshub_submit_form',
      'permission_callback' => '__return_true', // Nonce check happens inside the request header
   ]);
});

/**
 * Settings.
 */
function trh_settings_init()
{
   register_setting('trh', 'trh_options');

   add_settings_section(
      'trh_section_req_form_settings',
      __('TheRentalsHub Booking Request Form Options', 'therentalshub-request'),
      'trh_section_req_form_settings_callback',
      'trh'
   );

   add_settings_field(
		'trh_min_booking_period',
      __('Minimum booking period (days)', 'therentalshub-request'),
		'trh_min_booking_period_cb',
		'trh',
		'trh_section_req_form_settings',
		[
         'label_for' => 'trh_min_booking_period',
			'class' => 'trh_row'
      ]
	);

   add_settings_field(
		'trh_default_time',
      __('Default cut-off time', 'therentalshub-request'),
		'trh_default_time_cb',
		'trh',
		'trh_section_req_form_settings',
		[
         'label_for' => 'trh_default_time',
			'class' => 'trh_row'
      ]
	);

   add_settings_field(
		'trh_show_cars',
      __('Show cars selector', 'therentalshub-request'),
		'trh_show_cars_cb',
		'trh',
		'trh_section_req_form_settings',
		[
         'label_for' => 'trh_show_cars',
			'class' => 'trh_row'
      ]
	);

   add_settings_field(
		'trh_show_locations',
      __('Show locations selector', 'therentalshub-request'),
		'trh_show_locations_cb',
		'trh',
		'trh_section_req_form_settings',
		[
         'label_for' => 'trh_show_locations',
			'class' => 'trh_row'
      ]
	);

   add_settings_field(
		'trh_show_flight_nr',
      __('Show flight number field', 'therentalshub-request'),
		'trh_show_flight_nr_cb',
		'trh',
		'trh_section_req_form_settings',
		[
         'label_for' => 'trh_show_flight_nr',
			'class' => 'trh_row'
      ]
	);

   add_settings_field(
		'trh_send_email',
      __('Send confirmation email', 'therentalshub-request'),
		'trh_send_email_cb',
		'trh',
		'trh_section_req_form_settings',
		[
         'label_for' => 'trh_send_email',
			'class' => 'trh_row'
      ]
	);

   add_settings_field(
		'trh_notify_email',
      __('Send confirmation email to', 'therentalshub-request'),
		'trh_notify_email_cb',
		'trh',
		'trh_section_req_form_settings',
		[
         'label_for' => 'trh_notify_email',
			'class' => 'trh_row'
      ]
	);

   add_settings_field(
		'trh_api_key',
      __('API key', 'therentalshub-request'),
		'trh_api_key_cb',
		'trh',
		'trh_section_req_form_settings',
		[
         'label_for' => 'trh_api_key',
			'class' => 'trh_row'
      ]
	);
}

add_action('admin_init', 'trh_settings_init');

function trh_section_req_form_settings_callback($args)
{
   echo '<p id="'.esc_attr($args['id']).'">'.__('Setup request form options and connection to your fleet management account.', 'therentalshub-request').'</p>';
}

function trh_default_time_cb($args)
{
	$options = get_option('trh_options');
   ?>
	<input type="text" 
			id="<?php echo esc_attr( $args['label_for'] ); ?>" 
			name="trh_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
         value="<?=(isset($options[$args['label_for']]) ? $options[$args['label_for']] : '');?>" placeholder="11:00" style="width:100px"/>
	<p class="description">
		<?=__('Default pick-up &amp; drop-off time. Use 1 hour and 30 minutes increments only.', 'therentalshub-request' );?>
	</p>
	<?php
}

function trh_min_booking_period_cb($args)
{
	$options = get_option('trh_options');
   ?>
	<input type="text" 
			id="<?php echo esc_attr( $args['label_for'] ); ?>" 
			name="trh_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
         value="<?=(isset($options[$args['label_for']]) ? $options[$args['label_for']] : '');?>" placeholder="3" style="width:100px"/>
	<p class="description">
		<?=__('Minimum booking period in days.', 'therentalshub-request' );?>
	</p>
	<?php
}

function trh_show_cars_cb($args)
{
	$options = get_option('trh_options');
   ?>
	<select
			id="<?php echo esc_attr( $args['label_for'] ); ?>" 
			name="trh_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
		<option value="yes" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'yes', false ) ) : ( '' ); ?>>
			<?=__('Yes', 'therentalshub-request');?>
		</option>
 		<option value="no" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'no', false ) ) : ( '' ); ?>>
			<?=__('No', 'therentalshub-request');?>
		</option>
	</select>
	<p class="description">
		<?=__('Displays a list with cars from your fleet management account for selection.', 'therentalshub-request');?>
	</p>
	<?php
}

function trh_show_locations_cb($args)
{
	$options = get_option('trh_options');
   ?>
	<select
			id="<?php echo esc_attr( $args['label_for'] ); ?>" 
			name="trh_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
		<option value="yes" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'yes', false ) ) : ( '' ); ?>>
			<?=__('Yes', 'therentalshub-request');?>
		</option>
 		<option value="no" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'no', false ) ) : ( '' ); ?>>
			<?=__('No', 'therentalshub-request');?>
		</option>
	</select>
	<p class="description">
		<?=__('Displays a list with pick-up locations from your fleet management account for selection.', 'therentalshub-request');?>
	</p>
	<?php
}

function trh_show_flight_nr_cb($args)
{
	$options = get_option('trh_options');
   ?>
	<select
			id="<?php echo esc_attr( $args['label_for'] ); ?>" 
			name="trh_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
		<option value="yes" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'yes', false ) ) : ( '' ); ?>>
			<?=__('Yes', 'therentalshub-request');?>
		</option>
 		<option value="no" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'no', false ) ) : ( '' ); ?>>
			<?=__('No', 'therentalshub-request');?>
		</option>
	</select>
	<p class="description">
		<?=__('Displays a field to capture customer\'s filght number.', 'therentalshub-request');?>
	</p>
	<?php
}

function trh_send_email_cb($args)
{
	$options = get_option('trh_options');
   ?>
	<select
			id="<?php echo esc_attr( $args['label_for'] ); ?>" 
			name="trh_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
		<option value="yes" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'yes', false ) ) : ( '' ); ?>>
			<?=__('Yes', 'therentalshub-request');?>
		</option>
 		<option value="no" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'no', false ) ) : ( '' ); ?>>
			<?=__('No', 'therentalshub-request');?>
		</option>
	</select>
	<p class="description">
		<?=__('Send a confirmation email with the request details.', 'therentalshub-request');?>
	</p>
	<?php
}

function trh_notify_email_cb($args)
{
   $options = get_option('trh_options');
   ?>
	<input type="text" 
			id="<?php echo esc_attr( $args['label_for'] ); ?>" 
			name="trh_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
         value="<?=(isset($options[$args['label_for']]) ? $options[$args['label_for']] : '');?>" style="width:350px"/>
	<p class="description">
		<?=__('You will be notified to this email when a requests is submitted.', 'therentalshub-request' );?>
	</p>
	<?php
}

function trh_api_key_cb($args)
{
	$options = get_option('trh_options');
   ?>
	<input type="text" 
			id="<?php echo esc_attr( $args['label_for'] ); ?>" 
			name="trh_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
         value="<?=(isset($options[$args['label_for']]) ? $options[$args['label_for']] : '');?>" style="width:350px"/>
	<p class="description">
		<?=__('Your fleet management account API key.', 'therentalshub-request' );?>
	</p>
	<?php
}

function trh_options_page()
{
   add_menu_page(
      'TheRentalsHub',
      'TRH Bookings',
      'manage_options',
      'trh-request-form-options',
      'trh_options_page_html'
   );
}

add_action('admin_menu', 'trh_options_page');

function trh_options_page_html()
{
   if (!current_user_can('manage_options')) {
		return;
	}

   if (isset($_GET['settings-updated'])) {
		add_settings_error('trh_messages', 'trh_message', __('Settings Saved', 'therentalshub-request'), 'updated');
	}

   settings_errors('trh_messages');
   ?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields('trh');
			do_settings_sections('trh');
			submit_button('Save Settings');
			?>
		</form>
	</div>
	<?php
}

/**
 * The request form.
*/
add_action('wp_enqueue_scripts', function () {
   // register css
   wp_register_style('therentalshub-request', plugins_url(TRHBR_PLUGIN_NAME . '/css/request-form.css'), [], TRHBR_PLUGIN_VERSION);
   wp_register_style('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css');

   // register js
   wp_register_script('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js', ['jquery'], [], TRHBR_PLUGIN_VERSION, ['in_footer' => true]);

   $requestJs = (TRHBR_ENVIRONMENT == 'dev') ? 'request-form' : 'request-form-fL7v3ckm';

   wp_register_script('therentalshub-request', plugins_url(TRHBR_PLUGIN_NAME . '/js/' . $requestJs . '.js'), ['jquery', 'flatpickr'], [], TRHBR_PLUGIN_VERSION, ['strategy' => 'defer', 'in_footer' => true]);

   wp_localize_script(
      'therentalshub-request',
      'trh_ajax_obj',
      [
         'rest_url' => get_rest_url(null, 'rental/v1/booking/submit'),
         'nonce'    => wp_create_nonce('wp_rest'),
      ]
   );
});

function trh_request_form_shortcode()
{
   // enqueue assets only when shortcode is used
   wp_enqueue_style('flatpickr');
   wp_enqueue_style('therentalshub-request');
   wp_enqueue_script('therentalshub-request');

   $options = get_option('trh_options');

   $trhMinDays = 1;

   $trhDefaultTime = '11:00';

   $trhShowCars = 'false';

   $trhCarsByGroup = 'true';

   $trhShowLocations = 'false';

   $trhShowFlightNr = 'false';

   if (isset($options['trh_min_booking_period'])) {
      $trhMinDays = (int) $options['trh_min_booking_period'];
   }

   if (isset($options['trh_default_time'])) {
      if (preg_match('/^[0-9]{2}:[0-9]{2}$/', $options['trh_default_time'])) {
         $trhDefaultTime = $options['trh_default_time'];
      }
   }

   if (isset($options['trh_show_cars'])) {
      $trhShowCars = $options['trh_show_cars'] == 'yes' ? 'true' : 'false';
   }

   if (isset($options['trh_show_locations'])) {
      $trhShowLocations = $options['trh_show_locations'] == 'yes' ? 'true' : 'false';
   }

   if (isset($options['trh_show_flight_nr'])) {
      $trhShowFlightNr = $options['trh_show_flight_nr'] == 'yes' ? 'true' : 'false';
   }

   $trhSelectedCarId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

   // Load data in backend
   $cars = [];
   if ($trhShowCars === 'true') {
      $cars_response = trh_get_cars_list();
      $cars = !is_wp_error($cars_response) ? $cars_response->get_data() : [];
   }

   $locations = [];
   if ($trhShowLocations === 'true') {
      $locations_response = trh_get_locations_list();
      $locations = !is_wp_error($locations_response) ? $locations_response->get_data() : [];
   }

   ob_start();
   require 'request-form.php';
   $html = ob_get_contents();
   ob_end_clean();

   return $html;
}

add_shortcode('trh_request_form', 'trh_request_form_shortcode');

/**
 * Fleet listing page.
 */
add_action('wp_enqueue_scripts', function() {
   wp_register_style('therentalshub-fleet-listing', plugins_url(TRHBR_PLUGIN_NAME.'/css/fleet-listing.css'), [], TRHBR_PLUGIN_VERSION);
});

function trh_request_fleet_listing_shortcode()
{
   // enqueue css
   wp_enqueue_style('therentalshub-fleet-listing');

   // get data
   $categories = trh_get_categories_listing()->get_data();
   $fleet = trh_get_fleet_listing()->get_data();

   // Determine which category names are present in the fleet
   $active_category_names = [];
   if (!empty($fleet)) {
       foreach ($fleet as $car) {
           $active_category_names[] = $car->category_name;
       }
       $active_category_names = array_unique($active_category_names);
   }

   ob_start();
   ?>
   <div class="trhrf-fleet-app">
      <!-- Filters -->
      <div class="trhrf-filter-container" id="trhrf-filters">
         <button class="trhrf-category-pill trhrf-active" onclick="trhrfUpdateFilter(this, 0)">All Cars</button>
         <?php if (!empty($categories)) : ?>
            <?php foreach ($categories as $category) : ?>
               <?php if (in_array($category->name, $active_category_names)) : ?>
                  <button class="trhrf-category-pill" onclick="trhrfUpdateFilter(this, <?=(int) $car->category_id;?>)"><?php echo esc_html($category->name); ?></button>
               <?php endif; ?>
         <?php endforeach; ?>
         <?php endif; ?>
      </div>

      <!-- Fleet Container: Pre-rendered for SEO -->
      <div id="trhrf-fleet-container">
         <?php if (!empty($fleet)) : ?>
               <?php foreach ($fleet as $car) : ?>
                  <div class="trhrf-car-card" data-group="<?=(int) $car->category_id;?>">
                     <div class="trhrf-car-img-wrap">
                           <img src="<?php echo esc_url(str_replace('{{w}}', '250', $car->image)); ?>" loading="lazy" alt="<?php echo esc_attr($car->brand_name.' '.$car->model_name); ?>">
                     </div>
                     <div class="trhrf-car-info">
                           <div class="trhrf-badge-row">
                              <span class="trhrf-badge trhrf-badge-group"><?php echo esc_html($car->category_name); ?></span>
                              <?php if (isset($car->price) && $car->price < 100) : ?>
                                 <span class="trhrf-badge trhrf-badge-promo">Best Value</span>
                              <?php endif; ?>
                           </div>
                           <h2 class="trhrf-car-name"><?php echo esc_html($car->brand_name . ' ' . $car->model_name); ?> <span>or similar</span></h2>
                           <div class="trhrf-specs-grid">
                              <div class="trhrf-spec-item">👥 <?php echo esc_html($car->seats); ?> Seats</div>
                              <div class="trhrf-spec-item">⚙️ <?php echo esc_html($car->transmission_name); ?></div>
                              <div class="trhrf-spec-item">🧳 <?php echo esc_html($car->large_bags); ?> Bag(s)</div>
                              <?php if ($car->has_clima == "1") : ?>
                                 <div class="trhrf-spec-item">❄️ A/C</div>
                              <?php endif; ?>
                              <div class="trhrf-spec-item">⛽ <?php echo esc_html($car->fuel_name); ?></div>
                              <div class="trhrf-spec-item">📍 Unlimited KM</div>
                           </div>
                     </div>
                     <div class="trhrf-car-pricing">
                           <div>
                              <p class="trhrf-price-value">Custom Quote</p>
                           </div>
                           <button class="trhrf-btn-book" onclick="location.href='/request-booking/?id=<?php echo esc_attr($car->car_id); ?>'">Inquire Now</button>
                     </div>
                  </div>
               <?php endforeach; ?>
         <?php else : ?>
               <p style="text-align:center; padding:60px; color:#999;">No vehicles currently available.</p>
         <?php endif; ?>
      </div>
   </div>

   <script>
      function trhrfUpdateFilter(btn, filter) {
          document.querySelectorAll('.trhrf-category-pill').forEach(b => b.classList.remove('trhrf-active'));
          btn.classList.add('trhrf-active');
          
          const container = document.getElementById('trhrf-fleet-container');
          const cards = container.querySelectorAll('.trhrf-car-card');
          
          container.style.opacity = '0.5';
                
          setTimeout(() => {
             cards.forEach(card => {
                const group = card.getAttribute('data-group');
                if (filter === 0 || group === filter.toString()) {
                   card.style.display = 'grid';
                } else {
                   card.style.display = 'none';
                }
             });
             container.style.opacity = '1';
          }, 100);
      }
   </script>
   <?php
   return ob_get_clean();
}

add_shortcode('trh_request_fleet', 'trh_request_fleet_listing_shortcode');

/**
 * Handler for getting cars.
 */
function trh_get_cars_list()
{
   // transients to bypass external API latency
   $transient_key = 'trhrf_cars_list_cache';

   $cached_data = get_transient($transient_key);
   
   if (false !== $cached_data) {
      return new WP_REST_Response($cached_data, 200);
   }

   $options = get_option('trh_options');
   $apiKey = isset($options['trh_api_key']) ? $options['trh_api_key'] : '';

   $response = wp_remote_get((TRHBR_ENVIRONMENT == 'dev' ? TRHBR_API_ENDPOINT_DEV : TRHBR_API_ENDPOINT_PROD).'/cars/group', [
      'timeout' => 10,
      'headers' => [
         'Content-Type' => 'application/json',
         'X-Tenant-Key' => $apiKey,
      ]
   ]);

   if (is_wp_error($response)) {
      return new WP_Error('api_error', 'External API unreachable', ['status' => 500]);
   }
   
   $data = json_decode(wp_remote_retrieve_body($response));
   
   set_transient($transient_key, $data, 3600);

   return new WP_REST_Response($data, 200);
}

/**
 * Handler for getting locations.
 */
function trh_get_locations_list()
{
   // transients to bypass external API latency
   $transient_key = 'trhrf_locations_list_cache';

   $cached_data = get_transient($transient_key);
   
   if (false !== $cached_data) {
      return new WP_REST_Response($cached_data, 200);
   }

   $options = get_option('trh_options');
   $apiKey = isset($options['trh_api_key']) ? $options['trh_api_key'] : '';

   $response = wp_remote_get((TRHBR_ENVIRONMENT == 'dev' ? TRHBR_API_ENDPOINT_DEV : TRHBR_API_ENDPOINT_PROD).'/locations', [
      'timeout' => 10,
      'headers' => [
         'Content-Type' => 'application/json',
         'X-Tenant-Key' => $apiKey,
      ]
   ]);

   if (is_wp_error($response)) {
      return new WP_Error('api_error', 'External API unreachable', ['status' => 500]);
   }
   
   $data = json_decode(wp_remote_retrieve_body($response));
   
   set_transient($transient_key, $data, 3600);

   return new WP_REST_Response($data, 200);
}

/**
 * Handler for getting cars categories.
 */
function trh_get_categories_listing()
{
   // transients to bypass external API latency
   $transient_key = 'trhrf_categories_list_cache';

   $cached_data = get_transient($transient_key);
   
   if (false !== $cached_data) {
      return new WP_REST_Response($cached_data, 200);
   }

   $options = get_option('trh_options');
   $apiKey = isset($options['trh_api_key']) ? $options['trh_api_key'] : '';

   $response = wp_remote_get((TRHBR_ENVIRONMENT == 'dev' ? TRHBR_API_ENDPOINT_DEV : TRHBR_API_ENDPOINT_PROD).'/cars/categories', [
      'timeout' => 10,
      'headers' => [
         'Content-Type' => 'application/json',
         'X-Tenant-Key' => $apiKey,
      ]
   ]);

   if (is_wp_error($response)) {
      return new WP_Error('api_error', 'External API unreachable', ['status' => 500]);
   }
   
   $data = json_decode(wp_remote_retrieve_body($response));
   
   set_transient($transient_key, $data, 3600);

   return new WP_REST_Response($data, 200);
}

/**
 * Handler for getting cars.
 */
function trh_get_fleet_listing()
{
   // transients to bypass external API latency
   $transient_key = 'trhrf_fleet_list_cache';

   $cached_data = get_transient($transient_key);
   
   if (false !== $cached_data) {
      return new WP_REST_Response($cached_data, 200);
   }

   $options = get_option('trh_options');
   $apiKey = isset($options['trh_api_key']) ? $options['trh_api_key'] : '';

   $response = wp_remote_get((TRHBR_ENVIRONMENT == 'dev' ? TRHBR_API_ENDPOINT_DEV : TRHBR_API_ENDPOINT_PROD).'/cars/fleet', [
      'timeout' => 10,
      'headers' => [
         'Content-Type' => 'application/json',
         'X-Tenant-Key' => $apiKey,
      ]
   ]);

   if (is_wp_error($response)) {
      return new WP_Error('api_error', 'External API unreachable', ['status' => 500]);
   }
   
   $data = json_decode(wp_remote_retrieve_body($response));
   
   set_transient($transient_key, $data, 3600);

   return new WP_REST_Response($data, 200);
}

/**
 * REST API handler for submitted form.
 */
function rest_therentalshub_submit_form(WP_REST_Request $request)
{
   $params = (object) $request->get_params();
   
   $result = processRequest($params);

   if($result !== '') {
      return new WP_Error('submit_error', $result, ['status' => 400]);
   }

   return new WP_REST_Response(['msg' => 'OK'], 200);
}

/** 
 * Processes the request
 */
function processRequest($vars)
{
   // check for missing vars
   if (!isset($vars->startDate) || !isset($vars->startTime) || !isset($vars->endDate) || !isset($vars->endTime) 
      || !isset($vars->carId) || !isset($vars->pickLocId) || !isset($vars->dropLocId) || !isset($vars->firstName) || !isset($vars->lastName) 
         || !isset($vars->email) || !isset($vars->phone) || !isset($vars->notes) 
            || !isset($vars->carName) || !isset($vars->pickLocName) || !isset($vars->dropLocName) || !isset($vars->flightNumber)) {

      return __('Missing vars, cannot continue', 'trh');
   }

   // validate email at least
   if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/i', $vars->email)) {
      
      return __('Invalid email address provided.', 'trh');
   }

   // get needed options
   $options = get_option('trh_options');
   $apiKey = $options['trh_api_key'];
   $notifyUser = $options['trh_send_email'];
   $notifyEmail = $options['trh_notify_email'];
   $options = null;

   // Map semantic internal names back to the External API names
   $api_payload = [
      'sd'         => $vars->startDate,
      'st'         => $vars->startTime,
      'ed'         => $vars->endDate,
      'et'         => $vars->endTime,
      'car'        => $vars->carId,
      'pick'       => $vars->pickLocId,
      'drop'       => $vars->dropLocId,
      'fname'      => $vars->firstName,
      'lname'      => $vars->lastName,
      'email'      => $vars->email,
      'phone'      => $vars->phone,
      'notes'      => $vars->notes,
      'carname'    => $vars->carName,
      'plocname'   => $vars->pickLocName,
      'dlocname'   => $vars->dropLocName,
      'flightname' => $vars->flightNumber,
   ];

   $json = json_encode($api_payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

   // send to api
   $response = wp_remote_post((TRHBR_ENVIRONMENT == 'dev' ? TRHBR_API_ENDPOINT_DEV : TRHBR_API_ENDPOINT_PROD), [
      'headers' => [
         'Content-Type' => 'application/json',
         'X-Tenant-Key' => $apiKey
      ],
      'body' => $json
   ]);

   if ((int) $response['response']['code'] != 201) {

      $resultJson = json_decode($response['body'], false);
      return $resultJson !== null ? $resultJson->error : __('An error occured, please try again.', 'trh');
   }

   // send email to user
   if ($notifyUser == 'yes') {

      wp_mail($vars->email, __('Your booking request confirmation', 'trh'), emailTemplate($api_payload), [
         'Content-Type: text/html; charset=UTF-8'
      ]);
   }

   // send email to admin
   if ($notifyEmail != '') {

      if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/i', $notifyEmail)) {

         wp_mail($notifyEmail, __('New booking request', 'trh'), emailTemplateAdmin($api_payload), [
            'Content-Type: text/html; charset=UTF-8',
            'Reply-To: '.trim(strip_tags($vars->firstName)).' '.trim(strip_tags($vars->lastName)).' <'.$vars->email.'>'
         ]);
      }
   }

   // done
   return '';
}

/**
 * Creates email templates.
 */
function emailTemplate($vars)
{
   ob_start();
   require 'email-template.php';
   $html = ob_get_contents();
   ob_end_clean();

   return $html;
}

function emailTemplateAdmin($vars)
{
   ob_start();
   require 'email-template-admin.php';
   $html = ob_get_contents();
   ob_end_clean();

   return $html;
}