<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UserModel extends CI_Model
{
	function submitDashboard($userId, $json)
	{
		$this->db->set('json', $json);
		$this->db->where('id', $userId);
		$this->db->update('users');
	}

	function getDashboard($userId) {
		$this->db->select('json');
		$this->db->from('users');
		$this->db->where('id', $userId);
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
			return $query->result()[0]->json;
		else
			return false;
	}

	function login($username, $password)
	{
		$this->db->select('id, username, name, can_users_edit, can_instruments_rem, can_instruments_add, can_data_view, can_markers_manage');
		$this->db->from('users');
		$this->db->where('username', $username);
		$this->db->where('password', sha1(XENON_SALT . $password));
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
			return $query->result();
		else
			return false;
	}

	function allUsers($except=null)
	{
		$this->db->select('id, username, name, can_users_edit, can_instruments_rem, can_instruments_add, can_data_view, can_markers_manage');
		if ($except != null) {
			$this->db->where_not_in('id', array($except));
		}

		$this->db->order_by("name", "asc");
		$this->db->from('users');

		$query = $this->db->get();

		return $query->result();
	}

	function getUser($id)
	{
		$this->db->select('id, username, name, can_users_edit, can_instruments_rem, can_instruments_add, can_data_view, can_markers_manage');
		$this->db->from('users');
		$this->db->where('id', $id);
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
			return $query->result();
		else
			return false;
	}

	function updateUser($id, $name
		, $can_data_view, $can_instruments_add, $can_instruments_rem, $can_users_edit, $can_markers_manage)
	{
		$this->db->set('name', $name);
		$this->db->set('can_data_view', $can_data_view);
		$this->db->set('can_instruments_add', $can_instruments_add);
		$this->db->set('can_instruments_rem', $can_instruments_rem);
		$this->db->set('can_users_edit', $can_users_edit);
		$this->db->set('can_markers_manage', $can_markers_manage);

		$this->db->where('id', $id);
		$this->db->update('users');
	}

	function createUser($username, $password, $name
		, $can_data_view, $can_instruments_add, $can_instruments_rem, $can_users_edit, $can_markers_manage)
	{
		$this->db->set('username', $username);
		$this->db->set('password', sha1(XENON_SALT . $password));
		$this->db->set('name', $name);
		$this->db->set('can_data_view', $can_data_view);
		$this->db->set('can_instruments_add', $can_instruments_add);
		$this->db->set('can_instruments_rem', $can_instruments_rem);
		$this->db->set('can_users_edit', $can_users_edit);
		$this->db->set('can_markers_manage', $can_markers_manage);

		$this->db->insert('users');

		return true;
	}

	function changePass($id, $password)
	{
		$this->db->set('password', sha1(XENON_SALT . $password));

		$this->db->where('id', $id);
		$this->db->update('users');
	}

	function deleteUser($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('users');
	}

}
