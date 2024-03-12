<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GlobalController extends CI_Controller {

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

        $this->load->model('GlobalModel', 'global');
		$this->load->model('ItemModel', 'item');
    }
	public function index()
	{	
		#get last id in table Items
		$last_item = (int)$this->item->getLastItemId()->id;

		#set a array
		$all_item = [];

		#loop last id to create whole array
		for($last=0;$last <= $last_item;$last++){
			#add value to array using array_push
			array_push($all_item,$last);
		}

		#divide the last id into 2
		$for_rand_num = $last_item/2;

		#generate random number to array
		$random_keys=array_rand($all_item,$for_rand_num);
		

		#getting content/data with parameters
        $data['content'] = $this->item->getItem($random_keys);


		$this->load->view('HomeView',$data);
	}

	public function loginUser(){
		$data = $this->global->checkByUserName($_POST['UserName']);
		// var_dump($data);die;
		if(!empty($data)){
			if($_POST['Password']==$data->Password){
				$_SESSION['username'] = $data->UserName;
				echo json_encode(1);
			}else{
				echo json_encode(2);
			}
		}else{
			echo json_encode(0);
		}
	}

	public function logout(){
		$this->session->sess_destroy();

     redirect('');
	}


}
