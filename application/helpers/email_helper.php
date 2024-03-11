<?php
    # Email Handler
    if (!function_exists('SendEmailHandler')):
        function SendEmailHandler ( $email_addresses_to = array(), $email_addresses_cc = array(), $mailTemplate = array() )
        {
            # Email Must be Array
            if ( empty($email_addresses_to) || !is_array($email_addresses_to) ) return FALSE;

            # Load Libraries, Etc.
            $CI           =& get_instance();
            $CI->load->library('phpmailer_lib');
            $mail         = $CI->phpmailer_lib->load();
            
            # SMTP configuration
            $mail->isSMTP();
            $mail->Host         = SMTP_HOST;
            $mail->SMTPAuth     = true;
            $mail->Username     = SMTP_USERNAME;
            $mail->Password     = SMTP_PASSWORD;
            $mail->SMTPSecure   = SMTP_SECURE;
            $mail->SMTPAutoTLS  = true;
            $mail->Port         = SMTP_PORT;
            // $mail->Timeout      = 180; # 3 seconds to delay to send

            $mailTemplate       = (object) $mailTemplate;

            $mail->setFrom(SMTP_EMAIL_FROM, '');
            $mail->isHTML(true);
            $mail->Subject  = $mailTemplate->Subject;
            $mail->Body     = $mailTemplate->Body;

            for ( $i = 0; $i < count($email_addresses_to); $i ++ ):
                $mail->addAddress($email_addresses_to[$i]);
            endfor;

            # Adding Email CC
            if ( !empty($email_addresses_cc) ): 
                for ( $i = 0; $i < count($email_addresses_cc); $i++ ):
                    $mail->addCC( $email_addresses_cc[$i] );
                endfor;
            endif;
            
            if ( $mail->send() ) :
                return TRUE;
            endif;

            return TRUE;
            // return $mail->ErrorInfo;
        }
    endif;

    # Email Template Change Status
    if (!function_exists('email_template_change_status')):
        function email_template_change_status ($e)
        {
            $emc  = "";
            $emc .= ENVIRONMENT != 'production' ? "<h5 style='color:red;'>******* TEST ENVIRONMENT ***********</h5>" : "";

            $branchOrEmployee = $e->BranchName == '--' || empty($e->BranchName)
                ? $e->EmployeeName
                : $e->BranchName;

            $emc .= "The record has been added/updated. Please login to ERS for the complete details. <br \><br \>\r";
            $emc .= "<strong>Form Name:</strong> {$e->FormName}<br \>";
            $emc .= "<strong>Record ID:</strong> {$e->idFormatted}<br \>";

            $emc .= !empty($e->Title) ? "<strong>SUBJECT:</strong> {$e->Title}<br \>" : '';
            $emc .= "<strong>Branch/Employee:</strong> {$branchOrEmployee}<br \>";

            $emc .= "<strong>Previous Status:</strong> {$e->PreviousStatus}<br \>";
            $emc .= "<strong>Current Status:</strong> {$e->CurrentStatus}<br \>";
            $emc .= "<strong>Next Status:</strong> {$e->NexStatus}<br \>";
            $emc .= "<strong>Updated By:</strong> {$e->CreatedByName}<br \>";
            $emc .= "<strong>Updated Date:</strong> {$e->CreatedDate}<br \><br \>";
            $emc .= "<i>Note: This is a system-generated email only. Please do not reply.</i>";
            // $emc .= "<strong>Link:</strong> <a href='{$link}'>Click Here.</a><br \><br \>";

            return $emc;
        }
    endif;

    # Email Template Contract Of Lease Auto Renewal
    if (!function_exists('email_template_for_autorenewal_col')):
        function email_template_for_autorenewal_col($e)
        {
            // $formtitle, $formid, $previous_status, $current_status, $formRecordId, $createdby, $datecreated, $nextStatus
            $emc  = "";
            $emc .= ENVIRONMENT != 'production' ? "<h5 style='color:red;'>******* TEST ENVIRONMENT ***********</h5>" : "";

            $branchOrEmployee = $e->BranchName == '--' || empty($e->BranchName)
                ? $e->EmployeeName
                : $e->BranchName;

            $record = getItemRecord(56, $e->FormRecordId);
            $prevContractId = str_pad($record->PrevContractId, 6, 0, STR_PAD_LEFT);

            $emc .= "This is to inform you that COL #{$e->idFormatted} has been created automatically from the expiring COL #{$prevContractId}. <br \><br \>\r";
            $emc .= "<strong>Form Name:</strong> {$e->FormName}<br \>";
            $emc .= "<strong>Record ID:</strong> {$e->idFormatted}<br \>";
            $emc .= !empty($e->Title) ? "<strong>SUBJECT:</strong> {$e->Title}<br \>" : '';
            $emc .= "<strong>Branch/Employee:</strong> {$branchOrEmployee}<br \>";
            $emc .= "<strong>Previous Status:</strong> {$e->PreviousStatus}<br \>";
            $emc .= "<strong>Current Status:</strong> {$e->CurrentStatus}<br \>";
            $emc .= "<strong>Next Status:</strong> {$e->NexStatus}<br \>";
            $emc .= "<strong>Updated By:</strong> {$e->CreatedByName}<br \>";
            $emc .= "<strong>Updated Date:</strong> {$e->CreatedDate}<br \>";
            $emc .= "<i>Note: This is a system-generated email only. Please do not reply.</i>";

            return $emc;
        }
    endif;

    # Email Template Contract Of Lease Reminder Before Expiration
    if (!function_exists('email_template_for_before_expiration')):
        function email_template_for_before_expiration ($e)
        {
            $emc  = "";
            $emc .= ENVIRONMENT != 'production' ? "<h5 style='color:red;'>******* TEST ENVIRONMENT ***********</h5>" : "";

            $branchOrEmployee = $e->BranchName == '--' || empty($e->BranchName)
                ? $e->EmployeeName
                : $e->BranchName;

            $record = getItemRecord($e->FormId, $e->FormRecordId);
            
            $emc .= "This is to remind you that COL ID {$e->idFormatted} has a Contract End Date on {$record->ContractEndDate}. <br \><br \>\r";
            $emc .= "<strong>Form Name:</strong> {$e->FormName}<br \>";
            $emc .= "<strong>Record ID:</strong> {$e->idFormatted}<br \>";

            $emc .= !empty($e->Title) ? "<strong>SUBJECT:</strong> {$e->Title}<br \>" : '';
            $emc .= "<strong>Branch/Employee:</strong> {$branchOrEmployee}<br \>";

            $emc .= "<strong>Previous Status:</strong> {$e->PreviousStatus}<br \>";
            $emc .= "<strong>Current Status:</strong> {$e->CurrentStatus}<br \>";
            $emc .= "<strong>Next Status:</strong> {$e->NexStatus}<br \>";
            $emc .= "<strong>Updated By:</strong> {$e->CreatedByName}<br \>";
            $emc .= "<strong>Updated Date:</strong> {$e->CreatedDate}<br \>";
            $emc .= "<i>Note: This is a system-generated email only. Please do not reply.</i>";

            return $emc;
        }
    endif;

    # Email Subject Standard
    if ( !function_exists('emailSubjectSupplierAccreditation') ):
        function emailSubjectSupplierAccreditation ($supplier_name) {
            return "Motortrade Accreditation: {$supplier_name}";
        }
    endif;

    # Email Template Contract Of Lease Reminder Before Expiration
    if ( !function_exists('emailTemplateSupplierAccreditationForSubmission') ):
        function emailTemplateSupplierAccreditationForSubmission ($supplier_name, $supplier_code, $supplier_mobile_no) {
            $link = ENVIRONMENT != 'production' ? "http://172.0.0.22:8080/marketing-forms/supplier" : base_url() . "supplier";
            $a = '<a href="'.$link.'" target="_blank">Link</a>';
            $body = "Hi {$supplier_name},<br><br>";
            $body .= "We have received your application for Supplier Accreditation. Please use the details below to complete your application.<br><br>";

            $body .= "Supplier Portal {$a}. <br><br>";

            $body .= "<b>Supplier Code</b>: {$supplier_code}<br>";
            $body .= "<b>Mobile No</b>: {$supplier_mobile_no}<br><br>";

            $body .= "Please do not share this email for your protection. Should you have concerns, please contact our Purchasing Department.<br><br>";
            $body .= "Thank you.<br><br>";
            return $body;
        }
    endif;

    # Email Template Contract Of Lease Reminder Before Expiration
    if ( !function_exists('emailTemplateSupplierAccreditation') ):
        function emailTemplateSupplierAccreditation ($supplier_name) {
            $body = "Hi {$supplier_name},<br><br>";
            $body .= "We are happy to share that your application for Supplier Accreditation has been approved and is now active. <br><br>";
            $body .= "Thank you.<br><br>";

            return $body;
        }
    endif;

    # Email Template Reminder COL Tagged as Expired
    if (!function_exists('email_template_col_expired')):
        function email_template_col_expired ($e)
        {
            $emc  = "";
            $emc .= ENVIRONMENT != 'production' ? "<h5 style='color:red;'>******* TEST ENVIRONMENT ***********</h5>" : "";

            $branchOrEmployee = $e->BranchName == '--' || empty($e->BranchName)
                ? $e->EmployeeName
                : $e->BranchName;
            
            $emc .= "This is to inform you that COL #{$e->idFormatted} has been tagged as EXPIRED. <br \><br \>\r";
            $emc .= "<strong>Form Name:</strong> {$e->FormName}<br \>";
            $emc .= "<strong>Record ID:</strong> {$e->idFormatted}<br \>";

            $emc .= !empty($e->Title) ? "<strong>SUBJECT:</strong> {$e->Title}<br \>" : '';
            $emc .= "<strong>Branch/Employee:</strong> {$branchOrEmployee}<br \>";

            $emc .= "<strong>Previous Status:</strong> {$e->PreviousStatus}<br \>";
            $emc .= "<strong>Current Status:</strong> {$e->CurrentStatus}<br \>";
            $emc .= "<strong>Next Status:</strong> {$e->NexStatus}<br \>";
            $emc .= "<strong>Updated By:</strong> {$e->CreatedByName}<br \>";
            $emc .= "<strong>Updated Date:</strong> {$e->CreatedDate}<br \>";
            $emc .= "<i>Note: This is a system-generated email only. Please do not reply.</i>";

            return $emc;
        }
    endif;

    # Email Subject Change Status/Standardize
    if (!function_exists('email_subject')):
        function email_subject ($formTitle = "", $formRecordId = "", $branchOrEmployee, $NewStatus = '--', $type = "CHANGE STATUS") {
            return "{$type}: {$formTitle} ID#{$formRecordId} | {$branchOrEmployee} | {$NewStatus}";
        }
    endif;

    # Email Title per form
    if (!function_exists('email_title')):
        function email_title ($FormId, $formRecordId) {

            $record = getItemRecord($FormId, $formRecordId);
            $title  = '';

            $options = ['Title', 'title', 'ProjectTitle', 'Name'];

            if (!$record) return '--';

            for ($i = 0; $i < count($options); $i++):
                if (property_exists($record, $options[$i])):
                    $Title = $options[$i];
                    $title = $record->$Title;
                    break;
                endif;
            endfor;

            return $title;
        }
    endif;

    # Email Title Employee Name Only
    if (!function_exists('email_title_employee_name')):
        function email_title_employee_name ($FormId, $FormRecordId) {

            $record = getItemRecord($FormId, $FormRecordId);
            $title  = '';
            /**
             * TODO:
             * Add checking if user is special user access
             */
            $options = ['EmployeeCode', 'employeecode', 'Requestor', 'requestor', 'assign_user'];

            if (!$record) return '--';

            for ($i = 0; $i < count($options); $i++):
                if (property_exists($record, $options[$i])):
                    $EmployeeCode = $options[$i];
                    $fullName = employeefullname($record->$EmployeeCode, TRUE);
                    $title = $fullName;
                    break;
                endif;
            endfor;

            return $title;
        }
    endif;

    # Email Title Branch/OrgName Only
    if (!function_exists('email_title_org_name')):
        function email_title_org_name ($FormId, $FormRecordId) {

            $record = getItemRecord($FormId, $FormRecordId);
            $title  = '';

            /**
             * TODO:
             * Add Checking if org id. to get Org Name
             */
            $options = ['Branch', 'BranchCode', 'branch', 'branchcode'];

            if (!$record) return '--';

            for ($i = 0; $i < count($options); $i++):
                if (property_exists($record, $options[$i])):
                    $branchCode = $options[$i];
                    $branchName = get_branch_name($record->$branchCode, TRUE);
                    $title = empty($branchName) ? '--' : $branchName;
                    break;
                endif;
            endfor;

            return $title;
        }
    endif;
