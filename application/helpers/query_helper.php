<?php

if(!function_exists('query_branch'))
{
  function query_branch($branch)
  {
    $ems  = EMS_DB_NAME;
    $sql  = query_join ('left', 'org_group'   , 'brn'    , '`code`'       , $branch                , $ems);
    $sql .= query_join ('left', 'org_group'   , 'cluster', '`id`'         , 'brn.parent_org_id'    , $ems);
    $sql .= query_join ('left', 'org_group'   , 'area'   , '`id`'         , 'cluster.parent_org_id', $ems);
    $sql .= query_join ('left', 'org_group'   , 'reg'    , '`id`'         , 'area.parent_org_id'   , $ems);
    return  $sql;
  }
}

if(!function_exists('query_join'))
{
  function query_join($join, $table, $alias, $column, $column_ref ,$db = ERS_DB_NAME, $and = '')
  {
    return  " $join join $db.$table  $alias on $alias.$column = $column_ref $and";
  }
}

if(!function_exists('query_customer_inquiry'))
{
  function query_customer_inquiry($customer_id)
  {
    $sql  = query_join ('left', 'tblcustomerinq'        , 'customer_info' , '`id`'  , $customer_id                   );
    $sql .= query_join ('left', 'tbl_globalreference'   , 'occupation'    , '`grid`'       , 'customer_info.OccupationId' );
    $sql .= query_join ('left', 'refbrgy'               , 'barangay'      , '`brgyCode`'   , 'customer_info.BarangayCode' );
    $sql .= query_join ('left', 'refcitymun'            , 'city'          , '`citymunCode`', 'customer_info.CityCode' );
    $sql .= query_join ('left', 'refprovince'           , 'province'      , '`provCode`'   , 'customer_info.ProvinceCode' );
    $sql .= query_join ('left', 'refregion'             , 'region'        , '`regCode`'    , 'customer_info.RegionCode' );
    return  $sql;
  }
}

if(!function_exists('branch_details'))
{
  function branch_details($branch)
  {
    return
    "
      UPPER(CONCAT(
      IF($branch             IS NULL or $branch             = '', 'NONE', $branch),
      IF(brn.description     IS NULL or brn.description     = '', ''    , CONCAT(' - ',brn.description)),
      IF(reg.description     IS NULL or reg.description     = '', ''    , CONCAT('(',reg.description)),
      IF(area.code           IS NULL or area.code           = '', ''    , CONCAT(' | ',area.code       )),
      IF(cluster.code        IS NULL or cluster.code        = '', ''    , CONCAT(' | ',cluster.code ,')'))
      )) as branch_details
    ";
  }
}

# Russ Creator
if(!function_exists('branch_name_convention')) # Branch Naming Convention + Hierarchy
{
  function branch_name_convention ( $bcode = "", $return_string_name = FALSE )
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');

    $json = json_decode($CI->m->tmp_branch_name_convetion( $bcode ), 1);

    return $return_string_name === TRUE ?
      $json[$bcode] ?? '':
      $json
    ;

    #return json_decode($CI->m->tmp_branch_name_convetion(), 1);
  }
}

if(!function_exists('_branchNameConv')):
  function _branchNameConv ($code = "", $return_string_name = FALSE)
  {
    $CI = get_instance();
    $CI->load->model('OrgGroupModel', 'm_org');

    $json = $CI->m_org->getBranchNameConv();

    return $return_string_name === TRUE ?
      $json[$code] ?? '':
      $json
    ;
  }
endif;

if(!function_exists('get_branch_name')) # Branch Naming Convention [Branch Name Only]
{
  function get_branch_name ($bcode = "", $return_string_name = FALSE)
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');

    $json = json_decode($CI->m->tmp_get_branch_name($bcode), 1);

    return $return_string_name === TRUE ?
      $json[$bcode] ?? '':
      $json
    ;

    #return json_decode($CI->m->tmp_branch_name_convetion(), 1);
  }
}

# Russ Creator
if(!function_exists('cluster_name_convention')) # cluster Naming Convention
{
  function cluster_name_convention ( $code = 0)
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
    return $CI->m->tmp_cluster_name_convetion($code);
  }
}

