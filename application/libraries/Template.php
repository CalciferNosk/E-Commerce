<?php
    Class Template
    {

        //ci instance
        private $CI;
        //template Data
        var $template_data = array();

        public function __construct() 
        {
            $this->CI =& get_instance();
        }
 
        function set($content_area, $value)
        {
            $this->template_data[$content_area] = $value;
        }
        function post_js($content_area, $value = array()){
            $this->template_data[$content_area] = $value;
        }
        function modals($content_area, $value = array()){
            $this->template_data[$content_area] = $value;
        }
        function load($template = '', $name ='', $view = '' , $view_data = array(), $return = FALSE)
        {   
            $this->set($name , $this->CI->load->view($view, $view_data, TRUE));
            $this->CI->load->view('layouts/'.$template, $this->template_data);
        }
        function call_modals($array = array(),$data = array()){
            print_r($data);
            foreach ($array as $res): 
            $this->CI->load->view($res, $data);
            endforeach;
        }

    }
?>