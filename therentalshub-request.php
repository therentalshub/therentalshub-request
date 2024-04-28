<?php
/**
 * Plugin Name: TheRentalsHub Request
 * Plugin URI: https://www.therentalshub.com
 * Description: Capture booking requests
 * Version: 1.0.8
 * Requires PHP: 8.0
 * Author: The Rentals Hub
 * License: MIT
 * Text Domain: therentalshub-request
 * Domain Path: /languages
 */

 /**
  * Globals.
  */
const TRHBR_ENVIRONMENT = 'prod';
const TRHBR_PLUGIN_NAME = 'therentalshub-request';
const TRHBR_NONCE_CONTEXT = 'XVGBkdV8tL';
const TRHBR_API_ENDPOINT_DEV = 'http://fleet-haproxy-public:9015/requests';
const TRHBR_API_ENDPOINT_PROD = 'https://web-api.therentalshub.com/requests';

/**
 * Settings.
 */
function trh_settings_init()
{
   register_setting('trh', 'trh_options');

   add_settings_section(
      'trh_section_req_form_settings',
      __('TheRentalsHub Booking Request Form Options', 'trh'),
      'trh_section_req_form_settings_callback',
      'trh'
   );

   add_settings_field(
		'trh_min_booking_period',
      __('Minimum booking period (days)', 'trh'),
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
      __('Default cut-off time', 'trh'),
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
      __('Show cars selector', 'trh'),
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
      __('Show locations selector', 'trh'),
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
      __('Show flight number field', 'trh'),
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
      __('Send confirmation email', 'trh'),
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
      __('Send confirmation email to', 'trh'),
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
      __('API key', 'trh'),
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
   echo '<p id="'.esc_attr($args['id']).'">'.esc_html_e('Setup request form options and connection to your fleet management account.', 'trh').'</p>';
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
		<?php esc_html_e('Default pick-up &amp; drop-off time. Use 1 hour and 30 minutes increments only.', 'trh' ); ?>
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
		<?php esc_html_e('Minimum booking period in days.', 'trh' ); ?>
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
			<?php esc_html_e('Yes', 'trh'); ?>
		</option>
 		<option value="no" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'no', false ) ) : ( '' ); ?>>
			<?php esc_html_e('No', 'trh'); ?>
		</option>
	</select>
	<p class="description">
		<?php esc_html_e('Displays a list with cars from your fleet management account for selection.', 'trh'); ?>
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
			<?php esc_html_e('Yes', 'trh'); ?>
		</option>
 		<option value="no" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'no', false ) ) : ( '' ); ?>>
			<?php esc_html_e('No', 'trh'); ?>
		</option>
	</select>
	<p class="description">
		<?php esc_html_e('Displays a list with pick-up locations from your fleet management account for selection.', 'trh'); ?>
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
			<?php esc_html_e('Yes', 'trh'); ?>
		</option>
 		<option value="no" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'no', false ) ) : ( '' ); ?>>
			<?php esc_html_e('No', 'trh'); ?>
		</option>
	</select>
	<p class="description">
		<?php esc_html_e('Displays a field to capture customer\'s filght number.', 'trh'); ?>
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
			<?php esc_html_e('Yes', 'trh'); ?>
		</option>
 		<option value="no" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'no', false ) ) : ( '' ); ?>>
			<?php esc_html_e('No', 'trh'); ?>
		</option>
	</select>
	<p class="description">
		<?php esc_html_e('Send a confirmation email with the request details.', 'trh'); ?>
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
		<?php esc_html_e('You will be notified to this email when a requests is submitted.', 'trh' ); ?>
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
		<?php esc_html_e('Your fleet management account API key.', 'trh' ); ?>
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
		add_settings_error('trh_messages', 'trh_message', __('Settings Saved', 'trh'), 'updated');
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
 * CSS and Javascript for request form.
 */
function trh_register_plugin_scripts()
{
   // css
   wp_enqueue_style('therentalshub-request', plugins_url(TRHBR_PLUGIN_NAME.'/css/request-form.css'));

   wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css');

   wp_enqueue_style('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css');
   
   // js
   wp_enqueue_script('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js', ['jquery'], false, ['in_footer' => true]);
   
   $requestJs = 'request-form';

   if (TRHBR_ENVIRONMENT != 'dev') {
      $requestJs = 'request-form-d7Yewk3a';
   }

   wp_enqueue_script('therentalshub-request', plugins_url(TRHBR_PLUGIN_NAME.'/js/'.$requestJs.'.js'), ['jquery', 'flatpickr'], false, ['strategy' => 'defer', 'in_footer' => true]);

   $nonce = wp_create_nonce(TRHBR_NONCE_CONTEXT);

   wp_localize_script(
      'therentalshub-request',
      'trh_ajax_obj',
      [
         'ajax_url' => admin_url('admin-ajax.php'),
         'nonce' => $nonce,
      ]
   );
}

add_action('wp_enqueue_scripts', 'trh_register_plugin_scripts');

/**
 * The request form.
*/
function trh_request_form_shortcode()
{
   $options = get_option('trh_options');

   $trhMinDays = 1;

   $trhDefaultTime = '11:00';

   $trhShowCars = 'false';

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

   ob_start();
   require 'request-form.php';
   $html = ob_get_contents();
   ob_end_clean();

   return $html;
}