# maintain soon version add parameter to get assigned user only
if(!function_exists('data_control_branch')) # Org Group Filtering 
{
  function data_control_branch( $alias = 'a', $clus_control = "")
  {
    # Init
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
    // $Branch = $CI->m->data_control_branch();

    if ( is_super_workgroup() === TRUE ) return "";

    $clus_tbl = $clus_control;
    $clus_control = !empty($clus_control) ? TRUE : FALSE;
    $Branch = $CI->m->data_control_branch($pid = NULL, $empid = NULL, $orgid = NULL, $getChildIds = FALSE, $clus_control);
    $str = "";
    // var_dump($Branch->brn_code); die();

    if ( $clus_control === TRUE ) : # filter with cluster & branch

      $brn  = !empty($Branch->brn_code) ? implode(',', json_decode($Branch->brn_code, 1) ) : "'0'";
      $clus = !empty($Branch->clus_code) ? implode("','", json_decode($Branch->clus_code, 1) ) : 0 ;

      $str = !empty($brn) || !empty($clus) ? " AND {$alias}.Branch IN ({$brn}) AND ({$clus_tbl} IN ('{$clus}') OR {$clus_tbl} IS NULL OR {$clus_tbl} = '')" : "";
      
    else:

      $str = " AND ({$alias}.Branch IN ({$Branch}))";

    endif;

    return $str;
  }
}

if (!function_exists('is_super_workgroup')) # Super Admin Workgroup Ids
{
  function is_super_workgroup ($grid = 656) # Super Admin Default
  { 
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
    $getSuperWorkGroupIds = $CI->m->getSuperWorkGroupIds($grid);
    $superWorkGroupIds = !empty($getSuperWorkGroupIds ) ? explode(',', $getSuperWorkGroupIds ) : array(0);
    $userWorkGroup = !empty($_SESSION['work_group']) ? explode(',', $_SESSION['work_group']) : array(0);

    return !empty(array_intersect($userWorkGroup, $superWorkGroupIds)) ? TRUE : FALSE;
  }
}

if (!function_exists('last_updated_by')) # To be Removed
{
  function last_updated_by ( $code = 0, $return_string_name = FALSE ) 
  { 
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');

    $json = json_decode($CI->m->last_updated_by( $code ), 1);

    return $return_string_name === TRUE ?
      $json[$code] ?? '':
      $json
    ;

    # return json_decode($CI->m->last_updated_by( $code ), 1);
  }
}

if (!function_exists('employeefullname')) # this will be deleted once
{
  function employeefullname ($code = 0, $return_string_name = FALSE)
  { 
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');

    $json = json_decode($CI->m->employeefullname( $code ), 1);

    return $return_string_name === TRUE ?
      $json[$code] ?? '' :
      $json
    ;

    # return json_decode($CI->m->employeefullname( $code ), 1);
  }
}

if (!function_exists('_employeeFullName')): # employeefullname ($code = 0, $return_string_name = FALSE)
  function _employeeFullName ($code = 0, $return_string_name = FALSE)
  {
    $CI = get_instance();
    $CI->load->model('EmployeeModel', 'm_employee');

    $json = $CI->m_employee->getAllEmployeeFullName();

    return $return_string_name === TRUE ?
      $json[$code] ?? '' :
      $json
    ;
  }
endif;

if (!function_exists('_systemEmployeeFullName')):
  function _systemEmployeeFullName($username = 0, $return_string_name = FALSE)
  { 
    $CI = get_instance();
    $CI->load->model('Lookup_model', 'm');

    $json = json_decode($CI->m->systemEmployeeFullName($username), 1);

    return $return_string_name === TRUE ?
      $json[$username] ?? '' :
      $json
    ;
  }
endif;

if (!function_exists('is_showall_employee'))
{
  function is_showall_employee ( ) 
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
    $getSuperWorkGroupIds = $CI->m->getSuperWorkGroupIds(658);
    $superWorkGroupIds = !empty($getSuperWorkGroupIds ) ? explode(',', $getSuperWorkGroupIds ) : array(0);
    $userWorkGroup = !empty($_SESSION['work_group']) ? explode(',', $_SESSION['work_group']) : array(0);
    
    return !empty(array_intersect($userWorkGroup, $superWorkGroupIds)) ? TRUE : FALSE;
  }
}

