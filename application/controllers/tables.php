<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require '../libraries/php-export-data/php-export-data.class.php';

class Tables extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('instrumentmodel', '', true);
		$this->load->model('datamodel', '', true);
		$this->load->model('fieldmodel', '', true);
		$this->load->model('exportmodel', '', true);
	}
	
	// == Show Tables == //
	function index()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}

		if (!$userdata['can_data_view'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$dataFields = null;
		$dataFieldsNames = null;
		$query = null;
		$tree = $this->input->get('tree', true);
		
		$epoch = time() * 1000;		// Default values
		$end = $epoch - 1000 * 60 * 60 * 24;
		$interval = 1000;
		
		if ($tree != null)
		{
			$epoch = $this->input->get('epoch',  true);
			$end =   $this->input->get('end',  true);
			$interval = $this->input->get('interval',  true);
			
			$query = http_build_query(array('tree' => $tree, 'epoch' => $epoch, 'end' => $end, 'interval' => $interval));
			
			$dataFields = array();
			$dataFieldsNames = array();
			foreach (explode(',', $tree) as $s)
			{
				if ($s[0] != 'i')
				{
					$dataFields[] = $this->fieldmodel->getField($s);
					$dataFieldsNames[] = $this->fieldmodel->fullFieldName($s);
				}
			}
		}
		
		$allInstruments = $this->instrumentmodel->allInstruments();
		$allFields = array();
		
		foreach ($allInstruments as $inst)
			$allFields[] = $this->fieldmodel->allFields($inst->id);
		
		$data = array(
			"dataFields" => $dataFields,
			"dataFieldsNames" => $dataFieldsNames,
			'query' => $query,
			
			"name" => $userdata['name'],
			"instruments" => $allInstruments,
			"fields" => $allFields,
			
			"epoch" => $epoch,
			"end" => $end,
			"interval" => $interval,
		);
		
		$this->load->view('table', $data);
	}
	
	function submit()
	{
		$date1 = DateTime::createFromFormat('d-M-Y H:i:s', $this->input->post('time1'));
		if ($date1 == null)
			$date1 = DateTime::createFromFormat('d-m-Y H:i:s', $this->input->post('time1'));
		$date1 = $date1->getTimestamp() * 1000;
		
		$date2 = DateTime::createFromFormat('d-M-Y H:i:s', $this->input->post('time2'));
		if ($date2 == null)
			$date2 = DateTime::createFromFormat('d-m-Y H:i:s', $this->input->post('time2'));
		$date2 = $date2->getTimestamp() * 1000;
		
		if ($date1 > $date2)
		{
			$temp = $date1;
			$date1 = $date2;
			$date2 = $temp;
		}
		$epoch = $date1;
		$end = $date2;
		$tree = $this->input->post('tree');
		$interval = $this->input->post('interval');
		
		if ($this->input->post('prevsubmit') != null)
		{
			$query = http_build_query(array('tree' => $tree,
											'epoch' => $epoch,
											'end' => $end,
											'interval' => $interval
										));
			redirect('tables?' . $query, 'refresh');
		}
		else if ($this->input->post('exportsubmit') != null)
		{
			$this->csvExport($tree, $epoch, $end, $interval);
		}
	}
	
	function csvExport($tree, $masterEpoch, $masterEnd, $interval)
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}

		if (!$userdata['can_data_view'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		set_time_limit(0);						// Allow the script to run forever
		ignore_user_abort(false);				// Make sure the script aborts if the user stops listening
		
		// output headers so that the file is downloaded rather than displayed
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=export.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');
		
		// ====
		parse_str("tree=" . $tree, $out);
		$dataFieldIds = array();
		foreach (explode(',', $out['tree']) as $s)
		{
			if ($s[0] != 'i')
				$dataFieldIds[] = $s;
		}
		// ====
		
		$MAX_ROWS_PER_OPERATION = floor(100000 / count($dataFieldIds));
		
		$timePerMaxRows = $MAX_ROWS_PER_OPERATION * $interval;
		
		// Calculate DIVISIBLE epoch
		$masterEpoch = ceil($masterEpoch / $interval) * $interval;
		
		$dataFields = array();
		$dataFieldsNames = array();
		$dataFieldsNames[] = 'Time (ms)';
		foreach ($dataFieldIds as $id)
		{
			$dataFields[] = $this->fieldmodel->getField($id);
			$dataFieldsNames[] = $this->fieldmodel->fullFieldName($id);
		}
		
		// output the column headings
		fputcsv($output, $dataFieldsNames);
				
		$lastRow = null;
		for (
				$epoch = $masterEpoch;
				$epoch < $masterEnd;
				$epoch += $timePerMaxRows + $interval
			)
		{
			$end = $epoch + $timePerMaxRows;
			if ($end > $masterEnd)
				$end = $masterEnd;
			
			$this->extractData(2, $dataFields, $epoch, $end, $interval, $lastRow, $output);
		}
		
		fclose($output);
	}
	
	function mysqlData()
	{
		$userdata = $this->session->userdata('logged_in');
		if(!$userdata)
		{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}

		if (!$userdata['can_data_view'])
		{
			$this->load->view('forbidden');
			return;
		}
		
		$pagenum  = $this->input->get('pagenum',  true);
		$pagesize = $this->input->get('pagesize', true);
		
		parse_str("tree=" . $this->input->get('tree', true), $out);
		$dataFieldIds = array();
		foreach (explode(',', $out['tree']) as $s)
		{
			if ($s[0] != 'i')
				$dataFieldIds[] = $s;
		}
		
		$startItem = $pagenum * $pagesize;
		
		$masterEpoch = $this->input->get('epoch',  true);
		$masterEnd =   $this->input->get('end',  true);
		$interval = $this->input->get('interval',  true);
		
		// Calculate DIVISIBLE epoch
		$masterEpoch = ceil($masterEpoch / $interval) * $interval;
		
		$dataFields = array();
		foreach ($dataFieldIds as $id)
			$dataFields[] = $this->fieldmodel->getField($id);
		
		$epoch = $masterEpoch + $interval * $startItem;
		$end = $epoch + $interval * $pagesize;
		if ($end > $masterEnd)
			$end = $masterEnd;

		$totalRows = floor(($masterEnd - $masterEpoch) / $interval);
		
		$rows = $this->extractData(1, $dataFields, $epoch, $end, $interval);
		
		$data[] = array(
		   'TotalRows' => $totalRows,
		   'Rows' => $rows,
		);

		echo json_encode($data);
	}
	
	private function extractData($mode, $dataFields, $epoch, $end, $interval, &$lastRow=null, $output=null)
	{
		if ($mode == 1)
			$rows = array();
		
		// fetch the data
		$fieldData = array();
		$fieldDataPos = array();
		$fieldDataLen = array();
		$fieldDataTime = array();
		foreach ($dataFields as $field)
		{
			$fieldData[$field->guid] = $arr = $this->datamodel->getData($field->id, $field->type, $interval, $epoch - $interval, $end);
			$fieldDataLen[$field->guid] = count($arr);
			$fieldDataPos[$field->guid] = 0;
			$fieldDataTime[$field->guid] = 0;
		}
		
		// Iterate over every position in time we want to record
		for ($time = $epoch; $time <= $end; $time += $interval)
		{
			$row = array();
			
			// Save that time
			$row['00000000-0000-0000-0000-000000000000'] = $time;
			
			// Iterate over the data fields in the row
			foreach ($dataFields as $key=>$field)
			{
				// Find the next chunk of data in the field
				if ($fieldDataPos[$field->guid] >= $fieldDataLen[$field->guid])
					$nextDataChunk = null;
				else
					$nextDataChunk = $fieldData[$field->guid][$fieldDataPos[$field->guid]];
				
				// If this chunk of data belongs here
				if ($nextDataChunk != null && $time == $nextDataChunk->timestamp)
				{
					// Make sure it's not expired
					if ($time - $nextDataChunk->timestamp < $field->timeout)
					{
						// Good data
						$row[$field->guid] = $nextDataChunk->data;
						$fieldDataPos[$field->guid]++;
						$fieldDataTime[$field->guid] = $nextDataChunk->timestamp;
					}
					else
					{
						// Expired data
						$row[$field->guid] = null;
						$fieldDataPos[$field->guid]++;
					}
				}
				// If next chunk needs to come from history instead
				else
				{
					// If we have no history in the cache
					if ($lastRow == null)
					{
						// Look up the most recent value before this time using the slow method
						$value = $this->datamodel->getMostRecent($field->id, $time, $field->type, $field->timeout);
						
						if ($value == null)	
						{
							// No value before this time
							$row[$field->guid] = null;
						}
						else if ($time - $value->timestamp < $field->timeout)
						{
							// Value existed and was not expired
							$row[$field->guid] = $value->data;
							$fieldDataTime[$field->guid] = $value->timestamp;
						}
						else
						{
							// Value existed but had expired
							$row[$field->guid] = null;
						}
					}
					// If we do have a history in the cache
					else
					{
						if ($time - $fieldDataTime[$field->guid] < $field->timeout)
						{
							// Value existed and was not expired
							$row[$field->guid] = $lastRow[$field->guid];	
						}
						else
						{
							// Value existed but had expired
							$row[$field->guid] = null;
						}
					}
				}
			}
			
			if ($mode == 1)
				$rows[] = $row;
			else if ($mode == 2)
				fputcsv($output, $row);
			
			$lastRow = $row;
		}
		
		if ($mode == 1)
			return $rows;
	}
}

















