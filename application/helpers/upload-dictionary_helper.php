<?php
    /**
     * This is for Upload Controller Only
     * Created By: Russ
     * 2021-05-31
     */

    if (!function_exists('employee_fullname'))
    {
        function employee_fullname () 
        {
            $CI = get_instance();
            $CI->load->model('Upload_model', 'upload_model');
        
            return $CI->upload_model->get_employee_fullname();
        }
    }

    if (!function_exists('branch_code_list'))
    {
        function branch_code_list () 
        {
            $CI = get_instance();
            $CI->load->model('Upload_model', 'upload_model');
        
            return $CI->upload_model->get_branch_code();
        }
    }

?>