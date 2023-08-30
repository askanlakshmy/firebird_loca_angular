<?php
// Add custom Theme Functions here

// Shipping Calculator

// add_filter( 'woocommerce_shipping_calculator_enable_city', '__return_false' );

add_filter( 'woocommerce_shipping_calculator_enable_postcode', '__return_false' );

// add_filter( 'woocommerce_shipping_calculator_enable_state', '__return_false' );

/* --------------------------------------------------------------------------------------- */

/**
 * @snippet       Display Special contact form for "POA" Product
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.8
 * @customised by Masa - Two Moons Consulting
 */
/*
wc_enqueue_js( "
   $('#trigger_cf').on('click', function(){
      if ( $(this).text() == ' Request Quote ' ) {
         title = document.title;
         $('#product_inq').css('display','block');
         $('input[name=\'your-subject\']').val(title);
         $('#trigger_cf').html('Close');
      } else {
         $('#product_inq').hide();
         $('#trigger_cf').html(' Request Quote ');
      }
   });
" );
*/

// global $website_mode = "devel";

/* --------------------------------------------------------------------------------------- */

date_default_timezone_set("Australia/Perth");

/* --------------------------------------------------------------------------------------- */

/*
  Log file under theme folder
  2022.01.17
  Two Moons Consulting Masa
*/

function tm_log($log_msg)
{
    $log_filename = get_stylesheet_directory()."/log";
    if (!file_exists($log_filename))
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    $loghead = PHP_EOL."*****".date("F j, Y, g:i a").PHP_EOL;
    $log_msg = $loghead.$log_msg;
    file_put_contents($log_file_data, $log_msg. "\n", FILE_APPEND);
}


/* --------------------------------------------------------------------------------------- */

/*
  Shipping control
  2022.01.17
  Two Moons Consulting Masa
  This function let free shipping if all the products in the cart are small-products.
*/

function shipping_control( $rates, $package )
{
    $local = [];

    // Check Weight
    $weight = WC()->cart->get_cart_contents_weight();
    tm_log('weight:'.$weight."kg");

    // Check the cart is including Ponds
    $small_products = false;
    foreach( $package['contents'] as $item )
    {
      $product = $item['data'];
      $shipping_class = $product->get_shipping_class();
      if( $shipping_class == 'small-products' )
      {
        $small_products = true;
      }else{
        $small_products = false;
        break;
      }
    }

    tm_log('small_products:'.$small_products);

    // Check zone
    // Get cart shipping packages
    $shipping_packages =  WC()->cart->get_shipping_packages();

    // Get the WC_Shipping_Zones instance object for the first package
    $shipping_zone = wc_get_shipping_zone( reset( $shipping_packages ) );

    $zone_id   = $shipping_zone->get_id(); // Get the zone ID
    $zone_name = $shipping_zone->get_zone_name(); // Get the zone name

    // Testing output
    tm_log('Zone id: ' . $zone_id . ' | Zone name: ' . $zone_name);
    // Zone 1: Metro, Zone 2: Western Australia, Zone 3: Australia

    foreach( $package['contents'] as $item )
    {
      $product = $item['data'];
      $shipping_class = $product->get_shipping_class();

      tm_log($product);

      foreach( $rates as $rate_id => $rate )
      {
        if($small_products == true){
          if( 'free_shipping' === $rate->method_id ){
            $local[ $rate_id ] = $rate;
          }
        }else{
          if( 'free_shipping' != $rate->method_id ){
            $local[ $rate_id ] = $rate;
          }
        }
      }
    }

    return $local;
}

add_filter( 'woocommerce_package_rates', 'shipping_control', 10, 2 );

/* --------------------------------------------------------------------------------------- */

/*
  tm_remove_billing_checkout_fields
  2022.01.17
  Two Moons Consulting Masa
  If the products in the cart are small products only, remove billing_depo_code and billing_forklift.
*/

function tm_remove_billing_checkout_fields($fields) {
    $shipping_method ='free_shipping:13'; // Set the desired shipping method to hide the checkout field(s).
    global $woocommerce;
    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
    $chosen_shipping = $chosen_methods[0];

    tm_log('chosen_shipping:'.$chosen_shipping);

    if ($chosen_shipping === $shipping_method) {
      tm_log('free_shipping is true');
      unset($fields['billing']['billing_depo_code']); // Add/change filed name to be hide
      unset($fields['billing']['billing_forklift']);  // Add/change filed name to be hide
    }
    return $fields;
}

add_filter('woocommerce_checkout_fields', 'tm_remove_billing_checkout_fields');

/* --------------------------------------------------------------------------------------- */

/**
 * @snippet       Display "POA" for blank price products @ WooCommerce Single Product
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.8
 * @customised by Masa - Two Moons Consulting
 */

add_filter( 'woocommerce_empty_price_html', 'bbloomer_poa_and_enquiry_cf7_woocommerce' );

function bbloomer_poa_and_enquiry_cf7_woocommerce() {
  $html  = '<div class="poa">POA</div>';
  $html .= '<button type="submit" id="trigger_cf" class="single_add_to_button button alt"> Request Quote </button>';
  $html .= '<div id="product_inq" style="display:none">';
  $html .= do_shortcode('[contact-form-7 id="14" title="Contact Form Flat"]');
  $html .= '</div>';
  return $html;
}

/* --------------------------------------------------------------------------------------- */
/* Hiding buttons next to POA product in Backend */
/* --------------------------------------------------------------------------------------- */

add_action('admin_head', 'my_custom_style');
function my_custom_style() {
  echo '
  <style>
    .column-price .button{
      display: none;
    }

    .column-price #product_inq{
      display: none;
    }

  </style>';
}

/* --------------------------------------------------------------------------------------- */
/* Remove ADD TO CART button if the product has specific shipping option */
/* --------------------------------------------------------------------------------------- */

add_filter('woocommerce_is_purchasable', 'filter_is_purchasable', 10, 2);
function filter_is_purchasable($is_purchasable, $product ) {

  global $product;

  if($product != null){
    $shipping_class = $product->get_shipping_class();
    if($shipping_class == "cant-be-purchased"){
      return false;
    }
  }

  return $is_purchasable;
}