if (!function_exists('org_datacontrol'))
{
  function org_datacontrol () 
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
    $orgids = $CI->m->get_org_ids();

    return !empty($orgids) ? $orgids : '';
  }
}

if (!function_exists('is_showall_branch'))
{
  function is_showall_branch () 
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
    $getSuperWorkGroupIds = $CI->m->getSuperWorkGroupIds(843);
    $superWorkGroupIds = !empty($getSuperWorkGroupIds ) ? explode(',', $getSuperWorkGroupIds) : array(0);
    $userWorkGroup = !empty($_SESSION['work_group']) ? explode(',', $_SESSION['work_group']) : array(0);
    // var_dump(array_intersect($userWorkGroup, $superWorkGroupIds));die();
    return !empty(array_intersect($userWorkGroup, $superWorkGroupIds)) ? TRUE : FALSE;
  }
}

if (!function_exists('get_infraction_type_list')) # this will be removed once server side listview is deployed
{
  function get_infraction_type_list ($infrac_id = 0, $return_json = TRUE) 
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
    $r = $CI->m->get_infraction_map(0, $return_json);
   
    return $r !== FALSE ? json_decode( $r->row()->infrac_json, 1 ) : array();
  }
}

if (!function_exists('_infractionType')): # replaced for get_infraction_type_list ($infrac_id = 0, $return_json = TRUE)
  function _infractionType ()
  {
    $CI = get_instance();
    $CI->load->model('InfractionModel', 'm_infraction');
    
    return $CI->m_infraction->getTypeInfraction();
  }
endif;

if (!function_exists('get_org_name')) # To Be remove once server side is deployed
{
  function get_org_name ($org_type = array()) 
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
    $r = $CI->m->get_org_group_codename_using_org_id($org_type);
   
    return $r !== FALSE ? $r : array();
  }
}

if (!function_exists('_getOrgNameById')): # Replaced with get_org_name ($org_type = array())
  function _getOrgNameById ($org_type = array()) 
  {
    $CI = get_instance();
    $CI->load->model('OrgGroupModel', 'm_org');
   
    return $CI->m_org->getOrgNameById();
  }
endif;

if (!function_exists('get_cluster_param'))
{
  function get_cluster_param () 
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
   
    return $CI->m->get_cluster_param();
  }
}

if (!function_exists('get_system_user_fullname'))
{
  function get_system_user_fullname () 
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
   
    return $CI->m->get_system_user_fullname();
  }
}

if (!function_exists('get_record_has_attachment')) # to be remove once server side is deployed
{
  function get_record_has_attachment ($formid = 0)
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
   
    return $CI->m->get_record_has_attachment($formid);
  }
}

if (!function_exists('_recordHasAttach')): # replace for get_record_has_attachment ($formid = 0)
  function _recordHasAttach ($formid)
  {
    $CI = get_instance();
    $CI->load->model('AttachmentModel', 'm_attach');
   
    return $CI->m_attach->getAllAttachment($formid);
  }
endif;

if (!function_exists('get_strtotime'))
{
  function get_strtotime () 
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm');
   
    return $CI->m->get_strtotime();
  }
}

if (!function_exists('get_user_system_fullname'))
{
  function get_user_system_fullname () 
  {
    $CI = get_instance();
    $CI->load->model('Lookup_model', 'm_lookup');
   
    return json_decode($CI->m_lookup->get_user_system_fullname()->row()->json, 1);
  }
}

if (!function_exists('head_office_hierarchy'))
{
  function head_office_hierarchy ($org_code) 
  {
    $CI = get_instance();
    $CI->load->model('Lookup_model', 'm_lookup');
    $h = $CI->m_lookup->getHeadOfficeHierarchy($org_code);

    if (!$h || $h->num_rows() == 0) return null;
    $h = $h->row();

    $str    = [];
    $str[]  = $h->first_level;
    $str[]  = !empty($h->second_level) ? $h->second_level : '';
    $str[]  = !empty($h->third_level) ? $h->third_level : '';
    $str[]  = !empty($h->fourth_level) ? $h->fourth_level : '';
    $str[]  = !empty($h->fifth_level) ? $h->fifth_level : '';

    $str    = array_filter($str); # remove empty values
    $str    = implode(' | ', $str);
    
    return str_replace('1000', 'MNC', $str);
  }
}

