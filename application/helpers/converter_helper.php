<?php
if(!function_exists('converter'))
{
  function converter($type, $name, $id, $class , $reference, $val, $display, $value='', $parameter = array(), $employeeLookupRef = "",$option_group ="", $isSort = "", $maintenanceID=0, $cocid = 0)
  {
    $CI = get_instance();
    $CI->load->model('user_access_model');
    $CI->load->model('Main_model', 'main');
    $CI->load->model('Form_model', 'form_mdl');
    $CI->load->model('Branch_Hierarchy_Temp_model', 'branch_hierarchy');
    $CI->load->model('AM_Visitation_model', 'visitation');
    // print_r($maintenanceID);
    switch (trim($type)):
      case 'dropdown':
        $select_data = $CI->user_access_model->get_drop_down($reference, $val, $display, $parameter, $option_group, $isSort);
        $max_width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : 'width:440px';
        $options     = array();

        if (!empty($value)) {
          $text     = $CI->user_access_model->get_display($reference, $val, $value ,$display);
          if (empty($text)) {
              $options[] = '<option value="'.$value.'" selected hidden>NONE</option>';
          }else{
              $options[] = '<option value="'.$value.'" selected hidden>'.strtoupper($text).'</option>';
          }
        }
        else{
             $options[] = '<option selected hidden value=""></option>';
        }
        if((strpos(current_url(), 'view') !== false && empty($value))){
             $options[] = '<option value="'.$value.'" selected hidden>NONE</option>';
        }
        $optgrp = '';
         
        foreach ($select_data as $res) {
          
          if (!empty($option_group)) {
            if ($optgrp != $res['option_group']) {
            if (!empty($optgrp)) {
              $options[] ='</optgroup>';
            }
            $options[] ='<optgroup label="---'.$res['option_group'].'---"></optgroup>';

            }
            $options[] = '<option value="'.$res[''.$val.''].'">'.strtoupper($res[''.$display.'']).'</option>';
            $optgrp = $res['option_group'];    
            if (!empty($optgrp)) {
            $options[] ='</optgroup>';
            }
          }else{
            $options[] = '<option value="'.$res[''.$val.''].'">'.strtoupper($res[''.$display.'']).'</option>';
          }
        }

        // return '<select name="'.$name.'" id="'.$id.'" class="'.$class.'" style="'.$max_width.'">'.implode(" ",$options).'</select>';
        if(in_array(223,explode(',', $_SESSION['work_group']))):
          if($maintenanceID>0):
            $html = 
                 '<div class="input-group" style="display:block!important" >
                    <select name="'.$name.'" id="'.$id.'" class="'.$class.'" style="'.$max_width.'">'.implode(" ",$options).'</select>
                    <button type="button" class="btn-quick-add btn btn-primary" onclick= "quick_add('.$maintenanceID.')" data-target-display="dval-'.$maintenanceID.'" data-target="#modal-quick-add-'.$maintenanceID.'"> <i class="fa fa-plus"></i> </button>
              
                  </div>';
            return $html;
          else:
            return '<select name="'.$name.'" id="'.$id.'" class="'.$class.'" style="'.$max_width.'">'.implode(" ",$options).'</select>';
          endif;
        else:
          return '<select name="'.$name.'" id="'.$id.'" class="'.$class.'" style="'.$max_width.'">'.implode(" ",$options).'</select>';
        endif;
       

      case 'dropdown_no_select':
        $max_width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : 'width:440px';
        return '<select name="'.$name.'" id="'.$id.'" class="'.$class.'" style="'.$max_width.'"></select>';
        break;
      
      case 'employeelookup':
        $classes      = (!empty($class)) ? explode(' ', $class) : array(0);
        $width        = (strpos(current_url(), 'view') !== false) ? 'width:400px' : '';
        $height       = '';#(strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;width:42px' : '';
        $term_code    = in_array('default_user', $classes) && empty($value) ? $_SESSION['employee_code'] : $value;

        // FOR VISITATION FORM
        if(empty($value) && $id == 'manager_approver_id'){
          $menu_dept = [
            '1' => $CI->main->getPositionEmployee(393)->code,
            '3' => $CI->main->getPositionEmployee(394)->code,
            '12' => 901449,
            '16' => $CI->main->getPositionEmployee(392)->code
          ];

          $term_code = $menu_dept[$_SESSION['menu_id']];
        }


        $employee     = $CI->main->get_employee_display($term_code);
        $employeeName = ( isset($employee) && !empty($employee) ) ? trim(preg_replace('/\s\s+/', ' ', $employee->displayName)) : '--';
        $employeeCode = ( isset($employee) && !empty($employee) ) ? $employee->employeeCode : '';
        # assigning lookup type
        $lookuptype   = ( !empty( str_replace(' ', '', $employeeLookupRef) ) ) ? "type".str_replace(',' , '', $employeeLookupRef) : 'all';
        $isRequired   = ( in_array('required-field', $classes) ) ? ' req$CIasdadsauired-field' : '';
        $allowall     = !empty($classes) && in_array('show_emp_all', $classes) && is_showall_employee() === TRUE ? 1 : 0 ;
        $isemployee   = !empty($classes) && in_array('employee-lookup', $classes) ? 1 : 0 ;
        $isassignee   = in_array('assigned_user', $classes) ? 1 : 0;
        $isreadonly   = in_array('read-only-field', $classes) ? "disabled" : "";

        # Tmp, For Special user
        if ( !empty($value) && empty($employeeCode) ):
          $employeeCode = $value;
          $employeeName = $value;
        endif;
      
        return '
                <div class="input-group" style="display:block!important">
                  <input type="text" id="display_val_'.$id.'" class="employee-selected-name '.$class.'" value="'.$employeeName.'" style="'.$width.'" readonly>
                      <span class="input-group-btn">
                        <button type="button" class="btn-lookup-employee btn btn-primary btn '.$id.'" data-lookuprefids="'.$employeeLookupRef.'" data-containerid="div-'.$id.'" data-lookuptype="'.$lookuptype.'" data-descendant="'.$employeeLookupRef.'" data-bcode="" data-allowall="'.$allowall.'" data-isemployee="'.$isemployee.'" style="'.$height.'" '. $isreadonly .'>
                          <i class="fa fa-user-plus"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn btn-clear" data-target-display="display_val_'.$id.'" data-target="'.$id.'" style="'.$height.'">
                          <i class="fa fa-times"></i>
                        </button>
                      </span>
                </div>
                <input type="hidden" class="employee-selected-id '.$isRequired.'" name="'.$name.'" id="'.$id.'" data-isassignee="'.$isassignee.'" value="'.$employeeCode.'">
        ';
        break;
      case 'employeelookup-disabled':
          $classes      = (!empty($class)) ? explode(' ', $class) : array(0);
          $width        = (strpos(current_url(), 'view') !== false) ? 'width:400px' : '';
          $height       = (strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;width:42px' : '';
          $term_code    = in_array('default_user', $classes) && empty($value) ? $_SESSION['employee_code'] : $value;
          $employee     = $CI->main->get_employee_display($term_code);
          $employeeName = ( isset($employee) && !empty($employee) ) ? trim(preg_replace('/\s\s+/', ' ', $employee->displayName)) : '--';
          $employeeCode = ( isset($employee) && !empty($employee) ) ? $employee->employeeCode : '';
          # assigning lookup type
          $lookuptype   = ( !empty( str_replace(' ', '', $employeeLookupRef) ) ) ? "type".str_replace(',' , '', $employeeLookupRef) : 'all';
          $isRequired   = ( in_array('required-field', $classes) ) ? ' req$CIasdadsauired-field' : '';
          $allowall     = !empty($classes) && in_array('show_emp_all', $classes) && is_showall_employee() === TRUE ? 1 : 0 ;
          $isemployee   = !empty($classes) && in_array('employee-lookup', $classes) ? 1 : 0 ;
          $isassignee   = in_array('assigned_user', $classes) ? 1 : 0;
          $isreadonly   = in_array('read-only-field', $classes) ? "disabled" : "";
  
          # Tmp, For Special user
          if ( !empty($value) && empty($employeeCode) ):
            $employeeCode = $value;
            $employeeName = $value;
          endif;
        
          return '
              <div class="input-group" style="display:block!important">
                <input type="text" id="display_val_'.$id.'" class="employee-selected-name '.$class.'" value="'.$employeeName.'" style="'.$width.'" readonly>
                    <span class="input-group-btn">
                      <button type="button" class="btn-lookup-employee btn btn-primary btn '.$id.'" data-lookuprefids="'.$employeeLookupRef.'" data-containerid="div-'.$id.'" data-lookuptype="'.$lookuptype.'" data-descendant="'.$employeeLookupRef.'" data-bcode="" data-allowall="'.$allowall.'" data-isemployee="'.$isemployee.'" style="'.$height.'" '. $isreadonly .' disabled>
                        <i class="fa fa-user-plus"></i>
                      </button>
                      <button type="button" class="btn btn-primary btn btn-clear" data-target-display="display_val_'.$id.'" data-target="'.$id.'" style="'.$height.'" disabled>
                        <i class="fa fa-times"></i>
                      </button>
                    </span>
              </div>
              <input type="hidden" class="employee-selected-id '.$isRequired.'" name="'.$name.'" id="'.$id.'" data-isassignee="'.$isassignee.'" value="'.$employeeCode.'">
          ';
        break;
      case 'branchlookup':
        # Init
        $classes      = (!empty($class)) ? explode(' ', $class) : array(0);
        $orgid        = trim($_SESSION['branch_type']) == "SECTION" ? $_SESSION['dept_head_id'] : $_SESSION['department_id'];
        $orgid        = empty($value) ? $orgid : $value;
        $isorgid      = in_array('get_org_id', $classes) ? $orgid : FALSE;
        $isdefault    = in_array('default_branch', $classes) && !in_array(88, explode(',', $_SESSION['work_group'])) ? TRUE : FALSE;
        $bcode        = empty($value) ? $_SESSION['branch_code'] : $value ;
        $branches     = $isdefault === TRUE || !empty($value) ? $CI->main->get_branch_display($bcode, $isorgid) : FALSE;
        $width        = (strpos(current_url(), 'view') !== false) ? 'width:400px' : '';
        $height       = (strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;width:42px' : '';
        $displayName  = $branches !== FALSE && !empty($branches) ? $branches->displayName : $value ?? '--';
        $orgcode      = $branches !== FALSE && !empty($branches) && $isorgid === FALSE ? $branches->bcode : $branches->orgid ?? '';
        // var_dump($branches); die();
        $isRequired   = ( in_array('required-field', $classes) ) ? ' required-field' : '';
        $allowall     = is_showall_branch() === TRUE ? 1 : 0 ;
        $branch_only  = in_array('branch_only', $classes) ? 1 : 0;
        $dept_only    = in_array('dept_only', $classes) ? 1 : 0;
        $isreadonly   = in_array('read-only-field', $classes) ? "disabled" : "";
        // $displayName  =  ?

        # Restriction for Branch Only Selectio
        if (
            !empty(array_intersect(array('default_branch','branch_only'), $classes)) &&
            !in_array('dept_only', $classes) && !is_numeric($orgcode)
          ):
          $displayName  = '--';
          $orgcode      = '';
        endif;

        return '
          <div class="input-group" style="display:block!important">
            <input type="text" id="display_val_'.$id.'" class="branch-selected-name '.$class.'" value="'.$displayName.'" style="'.$width.'" readonly>
            <span class="input-group-btn">
              <button type="button" class="btn-lookup-branch btn btn-primary btn '.$id.'" data-containerid="div-'.$id.'" data-allowall="'.$allowall.'" data-targetelemid="'.$id.'" data-branch_only="'.$branch_only.'" data-dept_only="'.$dept_only.'" '.$isreadonly.'>
                <i class="fa fa-search"></i>
              </button>
              <button type="button" class="btn btn-primary btn btn-clear '.$id.'" data-target-display="display_val_'.$id.'" data-target="'.$id.'" '.$isreadonly.'>
                <i class="fa fa-times"></i>
              </button>
            </span>
          </div>
          <input type="hidden" class="branch-selected-bcode '.$isRequired.'" name="'.$name.'" id="'.$id.'" value="'.$orgcode.'" >
        ';
        break;
     
      case 'inquirylookup':
        $res = $CI->user_access_model->get_inquiry_display($value);
        // var_dump($res);die();
        $classes  = (!empty($class)) ? explode(' ', $class) : array(0);
        
        $width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : 'width:440px';
        $height = (strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;' : '';
        return '
                <div class="input-group" style="display:block!important">
                <input type="text"   id="display_'.$id.'" class="employee-selected-name '.$class.'" value="'.$res->display.'" style="'.$width.'" readonly >
                <span class="input-group-btn">
                        <button type="button" class="btn-lookup-inquiry btn btn-primary btn" data-target_id="'.$id.'"  style= "margin-left: -5px;border-radius: 0px 4px 4px 0px;min-width:42.8px;'.$height.'">
                          <i class="fa fa-search"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn btn-clear" data-target-display="display_'.$id.'" data-target="'.$id.'" style="'.$height.'">
                        <i class="fa fa-times"></i>
                          </button>
                </span>
                </div>  <input type="hidden" id="'.$id.'" value="'.$value.'" name="'.$name.'">';
        break;
        
        case 'warrantylookup': // warranty-lookup returned unit erickson adriano
          $res = $CI->user_access_model->get_warranty_display($value);
          
          $classes  = (!empty($class)) ? explode(' ', $class) : array(0);
          
          $width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : 'width:440px';
          $height = (strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;' : '';
          return '
                  <div class="input-group" style="display:block!important">
                  <input type="text"   id="display_'.$id.'" class="warranty-selected-name '.$class.'" value="'.$res->display.'" style="'.$width.'" readonly >
                  <span class="input-group-btn">
                          <button type="button" class="btn-lookup-warranty btn btn-primary btn" data-target_id="'.$id.'"  style= "margin-left: -5px;border-radius: 0px 4px 4px 0px;min-width:42.8px;'.$height.'">
                            <i class="fa fa-search"></i>
                          </button>
                          <button type="button" class="btn btn-primary btn btn-clear" data-target-display="display_'.$id.'" data-target="'.$id.'" style="'.$height.'">
                          <i class="fa fa-times"></i>
                            </button>
                  </span>
                  </div>  <input type="hidden" id="'.$id.'" value="'.$value.'" name="'.$name.'">';
          break;
          
      case 'systemlookup':
        $system = $CI->user_access_model->get_system_display($value);

        $classes  = (!empty($class)) ? explode(' ', $class) : array(0);
        
        $width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : 'width:440px';
        $height = (strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;' : '';
        return '
                <div class="input-group" style="display:block!important">
                <input type="text" id="'.$id.'" class="system-selected-name '.$class.'" value="'.$system->display.'" style="'.$width.'" readonly name="'.$name.'">
                <span class="input-group-btn">
                        <button type="button" class="btn-lookup-system btn btn-primary btn" data-target_id="'.$id.'"  style= "margin-left: -5px;border-radius: 0px 4px 4px 0px;min-width:42.8px;'.$height.'">
                          <i class="fa fa-search"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn btn-clear" data-target-display="'.$id.'" data-target="value_'.$id.'" style="'.$height.'">
                <i class="fa fa-times"></i>
              </button>
              </span>
                </div>
                 <input type="hidden" class="system-selected-id" name="'.$name.'" id="value_'.$id.'" value="'.$system->value.'" >';
        break;
      case 'ltrs_training_list_lookup': # LTRS Lookup is the Great by: sudo drop ers --infinite
        $ltrs = $CI->user_access_model->ltrs_training_display($value);

        $classes  = (!empty($class)) ? explode(' ', $class) : array(0);

        $width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : 'width:440px';
        $height = '';#(strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;' : '';
        return '
                  <div class="input-group" style="display:block!important">
                  <input type="text" id="input-display-' . $id . '" class="ltrs-training-selected-name ' . $class . '" value="' . $ltrs->display . '" style="' . $width . '" readonly name="' . $name . '">
                  <span class="input-group-btn">
                          <button type="button" class="btn-ltrs-training-lookup btn btn-primary btn" data-target_id="' . $id . '"  style= "margin-left: -5px;border-radius: 0px 4px 4px 0px;min-width:42.8px;' . $height . '">
                            <i class="fa fa-search"></i>
                          </button>
                          <button type="button" class="btn btn-primary btn btn-clear" data-dtarget="input-display-' . $id . '" data-target-display="' . $id . '" data-target="value_' . $id . '" style="' . $height . '">
                  <i class="fa fa-times"></i>
                </button>
                </span>
                  </div>
                   <input type="hidden" class="ltrs-training-selected-id" name="' . $name . '" id="' . $id . '" value="' . $ltrs->value . '" >';
        break;
      case 'psgc_lookup': # PSGC Lookup Using Select2
        # Init
        // $max_width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : 'width:440px';
        $max_width   = 'width: 50% !important';
        $psgc        = !empty($value) ? $CI->main->get_psgc_view("", $value)->row() : NULL;
        $attr        = 'data-psgc_brgy_code   ="'.(!empty($value) ? $psgc->brgyCode    ?? ''   : '').'" ';
        $attr       .= 'data-psgc_citymun_code="'.(!empty($value) ? $psgc->citymunCode ?? ''   : '').'" ';
        $attr       .= 'data-psgc_prov_code   ="'.(!empty($value) ? $psgc->provCode    ?? ''   : '').'" ';
        $attr       .= 'data-psgc_region_code ="'.(!empty($value) ? $psgc->regCode     ?? ''   : '').'" ';
        $attr       .= 'data-psgc_zip_code    ="'.(!empty($value) ? $psgc->zip_code    ?? ''   : '').'" ';
        
        $default = !empty($psgc) ? array('attr' => 'selected','text' => $psgc->psgc_convention_name ?? '') : array('attr' => '', 'text' => '');

        return '
          <select name="'.$name.'" id="'.$id.'" class="psgc-select2 '.$class.'" '.$attr.' style="'.$max_width.'" multiple>
          <option '.$default['attr'].' value="'.($psgc->brgyCode ?? '').'">'.$default['text'].'</option>
          </select>
        ';
        break;
      case 'position_lookup': # Position Lookup
        # Init
        $max_width    = 'width: 50% !important';
        $pos          = !empty($value) ? $CI->main->get_position_selected($value) : NULL;

        $classes  = (!empty($class)) ? explode(' ', $class) : array(0);
        
        $default      = !empty($pos) ? array('attr' => 'selected','text' => $pos->displayName ?? '') : array('attr' => '', 'text' => '');
        $selectedid   = !empty($pos) ? $pos->id : ''; 
        $isreadonly   = in_array('read-only-field', $classes) ? "disabled" : "";

        return '
          <select name="'.$name.'" id="'.$id.'" class="position-lookup-select2 '.$class.'" style="'.$max_width.'" '.$isreadonly.' multiple>
          <option '.$default['attr'].' value="'.$selectedid.'">'.$default['text'].'</option>
          </select>
        ';
        break;
      case 'textarea':
        $max_width   = !empty($value) ? 'max-width:400px' : '';
        return '<textarea name="'.$name.'" id="'.$id.'" class="'.$class.'"  style="'.$max_width.'">'.$value.'</textarea>';
        break;
      case 'radio':
        # Init
        $padding      = (!empty($value)) ? '' : 'padding-top:8px';
        $div          = (!empty($value)) ? '' : 'form-control';
        $radio_data   = $CI->user_access_model->get_radio($reference);
        $input        = '<div class="'.$div.'" style="border:unset!important;'.$padding.'">';
        $isadmin      = is_super_workgroup(844);
        $c            = "";
        $d            = "";

        foreach ( $radio_data as $res ):
          $selected = (!empty($value) && ($value == $res['grid'])) ? "checked" : '';
          
          // $d = !empty($value) && in_array( $res['grid'], array(552, 553) ) ? "disabled" : "";
          $c = empty($value) && $res['grid'] == 553 && $isadmin === FALSE ? ' checked' : '';
          $c_ = '';

          if (empty($value) && $res['grid'] == 552 && $isadmin === FALSE) {
            $c_ = 'disabled';
          } else if (!empty($value) && $res['grid'] == 552 && $isadmin === FALSE) {
            $c_ = 'disabled';
          } else if ($value == 552) {
            $c_ = 'disabled';
          }

          $input .= '<label style="font-size:14px;font-weight:unset">';
          $input .= '<input type="'.$type.'" class="'. $class . $c .'" ';
          $input .= 'data-strval="'.$res['referencename'].'" name="'.$name.'" id="'.$id.'" value="'.$res['grid'].'" style="min-width:unset!important" ';
          $input .= $selected ." ". $d ." ". $c ." ". $c_ .'>';

          $input .= " ". strtoupper( $res['referencename'] ).'</label>&nbsp';

        endforeach;
        $input .= '</div>';
        return $input;
        break;
      case 'checkbox_1':
        $checked = !empty($value) ? 'checked' : '';
        $max_width  = (strpos(current_url(), 'view') !== false) ? '' : '';
        return '<input type="checkbox" name="'.$name.'" id="'.$id.'" class="'.$class.'" value="1"  style="'.$max_width.';min-width:unset;margin-top:10px" '.$checked.'><div style="display:-webkit-inline-box;height:28px">'.$display.'</div>';
        break;
      case 'checkbox_2':
        $checked = !empty($value) ? 'checked' : '';
        $max_width  = (strpos(current_url(), 'view') !== false) ? '' : '';
        return '<input type="checkbox" name="'.$name.'" id="'.$id.'" class="'.$class.'" value="1"  style="'.$max_width.';min-width:unset;margin-top:10px" '.$checked.'><div style="display:-webkit-inline-box;height:28px">'.$display.'</div>';
        break;
      case 'checkbox_special':
        return '<input type="checkbox" name="'.$name.'"" id="'.$id.'" style="min-width: unset;"> '.$display;
        break;
      case 'button':
        $max_width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : 'width:440px';
        $readonly   = (strpos($class, 'datetime') !== false) ? 'readonly = "readonly"' : '';
        return '<button type="'.$type.'" name="'.$name.'" id="'.$id.'" class="'.$class.'" value="'.$value.'"  style="'.$max_width.'" '.$readonly.'>'.$display.'</button>';
        break;
      case 'coclookup':
        # Init
        $classes  = (!empty($class)) ? explode(' ', $class) : array(0);
        $isRequired = ( in_array('required-field', $classes) ) ? ' required-field' : '';
        $dval = $CI->user_access_model->get_cocDisplay($value);
        $dval = ($dval && !empty($dval->row())) ? $dval->row() : null;
        $cTitle = !empty($dval->article) ? $dval->article : '--';
        $cId =  !empty($dval->id) ? $dval->id : '';
        # Config
        $width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : '';
        $height = (strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;width:42px' : '';
        $html = '<div class="input-group" style="display:block!important">
                  <input type="text" id="dval-'.$id.'" class="display-coc '.$class.'" value="'.$cTitle.'" style="'.$width.'" readonly>
                      <span class="input-group-btn">
                        <button type="button" class="btn-lookup-coc btn btn-primary btn" data-dtarget="dval-'.$id.'" data-idtarget="cocid-'.$id.'" style="'.$height.'"><i class="fa fa-search"></i></button>

                        <button type="button" class="btn-clear btn btn-primary" data-target-display="dval-'.$id.'" data-target="cocid-'.$id.'" style="'.$height.'">
                          <i class="fa fa-times"></i>
                        </button>
                      </span>
                </div>
                <input type="hidden" class="coc-selectedid '.$isRequired.'" name="'.$name.'" id="cocid-'.$id.'" value="'.$cId.'" >';

        return $html;
        break;
      case 'infractioncaselookup': # Infraction Lookup Is Live bebe
        # Init
        $classes  = (!empty($class)) ? explode(' ', $class) : array(0);
        $isRequired = ( in_array('required-field', $classes) ) ? ' required-field' : '';
        $dval = !empty($value) ? $CI->main->get_infraction_map($value) : FALSE;
        $cTitle = $dval !== FALSE ? $dval->displayName : '--';
        $cId =  $dval !== FALSE ? $dval->infracmap_id : '';
        $isreadonly   = in_array('read-only-field', $classes) ? "disabled" : "";
        # Config
        $width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : '';
        $height = '';#(strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;width:42px' : '';
        $html = '<div class="input-group" style="display:block!important">
                  <input type="text" id="dval-'.$id.'" class="display-infraction '.$class.'" value="'.$cTitle.'" style="'.$width.'" readonly>
                      <span class="input-group-btn">
                        <button type="button" class="btn-lookup-infraction btn btn-primary btn" data-dtarget="dval-'.$id.'" data-idtarget="infrac-'.$id.'" style="'.$height.'" '.$isreadonly.'>
                          <i class="fa fa-search"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn btn-clear" data-target-display="dval'.$id.'" data-target="infrac-'.$id.'" style="'.$height.'">
                          <i class="fa fa-times"></i>
                        </button>
                      </span>
                </div>
                <input type="hidden" class="infraction-selectedid '.$isRequired.'" name="'.$name.'" id="infrac-'.$id.'" value="'.$cId.'" >';

        return $html;
        break;
      case 'audit_case_lookup': # Audit Case Lookup
        # Init
        $classes  = (!empty($class)) ? explode(' ', $class) : array(0);
        $isRequired = ( in_array('required-field', $classes) ) ? ' required-field' : '';
        // echo "<h2>".$display."</h2>";
        $dval = !empty($value) ? $CI->main->get_selected_auditcase_type($value) : FALSE;
        $cTitle = $dval !== FALSE ? $dval->row()->displayName : '--';
        $cId =  $dval !== FALSE ? $dval->row()->id : '';
        $isreadonly   = in_array('read-only-field', $classes) ? "disabled" : "";
        # Config
        $width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : '';
        $height = '';#(strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;width:42px' : '';
        $html = '<div class="input-group" style="display:block!important">
                  <input type="text" id="dval-'.$id.'" class="display-auditcasetype '.$class.'" value="'.$cTitle.'" style="'.$width.'" readonly>
                      <span class="input-group-btn">
                        <button type="button" class="btn-lookup-auditcasetype btn btn-primary btn" data-dtarget="dval-'.$id.'" data-idtarget="auditcasetype-'.$id.'" style="'.$height.'" '.$isreadonly.'>
                          <i class="fa fa-search"></i>
                        </button>
                        <button type="button" class="btn-clear btn btn-primary" data-target-display="dval-'.$id.'" data-target="auditcasetype-'.$id.'" style="'.$height.'">
                          <i class="fa fa-times"></i>
                        </button>
                      </span>
                </div>
                <input type="hidden" class="auditcasetype-selectedid '.$isRequired.'" name="'.$name.'" id="auditcasetype-'.$id.'" value="'.$cId.'" >';

        return $html;
        break;
      case 'summernote': # My Summernote
      
        # Init
        $val  = !empty($value) ? html_entity_decode( $value ) : ""; # Decoded
        
        $html = '
          <div class="my-summernote" id="'.$id.'" data-diplaydiv="display-'.$id.'" data-editdiv="edit-'.$id.'" data-attrname="'.$name.'">
            '. $val .'
          </div>
        ';

        return $html;
        break;
      case 'number':
        $max_width  = (strpos(current_url(), 'view') !== false) ? 'width:50px' : 'width:440px';
        $readonly   = (strpos($class, 'datetime') !== false) ? 'readonly = "readonly"' : '';
        $classes    = !empty($class) ? explode(' ', $class) : array();
        
        return '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" class="'.$class.'" value="'.$value.'"  style="'.$max_width.'" '.$readonly.'>';
        break;
      case 'progress':
       $text = !empty($value) ? $value : '0';
       $height = (strpos(current_url(), 'view') !== false) ? '25px' : '33px';

        $width  = (strpos(current_url(), 'view') !== false) ? 'width:350px' : 'width:390px';
        return '<div class="input-group" style="display:block!important">
                <input type="range" class="'.$class.'" name="'.$name.'" id="'.$id.'""   min="0" max="100" style="'.$width.';appearance:auto;padding:0px!important" oninput="'.$id.'_out_id.innerHTML  = '.$id.'.value+\'%\'" value="'.$text.'">
                <span class="input-group-addon" style="height:'.$height.';width:51px;border:none" id="'.$id.'_out_id">'.$text.'%</span>
              </div>';
       // return '<div class="RED"  style="'.$max_width.'"><input type="range" value="'.$text.'" name="'.$name.'" id="'.$id.'" class="'.$class.'"  style="'.$max_width.'" min="0" max="100" oninput="this.nextElementSibling.value = this.value">
               // <output>'.$text.'</output></div>';
        break;
      case 'label':
        $text = !empty($value) ? $value : '';
        $max_width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : 'width:440px';
        $readonly   = (strpos($class, 'datetime') !== false) ? 'readonly = "readonly"' : '';
        return '<p id="'.$id.'" class="'.$class.'"  style="'.$max_width.';height:24px;border:unset;padding-top:0px">'.$text.'</p>';
        break;
      case 'contract_of_lease_lookup': # Contract Of Lease Lookup
        # Init
        $classes  = (!empty($class)) ? explode(' ', $class) : array(0);
        $dval = false;#!empty($value) ? $CI->main->get_infraction_map($value) : FALSE;
        $cTitle = $dval !== FALSE ? $dval->displayName : '--';
        $cId =  $dval !== FALSE ? $dval->infracmap_id : '';
        $col = $CI->main->get_col_name($value);
        $isreadonly   = in_array('read-only-field', $classes) ? "disabled" : "";
        # Config
        $width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : '';
        $height = (strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;width:42px' : '';
        $html = '<div class="input-group" style="display:block!important">
                  <input type="text" id="dval-'.$id.'" class="display-col '.$class.'" value="'.$col->displayName.'" style="'.$width.'" readonly>
                      <span class="input-group-btn">
                        <button type="button" class="btn-col-lookup btn btn-primary btn" data-dtarget="dval-'.$id.'" data-idtarget="col-'.$id.'" style="'.$height.'" '.$isreadonly.'>
                          <i class="fa fa-search"></i>
                        </button>
                        <button type="button" class="btn-clear btn btn-primary btn" data-dtarget="dval-'.$id.'" data-idtarget="col-'.$id.'" style="'.$height.'">
                          <i class="fa fa-times"></i>
                        </button>
                      </span>
                </div>
                <input type="hidden" class="lease_selected_id " name="'.$name.'" id="col-'.$id.'" value="'.$value.'" >';

        return $html;
        break;
      case 'owner_dealer_lookup': # owner dealer Lookup
        # Init
        $classes  = (!empty($class)) ? explode(' ', $class) : array(0);
        $isRequired = ( in_array('required-field', $classes) ) ? ' required-field' : '';
        $dval = !empty($value) ? $CI->main->get_selected_owner_dealer($value) : FALSE;
        $cTitle = $dval !== FALSE ? $dval->row()->displayName : '--';
        $cId =  $dval !== FALSE ? $dval->row()->DealerId : '';
        $isreadonly   = in_array('read-only-field', $classes) ? "disabled" : "";
        # Config
        $width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : '';
        $height = (strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;width:42px' : '';
        if(in_array(223,explode(',',$_SESSION['work_group']))):
          if($maintenanceID>0):
            $html = '<div class="input-group" style="display:block!important">
                    <input type="text" id="dval-'.$id.'" class="display-ownerdealer '.$class.'" value="'.$cTitle.'" style="'.$width.'" readonly>
                        <span class="input-group-btn">
                          <button type="button" class="btn-lookup-ownerdealer btn btn-primary btn" data-dtarget="dval-'.$id.'" data-idtarget="ownerdealer-'.$id.'" style="'.$height.'" '.$isreadonly.'>
                            <i class="fa fa-search"></i>
                          </button>
                          <button type="button" class="btn-clear btn btn-primary" data-target-display="dval-'.$id.'" data-target="ownerdealer-'.$id.'" style="'.$height.'">
                            <i class="fa fa-times"></i>
                          </button>
                           <button type="button" class="btn-quick-add btn btn-primary" onclick= "quick_add('.$maintenanceID.')" data-target-display="dval-'.$maintenanceID.'" data-target="#modal-quick-add-'.$maintenanceID.'" style="'.$height.'"> <i class="fa fa-plus"></i> </button>
                         
                          
                        </span>
                  </div>
                  <input type="hidden" class="ownerdealer-selected-id"'.$isRequired.'" name="'.$name.'" id="ownerdealer-'.$id.'" value="'.$cId.'" >';
  
          return $html;
          else:
            $html = '<div class="input-group" style="display:block!important">
                      <input type="text" id="dval-'.$id.'" class="display-ownerdealer '.$class.'" value="'.$cTitle.'" style="'.$width.'" readonly>
                          <span class="input-group-btn">
                            <button type="button" class="btn-lookup-ownerdealer btn btn-primary btn" data-dtarget="dval-'.$id.'" data-idtarget="ownerdealer-'.$id.'" style="'.$height.'" '.$isreadonly.'>
                              <i class="fa fa-search"></i>
                            </button>
                            <button type="button" class="btn-clear btn btn-primary" data-target-display="dval-'.$id.'" data-target="ownerdealer-'.$id.'" style="'.$height.'">
                              <i class="fa fa-times"></i>
                            
                          </span>
                    </div>
                    <input type="hidden" class="ownerdealer-selected-id"'.$isRequired.'" name="'.$name.'" id="ownerdealer-'.$id.'" value="'.$cId.'" >';
    
            return $html;
          endif;
        else:
          $html = '<div class="input-group" style="display:block!important">
                    <input type="text" id="dval-'.$id.'" class="display-ownerdealer '.$class.'" value="'.$cTitle.'" style="'.$width.'" readonly>
                        <span class="input-group-btn">
                          <button type="button" class="btn-lookup-ownerdealer btn btn-primary btn" data-dtarget="dval-'.$id.'" data-idtarget="ownerdealer-'.$id.'" style="'.$height.'" '.$isreadonly.'>
                            <i class="fa fa-search"></i>
                          </button>
                          <button type="button" class="btn-clear btn btn-primary" data-target-display="dval-'.$id.'" data-target="ownerdealer-'.$id.'" style="'.$height.'">
                            <i class="fa fa-times"></i>
                          
                        </span>
                  </div>
                  <input type="hidden" class="ownerdealer-selected-id"'.$isRequired.'" name="'.$name.'" id="ownerdealer-'.$id.'" value="'.$cId.'" >';
  
          return $html;
        endif;
        
        
      case 'image-upload': # Image Upload
        $html = '<input type="file" name="'.$name.'" id="'.$id.'" class="'.$class.'" action="file" accept="image/*" enctype="multipart/form-data">';
        return $html;
        break;
      case 'campaignlookup':
        # Init
        $classes  = (!empty($class)) ? explode(' ', $class) : array(0);
        $dval = false;#!empty($value) ? $CI->main->get_infraction_map($value) : FALSE;
        $cTitle = $dval !== FALSE ? $dval->displayName : '--';
        $cId =  $dval !== FALSE ? $dval->infracmap_id : '';
        $campaign = $CI->main->getCampaignName($value);
        $display_name = empty($campaign) ? '--' : $campaign->diplay_name;
        $isreadonly   = in_array('read-only-field', $classes) ? "disabled" : "";
        # Config
        $width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : '';
        $height = (strpos(current_url(), 'view') !== false) ? 'height:22px;padding: unset!important;width:42px' : '';
        $html = '<div class="input-group" style="display:block!important">
                  <input type="text" id="dval-'.$id.'" class="display-campaign '.$class.'" value="'.$display_name.'" style="'.$width.'" readonly>
                      <span class="input-group-btn">
                        <button type="button" class="btn-campaign-lookup btn btn-primary btn" data-dtarget="dval-'.$id.'" data-idtarget="campaign-'.$id.'" style="'.$height.'" '.$isreadonly.'>
                          <i class="fa fa-search"></i>
                        </button>
                        <button type="button" class="btn-clear btn btn-primary btn" data-dtarget="dval-'.$id.'" data-idtarget="campaign-'.$id.'" style="'.$height.'">
                          <i class="fa fa-times"></i>
                        </button>
                      </span>
                </div>
                <input type="hidden" class="campaign_selected_id" name="'.$name.'" id="campaign-'.$id.'" value="'.$value.'" >';
        return $html;
        break;
      case 'double_field':
          $max_width  = (strpos(current_url(), 'view') !== false) ? 'width:100px' : 'width:100px';
          $readonly   = (strpos($class, 'datetime') !== false) ? 'readonly = "readonly"' : '';
          $classes    = !empty($class) ? explode(' ', $class) : array();
          $first_val = '';
          $second_val = '';
          if(in_array('down-time', $classes)) {
          
            if(!empty($value)) {
              if (strpos($value, ':') !== false) {
                $value = explode(':', $value);
                $first_val = $value[0];
                $second_val = isset($value[1]) ? $value[1] : '';
              } else {
                $first_val = sprintf("%02d", floor($value / 60));
                $second_val = sprintf("%02d", $value % 60);
              }
              
            }

            $first_field = '<div class="col-sm-3"><input type="text" name="'.$name.'_hh" id="'.$id.'_hh_id" class="'.$class.'" value="'.$first_val.'"  style="'.$max_width.'" ></div>';
            $label = '<label class="col-sm-2" style="min-width:75px;width:70px;color:#7c7373; font-weight: unset;">Minutes:</label>';
            $second_field = '<div class="form-group row">'.$label.'
            
            <input type="text" name="'.$name.'_mm" id="'.$id.'_mm_id" class="'.$class.' " value="'.$second_val.'"  style="'.$max_width.'" ></div>';
            
            $div = '<div class="row">'.$first_field.$second_field.'</div>';
            return $div;
          }

          $first_field = '<div class="col-sm-2"><input type="text" name="'.$name.'_hh" id="'.$id.'_hh_id" class="'.$class.'" value="'.$first_val.'"  style="'.$max_width.'" ></div>';
          $second_field = '<div class="col-sm-2"><input type="text" name="'.$name.'_mm" id="'.$id.'_mm_id" class="'.$class.'" value="'.$second_val.'"  style="'.$max_width.'" ></div>';
          
          $div = '<div class="row">'.$first_field.$second_field.'</div>';
          return $div;
        break;
      default:
        $max_width  = (strpos(current_url(), 'view') !== false) ? 'width:400px' : 'width:440px';
        $readonly   = (strpos($class, 'datetime') !== false) ? 'readonly = "readonly"' : '';
        $classes    = !empty($class) ? explode(' ', $class) : array();

        if ( in_array('date-created-field', $classes) ) :

          $d = date_create($value);
          if ( !empty($value) && $d !== FALSE ) :
            $value = date_format($d, "m-d-Y");
          else:
            $value = date("m-d-Y");
          endif;

          return '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" class="'.$class.'" value="'.$value.'"  style="'.$max_width.'" >';
        else:
              
          return '<input type="'.$type.'" name="'.$name.'" id="'.$id.'"  class="'.$class.'" value="'.$value.'"  style="'.$max_width.'" '.$readonly.'>' . ($id == 'wc_mcenginenumber' ? '<span id="verify-engine"> </span>': ' ');
        endif;
        
        break;
      case 'branch_select' :
        // $max_width   = 'width: 50% !important';
        $max_width  = (strpos(current_url(), 'view') !== false) ? 'width: 75% !important' : 'width: 50% !important';
        $branchs = $CI->branch_hierarchy->forBranchSelect2();
        $option = '';
        // $selected_branch = explode(',',$value);
        $default = 'selected';
        foreach ($branchs as $branch) {
          if($branch->branch_code == $value) {
            $selected = 'selected';
            $default = '';
          } else {
            $selected = '';
          }
          $option .= '<option value="'.$branch->branch_code.'" '.$selected.'>'.$branch->branch_code.' | '.strtoupper($branch->branch_name).'</option>';
        }

        return '
          <select name="'.$name.'" id="'.$id.'" class="branch-select2 '.$class.'" style="'.$max_width.'" multiple>
          '.$option.'
          </select>
        ';

        break;
      case 'visitation_issue_tag' :
        // $max_width   = 'width: 50% !important';
        $max_width  = (strpos(current_url(), 'view') !== false) ? 'width: 100% !important' : 'width: 50% !important';
        //$tags = $CI->visitation->forIssueTag();
        $option = '';

        if (!empty(trim($value))):
          $selected_issue = explode(',',$value);
          foreach ($selected_issue as $val) :
            $option .= '<option value="'.$val.'" selected>'.strtoupper($val).'</option>';
          endforeach;
        endif;
        
        return '
          <select name="'.$name.'[]" id="'.$id.'" class="issue-tag-select2 '.$class.'" style="'.$max_width.'" multiple>
          '.$option.'
          </select>
        ';


        break;
      case 'link_ticket':
        // $max_width   = 'width: 50% !important';
        $max_width  = (strpos(current_url(), 'view') !== false) ? 'width: 120% !important' : 'width: 50% !important';
        // $tickets = $CI->main->getTicketList($reference);
        $option = '';

        if(!empty($value)) {
          $tickets = explode(',', $value);

          foreach ($tickets as $ticket) {
            $option .= '<option value="'.$ticket.'" selected>'.$ticket.'</option>';
          }
        }

        return '
          <select name="'.$name.'[]" id="'.$id.'" class="link-ticket-select2 '.$class.'" style="'.$max_width.'" data-formid=53 multiple>
          '.$option.'
          </select>
        ';
        break;
      case 'date_range': # Date Range
        $dateFrom   = '';
        $dateTo     = '';

        if (!empty($value) && (trim($value) != '--' || trim($value) != '##')):
          $rawDate = explode('##', $value);
          $dateFrom = date_create(trim($rawDate[0]));
          $dateTo   = date_create(trim($rawDate[1]));
          $dateFrom = $dateFrom !== FALSE ? date_format($dateFrom, "m-d-Y") : '';
          $dateTo   = $dateTo !== FALSE ? date_format($dateTo, "m-d-Y") : '';
        endif;

        return "
          <div class='row'>
            <div class='col-md-3' style='padding-right:unset !important'>
              <input type='text' autocomplete='off' class='{$class}' name='{$name}_from' id='{$id}_from' value='{$dateFrom}' placeholder='Date Start'>
            </div>
            <div class='col-md-3' style='padding-left:4px !important'>
              <input type='text' autocomplete='off' class='{$class}' name='{$name}_to' id='{$id}_to' value='{$dateTo}' placeholder='Date End'>
            </div>
          </div>
        ";
        break;
    endswitch;
  }
}

if(!function_exists('has_access'))
{
  function has_access($form)
  {
    $key = array_search($form, array_column($_SESSION['user_form'], 'form'));
    if($key === false ):
      $form_array = empty($key) ? array() : $_SESSION['user_form'][$key];
    else:
      $form_array =  $_SESSION['user_form'][$key];
    endif;

    /**
     * Modified by Russ: 06/30/21
     * Added Checking Of Company
     */

    if (empty($form_array)) return array();

    $CI           = get_instance();
    $CI->load->model('Lookup_Model', 'm_lookup');
    $company_code = $_SESSION['company_code'];
    $wids         = $form_array['workgroupid'];
    if ($CI->m_lookup->has_access_company($wids, $company_code) === FALSE) return [];

    return $form_array;
  }
}

if(!function_exists('data_query_acces'))
{
  function data_query_acces()
  {
    $CI = get_instance();
    $CI->load->model('main_model');
    $access = $CI->main_model->data_query_acces();
    return $access;
  }
}


if(!function_exists('column_as_row'))
{
  function column_as_row($array)
  {
    $new_array = array_map (function($value){
     return $value['label']; 
    } , $array);
    return $new_array;
  }
}


if(!function_exists('get_sub_array'))
{
  function get_sub_array($array)
  {
    if ( isset($array [0]) ) {
      return array_values($array[0]);
    }
  }
}

if(!function_exists('view_data'))
{
  function view_data($value_array, $attributes_array)
  {
    $new_array = array();
      if(!empty($attributes_array)){
        foreach ($value_array as $key => $value) {
          $new_array[] =  $attributes_array[$key];
        }
        foreach ($value_array as $key => $value) {
          $new_array[$key]['current_value'] =  $value;
        }
        return $new_array;
      }
      return array();

  }
}

if(!function_exists('get_full_name'))
{
  function get_full_name($formid,$recordid)
  {
    $CI = get_instance();
    $CI->load->model('main_model');
    $fullname = $CI->main_model->get_full_name($formid,$recordid);
    return strtoupper($fullname);
  }
}

function manual_tab( $id ) # Form Creation
{
  $header = '';
  $content = '';
  switch ($id) :
    case '99': //3
        $header   = '<li class=""><a href="#Parts" data-toggle="tab" aria-expanded="true">Parts</a></li>';
        $content  = '<div class="tab-pane fade in" id="Parts" style="border: solid 1px #ccc;padding:3px;">';
        $content .= '<br>';
        $content .= '<div class="row">';
        $content .= '<div class="col-sm-12" >';
        $content .= '<table class="table table-bordered table-striped  table-hover" id="table_parts" style="width:100%"></table>';
        $content .= '</div></div></div></div>'; 
      break;
    default:
      # NO ACTION
      break;
  endswitch;

  # Attachment Will be in the tblform Configuration
  if (in_array($id, $_SESSION['attachment_tab'])):
    $header  .= '<li class=""><a href="#attachments-tab-formcreate" data-toggle="tab" aria-expanded="true">Attachments</a></li>';
    $content .= '<div class="tab-pane fade in" id="attachments-tab-formcreate" style="border: solid 1px #ccc;padding:3px;">';
    $content .=   '<div class="row">';
    $content .=     '<div class="col-sm-12" id="attachments-tab-formcreate-container"> LOAD VIA AJAX </div>';
    $content .=   '</div>';
    $content .= '</div>';
  endif;

  if (in_array($id, isQuestionTab())):
    if($_SESSION['box_id'] == 12):
       $header  .= '<li class=""><a href="#tab_questionnaire" data-toggle="tab" aria-expanded="true">Checklist (Questions)</a></li>';
    else:
       $header  .= '<li class=""><a href="#tab_questionnaire" data-toggle="tab" aria-expanded="true">Checklist (Questions)</a></li>';
    endif;
    $content .= '<div class="tab-pane fade in" id="tab_questionnaire" style="border: solid 1px #ccc;padding:3px;">';
    $content .=   '<div class="row">';
    $content .=     '<div class="col-sm-12" id="tab_questionnaire_container"> LOAD VIA AJAX </div>';
    $content .=   '</div>';
    $content .= '</div>';
  endif;

  $tab = new stdClass;
  $tab->header  = $header  ?? ''; 
  $tab->content = $content ?? '';
  return  $tab;
}

function view_manual_tab($id,$record,$max_width = 0 )
{
  $header = '';
  $content = '';
  switch ($id):
    case 34: //Loan table
      if (in_array($record->categoryid, array(149,150,151,152,206))) { //&& $record->StatusId == 243
         $CI = get_instance();
         $CI->load->model('form_model');
        //  $table = $CI->form_model->get_loan_inquiry_list($record->id,$record->LoanId);
        $table = $CI->form_model->get_loan_inquiry_list($record->id,$record->LoanId);
        $object = $table !== FALSE ? (object)$table->row() : (object)'';
        $header  .= '<li class=""><a href="#attachments-tab-formcreate" data-toggle="tab" aria-expanded="true">Loan Details</a></li>';
        $content .= '<div class="tab-pane fade in" id="attachments-tab-formcreate" style="border: solid 1px #ccc;padding:3px;">';
        $content .=   '<div class="row">';
        //$content .=     '<div class="col-sm-12" style="padding:20px 35px">';
        //$content .=       '<center>';
        $content .= '<table class="table table-bordered rb rr rl rt">';
        /*$content .=         '<table class="table table-bordered table-striped  table-hover" id="table-inquiry" style="width:30%">';
        $content .=           '<thead>';
        $content .=             '<tr>';
        $content .=               '<th>ID</th>';
        $content .=               '<th>Status</th>';
        $content .=             '</tr>';
        $content .=           '</thead>';*/
        $content .=           '<tbody>';
        //                    foreach ($table->result_array() as $key => $res):
        $content .=             '<tr>';
        $content .= '<td style="text-align:right;width:'.$max_width.'px;color:#7c7373">Loan ID:</td>';
        $content .= '<td><a href="#" onclick="inquiry_redirect('.null_obj($object,'id').')">'.null_obj($object,'display_id').'</a></td>'; //$res['display_id']
        $content .=              '</tr>';
        $content .=             '<tr>';
        $content .= '<td style="text-align:right;width:'.$max_width.'px;color:#7c7373">Status:</td>';
        $content .=               '<td>'.null_obj($object,'status').'</td>'; //$res['status']
        $content .=              '</tr>';
        $content .=             '</form>';
        //                    endforeach;
        $content .=           '</tbody>';
        $content .=         '</table>';
        //$content .=       '</center>';
        //$content .=     '</div>';
        $content .=   '</div>';
        $content .= '</div>';
      }
      break;
    case 83: # LTRS APPLICANT
      $CI = get_instance();
      $CI->load->model('Lookup_model', 'm_lookup');
      $traineeList = $CI->m_lookup->get_filter_trainee($record->id);
      
      $header  .= '<li class=""><a href="#trainee-tab-list" data-toggle="tab" aria-expanded="true">Trainee List</a></li>';
      $content .= '<div class="tab-pane fade in" id="trainee-tab-list" style="min-height: 200px;
        border-width: thin;
        border-color: #efebeb;
        border-style: solid;
        border-bottom:unset;
      ">';
      $content .= '<div class="col-md-6 table-responsive" style="padding: 1em">';
      $content .= '<div class="row" style="margin-bottom: 1em">';
      $content .= '<div class="col-md-3">';
      $content .= '<button type="button" class="btn btn-primary btn-md" id="btn-add-trainees">';
      $content .= '<i class="fa fa-plus"></i> Add Trainees';
      $content .= '</button>';
      $content .= '</div>'; # End of col-md-3
      $content .= '</div>'; # End of row
      
      
      $content .= '
        <table class="table table-striped table-enrolled-trainees" id="table-enrolled-trainees">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">TraineeID</th>
              <th scope="col">Full Name</th>
            </tr>
          </thead>
          <tbody>';

          if ( $traineeList === FALSE || $traineeList->num_rows() < 1 ) {
            $content .= '<tr> <th scope="row" colspan="3" style="text-align:center">No Record</th> </tr>';
          } else {
            $i = 1;
            foreach ( $traineeList->result() as $list ):
              $href = base_url() .'record-list/view/'.$list->TraineeID.'/82';
              $content .= '<tr>';
              $content .= '<th>'.$i.'</th>';
              $content .= '<th><a href="'.$href.'">'.$list->TraineeID.'</a></th>';
              $content .= '<th>'.$list->TraineeName.'</th>';
              $content .= '</tr>';

              $i++;
            endforeach;
          }

      $content .='</tbody></table>';
      $content .=   '</div>';
      $content .= '</div>';
      break;
    default:
      # NO ACTION
      break;
  endswitch;

  if (in_array($id, isQuestionTab())):
    if($_SESSION['box_id'] == 12):
       $header  .= '<li class=""><a href="#tab_questionnaire" data-toggle="tab" aria-expanded="true">Checklist (Questions)</a></li>';
    else:
       $header  .= '<li class=""><a href="#tab_questionnaire" data-toggle="tab" aria-expanded="true">Checklist (Questions)</a></li>';
    endif;
    $content .= '<div class="tab-pane fade in" id="tab_questionnaire" style="border: solid 1px #ccc;padding:3px;">';
    $content .=   '<div class="row">';
    $content .=     '<div class="col-sm-12" id="tab_questionnaire_container"> Loading, please wait! </div>';
    $content .=   '</div>';
    $content .= '</div>';
  endif;

  $tab = new stdClass;
  $tab->header  = $header  ?? '';
  $tab->content = $content ?? '';
  return  $tab;
}

if(!function_exists('required_status'))
{
  function required_status($fields,$parameter,$form_id = 0,$formrecordid = 0, $view_values)
  {
    $type_name = '';
    $name_image = '';
      $page_mode = strpos(current_url(), 'create_page');
      if($page_mode !== false){
        $labelsz = '4'; 
        $fieldsz = '9';
      } else{
        $labelsz = '4';
        $fieldsz = '8';
      }
      $form = '';
        $form .='<div class="row">';
        $CI = get_instance();
        $CI->load->model('Change_Status_model', 'change_status_model');  
        $fieldIds = $CI->change_status_model->getFieldsByFormId($form_id);
        $fieldIds = explode(',',$fieldIds->fields);
        $positionIndex = 0;
        $ColumnName = '';
        
          foreach ($fields as $subkey => $subfield) {
            $positionIndex = array_search($subfield['fieldid'], $fieldIds);

            // var_dump($positionIndex, $subfield['fieldid']); die();
 
            $i = 0;
            foreach($view_values[$i] as $k => $v):
              if ($i == $positionIndex):
                  // if (strpos(strtolower($k), 'if') >= 0):
                  //   $extract = explode('.', $k);
                  //   $rmSpaces = explode(' ', $extract[1]);
                  //   $k = $rmSpaces[0];
                  // endif;
                  
                  $ColumnName = $k;
                  break;
                endif;
              $i++;
            endforeach;
    
            $recordData = getItemRecord($form_id, $formrecordid);
            // var_dump($recordData->$ColumnName);die();
            if (!empty($recordData->$ColumnName)) continue;
            $req = !empty($subfield['IsRequired']) ? '<span class="text-danger">*</span> ' : '';
            if ($subfield['type'] == 'hidden') {
              $form .= converter($subfield['type'], $subfield['name'], $subfield['id'], $subfield['class'], $subfield['reference'], $subfield['value'], $subfield['display'], $value = '', $param = array(), $subfield['emplookupref'],$subfield['MaintenanceID']);
            }else{
              $form .= '<div class="form-group required-main-div" id="div-'. $subfield['id'].'" data-columnname="'.$ColumnName.'">';
              $subFieldName = $subfield['name'];
              $form .= "<input type='hidden' name='columnName[{$subFieldName}]' value='{$ColumnName}'>";
              $field_label = wordwrap($subfield['label'],30,"<br>\n");
              $form .= '<label  class="col-sm-'.$labelsz.' control-label mg-top" style="text-align:right;padding-right: unset!important;font-weight:unset!important;color:#7c7373">'.$req.$subfield['label'].':</label>';
              $form .= '<div class="col-sm-'.$fieldsz.'" >';
              if($subfield['parameter'] == 1)
              {
                $form .= converter($subfield['type'], $subfield['name'], $subfield['id']."_dynamic", $subfield['class'], $subfield['reference'], $subfield['value'], $subfield['display'],'',$parameter[$positionIndex], $subfield['emplookupref'],$subfield['optiongroup'],$subfield['IsSort'],$subfield['MaintenanceID']);
                // $form .= converter($subfield['type'], $subfield['name'], $subfield['id'], $subfield['class'], $subfield['reference'], $subfield['value'], $subfield['display'], $value='', $param = array(), $subfield['emplookupref'],$subfield['optiongroup'] ,$subfield['IsSort'],$subfield['MaintenanceID'])."<br>";
              }else{ 
                $form .= converter($subfield['type'], $subfield['name'], $subfield['id']."_dynamic", $subfield['class'], $subfield['reference'], $subfield['value'], $subfield['display'], $value='', $param = array(), $subfield['emplookupref'],$subfield['optiongroup'] ,$subfield['IsSort'],$subfield['MaintenanceID'])."<br>";    
              } 
              $type_name = $subfield['type'];
              $name_image = $subfield['name'];
              $form .= '</div>';
              $form .= '</div>';
            }
          }
          $form .='</div>';
          if($type_name == 'image-upload') {
            $form .= '<br><div class="row">
            <div class="col-sm-3 gallery-series-'.$name_image.'"  style="text-align:right">
            </div>
            <div class="col-sm-9">
            <img id="expandedImg_'.$name_image.'" style="width:100%">
            <div id="imgtext_'.$name_image.'"></div>
            </div>
            </div>';
          }
          $form .= '<br>';
          $form .= '</div>';
                       
      $form .= '</div>';
      $form .= '</div>';
      return $form;
  }
}

if(!function_exists('create_form'))
{
  function create_form($fields,$parameter,$form_id = 0)
  {  
      $type_name = '';
      $name_image = '';
      $page_mode = strpos(current_url(), 'create_page');
      if($page_mode !== false){
        $labelsz = '3'; 
        $fieldsz = '9';
      } else{
        $labelsz = '4';
        $fieldsz = '8';
      }
      $form  = '<div class="nav-tabs-custom">';
      $form .= '<ul class="nav nav-tabs">';
      
        $current_group = ''; 
        $active        = "active"; 
          foreach ($fields as $key => $result): 
            // var_dump($result);die();
              if(empty($result['GroupTab'])){ $current_group = $result['GroupTab']; } 
              if($current_group != $result['GroupTab'])
              { 
                $form .= '<li class="'.$active.'"><a href="#'.clean_string($result['GroupTab']).'" data-toggle="tab" aria-expanded="true">'.$result['GroupTab'].'</a></li>';
              } 
                $current_group = $result['GroupTab']; 
                $active        = "";
          endforeach;
          $form .= manual_tab($form_id)->header; 
      $form .= '</ul>';
      $form .= '<div class="tab-content">';
              
        $current_group = ''; 
        $active        = "active"; 
          foreach ($fields as $key => $result):
               if(empty($result['GroupTab'])){ $current_group = $result['GroupTab']; } 
               if($current_group != $result['GroupTab'])
               { 
                  $form .='<div class="tab-pane fade in '.$active.'" id="'.clean_string($result['GroupTab']).'" style="border: solid 1px #ccc;">';
                  $form .='<br>';
                  $form .='<div class="row">';
                    foreach ($fields as $subkey => $subfield) {
                      // print_r(json_encode($subfield));
                        if($subfield['GroupTab'] ==  $result['GroupTab'])
                        { 
                          $req = !empty($subfield['IsRequired']) ? '<span class="text-danger">*</span> ' : '';
                          
                          if ($subfield['type'] == 'hidden') {
                            $form .= converter($subfield['type'], $subfield['name'], $subfield['id'], $subfield['class'], $subfield['reference'], $subfield['value'], $subfield['display'], $value = '', $param = array(), $subfield['emplookupref'],$subfield['MaintenanceID']);
                            }else{
                            $form .= '<div class="form-group" id="div-'. $subfield['id'].'">';
                            $field_label = wordwrap($subfield['label'],30,"<br>\n");
                            $form .= '<label  class="col-sm-'.$labelsz.' control-label mg-top" style="text-align:right;padding-right: unset!important;font-weight:unset!important;color:#7c7373">'.$req.$subfield['label'].':</label>';
                            $form .= '<div class="col-sm-'.$fieldsz.'" >';
                            if($subfield['parameter'] == 1)
                            {
                              // $form .= converter($subfield['type'], $subfield['name'], $subfield['id'], $subfield['class'], $subfield['reference'], $subfield['value'], $subfield['display'],'',$parameter[$subkey], $subfield['emplookupref'],$subfield['optiongroup'],$subfield['IsSort']);
                              $form .= converter($subfield['type'], $subfield['name'], $subfield['id'], $subfield['class'], $subfield['reference'], $subfield['value'], $subfield['display'],'',$parameter[$subkey], $subfield['emplookupref'],$subfield['optiongroup'],$subfield['IsSort'],$subfield['MaintenanceID']);
                            }else{ 
                              $form .= converter($subfield['type'], $subfield['name'], $subfield['id'], $subfield['class'], $subfield['reference'], $subfield['value'], $subfield['display'], $value='', $param = array(), $subfield['emplookupref'],$subfield['optiongroup'] ,$subfield['IsSort'],$subfield['MaintenanceID']);    
                            } 
                            $type_name = $subfield['type'];
                            $name_image = $subfield['name'];
                            $form .= '</div> ';
                            $form .= '</div>';
                          }
                        }
                    }
                  $form .='</div>';
                  if($type_name == 'image-upload') {
                    $form .= '<br><div class="row">
                                <div class="col-sm-3 gallery-series-'.$name_image.'"  style="text-align:right">
                                </div>
                                <div class="col-sm-9">
                                    <img id="expandedImg_'.$name_image.'" style="width:100%">
                                    <div id="imgtext_'.$name_image.'"></div>
                                </div>
                              </div>';
                  }

                  $form .= '<br>';
                  $form .= '</div>';
                }
                  $current_group = $result['GroupTab']; 
                  $active        = ""; 
                  endforeach;
                  $form .= manual_tab($form_id)->content; 
      $form .= '</div>';
      $form .= '</div>';
      return $form;
  }
}

if(!function_exists('view_form'))
{
  function view_form($fields,$drop_down_status,$statusid,$status_display,$can_change_status,$can_edit,$parameter,$form_id,$RecordId)
  {
    $maxarray = array();
    foreach ($fields as $getmaxsize){
      if (!empty($getmaxsize['label'])) {
        $maxarray[] = $getmaxsize['label'];   
      }
       
    }
    $maxlen = 0;
    if(!empty($maxarray)){
      $maxlen = max(array_map('strlen', $maxarray)) * 10;
    }
    $CI = get_instance();
    $CI->load->model('user_access_model');
    $CI->load->model('Main_model', 'main');
    echo "<style>input,select{
        max-height: 22px!important;
      }</style>";
    $style="    
      min-height: 200px;
      border-width: thin;
      border-color: #efebeb;
      border-style: solid;
      border-bottom:unset;
    ";
    $form  = '<div class="nav-tabs-custom">';
    $form .= '<ul class="nav nav-tabs">';
              
    $current_group = ''; 
    $active        = "active"; 
    foreach ($fields as $key => $result): 
        if(empty($result['GroupTab'])){ $current_group = $result['GroupTab']; } 
        if($current_group != $result['GroupTab'])
        { 
          $form .= '<li class="'.$active.'"><a href="#'.clean_string($result['GroupTab']).'" data-toggle="tab" aria-expanded="true">'.$result['GroupTab'].'</a></li>';
        } 
          $current_group = $result['GroupTab']; 
          $active        = ""; 
    endforeach;
    if ($form_id == 79) {
      $form .= '<li class="active"><a href="#branch_log_tab" class="branch_log_tab" data-toggle="tab" aria-expanded="true">Branch Log</a></li>';
      $form .= '<li class><a href="#branch_image_tab" class="branch_image_tab" data-toggle="tab" aria-expanded="true">Branch Image</a></li>';
    }
    $form .= view_manual_tab($form_id,$RecordId,$maxlen)->header; 
    if ( in_array($form_id, $_SESSION['email_collaborator_tab']) ){ # Email Collaborator Tab
      $form .= '<li class><a href="#email_tab" class="email_tab" data-toggle="tab" aria-expanded="true">Collaborator</a></li>';
    }
    $form .= '</ul>';
    $form .= '<div class="tab-content tab-edit">';
    $current_group = ''; 
    $active        = "active";
    $i = 0;
    $mm = ''; // IT HELPDESK DOWN TIME
          foreach ($fields as $key => $result):

            if(empty($result['GroupTab'])) $current_group = $result['GroupTab'];
            
            if($result['GroupTab'] == 'Branch Detail') {
              $active = '';
            }

            if($current_group != $result['GroupTab']) :
               
              $form .= '<div class="tab-pane fade in '.$active.'" id="'.clean_string($result['GroupTab']).'" style="'.$style.'">';
              $form .= '<div>';
              $form .= '<table class="table table-bordered rb rr rl rt">';
              if($key == 0 && $can_change_status == 1) {
                //$form .= change_status_div($drop_down_status,$statusid,$status_display,$maxlen);
              }
                    //$maxlen = max(array_map('strlen', $fields));
                    //print_r($fields['label']);
              foreach ($fields as $keys=> $subfield):
                $req = !empty($subfield['IsRequired']) ? '<span class="text-danger">*</span> ' : '';
                if($subfield['GroupTab'] ==  $result['GroupTab'])
                {
                  # Init
                  $fieldClasses = ( !empty($subfield['class']) ) ? explode(' ', $subfield['class']) : array(0);
                  $display      = $subfield['current_value'];
                  $fieldid      = $subfield['id'];

                  $form .= '<tr class="field-display" id="display-'.$fieldid.'">';
                  $formlabel = ($subfield['type'] == 'hidden')  ? '' : '<td style="min-width:101px;width:'.$maxlen.'px;color:#7c7373">'.$req.$subfield['label'].':</td>';
                  $form .= $formlabel;
                  
                  # Field Type Configuration
                  if ( trim($subfield['type']) == 'label' ) # Checkbox_1 
                  {
                    $display = $subfield['current_value']." ";
                  }
                  else if ( trim($subfield['type']) == 'progress' ) # Checkbox_1 
                  {
                    $display = !empty($subfield['current_value']) ? $subfield['current_value']."%" : '0%';
                  }
                  else if ( trim($subfield['type']) == 'checkbox_1' ) # Checkbox_1 
                  {
                    $display = !empty($subfield['current_value']) ? 'Same as Present' : '--';
                  }
                  else if ( trim($subfield['type']) == 'employeelookup') # Employee Lookup 
                  {
                    $employee;
                    if(!empty($subfield['current_value'])){
                      $employee = $CI->main->get_employee_display($subfield['current_value']);
                      $employeeName = ( isset($employee) && !empty($employee) ) ? $employee->displayName : $subfield['current_value'] ?? '--';
                      $display = $employeeName;
                    } else {
                      $display = '--';
                    }
                    
                  }
                  else if ( trim($subfield['type']) == 'employeelookup-disabled') # Employee Lookup 
                  {
                    $employee;
                    $employee = $CI->main->get_employee_display($subfield['current_value']);
                    $employeeName = ( isset($employee) && !empty($employee) ) ? $employee->displayName : $subfield['current_value'] ?? '--';
                    $display = $employeeName;
                  }
                  else if( trim($subfield['type']) == 'branchlookup' ) # Branch Lookup 
                  {
                    $is_orgid = in_array('get_org_id', $fieldClasses) ? $subfield['current_value'] : FALSE;
                    $branch   = $CI->main->get_branch_display($subfield['current_value'], $is_orgid);
                    $display  = $branch !== FALSE ? $branch->displayName ?? '--' : '--';
                  }
                
                  else if ($subfield['type'] == 'systemlookup'){
                    $display = $CI->user_access_model->get_system_display($subfield['current_value'])->display;    
                  }
                  else if ( $subfield['type'] == 'dropdown'  ){
                      $dropdown_val =  $CI->user_access_model->get_display($subfield['reference'], $subfield['value'], $subfield['current_value'] ,$subfield['display']);
                      $display = !empty($dropdown_val) && strtoupper(trim($dropdown_val)) != 'N/A' ? $dropdown_val : "--";
                      
                      if(!empty($fieldClasses) && !empty($subfield['current_value']) && in_array('overall-status-color', $fieldClasses)):
                        $color = overAllStatusColor($subfield['current_value']);
                        // var_dump($color);
                        $display = "<span class='{$color}'>{$display}</span>";
                      endif;
                  }
                  else if ( $subfield['type'] == 'radio'  ){
                    $display = !empty($subfield['current_value']) && $subfield['current_value'] != '' ? $CI->user_access_model->get_display('tbl_globalreference', 'grid',  $subfield['current_value'],'referencename') : $subfield['current_value'];
                  }
                  else if ( trim($subfield['type']) == 'coclookup' ) { # COC Lookup
                    $display = '--';
                    $cocid  = $subfield['current_value'];
                    $get_coc = $CI->user_access_model->get_cocDisplay($cocid);
                    if ( !empty($cocid) && $get_coc !== FALSE ):
                      $display = $get_coc->row()->article;
                    endif;
                  }
                  else if ( trim($subfield['type']) == 'psgc_lookup' ) { # PSGC Lookup
                    $get_psgc = $CI->main->get_psgc_view("", $subfield['current_value']);
                    $display = !empty($subfield['current_value']) && is_numeric($subfield['current_value']) && $get_psgc->num_rows() >0 ?
                    $get_psgc->row()->psgc_convention_name
                    : '--';
                  }
                  else if ( trim($subfield['type']) == 'position_lookup' ) { # Position Lookup
                    $get_position = $CI->main->get_position_selected(trim($subfield['current_value']));
                    $display = !empty($subfield['current_value']) && !empty($get_position->displayName) ?
                    $get_position->displayName
                    : '--';
                  }
                  else if ( trim($subfield['type']) == 'audit_case_lookup' ) { # Audit Case Lookup
                    $casetype = $CI->main->get_selected_auditcase_type($subfield['current_value']);
                    $display = !empty($subfield['current_value']) && !empty($casetype->row()->displayName) ?
                    $casetype->row()->displayName
                    : '--';
                  }
                  else if ( trim($subfield['type']) == 'infractioncaselookup' ) { # Infraction Case Lookup
                    $dval = !empty($subfield['current_value']) ? $CI->main->get_infraction_map($subfield['current_value']) : FALSE;
                    $display = $dval !== FALSE ? $dval->displayName : '--';
                  }
                  else if( trim($subfield['type']) == 'campaignlookup' ) # Branch Lookup 
                  {
                    $campaign   = $CI->main->getCampaignName($subfield['current_value']);
                    $display  = $campaign !== FALSE ? $campaign->diplay_name ?? '--' : '--';
                  }
                  else if( trim($subfield['type'] == 'ltrs_training_list_lookup') ) # LTRS Training List Lookup
                  {
                    $trainingName = $CI->main->displayTrainingTitle($subfield['current_value']);
                    // var_dump($subfield['current_value']);die();
                    if ( empty($trainingName) ) {
                      $display = '--';
                    } else {
                      $href = base_url() . 'record-list/view/'.$subfield['current_value'].'/83';
                      $display = '<a href="'.$href.'">'.$trainingName.'</a>';
                    }
                  }
                  else if ( trim($subfield['type']) ==  'double_field')
                  {
                    if(!empty($subfield['current_value'])){
                      if(strpos($subfield['current_value'], ':') !== false){
                        $a = explode(':', $subfield['current_value']);
                        $display = $a[0];
                        $mm = $a[1];
                      } else {
                        $num = $subfield['current_value'];
                        $display = sprintf("%02d", floor($num / 60));
                        $mm = sprintf("%02d", $num % 60);
                      }
                    } else {
                      $display = '--';
                      $mm = '--';
                    }
                  }
                  else if( trim($subfield['type']) == 'branch_select' ) # Branch Select2
                  {
                    $branch   = $CI->branch_hierarchy->getBranchSelect2($subfield['current_value']);
                    $display  = $branch !== FALSE ? $branch->branch_code.' | '.$branch->branch_name ?? '--' : '--';
                  }
                  elseif(trim($subfield['type']) == 'visitation_issue_tag')
                  {
                    $issue_id = explode(',',$subfield['current_value']);

                    if(!empty($issue_id)) {
                      $d='';
                      foreach ($issue_id as $issue) {
                        $d .= $issue.', ';
                      }
                      $display = substr($d, 0, -2);
                    } else {
                      $display = '--';
                    }
                  }
                  elseif(trim($subfield['type']) == 'link_ticket')
                  {
                    if(empty($subfield['current_value'])){
                      $display = '--';
                    } else {
                      $tickets = explode(',', $subfield['current_value']);
                      $list = '';
                      foreach ($tickets as $ticket) {
                        $list .= ' <a target="_blank" href="'.base_url().'/record-list/view/'.$ticket.'/'.$subfield['reference'].'">'.$ticket.'</a>';
                      }
                      $display = $list;
                    }
                  }
                  elseif(trim($subfield['type']) == 'date_range') # Date Range
                  {
                    if (!empty($subfield['current_value'])):
                      $rawDate = explode('##', $subfield['current_value']);
                      $dateFrom = date_create(trim($rawDate[0]));
                      $dateTo = date_create(trim($rawDate[1]));

                      $dateFrom = $dateFrom !== FALSE ? date_format($dateFrom, "m-d-Y") : '--';
                      $dateTo = $dateTo !== FALSE ? date_format($dateTo, "m-d-Y") : '--';

                      $display = "{$dateFrom} / {$dateTo}";
                    else:
                      $display = '-- / --';
                    endif;
                  }
                  if ( !empty($fieldClasses) && (in_array('date', $fieldClasses) || in_array('date-no-default', $fieldClasses)) ) # Has Class date
                  {
                    $thisDate = ($subfield['current_value'] != '0000-00-00' && !empty($subfield['current_value'])) ? date_create($subfield['current_value']) : FALSE;
                    $dateFormatted = '--';
                    if ( $thisDate !== FALSE ) {
                      $dateFormatted = date_format($thisDate, "m-d-Y");
                    }
                    $display = $dateFormatted;
                  }
                  if ( (!empty($fieldClasses) || !empty($subfield['class'])) && in_array('datetime', $fieldClasses) ) # Has Class datetime
                  {
                    $thisDate = ($subfield['current_value'] != '0000-00-00 00:00:00' && !empty($subfield['current_value'])) ? date_create($subfield['current_value']) : FALSE;
                    $dateFormatted = '-- --';
                    if ( $thisDate !== FALSE ){
                      $dateFormatted = date_format($thisDate, "m-d-Y H:i a");
                    }
                    $display = $dateFormatted;
                  }
                  
                  if ( !empty($fieldClasses) && in_array('date-created-field', $fieldClasses) ) # Has Class date-created-field
                  {
                    $thisDate = ($subfield['current_value'] != '0000-00-00 00:00:00' && !empty($subfield['current_value'])) ? date_create($subfield['current_value']) : FALSE;
                    $dateFormatted = '--';
                    if ( $thisDate !== FALSE ) {
                      $dateFormatted = date_format($thisDate, "m-d-Y");
                    }

                    $display = $dateFormatted;
                  }

                  if( $subfield['type'] != 'hidden' ) {
                    if (in_array('this-summer', $fieldClasses) ) { # this will remove
                        $form .= '<td>'.html_entity_decode($subfield['current_value']).'</td>';
                    } else {
                      if (
                        $subfield['type'] == 'inquirylookup' ||
                        $subfield['type'] == 'warrantylookup' ||
                        $subfield['type'] == 'ltrs_training_list_lookup' ||
                        $subfield['type'] == 'branchlookup' ||
                        $subfield['type'] == 'link_ticket' ||
                        in_array('overall-status-color', $fieldClasses)
                      )
                      {
                        $form .= '<td>'.$display.'</td>';
                      } elseif($subfield['type'] == 'double_field'){
                        $form .= '<td>'.wordwrap(($display == "") ? "--" : strtoupper($display), 55, '<br />').'<span style="color:#7c7373;margin-left:20px">Minutes:</span> '.$mm.'</td>';
                      } else{
                        $form .= '<td>'.wordwrap(($display == "") ? "--": strtoupper($display), 55, '<br />').'</td>';
                      }
                      
                    }
                    // $form .= '<td>'.wordwrap(($display == "") ? "--": strtoupper($display), 55, '<br />').'</td>';
                  }
                  $form .= '</tr>';
                  # End Field Type Configuration
                  
                  $form .= '<tr class="field-edit" style="display:none" id="div-'.$subfield['id'].'">';
                  if ($subfield['type'] != 'hidden') {
                    $form .= '<td style="min-width:101px;width:'.$maxlen.'px;color:#7c7373"><label style="font-weight:unset">'.$req.$subfield['label'].':</label></td>';
                  }
                  if ($can_edit == 0) {
                    $form .= '<td>'.$display.'</td>';
                  }
                  else{
                    if ($subfield['type'] != 'hidden')
                    {
                      if ($subfield['type'] == 'employeelookup')
                      {
                        $form .= '<td id="div-'.$subfield['id'].'" style="width:75% !important">';
                      }
                      else if ($subfield['type'] == 'branchlookup')
                      {
                        $form .= '<td id="div-'.$subfield['id'].'">';
                      }
                      else if(strpos($subfield['class'], 'this-summer') !== false)
                      {
                        $form .= '<td style="width:unset !important">';
                      }
                      else
                      {
                        $form .= '<td style="width:75% !important">';
                      }
                    }
                    $currentValue = $subfield['current_value'];

                    if ( !empty($fieldClasses) && (in_array('date', $fieldClasses) || in_array('date-no-default', $fieldClasses)) ) # Has Class date
                    {
                      $thisDate = ($subfield['current_value'] != '0000-00-00' && !empty($subfield['current_value'])) ? date_create($subfield['current_value']) : FALSE;
                      // $thisDate = date_create($subfield['current_value']);
                      $dateFormatted = '--';
                      if ( $thisDate !== FALSE ){
                        $dateFormatted = date_format($thisDate, "m-d-Y");
                      }
                      $currentValue = $dateFormatted;
                    }
                    if ( !empty($fieldClasses) && in_array('datetime', $fieldClasses) ) # Has Class datetime
                    {
                      $thisDate = ($subfield['current_value'] != '0000-00-00 00:00:00' && !empty($subfield['current_value'])) ? date_create($subfield['current_value']) : FALSE;
                      $dateFormatted = '-- --';
                      if ( $thisDate !== FALSE ){
                        $dateFormatted = date_format($thisDate, "m-d-Y H:i a");
                      }
                      $currentValue = $dateFormatted;
                    }
                    if ( in_array('my-summernote', $fieldClasses)) # hasClass my-summernote
                    {
                      $currentValue = html_entity_decode($subfield['current_value']);
                    }
                    
                    if($subfield['parameter'] == 1){
                      $form .= converter($subfield['type'], $subfield['name'], $subfield['id'], $subfield['class'], $subfield['reference'], $subfield['value'], $subfield['display'],$currentValue,$parameter[$keys], $subfield['emplookupref'],$subfield['optiongroup'],$subfield['IsSort'],$subfield['MaintenanceID']);
                    }else{
                      $form .= converter($subfield['type'], $subfield['name'], $subfield['id'], $subfield['class'], $subfield['reference'], $subfield['value'], $subfield['display'], $currentValue, $param = array(), $subfield['emplookupref'],$subfield['optiongroup'],$subfield['IsSort'],$subfield['MaintenanceID']);    
                    }
                    if ($subfield['type'] != 'hidden') {
                        $form .= '</td>';
                    } 
                  }
                  $form .= '</tr>';
                }
              echo "<script>var min_width = '$maxlen'; </script>";
              endforeach;

              $form .= '</table>';
              $form .= '</div>';
              $form .= '</div>';

            endif;
              if ( in_array($form_id, $_SESSION['email_collaborator_tab']) ) { # Email Collaborator Tab
                $form .= '<div class="tab-pane fade in" id="email_tab" style="padding:10px;max-height: 600px;min-height: 150px;border-width: thin;border-color: #efebeb;border-style: solid;"></div>';
              }
              if ($form_id == 79) {
                $form .= '<div class="tab-pane fade in active" id="branch_log_tab" style="padding:10px;max-height: 600px;min-height: 150px;border-width: thin;border-color: #efebeb;border-style: solid;"></div>';
                $form .= '<div class="tab-pane fade in" id="branch_image_tab" style="padding:10px;max-height: 600px;min-height: 150px;border-width: thin;border-color: #efebeb;border-style: solid;"></div>';
              }
               $i++;
              $current_group = $result['GroupTab']; 
              $active        = ""; 
          endforeach;
      $form .= view_manual_tab($form_id,$RecordId,$maxlen)->content;  
      $form .= '</div>';
      $form .= '</div>';
      // var_dump($form); die();
      if(!empty($fields)){
        return $form;
      }
  }
}

  function change_status_div($array,$statusid,$display,$max){

    $form  = '';
    $form .= '<tr class="field-edit" style="display:none"><td style="min-width:101px;width:'.$max.'px;color:#7c7373">*Status:</td>';
    $form .= '<td><select name="status" id="status" class="form-control" style="min-width:unset;min-width:101px;width:220px" disabled>';
    $form .= '<option value="'.$statusid.'" selected>'.$display.'</option>';
    $optgrp = '';
    foreach ($array as $subres):
        if($optgrp != $subres['optiongroup']){
          if(!empty($optgrp)) {
          $form .='</optgroup>';
          }
          if (!empty($subres['optiongroup'])) {
            $option = $subres['optiongroup'];
          }else{
            $option = 'Option';
          }
          $form .='<optgroup label="---'.$option.'---"></optgroup>';
        }
        $form .='<option value="'.$subres['ID'].'">'.$subres['status'].'</option>';
        $optgrp = $subres['optiongroup'];    
        if (!empty($optgrp)) {
          $form .='</optgroup>';
        }
      
    //$form .= '<option value="'.$subres['ID'].'">'.$subres['status'].'</option>';
    endforeach;
    $form .= '</select></td>';
    $form .= '</tr>';
    $form .= '<tr class="field-edit" style="display:none"><td style="min-width:101px;width:'.$max.'px;color:#7c7373">Effective Date:</td>';
    $form .= '<td><input type="text" width:220px;" class="form-control date-created-field" name="effective_date" id="effective_date" value="'.date('Y-m-d').'""></td>';
    $form .= '</tr>';
    $form .= '<tr class="field-edit" style="display:none"><td style="min-width:101px;width:'.$max.'px;color:#7c7373">Remarks:</td>';
    $form .= '<td><input type="text" width:220px;" name="status_remarks" id="status_remarks" class="form-control"></td>';
    $form .= '</tr>';
    return $form;
  }
  function change_status_form($array,$statusid,$display,$formid){
    $form  = '<table>';
    $form .= '<tr class=""><td style="min-width:101px;color:#7c7373">*Status:</td>';
    $form .= '<td><select name="status" id="status" class="form-control" style="min-width:unset;min-width:101px;width:220px">';
    $form .= '<option value="'.$statusid.'" selected>'.$display.'</option>';
    $optgrp = '';
    foreach ($array as $subres):
        if($optgrp != $subres['optiongroup']){
          if(!empty($optgrp)) {
          $form .='</optgroup>';
          }
          if (!empty($subres['optiongroup'])) {
            $option = $subres['optiongroup'];
          }else{
            $option = 'Option';
          }
          $form .='<optgroup label="---'.$option.'---"></optgroup>';
        }
        $form .='<option id="'.$subres['ID'].'" value="'.$subres['ID'].'" data-mandatory = "'.$subres['isRemarksMandatory'].'">'.$subres['status'].'</option>';
        $optgrp = $subres['optiongroup'];    
        if (!empty($optgrp)) {
          $form .='</optgroup>';
        }
      
    //$form .= '<option value="'.$subres['ID'].'">'.$subres['status'].'</option>';
    endforeach;
    $form .= '</select></td>';
    $form .= '</tr>';
    $form .= '<tr class=""><td style="min-width:101px;color:#7c7373">Effective Date:</td>';
    $form .= '<td><input type="text" width:220px;" class="form-control date" name="effective_date" id="effective_date" disabled>
    <input type="hidden" width:220px;" class="form-control date" name="effective_date" id="effective_date"></td>';
    $form .= '</tr>';
    $form .= '<tr class=""><td style="min-width:101px;color:#7c7373">Remarks:</td>';
    $form .= '<td><select name="status_remarks" id="status_remarks" style="min-width:unset;min-width:101px;width:220px;max-height: unset!important;" multiple="multiple">';
    // $form .= ' <input type="text" width:220px;"  class="form-control" list="saved_remarks">';
    // $form .= '<datalist id="saved_remarks">';
    $CI = get_instance();
    $CI->load->model('user_access_model');
    $get_remarks = $CI->user_access_model->get_remarks($formid);
    foreach ($get_remarks as $key => $res):
      $form .= '<option data-status_id="'.$res['StatusId'].'" value="'.$res['remarks'].'">'.$res['remarks'].'</option>';
    endforeach;
    // $form .= '</datalist>';
    $form .= '</td>';
    $form .= '</tr>';
    $form .= '</table>';
    return $form;

  }

#remove undercore
if(!function_exists('r_underscore'))
{
  function r_underscore($string)
  {
    return str_replace('_', ' ', $string);
  }
}

#remove undercore
if(!function_exists('r_comma'))
{
  function r_comma($string)
  {
    return str_replace( ',', '', $string );
  }
}

#CCOD TARGET EDITABLE TABLE
if(!function_exists('td_text'))
{
  function td_text($id ,$field, $value)
  {
     $td  = '<td><span class="edit" >'.$value.'</span>';
     $td .= '<input type="text" class="txtedit" data-id="'.$id.'" data-field="'.$field.'" value="'.$value.'"></td>';
    return $td;
  }
}
#OBJECT
if(!function_exists('null_obj'))
{
  function null_obj($obj,$property)
  {
    if (property_exists($obj,$property)) {
      return $obj->$property;
    }
      return '';
  }
}

if(!function_exists('date_format_'))
{
  function date_format_( $date )
  {
    if ( !empty( $date ) )
    {
      $d = DateTime::createFromFormat('m-d-Y', $date);
      return $d !== FALSE ? $d->format('Y-m-d') : NULL;
    }
    return NULL;
  }
}

if(!function_exists('dateToDateTime_FromMdy'))
{
  // @param Date or DateTime 
  function dateToDateTime_FromMdy($date)
  {
    if (!empty($date))
    {
      $d = DateTime::createFromFormat('m-d-Y', $date);
      return $d !== FALSE ? $d->format('Y-m-d 00:00:00') : NULL;
    }
    return NULL;
  }
}

if(!function_exists('date_format_myd'))
{
  function date_format_myd($date)
  {
    if (!empty($date))
    {
      $d = DateTime::createFromFormat('Y-m-d', $date);
      return $d !== FALSE ? $d->format('m-d-Y') : NULL;
    }
    return NULL;
  }
}

if(!function_exists('datetime_format_myd'))
{
  function datetime_format_myd($datetime)
  {
    if (!empty($datetime))
    {
      $d = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
      return $d !== FALSE ? $d->format('m-d-Y') : NULL;
    }
    return NULL;
  }
}

#DATETIME CONVERTER
if(!function_exists('progress_column'))
{
  function progress_column($val = 0)
  {
    $val = !empty($val) ? $val : 0;
     return '<div class="progress active" style="width:150px;margin:0">
                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:'.$val.'%">
                </div>
                <p style="position: absolute;padding-left: 50px;">'.$val.'%</p>
              </div>';  
  }
}
#DATETIME CONVERTER
if(!function_exists('datetime_format'))
{
  function datetime_format($datetime)
  {
    if ( !empty($datetime) ) {

      $d = DateTime::createFromFormat('m-d-Y H:i', $datetime);

      return $d !== FALSE ? $d->format('Y-m-d H:i') : NULL;
    }
    return NULL;
  }
}

if(!function_exists('datetimeA_format'))
{
  function datetimeA_format($datetime)
  {
    if ( !empty($datetime) ) {

        date('h:i:s a', strtotime($mysql_column['time']));
      $d = DateTime::createFromFormat('m-d-Y H:i A', $datetime);
      

      return $d !== FALSE ? $d->format('Y-m-d H:i A') : NULL;
    }
    return NULL;
  }
}
#CCOD TARGET
if(!function_exists('td_dropdown'))
{
  function td_dropdown($id ,$field, $value ,$drop)
  {
     $td  = '<td><span class="edit" >'.$value.'</span>';
     $td .= '<select data-id="'.$id.'" data-field="'.$field.'" class="txtedit" style="width:120px">';
     $td .= '<option value="'.$value.'" selected>'.$value.'</option>';
     foreach ($drop as $subres):
     if($subres['status'] != $value){ 
        $td .='<option value="'.$subres['status'].'">'.$subres['status'].'</option>';
      }
      endforeach;
      $td .='</select></td>';
    return $td;
  }
}


if(!function_exists('header_title'))
{
  function header_title($id)
  {
    // if(!empty($checkls)){ echo strtoupper($record->ProjectTitle); }else{echo get_full_name($record->FormId,$record->FormRecordId); }
    //return $td;
  }
}

if(!function_exists('form_parameter'))
{
  function form_parameter($id)
  {
    $parameter = array();
    switch ($id) {
            case 1:
              // $parameter[0] = array("deptid"    => "18");
            break;

            case 2: # Audit Case
              $parameter[8] = array("grgid"    => "2"); # Classification
              $parameter[12] = array("grgid"    => "4"); # Discplinary Action
            break;
            case 3:
                $parameter[]  = array("formid"    => "3");
                $parameter[]  = array("categoryid"=> "101");
                $parameter[6] = array("grgid"     => "13");
                $parameter[7] = array("grgid"     => "14" ,"parentid" => "132");
                $parameter[18] = array("grgid"     => "20");
            break;
            case 10:
                $parameter[] = array("deptid"    => "6");
                $parameter[] = array("categoryid"=> "113");
            break;
            case 12:
                $parameter[] = array("deptid"    => "2","formid" => "12");
                $parameter[] = array("categoryid"=> "92");
            break;
            case 16: # Fixed Asset form_parameter
                // $parameter[] = array("formid" => "16");
                $parameter[7] = array("grgid" => "36");
                // $parameter[1] = array("grgid" => "39");
            break;
            case 17:
                $parameter[] = array("deptid"     => "14","formid" => "17");
            break;
            case 18:
                $parameter[]  = array("deptid"     => "14","formid" => "18");
                $parameter[]  = array("categoryid" => "124");
                $parameter[4] = array("grgid"      => "15");
                $parameter[5] = array("grgid"      => "16");
            break;
            case 19:
              $parameter[1]   = array("formid" => "19"); # Category
              $parameter[2] = array("categoryid"  => "135"); #subcategory
              $parameter[12] = array("grgid"  => "56"); #Company
              $parameter[15] = array("grgid"  => "11");  #Rennovation type 
            break;
            case 22: # gas card request transaction
              $parameter[0] = array("grgid" => 66); # Request Type
              $parameter[4] = array("BranchCode" => $_SESSION['branch_code']);
            break;
            case 26: # gas card replacement
              $parameter[1] = array("formid" => "26");
              $parameter[2] = array("BranchCode" => $_SESSION['branch_code']);
            break;
            case 28: # hrcase car nte
              $parameter[] = array("formid" => "28");
              $parameter[33] = array("grgid"  => "24");
              break;
            case 29: # Accounting Sales InterCompany
              $parameter[1]  = array("formid" => "29");
              $parameter[6] = array("id"  => "0"); # MC Model
            break;
            case 30: # accounting other request
              $parameter[] = array("formid" => "30");
            break;
            case 33: # CAR
              $parameter[1] = array("formid" => "33");
              $parameter[2] = array("categoryid" => "146");
            break;
             case 34: // inquiry
              $parameter[1]  = array("formid" => "34"); # category
              $parameter[3]  = array("grgid"  => "7"); #Source
              // $parameter[6]  = array("grgid"  => "8"); #mc Type
              // $parameter[7]  = array("grgid"  => "13"); #Brand
              $parameter[8]  = array("BrandId"  => "0"); #model
              $parameter[9]  = array("id"  => "0");  #MC TYPE
              
              $parameter[10]  = array("grgid"  => "9");#color
              $parameter[11]  = array("grgid"  => "25");#telesales
              $parameter[23] = array("grgid"  => "6"); #occupation
            break;
            case 35: # Complaint
              $parameter[2] = array("grgid"       => "17");
              $parameter[12] = array("formid"     => "35");
              $parameter[13] = array("categoryid" => "169");
            break;
            case 37: # service appointment
              $parameter[2] = array("formid" => "37");
              // $parameter[6] = array("grgid"  => "13");
              $parameter[7] = array("BrandId"  => "0");
              $parameter[8] = array("grgid"       => "110");
            break;
            case 38: // 
              $parameter[0] = array("formid" => "38");
            break;
            case 39: // loan application
                $parameter[3]  = array("grgid"  => "55");  #Priority
               
                // $parameter[10]  = array("grgid"  => "13");  #Brand
                $parameter[11]  = array("BrandId" => "0"); #Model
                 $parameter[12]  = array("id"  => "0");  #MC TYPE
                $parameter[13]  = array("grgid"  => "9");   #color
                $parameter[14]  = array("grgid"  => "25"); #telesales

                $parameter[15]  = array("grgid"  => "7");  #source
                $parameter[39]  = array("grgid"  => "38"); #Marital Status
                $parameter[53]  = array("grgid"  => "30"); #Education
                $parameter[56]  = array("grgid"  => "29"); #Type of Borrower
                $parameter[72]  = array("grgid"  => "31"); #Asset Size
                $parameter[73]  = array("grgid"  => "32"); #Nature of Busienss
                
                $parameter[59]  = array("grgid"  => "33"); #Residence
                $parameter[77]  = array("grgid"  => "34"); #Employment Status
                $parameter[93]  = array("grgid"  => "35");  #Source of Fund
              
            break;
            case 40: // Reservation
               // $parameter[0] = array("1"   => "1");
              $parameter[1] = array("1"   => "1");
              $parameter[2] = array("1"   => "1");
              $parameter[4] = array("grgid"   => "74");
              $parameter[5] = array("grgid"   => "147");
              // $parameter[2] = array("1"   => "2");
            break;
            case 41: // Promo
              $parameter[2] = array("grgid"   => "13");
              $parameter[3] = array("grgid"   => "14" ,"parentid" => "132");
            break;
            case 42: //Benefits
                $parameter[1] = array("formid"=> "42");
            break;
            case 43: //ER
                $parameter[] = array("categoryid"=> "92");
            break;
            case 44: // OD
                $parameter[] = array("categoryid"=> "93");
            break;
            case 45: # Recruitment
                $parameter[1] = array("formid"=> "45");
            break;
            case 47: # CSOD WARRANTY CLAIMS
                $parameter[4]   = array("BrandId"=> "0"); # MC Model
                $parameter[5]   = array("grgid"=> "9");   # MC Color
                $parameter[9]   = array("grgid"=> "67");  # MC Defect
            break;
            case 48: # CSOD Branch Request
                $parameter[1]  = array("formid"=> "48");
            break;
            case 49: //CSOD Registration
                $parameter[1]  = array("formid"=> "49");
                // $parameter[4] = array("deletedflag"=> "0");
                $parameter[5] = array("id"=> "0");
                $parameter[6] = array("grgid"=> "9");
            break;
            case 50: # INFRACTION
                // $parameter[2] = array("grgid"=> "43"); # Infraction type
                // $parameter[3] = array("grgid"=> "44"); # Subtype Infraction
                // $parameter[9] = array("grgid"=> "45"); # Degree
            break;
            case 51: # APPLICATION FORM
                // $parameter[4] = array("grgid"=> "43"); # psgc
                $parameter[5] = array("grgid"=> "50"); # Source
            break;
            case 52: # PAYROLL ISSUE
                $parameter[1] = array("formid"=> "52"); # Category
                $parameter[2] = array("categoryid"=> "248"); # Subcategory
                // $parameter[3] = array("grgid"=> "54"); # Type
            break;
            case 53: # IT REQUEST
                $parameter[1] = array("grgid"=> "48"); # Issue
                $parameter[7] = array("grgid"=> "49"); # Type
                $parameter[8] = array("formid"=> "53"); # Category
                $parameter[9] = array("categoryid"=> "239"); # Subcategory
                $parameter[12] = array("grgid"=> "52"); # severiry
                $parameter[13] = array("grgid"=> "53"); # priority
                
            break;
            case 54: # MC DAMAGE
                $parameter[] = array("formid" => $id); # FormId pero para saan? -Russel
                // $parameter[3] = array("grgid"  => "13");
                // $parameter[3] = array("id"  => "0");
                $parameter[4] = array("BrandId"  => "0");
                $parameter[8] = array("grgid"  => "61"); # Nature of Damage
                $parameter[13] = array("grgid"  => "62"); # Branch Response
                break;
            case 56: # Contract of Lease
                $parameter[1] = array("formid" => $id); 
                $parameter[2] = array("grgid"  => "57");
                $parameter[12] = array("grgid"  => "115");
                $parameter[37] = array("grgid"  => "114");
                $parameter[39] = array("grgid"  => "113");
            break;
            case 57: # BDD Closure
                $parameter[1] = array("formid" => $id); 
            break;
            case 58: # BDD Helpdesk Complaint
              $parameter[1] = array("formid"=> "58"); # Category
              $parameter[2] = array("categoryid"=> "260"); # Subcategory
              $parameter[11] = array("grgid"=> "69"); # severiry
              $parameter[12] = array("grgid"=> "70"); # priority
            break; 
            case 59: # HR Helpdesk Request
              $parameter[1] = array("formid"=> "59"); # Category
              $parameter[2] = array("categoryid"=> "278"); # Subcategory
              $parameter[11] = array("grgid"=> "73"); # severiry
              $parameter[12] = array("grgid"=> "74"); # priority
            break;
            case 60: # AUDIT Helpdesk Request
              $parameter[1] = array("formid"=> "60"); # Category
              $parameter[2] = array("categoryid"=> "279"); # Subcategory
              $parameter[11] = array("grgid"=> "71"); # severiry
              $parameter[12] = array("grgid"=> "72"); # priority
              break;
            case 61: # CCOD Helpdesk Request
              $parameter[1] = array("formid"=> "61"); # Category
              $parameter[2] = array("categoryid"=> "294"); # Subcategory
              $parameter[11] = array("grgid"=> "75"); # severiry
              $parameter[12] = array("grgid"=> "76"); # priority
              break;
            case 62: # CSOD Helpdesk Request
              $parameter[1] = array("formid"=> "62"); # Category
              $parameter[2] = array("categoryid"=> "300"); # Subcategory
              $parameter[11] = array("grgid"=> "77"); # severiry
              $parameter[12] = array("grgid"=> "78"); # priority
              break;
            case 63: # MARKETING Helpdesk Request
              $parameter[1] = array("formid"=> "63"); # Category
              $parameter[2] = array("categoryid"=> "314"); # Subcategory
              $parameter[11] = array("grgid"=> "79"); # severiry
              $parameter[12] = array("grgid"=> "80"); # priority
              break;
            case 64: # ACCOUNTING Helpdesk Request
              $parameter[1] = array("formid"=> "64"); # Category
              $parameter[2] = array("categoryid"=> "317"); # Subcategory
              $parameter[11] = array("grgid"=> "81"); # severiry
              $parameter[12] = array("grgid"=> "82"); # priority
              break;
            case 65: # PURCHASING Helpdesk Request
              $parameter[1] = array("formid"=> "65"); # Category
              $parameter[2] = array("categoryid"=> "331"); # Subcategory
              $parameter[11] = array("grgid"=> "83"); # severiry
              $parameter[12] = array("grgid"=> "84"); # priority
              break;
            case 71: # CAR Transfer
              $parameter[1] = array("formid" => 71); # Category param
              $parameter[2] = array("categoryid" => 355); # SubCategory
              $parameter[11] = array("grgid" => 99); # Global Ref
              break;
            case 66: # SnD Helpdesk Request
              $parameter[1] = array("formid"=> "66"); # Category
              $parameter[2] = array("categoryid"=> "334"); # Subcategory
              $parameter[11] = array("grgid"=> "87"); # severiry
              $parameter[12] = array("grgid"=> "88"); # priority
              break;
            case 67: # WAREHOUSE Helpdesk Request
              $parameter[1] = array("formid"=> "67"); # Category
              $parameter[2] = array("categoryid"=> "339"); # Subcategory
              $parameter[11] = array("grgid"=> "89"); # severiry
              $parameter[12] = array("grgid"=> "90"); # priority
              break;
            case 68: # LEGAL Helpdesk Request
              $parameter[1] = array("formid"=> "68"); # Category
              $parameter[2] = array("categoryid"=> "344"); # Subcategory
              $parameter[11] = array("grgid"=> "91"); # severiry
              $parameter[12] = array("grgid"=> "92"); # priority
              break;
            case 69: # CSM Helpdesk Request
              $parameter[1] = array("formid"=> "69"); # Category
              $parameter[2] = array("categoryid"=> "348"); # Subcategory
              $parameter[9] = array("grgid"=> "131");#purpose of request
              $parameter[17] = array("grgid"=> "93"); # severiry
              $parameter[18] = array("grgid"=> "94"); # priority
              break;
            case 79: # CSM Helpdesk Request
              // $parameter[1] = array("formid"=> "69"); # Category
              // $parameter[2] = array("categoryid"=> "348"); # Subcategory
              // $parameter[12] = array("grgid"=> "93"); # severiry
              // $parameter[13] = array("grgid"=> "94"); # priority
              break;
            case 70: # SUPPLIER PORTAL
              // $parameter[2] = ["formid"=> "70"]; # Category
              $parameter[2] = ["grgid"=> "121"]; # Ownership Type
              $parameter[4] = ["grgid"=> "96"]; # Tax Type
              $parameter[10] = ["grgid"=> "132"]; # Endorsing Department
              $parameter[19] = ["grgid"=> "153"]; # Terms of payment
              break;
            case 71: # CAR Transfer
              $parameter[1] = array("formid" => 71); # Category param
              $parameter[2] = array("grgid" => 99); # SubCategory Param Global Ref. table
              break;
            case 72: # Helpdesk Survey
              $parameter[0] = array("grgid"=> "98"); # severiry
              break;
            case 74: # CAR Employment
              $parameter[1] = array("formid" => 74); # Category param
              $parameter[2] = array("categoryid" => 369); # SubCategory Param
              break;
            case 73: # IT Project Request
              // $parameter[1]   = ["grgid"  => "104"]; # Project Type
              $parameter[1]   = ["formid"  => "73"]; # Category
              $parameter[6]   = ["grgid"  => "100"]; # Revenue
              $parameter[7]   = ["grgid"  => "101"]; # Customer Experience
              $parameter[8]   = ["grgid"  => "102"]; # Operational Factor
              $parameter[9]   = ["grgid"  => "103"]; # Risk Factor
              $parameter[17]   = ["grgid"  => "151"]; # Time Status
              $parameter[18]   = ["grgid"  => "151"]; # Scope Status
              $parameter[19]   = ["grgid"  => "151"]; # Cost Status
              break;
            case 75: # PDOC
              $parameter[4]  = array("id"  => "0"); #model brand
              $parameter[5]  = array("id"  => "0");  #MC TYPE model
              // $parameter[7] = array("grgid"=> "93"); # severiry source
              $parameter[2] = array("grgid"=> "94"); # priority branch
              $parameter[1] = array("grgid"=> "120"); # priority category
              // $parameter[7] = array("grgid"=> "105"); # priority purpose of application REMOVE
              $parameter[7] = array("FormID"=> "75"); # primaryreason
              // $parameter[8] = array("secondary_pending_reason"=> "0"); # priority
              $parameter[14] = array("grgid"=> "108"); # priority
              $parameter[18] = array("grgid"=> "6"); # priority 
              break;
            case 77: # REPEAT BUY FORM
              $parameter[1] = array("grgid"=> "126");
              $parameter[4]  = array("id"  => "0"); #model
              $parameter[5]  = array("id"  => "0");  #MC TYPE
              $parameter[2] = array("grgid"=> "94"); # priority
              $parameter[7] = array("grgid" => "136");
              $parameter[8] = array("grgid"=> "105"); # priority
        $parameter[9] = array("FormID"=> "77"); # primary
              $parameter[16] = array("grgid"=> "108"); # priority
              $parameter[20] = array("grgid"=> "6"); # priority
              break;
            case 78: # DNMS
              $parameter[1] = array("formid"=> "78"); # dealer type / category
              $parameter[2] = array("categoryid"=> "407"); # store category / subcategory
              $parameter[3] = array("grgid"=> "117"); # dealer category
              $parameter[5] = array("grgid"=> "118"); # dealer status
              $parameter[7] = array("grgid"=> "125"); # mnc region
              $parameter[11] = array("grgid"=> "119"); # financing type
              break;
            case 82: # LTRS Applicant
              $parameter[7] = array("grgid" => "138"); # Gender
              $parameter[8] = array("grgid" => "38"); # Marital Status
              $parameter[11] = array("grgid" => "6"); # Occupation
              $parameter[12] = array("grgid" => "22"); # Source of Income

              $parameter[14] = array("grgid" => "143"); # Untrained Reason
              $parameter[16] = array("grgid" => "144"); # Unbought Reason
              $parameter[17]  = array("BrandId"  => "0"); #MC Brand
              $parameter[18]  = array("id"  => "0");  # MC Model

              $parameter[22] = array("grgid" => "139"); # Motorcycle Preferred
              
              $parameter[29] = array("grgid" => "141"); # License Type
              break;
            case 83: # LTRS Training
              $parameter[2] = array("formid" => "83"); # dealer type / category
              break;
            case 87: # AM VISITATION
              $parameter[0] = array("formid"=> "87"); # Category
              $parameter[1] = array("grgid"=> "133"); # TYPE
              $parameter[2] = array("grgid"=> "134"); # PRIORITY
              $parameter[9] = array("grgid"=> "135"); # ISSUE
            break;
            case 88: # 5S branch monitoring
              $parameter[0] = array("formid"=> "88"); # Category
              $parameter[1] = array("grgid"=> "133"); # TYPE
              $parameter[2] = array("grgid"=> "134"); # PRIORITY
              $parameter[9] = array("grgid"=> "135"); # ISSUE
            break;
            case 90: // RETURNED UNIT
              $parameter[1] = array("formid" => $id); #Category (MC Condition)
              // $parameter[3]   = array("BrandId"=> "0"); # MC Model
              $parameter[2] = array("grgid" => "150"); 
              $parameter[7]   = array("grgid"=> "9");   # MC Color
              $parameter[9]   = array("grgid"=> "67");
               break;
            default:
              # NO DEFAULT ACTION
            break;
        }
        $parameter['allowEmailTab'] = array('28','19'); #'19' add your formid here
        return $parameter;
  }
}

# Russ Creator
if (!function_exists('RemoveSpecialChar')) # Remove Special Character
{
  function RemoveSpecialChar($str)
  {
    // Using preg_replace() function  
    // to replace the word  
    $res = preg_replace('/[^a-zA-Z0-9_ ]/s', '', $str); 
      
    // Returning the result  
    return $res; 
  }
}

if (!function_exists('OnlyAlphabet')) # Only Alphabet
{
  function OnlyAlphabet($str)
  {
    // Using preg_replace() function  
    // to replace the word  
    $res = preg_replace('/[^a-zA-Z_ ]/s', '', $str); 
      
    // Returning the result  
    return $res;
  }
}

if (!function_exists('get_column_list')) #
{
  function get_column_list($form)
  {
    $CI = get_instance();
    $CI->load->model('Form_model', 'form_mdl');
    return $CI->form_mdl->get_header_all_data($form);
  }
}

if (!function_exists('RemoveSpaces')) # Remove Spaces Only
{
  function RemoveSpaces($str)
  {
    // Using preg_replace() function
    // to replace the word
    $res = preg_replace('/\s+/', '', $str); 
      
    // Returning the result  
    return $res; 
  }
}

if (!function_exists('ajax_response'))
{
  function ajax_response( $res = "true", $msg = "", $err = "" )
  {
    $output = array(
      "response"    =>  $res,
      "message"     =>  $msg,
      "error"       =>  $err
    );

    return json_encode($output);
  }
}

if ( !function_exists('check_mobile_num') )
{
  function check_mobile_num ( $num = "" )
  {
    if (empty($num) || (strlen($num) > 12 || strlen($num) < 10)) return false;
    
    $num = strlen(RemoveSpecialChar($num)) == 11 ? $num : "0". $num;

    return str_replace("-", "", $num);
  }
}

# End Russ

function today_date() {
  date_default_timezone_set('Asia/Manila');
  return date("Y-m-d");
}

function yesterday_date() {
  date_default_timezone_set('Asia/Manila');
  return date("Y-m-d", strtotime( 'yesterday' ) );
}
function mobile_validation($mobile_no, $num = '') {
  if(preg_match("/^010[0|1|6|9][0-9]{7}$/", $mobile_no)) {
   $num = strlen( RemoveSpecialChar($num) ) == 11 ? $num : "0". $num;

    return str_replace("-", "", $num);
   }
}

if ( !function_exists('advance_filter') )
{
  function advance_filter ($x) {
    return ($x !== NULL && $x !== FALSE && $x !== "" && intval($x) > 0);
  }
}

if (!function_exists('htmlConverter')): # This is only for Visitation
  function htmlConverter($fieldType, $options) {
    $html  = '';
    $options = empty($options) ? [] : (object) $options;

    $CI = get_instance();
    $CI->load->model('Main_model', 'm_main');

    if (isset($options->isViewPage) && $options->isViewPage):
      $MainAnswer = empty($options->MainAnswer) ? '--' : $options->MainAnswer;
      return $html .= "<span>{$MainAnswer}</span>";
    endif;

    switch ($fieldType):
      # Single Dropdown
      case 'dropdown':
        $ref = $CI->m_main->getReferenceByGrgid($options->ReferenceId);
        $html .= "<select name='question_{$options->FieldId}[main_answer]' class='{$options->Class}' data-field_name='main_answer'>";
        $html .= "<option selected disabled>-- Select --</option>";
        if ($ref && $ref->num_rows() > 0):
          foreach($ref->result() as $r):
            $selected = isset($options->MainAnswer) && $r->displayText == $options->MainAnswer 
              ? 'selected'
              : '';
            $html .= "<option value='{$r->displayText}' {$selected}>{$r->displayText}</option>";
          endforeach;
        endif;
        $html .= '</select>';
        break;
      # Text Only
      case 'text':
        $MainAnswer = isset($options->MainAnswer) && !empty($options->MainAnswer) ? $options->MainAnswer :  '';
        $html .= "<input type='text' class='{$options->Class}' name='question_{$options->FieldId}[main_answer]' data-field_name='main_answer' value='{$MainAnswer}'> ";  
        break;
      # Multi Dropdown
      case 'multi_dropdown':
        $ref = $CI->m_main->getReferenceByGrgid($options->ReferenceId);
        $html .= "<select name='question_{$options->FieldId}[main_answer][]' class='multi_dropdown form-control' style='width: 100% !important' multiple data-field_name='main_answer'>";
        if ($ref && $ref->num_rows() > 0):
          foreach($ref->result() as $r):
            $selected = isset($options->MainAnswer) && $r->displayText == $options->MainAnswer 
              ? 'selected'
              : '';
            $html .= "<option value='{$r->displayText}' {$selected}>{$r->displayText}</option>";
            $selected = '';
          endforeach;
        endif;
        $html .= '</select>';
        break;
      # Multi Tags
      case 'multi_tags':
        $ref = $CI->m_main->getReferenceByGrgid($options->ReferenceId);
        $html .= "<select name='question_{$options->FieldId}[][main_answer]' class='multi_tags form-control' style='width: 100% !important' multiple data-field_name='main_answer'>";
        if ($ref && $ref->num_rows() > 0):
          foreach($ref->result() as $r):
            $selected = isset($options->MainAnswer) && $r->displayText == $options->MainAnswer 
              ? 'selected'
              : '';
            $html .= "<option value='{$r->displayText}' {$selected}>{$r->displayText}</option>";
          endforeach;
        endif;
        $html .= '</select>';
        break;
      case 'number': //to make a rating 
        $MainAnswer = isset($options->MainAnswer) && !empty($options->MainAnswer) ? $options->MainAnswer :  '';
        $html .= "<input type='number' placeholder='1-3' min='0' max='3' style='text-align:center;font-weight:bold;' class='{$options->Class}' name='question_{$options->FieldId}[main_answer]' data-field_name='main_answer' value='{$MainAnswer}'>"; 
        break;
      default:
          # nani
          break;
    endswitch;
    return $html;
  }
endif;

if(!function_exists('isQuestionTab')):
  function isQuestionTab() {
    return [87,88];
  }
endif;

?>