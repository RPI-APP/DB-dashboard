<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('usermodel','',TRUE);
	}

	function index()
	{
		if($this->session->userdata('logged_in'))
		{
			//If no session, redirect to login page
			redirect('dashboard', 'refresh');
		}

		//This method will have the credentials validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean|callback_check_database');

		if($this->form_validation->run() == FALSE)
		{
			//Field validation failed.  User redirected to login page
			$this->load->view('login_view');
		}
		else
		{
			//Go to private area
			redirect('dashboard', 'refresh');
		}

	}

	function check_database($password)
	{
		//Field validation succeeded.  Validate against database
		$username = $this->input->post('username');

		//query the database
		$result = $this->usermodel->login($username, $password);

		if($result)
		{
			$sess_array = array();
			foreach($result as $row)
			{
				$sess_array = array(
				 'id' => $row->id,
				 'username' => $row->username,
				 'name' => $row->name,
				 'can_users_edit' => $row->can_users_edit,
				 'can_instruments_add' => $row->can_instruments_add,
				 'can_instruments_rem' => $row->can_instruments_rem,
				 'can_data_view' => $row->can_data_view,
				 'can_markers_manage' => $row->can_markers_manage,
				);
				$this->session->set_userdata('logged_in', $sess_array);
			}
			return true;
		}
		else
		{
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
		}
	}
}
?>
