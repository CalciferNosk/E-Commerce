
<script type="text/javascript">
var username = "<?php echo $_SESSION['username']; ?>";
var assign_display = "<?php echo strtoupper($_SESSION['firstname']." ".$_SESSION['middlename']." ".$_SESSION['lastname']."(".str_pad($_SESSION['employee_code'], 6, '0', STR_PAD_LEFT).")"); ?>"
var options = [{Name:"OPEN",value:"76"},{Name:"WIP",value:"77"},{Name:"CLOSED",value:"78"}];
</script>

<?php


if(!function_exists('form_header'))
{
    function form_header($form_id, $record=array())
    {
 
        ini_set('memory_limit', '-1');
        $bNameConList         = get_branch_name();
        $emp_fullname_list = employeefullname();
    switch ($form_id):
        case 1:
          echo " | ".$record->categoryname. " | ". $record->Year." | ". $bNameConList[$record->Branch]. '' ;
          break;
        case 2: # Audit Case v2
          echo  !empty($record->emp_fullname) && !empty($emp_fullname_list[$record->emp_fullname]) ? " | ".$emp_fullname_list[$record->emp_fullname] : '';
          break;
        case 10:
          break;
        case 12:
          echo  !empty($record->employeeFullname) ? " | ".$record->employeeFullname : '';
          break;
        case 3:
          break;
        case 16: # Fixed Asset
          echo  !empty($record->item_desc) ? " | ".$record->item_desc : '';
          break;
        case 18:
          echo  !empty($record->Title) ? " | ".$record->Title : '';
          break;
        case 19:
          echo " | ".strtoupper($record->ProjectTitle); 
          break;
        case 22: # Gas Card Consumpion
          echo  !empty($bNameConList[$record->BranchCode]) ? " | ". $bNameConList[$record->BranchCode]:  $record->BranchCode;
        case 25:
          echo  !empty($record->title) ? " | ".$record->title : '';
          break;
        case 26: # Gas Card Replacement 
          echo !empty($record->categoryname) ? " | ".$record->categoryname ." (".$record->BranchCode.")" : "";
          break;
        case 28: # HRCASE NTE
        //Update
          echo  !empty($record->employeecode) ? " | ".get_employee($record->employeecode) : '';
          break;
        case 29:
          echo  !empty($bNameConList[$record->BranchCode]) ? " | ". $bNameConList[$record->BranchCode]:  $record->BranchCode;
          break;  
        case 34:
          echo  !empty($record->CustomerId) ? " | ". get_customer_fullname($record->CustomerId) : '';
          break;
        case 33:
          echo  !empty($record->emp_fullname) && !empty($emp_fullname_list[$record->emp_fullname]) ? " | ".$emp_fullname_list[$record->emp_fullname] : '';
          break;
        case 35:
          echo  !empty($record->CustomerId) ? " | ". get_customer_fullname($record->CustomerId) : '';
          break;
        case 37:
          echo  !empty($record->CustomerId) ? " | ".get_customer_fullname($record->CustomerId) : '';
          break;
        case 39:
          echo  !empty($record->CustomerId) ? " | ".get_customer_fullname($record->CustomerId) : '';
          break;
        case 42:
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;  
        case 45:
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;  
        case 47: # Warranty Claims
          echo !empty($bNameConList[$record->BranchCode]) ? " | ". $bNameConList[$record->BranchCode] : $record->BranchCode;
          break;  
        case 49: # CSOD Registration
          echo !empty($bNameConList[$record->BranchCode]) ? " | ". $bNameConList[$record->BranchCode] : $record->BranchCode;
          break;
        case 50: # Infraction
          echo  !empty($record->emp_fullname) && !empty($emp_fullname_list[$record->emp_fullname]) ? " | ". $emp_fullname_list[$record->emp_fullname] : 'Employee Not Registered';
          break;
        case 53: # IT Request Title
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 54: # MC Damage
          echo  !empty($bNameConList[$record->BranchCode]) ? " | ". $bNameConList[$record->BranchCode]:  $record->BranchCode;
          break;
        case 56: # Contract Of Lease
          echo  !empty($bNameConList[$record->BranchCode]) ? " | ". $bNameConList[$record->BranchCode]:  ' | '.$record->BranchCode;
          break;
        case 57: # BDD CLOSURE
          echo  !empty($bNameConList[$record->BranchCode]) ? " | ". $bNameConList[$record->BranchCode]:  $record->BranchCode;
          break;
        case 58: # BDD Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 59: # HR Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 60: # AUDIT Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 61: # CCOD Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 62: # CSOD Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 63: # MARKETING Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 64: # ACCOUNTING Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 65: # PURCHASING Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 66: # SnD Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 67: # WAREHOUSE Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 68: # LEGAL Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 69: # CSM Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 70: # SUPPLIER PORTAL
          echo  !empty($record->SupplierName) ? " | ".$record->SupplierName : ''; //" | ".$record->SupplierName.
          break;
        case 71: # CAR TRANSFER
          echo  !empty($record->emp_fullname) && !empty($emp_fullname_list[$record->emp_fullname]) ? " | ".$emp_fullname_list[$record->emp_fullname] : '';
          break;
        case 73: # IT Project Request
          echo  !empty($record->Title) ? " | ".$record->Title : ''; //" | ".$record->Title.
          break;
        case 74: # CAR EMPLOYMENT
          echo  !empty($record->emp_fullname) && !empty($emp_fullname_list[$record->emp_fullname]) ? " | ".$emp_fullname_list[$record->emp_fullname] : '';
          break;
        case 75: # PDOC
          echo  !empty($record->CustomerId) ? " | ".get_customer_fullname($record->CustomerId) : '';
          break;
        case 77: # REPEAT BUY
          echo  !empty($record->CustomerId) ? " | ".get_customer_fullname($record->CustomerId) : '';
          break;
          case 78: # DNMS Request
          echo  !empty($record->Owner) ? " | ".$record->Owner." - ".$record->Dealer : ''; //" | ".$record->Title.
          break;
          case 80:
            echo " | ".$record->categoryname. " | ". $record->Year." | ". $bNameConList[$record->Branch]. '' ;
          break;
        default:
          # NO DEFAULT ACTION
          break;
      endswitch;
    }

}


?>