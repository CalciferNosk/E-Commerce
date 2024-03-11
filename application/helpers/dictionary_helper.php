<?php 

if(!function_exists('get_customer_fullname')) # 
{
  function get_customer_fullname( $cus_id = 0 )
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'main');
    return $CI->main->get_customer_fullname($cus_id);
  }
}

if(!function_exists('get_customer_number')) # 
{
  function get_customer_number( $code = '' )
  {
    $CI = get_instance();
    $CI->load->model('Dictionary_model', 'dic');
    return $CI->dic->customer_number($code);
  }
}

if(!function_exists('get_employee')) # 
{
  function get_employee( $code = '' )
  {
    $CI = get_instance();
    $CI->load->model('Dictionary_model', 'dic');
    return $CI->dic->get_employee($code);
  }
}

if (!function_exists('overAllStatusColor')):
  function overAllStatusColor($id) {
    $colorPallete =  [
      1916 => 'off-track-color',
      1917 => 'on-hold-color',
      1918 => 'not-started-color',
      1919 => 'at-risk-color',
      1920 => 'on-track-color'
    ];

    return $colorPallete[$id];
  }
endif;

?>