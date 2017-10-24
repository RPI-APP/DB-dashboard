<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instrument extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('instrumentmodel', '', true);
		$this->load->model('fieldmodel', '', true);
	}
	
	// == Edit Instrument == //
	function index()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}

		$allInstruments = $this->instrumentmodel->allInstruments();
		
		$instrumentNames = array();
		
		foreach ($allInstruments as $u)
			$instrumentNames[$u->id] = ($u->enabled ? '✓ ' : '✗ ') .  $u->name;
		
		$data = array(
			"instrumentNames" => $instrumentNames,
			"name" => $userdata['name'],
			'userdata' => $userdata,
		);
		
		$this->load->view('instrumentedit', $data);
	}
	
	function instrumentselect()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		$id = $this->input->post('instrumentselect');
		
		if ($this->input->post('delsubmit') != null)
		{
			if (!$userdata['can_instruments_rem'])
			{
				$this->load->view('forbidden');
				return;
			}
			
			$this->instrumentmodel->deleteInstrument($id);
			redirect('instrument', 'refresh');
		}
		else if ($this->input->post('addsubmit') != null)
		{
			if (!$userdata['can_instruments_add'])
			{
				$this->load->view('forbidden');
				return;
			}
			
			redirect('instrument/create', 'refresh');
		}
		else if ($this->input->post('fieldsubmit') != null)
			redirect('instrument/fields/' . $id, 'refresh');
		else
		{
			if (!$userdata['can_instruments_add'])
			{
				$this->load->view('forbidden');
				return;
			}
			redirect('instrument/editinstrument/' . $id, 'refresh');
		}
	}
	
	function deleteinstrument($id)
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		if (!$userdata['can_instruments_rem'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$this->instrumentmodel->deleteInstrument($id);
		redirect('instrument', 'refresh');
	}
	
	function editinstrument($id)
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		$instrument = $this->instrumentmodel->getInstrument($id);
		
		if (!$userdata['can_instruments_add'] || !$instrument)
		{
			$this->load->view('forbidden');
			return;
		}
		
		$data = array(
			'u' => $instrument,
			'name' => $userdata['name'],
		);
		
		$this->load->view('instrumentedit-single', $data);
	}
	
	function submitedit()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		if (!$userdata['can_instruments_add'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$enabled = $this->input->post('enabled') ? 1 : 0;
		
		$this->instrumentmodel->updateInstrument($id, $name, $enabled);
		
		redirect('instrument', 'refresh');
	}
	
	// == Create Instrument == //
	function create()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}

		if (!$userdata['can_instruments_add'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$data['name'] = $userdata['name'];
		
		$this->load->view('instrumentcreate', $data);
	}
	
	function submitcreate()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		if (!$userdata['can_instruments_add'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$name = $this->input->post('name');
		
		$this->instrumentmodel->createInstrument($name);
		
		redirect('instrument', 'refresh');
	}
	
	// == Edit Fields == //
	
	function fields($instrumentId)
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}

		$instrumentName = $this->instrumentmodel->getInstrument($instrumentId)->name;
		
		$allFields = $this->fieldmodel->allFields($instrumentId);
		
		$allFieldNames = array();
		
		foreach ($allFields as $f)
		{
			$type = "error";
			if ($f->type == 0)		$type = "int";
			else if ($f->type == 1)	$type = "dbl";
			else if ($f->type == 2)	$type = "str";
			
			$allFieldNames[$f->id] = array(
				"name" => ($f->enabled ? '✓' : '✗') . " ($type) " . $f->name,
				"uuid" => "($type) [ " . $instrumentName . " / " . $f->name . " ]\n" . $f->guid,
				"id" => $f->id,
				"description" => $f->description,
			);
		}
		
		$data = array(
			"name" => $userdata['name'],
			"allFieldNames" => $allFieldNames,
			"instrumentName" => $instrumentName,
			"instrumentId" => $instrumentId,
			'userdata' => $userdata,
		);
		
		$this->load->view('fieldedit', $data);
	}
	
	function deletefield($id)
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		if (!$userdata['can_instruments_rem'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$instrument = $this->db->fieldmodel->getField($id)->instrument;
		
		$this->fieldmodel->deleteField($id);
		redirect('instrument/fields/' . $instrument, 'refresh');
	}
	
	function fieldselect()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		$id = $this->input->post('fieldselect');
		
		$instrument = $this->input->post('instrumentId');
		
		if ($this->input->post('delsubmit') != null)
		{
			if (!$userdata['can_instruments_rem'])
			{
				$this->load->view('forbidden');
				return;
			}
			
			$this->fieldmodel->deleteField($id);
			redirect('instrument/fields/' . $instrument, 'refresh');
		}
		else if ($this->input->post('addsubmit') != null)
		{
			if (!$userdata['can_instruments_add'])
			{
				$this->load->view('forbidden');
				return;
			}
			
			redirect('instrument/createfield/' . $instrument, 'refresh');
		}
		else
		{
			if (!$userdata['can_instruments_add'])
			{
				$this->load->view('forbidden');
				return;
			}
			
			redirect('instrument/editfield/' . $id, 'refresh');
		}
	}
	
	function createfield($instrument)
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		if (!$userdata['can_instruments_add'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$instrumentName = $this->instrumentmodel->getInstrument($instrument)->name;
		
		$data = array(
			"name" => $userdata['name'],
			"instrumentId" => $instrument,
			"instrumentName" => $instrumentName,
		);
		
		$this->load->view('fieldcreate', $data);
	}
	
	function submitcreatefield()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		if (!$userdata['can_instruments_add'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$name = $this->input->post('name');
		$instrumentId = $this->input->post('instrumentId');
		$dataType = $this->input->post('datatype');
		$description = $this->input->post('description');
		
		$this->fieldmodel->createField($name, $description, $instrumentId, $dataType);
		
		redirect('instrument/fields/' . $instrumentId, 'refresh');
	}
	
	function editfield($id)
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		if (!$userdata['can_instruments_add'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$field = $this->fieldmodel->getField($id);
		
		// Get all the instruments
		$allInstruments = $this->instrumentmodel->allInstruments();
		
		$instrumentNames = array();
		
		foreach ($allInstruments as $u)
			$instrumentNames[$u->id] = ($u->enabled ? '✓ ' : '✗ ') .  $u->name;
		
		$data = array(
			"instrumentNames" => $instrumentNames,
			"name" => $userdata['name'],
			"field" => $field,
		);
		
		$this->load->view('fieldedit-single', $data);
	}
	
	function submiteditfield()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			redirect('login', 'refresh');
		}
		
		if (!$userdata['can_instruments_add'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		$instrumentId = $this->input->post('instrumentselect');
		$timeout = $this->input->post('timeout');
		$enabled = $this->input->post('enabled');
		
		$this->fieldmodel->updateField($id, $name, $description, $instrumentId, $timeout, $enabled);
		
		redirect('instrument/fields/' . $instrumentId, 'refresh');
	}
}

















