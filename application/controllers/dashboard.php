<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TODO
 *
 * User change own name and pass
 *
 * Breadcrumbs
 *
 * Friendly error messages (ex Duplicate names)
 *
 * Enable/disable sorting in export tree
 *
 * Automatic backup
 *
 */
class Dashboard extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('datamodel', '', true);
		$this->load->model('usermodel', '', true);
	}

	function index()
	{
		if(!$this->session->userdata('logged_in'))
		{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}

		$session_data = $this->session->userdata('logged_in');
		$data['username'] = $session_data['username'];
		$data['name'] = $session_data['name'];
		$this->load->view('dashboard', $data);
	}

	function logout()
	{
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('home', 'refresh');
	}

	function lastReading($guid)
	{
		$value = $this->datamodel->getLastReading($guid);
		if ($value === null)
			echo "null";
		else
			echo $value;
	}

	function dashboardUpdate() {
		$session_data = $this->session->userdata('logged_in');

		if(!$session_data) {
			// No active session.
			echo "1";
			return;
		}

		$json = $this->input->post('json');

		if (!$json) {
			// No json
			echo "2";
			return;
		}

		$this->usermodel->submitDashboard($session_data['id'], $json);

		echo "0";
	}

	function getDashboard() {
		$session_data = $this->session->userdata('logged_in');
		$dash = $this->usermodel->getDashboard($session_data['id']);

		if (!empty($dash)) {
			echo $dash;
		}
	}
}
