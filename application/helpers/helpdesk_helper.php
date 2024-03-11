<?php

if(!function_exists('getSurveyUri'))
{
    function getSurveyUri(string $id, string $formId)
    {
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            $protocol = 'http';
        }

        $uriFull = explode('/', $_SERVER['REQUEST_URI']);
        $uriFirst = $uriFull[1];
        $uri = $formId.'.'.$id;
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . "/{$uriFirst}/Form_Dynamic_Main/create_page/72/{$uri}";
    }
}

if(!function_exists('checkResolveStatus'))
{
    function checkResolveStatus(string $id, string $formId, string $statusId)
    {
        switch($formId) {
            case 53:
                $resolvedChecker = $statusId == 416 ? TRUE : FALSE;
                break;
            case 58:
                $resolvedChecker = $statusId == 511 ? TRUE : FALSE;
                break;
            case 59:
                $resolvedChecker = $statusId == 539 ? TRUE : FALSE;
                break;
            case 60:
                $resolvedChecker = $statusId == 530 ? TRUE : FALSE;
                break;
            case 61:
                $resolvedChecker = $statusId == 577 ? TRUE : FALSE;
                break;
            case 62:
                $resolvedChecker = $statusId == 586 ? TRUE : FALSE;
                break;
            case 63:
                $resolvedChecker = $statusId == 595 ? TRUE : FALSE;
                break;
            case 64:
                $resolvedChecker = $statusId == 604 ? TRUE : FALSE;
                break;
            case 65:
                $resolvedChecker = $statusId == 614 ? TRUE : FALSE;
                break;
            case 66:
                $resolvedChecker = $statusId == 623 ? TRUE : FALSE;
                break;
            case 67:
                $resolvedChecker = $statusId == 632 ? TRUE : FALSE;
                break;
            case 68:
                $resolvedChecker = $statusId == 641 ? TRUE : FALSE;
                break;
            case 69:
                $resolvedChecker = $statusId == 650 ? TRUE : FALSE;
                break;
            default:
                $resolvedChecker = FALSE;
                break;
        }

        if($resolvedChecker) {
            return "<a href='".getSurveyUri($id, $formId)."' class='btn btn-sm btn-success pull-left top-btn'>Survey <i class='fa fa-edit'></i></a>";
        }
        return "";

    }
}

if(!function_exists('getTableName')) {
    function getTableName($form_id) {
        $table_list = [
            50 => 'tblforminfraction',
            52 => 'tblformpayrollissue',
            53 => 'tblformitrequest',
            58 => 'tblFormBDDHelpdesk',
            59 => 'tblFormHRHelpdesk',
            60 => 'tblFormAuditHelpdesk',
            61 => 'tblFormCCODHelpdesk',
            62 => 'tblFormCSODHelpdesk',
            63 => 'tblFormMarketingHelpdesk',
            64 => 'tblFormAccountingHelpdesk',
            65 => 'tblFormPurchasingHelpdesk',
            66 => 'tblFormSnDHelpdesk',
            67 => 'tblFormWarehouseHelpdesk',
            68 => 'tblFormLegalHelpdesk',
            69 => 'tbl_form_csm_request',
            70 => 'tblFormPurSupplierAccreditation'
        ];

        return $table_list[$form_id];
    }
}
