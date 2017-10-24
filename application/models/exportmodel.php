<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ExportModel extends CI_Model
{
	function insertExportRecord($name, $path)
	{
		$this->db->set('name', $name);
		$this->db->set('path', $path);
		
		$this->db->insert('export_files');
		
		return true;
	}
}