if (!function_exists('getTATComputed')):
  function getTATComputed ($form_id, $formrecordid) 
  {
    $CI = get_instance();
    $CI->load->model('Lookup_Model', 'm_lookup');
    $data_object = new stdClass;
    $s_time = '';
    $record = getItemRecord($form_id, $formrecordid);

    # Set The Start Date
    $s_time = $record->CreatedDate ?? $record->DateCreated ?? null;
    $s_time = !empty($s_time) ? $s_time : null;
    
    # Validate if all required data is completed
    if (!$s_time || empty($s_time) || empty($record)):
      $data_object->ActualTAT  = '--';
      $data_object->TargetTAT  = '--';
      return $data_object;
    endif;

    # Get Status Tag
    $status_tag = $CI->m_lookup->getStatusType($record->CurrentStatusId);

    # Compute Date Diff
    $d1                     = strtotime($s_time);
    $d2                     = $status_tag == 'OPEN' ? strtotime(date("Y-m-d H:i:s")) : strtotime($record->UpdatedDate);
    $actual_seconds_diff    = abs($d1-$d2);

    # Checking if Form is Closed Then Actual TAT in Minutes will be used
    $actual_min_diff   = $status_tag == 'CLOSE' && in_array($form_id, allowedFormWithTAT())
      ? $record->ActualTAT
      : floor($actual_seconds_diff/60);

    # Actual Date Convertion
    $actual_tat_day       = floor($actual_min_diff/60/24);
    $actual_tat_hour      = ($actual_min_diff/60) % 24; # Only the reminder hour
    $actual_tat_min       = $actual_min_diff % 60; # Only the reminder minutes
    $Actual_tat           = "{$actual_tat_day}d {$actual_tat_hour}h {$actual_tat_min}m";

    # Check if Form Is not allowed then retrive Actual TAT Only
    if (!in_array($form_id, allowedFormWithTAT())):
      $data_object->ActualTAT  = $Actual_tat;
      $data_object->TargetTAT  = '--';
      return $data_object;
    endif;

    $totalActualTATinMinutes = $actual_min_diff;
    $totalTargetTATinMinutes = $record->TargetTAT;

    # Target TAT computation for Displaying
    $targetTATDay    = floor($totalTargetTATinMinutes/60/24);
    $targetTATHour   = ($totalTargetTATinMinutes/60) % 24; # Only the reminder hour
    $targetTATMinutes = $totalTargetTATinMinutes % 60; # Only the reminder minutes

    $color = $totalActualTATinMinutes > $totalTargetTATinMinutes
      ? 'red'
      : 'green';

    $target_tat = "{$targetTATDay}d {$targetTATHour}h {$targetTATMinutes}m";

    $data_object->ActualTAT  = "<span style='color:{$color}'>{$Actual_tat}</span>";
    $data_object->TargetTAT  = $target_tat;

    return $data_object;
  }
endif;

if (!function_exists('getListviewTATComputed')):
  function getListviewTATComputed($created_date, $actual_tat_min, $target_tat_min, $status_tag)
  {
    $total_actual_tat_min = $total_target_tat_min = 0;

    if ($status_tag == 'OPEN'):
      $d1                  = strtotime($created_date);
      $d2                  = strtotime(date("Y-m-d H:i:s"));
      $actual_sec_diff   = abs($d1-$d2);
      $actual_min_diff   = $total_actual_tat_min = floor($actual_sec_diff/60);

      # Actual Diff Computation
      $actual_tat_day       = floor($actual_min_diff/60/24);
      $actual_tat_hour      = ($actual_min_diff/60) % 24; # Only the reminder hour
      $actual_tat_min   = $actual_min_diff % 60; # Only the reminder minutes
      $ActualTAT          = "{$actual_tat_day}d {$actual_tat_hour}h {$actual_tat_min}m";
    else:
      $total_actual_tat_min = $actual_tat_min;
      
      # Actual TAT computation for Displaying
      $actual_tat_day    = floor($total_actual_tat_min/60/24);
      $actual_tat_hour   = ($total_actual_tat_min/60) % 24; # Only the reminder hour
      $actual_tat_min = $total_actual_tat_min % 60; # Only the reminder minutes
      $ActualTAT        = "{$actual_tat_day}d {$actual_tat_hour}h {$actual_tat_min}m";
    endif;
    
    # Target TAT computation for Displaying
    $total_target_tat_min = $target_tat_min;
    $target_tat_day    = floor($total_target_tat_min/60/24);
    $target_tat_hour   = ($total_target_tat_min/60) % 24; # Only the reminder hour
    $target_tat_min    = $total_target_tat_min % 60; # Only the reminder minutes

    $targe_tat = "{$target_tat_day}d {$target_tat_hour}h {$target_tat_min}m";
    
    $color = $total_actual_tat_min > $total_target_tat_min
    ? 'red'
    : 'green';

    return "<span style='color:{$color}'><span title='Actual TAT'>{$ActualTAT}</span> <span title='Target SLA'>({$targe_tat})</span></span>";
  }
