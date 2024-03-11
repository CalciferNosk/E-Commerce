<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VideoController extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	public function __construct()
    {
        parent::__construct();
		
        $this->load->model('VideoModel', 'V_model');
    }
	public function index()
	{
		$data['video'] = $this->V_model->getVideo()->result_array();
		$_SESSION['username'] = '';
		// var_dump($video);
		$this->load->view('VideoView',$data);
	}
	function UserLogin(){
	
	}
	function playVideo($id){
		$data['video_data'] = $this->V_model->getVideoById($id)->row();
		$this->load->view('PlayVideoView',$data);
	
	}
	
}
