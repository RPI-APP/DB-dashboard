<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class InstrumentModel extends CI_Model
{
	function createInstrument($name)
	{
		$this->db->set('name', $name);

		$this->db->insert('instrument');
		
		return true;
	}
	
	function allInstruments()
	{
		$this->db->select('*');
		$this->db->from('instrument');
		$this->db->order_by("enabled", "desc");
		$this->db->order_by("name", "asc");
		
		$query = $this->db->get();
		
		return $query->result();
	}
	
	function getInstrument($id)
	{
		$this->db->select('*');
		$this->db->from('instrument');
		$this->db->where('id', $id);
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
			return $query->result()[0];
		else
			return false;
	}
	
	function updateInstrument($id, $name, $enabled)
	{
		$this->db->set('name', $name);
		$this->db->set('enabled', $enabled);

		$this->db->where('id', $id);
		$this->db->update('instrument');
	}
	
	function deleteInstrument($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('instrument');
	}
}













