<?php 


 
//////////////////////////////////////////////////////////
// 1. New select field @ billing section
 
add_filter( 'woocommerce_checkout_fields' , 'bbloomer_display_pickup_locations' );
 
function bbloomer_display_pickup_locations( $fields ) {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT address FROM wp_store");
    $arr = json_decode(json_encode( $results), TRUE);
      $items = array();
    foreach( $arr as $key=>$value)
{
   $items[] = $value[address];
}
array_unshift($items,"Select location");
$fields['billing']['pick_up_locations'] = array(
      'type'     => 'select',
   'options'  => $items,
   'label'     => __('Pick Up Location', 'woocommerce'),
   'class'     => array('form-row-wide'),
       'clear'     => true
     );
  
return $fields;
 
}
 
//////////////////////////////////////////////////////////
// 2. Field to show only when country == Australia
 
add_action( 'woocommerce_after_checkout_form', 'bbloomer_conditionally_hide_show_pickup', 5);
 
function bbloomer_conditionally_hide_show_pickup() {
   
  ?>
   <script type="text/javascript">
      jQuery('select#billing_country').live('change', function(){
          
         var country = jQuery('select#billing_country').val();
          
         var check_country = new Array(<?php echo '"AU"'; ?>);
         if (country && jQuery.inArray( country, check_country ) >= 0) {
            jQuery('#pick_up_locations_field').fadeIn();
         } else {
            jQuery('#pick_up_locations_field').fadeOut();
            jQuery('#pick_up_locations_field input').val('');
         }
          
      });
   </script>
   <?php
      
}
 
//////////////////////////////////////////////////////////
// 3. "Ship to a different address" opened by default
 
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_true' );
 
//////////////////////////////////////////////////////////
// 4. Change shipping address when local pickup location changes
 
add_action( 'woocommerce_after_checkout_form', 'bbloomer_checkout_update_pickup_address', 10);
 
function bbloomer_checkout_update_pickup_address() {
         
        ?>
      <script type="text/javascript">
 
      jQuery('select#pick_up_locations').live('change', function(){
 
      var location = jQuery('select#pick_up_locations').val();
       
      if (location == 'option_2') {
       
      jQuery('select#shipping_country').val('AU').change();
      jQuery('select#shipping_state').val('ACT').change();
      jQuery('#shipping_city_field input').val('Sidney');
      jQuery('#shipping_address_1_field input').val('Melbourne Road');
      jQuery('#shipping_postcode_field input').val('34500');
      jQuery(".shipping_address input[id^='shipping_']").prop("disabled", true);
      jQuery(".shipping_address select[id^='shipping_']").prop("disabled", true);
       
      } else if (location == 'option_3') {
 
      jQuery('select#shipping_country').val('AU').change();
      jQuery('select#shipping_state').val('ACT').change();
      jQuery('#shipping_city_field input').val('Sidney');
      jQuery('#shipping_address_1_field input').val('Perth Road');
      jQuery('#shipping_postcode_field input').val('79200');
      jQuery(".shipping_address input[id^='shipping_']").prop("disabled", true);
      jQuery(".shipping_address select[id^='shipping_']").prop("disabled", true);
 
                } else {
 
      jQuery(".shipping_address input[id^='shipping_']").prop("disabled", false);
      jQuery(".shipping_address select[id^='shipping_']").prop("disabled", false);
 
      }
       
      });
 
      </script>
      <?php
                      
}