<?php

if(!function_exists('send_sms'))
{
  function send_sms($number, $message, $sms_logs = array(), $api_key="PWFZ0_9SI9zntpB03gm68kuJe7uG0R9GiLHS", $project_id ="PJ1430e473b04d04cd")
  {
    /**
     * added ENVIRONMENT to avoid sending of sms in local environment
     */
    if (ENVIRONMENT == 'development') return false;
    if ($number && $message)
    {
      require_once(APPPATH.'libraries/telerivet.php');
      $api = new Telerivet_API($api_key);
  
      $project = $api->initProjectById($project_id);

      # Initialize Logs Model
      $CI = get_instance();
      $CI->load->model('Logs_model', 'logs');
      $sms_logs = (object) $sms_logs; # Array to Object

      try
      {
        $contact = $project->sendMessage(array(
            'to_number' => clean_string($number),
            'content' => $message
        ));
        
        $data = array(
          'FormId'        => property_exists($sms_logs, 'FormId') ? $sms_logs->FormId : NULL,
          'FormRecordId'  => property_exists($sms_logs, 'FormRecordId') ? $sms_logs->FormRecordId : NULL,
          'Details'       => $message,
          'sendby'        => property_exists($sms_logs, 'sendby') ? $sms_logs->sendby : 'INTERNAL',
          'mobileNumber'  => clean_string($number),
          'isSent'        => 1
        );
        
        $CI->logs->sms_logs($data);
      }
      catch (Telerivet_Exception $ex)
      {
        //echo $ex;//"<div class='error'>".htmlentities($ex->getMessage())."</div>";
        // die($ex);

        # Logs Error
        $data = array(
          'FormId'        => property_exists($sms_logs, 'FormId') ? $sms_logs->FormId : NULL,
          'FormRecordId'  => property_exists($sms_logs, 'FormRecordId') ? $sms_logs->FormRecordId : NULL,
          'Details'       => $ex->getMessage(),
          'sendby'        => property_exists($sms_logs, 'sendby') ? $sms_logs->sendby : 'INTERNAL',
          'mobileNumber'  => clean_string($number),
          'isSent'        => 0
        );
        
        $CI->logs->sms_logs($data);
      }
    }
  }
}

if(!function_exists('clean_string'))
{
  function clean_string($string)
  {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return str_replace(' ', '', preg_replace('/[^A-Za-z0-9\-]/', '', $string)); // Removes special chars.
  }
}
?>