add_shortcode('trh_request_form', 'trh_request_form_shortcode');

/**
 * AJAX handler for getting cars.
 */
function ajax_therentalshub_get_cars()
{
   // generic error
   $error = __('Request form is currently not available', 'trh');

   header('Content-Type: application/json', true);

   // get api key
   $options = get_option('trh_options');
   $apiKey = $options['trh_api_key'];
   $options = null;

   if (check_ajax_referer(TRHBR_NONCE_CONTEXT) === false) {

      echo '{"error":"'.$error.'"}';
      
      wp_die();
   }

   // request cars
   $response = wp_remote_get((TRHBR_ENVIRONMENT == 'dev' ? TRHBR_API_ENDPOINT_DEV : TRHBR_API_ENDPOINT_PROD).'/cars', [
      'headers' => [
         'Content-Type' => 'application/json',
         'X-Api-Key' => $apiKey,
      ]
   ]);

   if ((int) $response['response']['code'] != 200) {

      echo '{"error":"'.$error.'"}';

      wp_die();
   }

   echo $response['body'];

   wp_die();
}

/**
 * AJAX handler for getting locations.
 */
function ajax_therentalshub_get_locations()
{
   // generic error
   $error = __('Request form is currently not available', 'trh');

   header('Content-Type: application/json', true);

   // get api key
   $options = get_option('trh_options');
   $apiKey = $options['trh_api_key'];
   $options = null;

   if (check_ajax_referer(TRHBR_NONCE_CONTEXT) === false) {

      echo '{"error":"'.$error.'"}';
      
      wp_die();
   }

   // request cars
   $response = wp_remote_get((TRHBR_ENVIRONMENT == 'dev' ? TRHBR_API_ENDPOINT_DEV : TRHBR_API_ENDPOINT_PROD).'/locations', [
      'headers' => [
         'Content-Type' => 'application/json',
         'X-Api-Key' => $apiKey,
      ]
   ]);

   if ((int) $response['response']['code'] != 200) {

      echo '{"error":"'.$error.'"}';

      wp_die();
   }

   echo $response['body'];

   wp_die();
}

/**
 * AJAX handler for submitted form.
 */
function ajax_therentalshub_submit_form()
{
   // generic error
   $error = __('Request registration is currently not available', 'trh');

   header('Content-Type: application/json', true);

   if (check_ajax_referer(TRHBR_NONCE_CONTEXT) === false) {

      echo '{"error":"'.$error.'"}';
      
      wp_die();
   }

   if(($result = processRequest((object) $_POST)) != '') {

      echo '{"error":"'.$result.'"}';

      wp_die();
   }

   echo '{"msg":"OK"}';

   wp_die();
}

if ( is_admin() ) {

   add_action('wp_ajax_nopriv_therentalshub_get_cars', 'ajax_therentalshub_get_cars');
   add_action('wp_ajax_therentalshub_get_cars', 'ajax_therentalshub_get_cars');

   add_action('wp_ajax_nopriv_therentalshub_get_locations', 'ajax_therentalshub_get_locations');
   add_action('wp_ajax_therentalshub_get_locations', 'ajax_therentalshub_get_locations');

   add_action('wp_ajax_nopriv_therentalshub_submit_form', 'ajax_therentalshub_submit_form');
   add_action('wp_ajax_therentalshub_submit_form', 'ajax_therentalshub_submit_form');
}

/** 
 * Processes the request
 */
function processRequest($vars)
{
   // check for missing vars
   if (!isset($vars->sd) || !isset($vars->st) || !isset($vars->ed) || !isset($vars->et) 
      || !isset($vars->car) || !isset($vars->loc) || !isset($vars->fname) || !isset($vars->lname) 
         || !isset($vars->email) || !isset($vars->phone) || !isset($vars->notes) 
            || !isset($vars->carname) || !isset($vars->locname) || !isset($vars->flightname)) {

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

   // vars to json
   $json = json_encode($vars, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

   // send to api
   $response = wp_remote_post((TRHBR_ENVIRONMENT == 'dev' ? TRHBR_API_ENDPOINT_DEV : TRHBR_API_ENDPOINT_PROD), [
      'headers' => [
         'Content-Type' => 'application/json',
         'X-Api-Key' => $apiKey
      ],
      'body' => $json
   ]);

   if ((int) $response['response']['code'] != 201) {

      $resultJson = json_decode($response['body'], false);
      return $resultJson !== null ? $resultJson->error : __('An error occured, please try again.', 'trh');
   }

   // send email to user
   if ($notifyUser == 'yes') {

      wp_mail($vars->email, __('Your booking request confirmation', 'trh'), emailTemplate($vars), [
         'Content-Type: text/html; charset=UTF-8'
      ]);
   }

   // send email to admin
   if ($notifyEmail != '') {

      if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/i', $notifyEmail)) {

         wp_mail($notifyEmail, __('New booking request', 'trh'), emailTemplateAdmin($vars), [
            'Content-Type: text/html; charset=UTF-8'
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