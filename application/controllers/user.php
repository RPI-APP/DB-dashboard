<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('usermodel', '', true);
	}
	
	// === For editing users == //
	
	function index()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}

		if (!$userdata['can_users_edit'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$allUsers = $this->usermodel->allUsers($userdata['id']);
		
		$usernamePass = array();
		
		foreach ($allUsers as $u)
			$usernamePass[$u->id] = $u->name . ' (' . $u->username . ')';
		
		$data = array(
			"name" => $userdata['name'],
			"usernamePass" => $usernamePass,
		);
		
		$this->load->view('useredit', $data);
	}
	
	/*
	 * Gets told which user we want to edit and then passes that user on to the
	 * edituser(..) function
	 */
	function editselect()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		if (!$userdata['can_users_edit'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$id = $this->input->post('userselect');
		
		if ($this->input->post('delsubmit') != null)
		{
			$this->usermodel->deleteUser($id);
			redirect('user', 'refresh');
		}
		else if ($this->input->post('addsubmit') != null)
			redirect('user/create', 'refresh');
		else
			redirect('user/edituser/' . $id, 'refresh');
	}
	
	/*
	 * Edits a user's information
	 */
	function edituser($id)
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		$user = $this->usermodel->getUser($id)[0];
		
		if (!$userdata['can_users_edit'] || !$user)
		{
			$this->load->view('forbidden');
			return;
		}
		
		$data = array(
			'name' => $userdata['name'],
			'u' => $user,
		);
		
		$this->load->view('useredit-single', $data);
	}
	
	function submitedit()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		if (!$userdata['can_users_edit'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$can_data_view = $this->input->post('can_data_view')				? 1 : 0;
		$can_data_edit = $this->input->post('can_data_edit')				? 1 : 0;
		$can_instruments_add = $this->input->post('can_instruments_add')	? 1 : 0;
		$can_instruments_rem = $this->input->post('can_instruments_rem')	? 1 : 0;
		$can_users_edit = $this->input->post('can_users_edit')				? 1 : 0;
		$can_markers_manage = $this->input->post('can_markers_manage')		? 1 : 0;
	
		$this->usermodel->updateUser($id, $name
			, $can_data_view, $can_instruments_add, $can_instruments_rem, $can_users_edit, $can_markers_manage);
		
		redirect('user', 'refresh');
	}
	
	// === For creating users == //

	function create()
	{
		$userdata = $this->session->userdata('logged_in');
		$data['name'] = $userdata['name'];
		
		if(!$userdata)
		{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}

		if (!$userdata['can_users_edit'])
		{
			$this->load->view('forbidden', $data);
			return;
		}
		
		$this->load->view('usercreate', $data);
	}
	
	function submitcreate()
	{
		$userdata = $this->session->userdata('logged_in');
		$data['name'] = $userdata['name'];
		
		if(!$userdata)
			redirect('login', 'refresh');
		
		if (!$userdata['can_users_edit'])
		{
			$this->load->view('forbidden', $data);
			return;
		}
		
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$name = $this->input->post('name');
		$can_data_view = $this->input->post('can_data_view')				? 1 : 0;
		$can_instruments_add = $this->input->post('can_instruments_add')	? 1 : 0;
		$can_instruments_rem = $this->input->post('can_instruments_rem')	? 1 : 0;
		$can_users_edit = $this->input->post('can_users_edit')				? 1 : 0;
		$can_markers_manage = $this->input->post('can_markers_manage')		? 1 : 0;
		
		$this->usermodel->createUser($username, $password, $name
			, $can_data_view, $can_instruments_add, $can_instruments_rem, $can_users_edit, $can_markers_manage);
		
		redirect('user', 'refresh');
	}

}

















