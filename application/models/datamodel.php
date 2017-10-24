<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class DataModel extends CI_Model
{

	function getLastReading($guid)
	{
		$timestamp = $this->currentTimestamp();

		$this->db->select("id, type, timeout");
		$this->db->from("data_field");
		$this->db->where("guid", $guid);
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() != 1)
			return null;

		$result = $query->result()[0];
		$data_field = $result->id;
		$timeout = $result->timeout;
		$data_type = $result->type;
		$fromWhere = null;
		if      ($data_type == 0)	$fromWhere = "data_type_int";
		else if ($data_type == 1)	$fromWhere = "data_type_float";
		else if ($data_type == 2)	$fromWhere = "data_type_string";

		$this->db->select("data, timestamp");
		$this->db->from($fromWhere);
		$this->db->order_by("timestamp", "desc");
		$this->db->where('data_field', $data_field);
		$this->db->where('timestamp >=', $timestamp - $timeout);
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() != 1)
			return null;

		return $query->result()[0]->data;
	}

	function currentTimestamp()
	{
		$this->db->select("value");
		$this->db->from("information");
		$this->db->where('title', 'clock');

		$query = $this->db->get();

		if($query->num_rows() != 1)
			return 0;

		return $query->result()[0]->value;
	}

	function getData($dataFieldId, $data_type, $interval, $startTime, $endTime)
	{
		// SANITIZE THE INPUTS
		if (!filter_var($dataFieldId, FILTER_VALIDATE_INT)
			|| !filter_var($data_type, FILTER_VALIDATE_INT)
			|| !filter_var($interval, FILTER_VALIDATE_INT)
			|| !filter_var($startTime, FILTER_VALIDATE_INT)
			|| !filter_var($endTime, FILTER_VALIDATE_INT)
		)
		{
			return false;
		}

		$fromWhere = null;
		if      ($data_type == 0)	$fromWhere = "data_type_int";
		else if ($data_type == 1)	$fromWhere = "data_type_float";
		else if ($data_type == 2)	$fromWhere = "data_type_string";

		// Both SQL1 and SQL2 produce identical results.
		// However, SQL2 has been shown to be faster by several orders of magnitude*
		//  * This testing was completely unscientific

/*
		$sql1 =
"select $interval * ceil(lt.timestamp / $interval) as timestamp, data
from $fromWhere lt
where
lt.timestamp >= $startTime AND lt.timestamp < $endTime AND data_field=$dataFieldId
AND not exists (select 1
	from $fromWhere lt2
	where lt2.timestamp > lt.timestamp and
	lt2.timestamp <= $interval * ceil(lt.timestamp / $interval) and
	lt2.data_field=$dataFieldId
)
ORDER BY timestamp;";
*/


		$sql2 = <<<EOT
SELECT
	a.RoundedTimeStamp as timestamp,
	t.data as data

	FROM(SELECT
		CEIL(timestamp/$interval)*$interval as RoundedTimeStamp,
		MAX(timestamp) as timestamp

		FROM $fromWhere
		where timestamp >= $startTime AND timestamp < $endTime AND data_field=$dataFieldId

		GROUP BY CEIL(timestamp/$interval)*$interval
    ) a

	JOIN $fromWhere t
	ON t.timestamp = a.timestamp AND t.data_field=$dataFieldId
EOT;

		$sql = $sql2;

		$query = $this->db->query($sql);

		return $query->result();
	}

	function getMostRecent($dataFieldId, $timestamp, $data_type, $timeout)
	{
		$this->db->select('timestamp, data');

		$fromWhere = null;
		if      ($data_type == 0)	$fromWhere = "data_type_int";
		else if ($data_type == 1)	$fromWhere = "data_type_float";
		else if ($data_type == 2)	$fromWhere = "data_type_string";

		$this->db->from($fromWhere);
		$this->db->where('data_field', $dataFieldId);
		$this->db->where('timestamp <=', $timestamp);
		$this->db->where('timestamp >=', $timestamp - $timeout);
		$this->db->order_by("timestamp", "desc");
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
			return $query->result()[0];
		else
			return null;
	}
}
