<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class FieldModel extends CI_Model
{
	function createField($name, $description, $instrumentId, $type)
	{
		if ($type < 0 || $type > 2)
			die("Invalid type: " . $type);
		
		// Insert the field
		$this->db->set('name', $name);
		$this->db->set('description', $description);
		$this->db->set('guid', 'UUID()', false);
		$this->db->set('instrument', $instrumentId);
		$this->db->set('type', $type);

		$this->db->insert('data_field');
		
		return true;
	}
	
	function allFields($instrumentId)
	{
		$this->db->select('*');
		$this->db->from('data_field');
		$this->db->where('instrument', $instrumentId);
		$this->db->order_by("enabled", "desc");
		$this->db->order_by("name", "asc");
		
		$query = $this->db->get();
		
		return $query->result();
	}
	
	function getField($id)
	{
		$this->db->select('*');
		$this->db->from('data_field');
		$this->db->where('id', $id);
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
			return $query->result()[0];
		else
			return false;
	}
	
	function deleteField($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('data_field');
	}
	
	
	function updateField($id, $name, $description, $instrumentId, $timeout, $enabled)
	{
		$this->db->set('name', $name);
		$this->db->set('description', $description);
		$this->db->set('instrument', $instrumentId);
		$this->db->set('timeout', $timeout);
		$this->db->set('enabled', $enabled);

		$this->db->where('id', $id);
		$this->db->update('data_field');
	}
	
	function fullFieldName($id)
	{
		$this->db->select('name, instrument');
		$this->db->from('data_field');
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get();
		$field = $query->result()[0];
		
		$this->db->select('name');
		$this->db->from('instrument');
		$this->db->where('id', $field->instrument);
		$this->db->limit(1);
		$query = $this->db->get();
		$instrument = $query->result()[0];
		
		return $instrument->name . " / " . $field->name;
	}
	
}