endif;

if (!function_exists('getTableNameByFormId'))
{
  function getTableNameByFormId ($FormId) 
  {
    $CI = get_instance();
    $CI->load->model('Lookup_Model', 'm_lookup');

    $tableName = $CI->m_lookup->getTableNameByFormId($FormId);

    if (!$tableName || $tableName->num_rows() == 0 || empty($tableName->row()->tableName)) return null;

    return $tableName->row()->tableName;
  }
}

if (!function_exists('allowedFormWithTAT'))
{
  function allowedFormWithTAT () 
  {
    return [35,53]; # 53,56,62,19
  }
}

if (!function_exists('getRegionName'))
{
  function getRegionName ($regions = '') 
  {
    $CI = get_instance();
    $CI->load->model('Branch_Hierarchy_Temp_model', 'branch_heirarchy');
   
    return $CI->branch_heirarchy->getRegion($regions);
  }
}

if (!function_exists('getItemRecord'))
{
  function getItemRecord($FormId, $FormRecordId)
  {
    $tableName = getTableNameByFormId ($FormId);

    $CI = get_instance();
    $CI->load->model('Main_model', 'm_main');

    if (empty($tableName)) return false;

    $record = $CI->m_main->getItemRecord($tableName, $FormRecordId);

    if(!$record) return false;

    return $record;
  }
}


if (!function_exists('getPreviousStatus'))
{
  function getPreviousStatus($FormId, $FormRecordId, $CurrentStatusId)
  {
    $CI = get_instance();
    $CI->load->model('Main_model', 'm_main');

    $record = $CI->m_main->getPreviousStatus($FormId, $FormRecordId, $CurrentStatusId);

    if(!$record || $record->num_rows() == 0) return null;

    // var_dump($record->row()); die();
    return $record->row();
  }
}

if (!function_exists('allowedAttachmentIcon'))
{
  function allowedAttachmentIcon () 
  {
    return [50,69,33,59,28,56,19,58,60,45,39,61,73,29,22,26];
  }
}

if (!function_exists('updateAnswerJSONData')):
  function updateAnswerJSONData($id, $FormId, $AnswerDataJson)
  {
    $tableName = getTableNameByFormId($FormId);
    // var_dump($tableName, $FormId); die();
    $CI = get_instance();
    $CI->load->model('tab/Questionnaire_model', 'm_question');

    $result = $CI->m_question->updateAnswerData($id, $tableName, $AnswerDataJson);

    return $result;
  }
endif;

if (!function_exists('answerDataToJSON')):
  function answerDataToJSON($inputData = [])
  {
    if (empty($inputData)) return false;
    $CI = get_instance();
    $CI->load->model('tab/Questionnaire_model', 'm_question');

    return $CI->m_question->answerDataToJSON($inputData);
  }
endif;

if (!function_exists('getFormNameByFormId')):
  function getFormNameByFormId($FormId) {
    $CI = get_instance();
    $CI->load->model('Email_model', 'm_email');

    $form = $CI->m_email->getFormNameByFormId($FormId);

    return !$form || $form->num_rows() == 0 ? '' : $form->row()->form;
  }
endif;
# End Russ Creator
?>