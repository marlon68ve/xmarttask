<?php

class Note extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
		"note_num",
		"task_num",
		"note_title",
		"note_status",		
		"note_createdat",
		"note_content"
	);
	
	private function sanitizeInput(array $data, array $fieldNames) 
	{ //sanitize input - with thanks to richgoldmd
	   return array_intersect_key($data, array_flip($fieldNames));
	}
	
	private function getCurrentdate()
	{
		return date("Y-m-d H:i:s");
	}
	
	public function __construct(DB\SQL $db) 
	{
		parent::__construct($db,'note');
	}

	public function all() 
	{ //get all users, admin only!
		$this->load();
		return $this->query;
	}

	public function getByNum($note_num) 
	{
		$this->load(array('note_num=?',$note_num));
		$this->copyTo('POST');
	}

    public function existsByPersonNum($personnum, $taskdate)
    {
		$this->load(array('person_num=? AND task_date=?', $personnum, $taskdate));
        return $this->dry() ? false : true;
    }

	public function getByFechCita($cFech)
	{
		$this->load(array('FechCita = ?', $cFech));
		return $this->query;
	}

	public function getTaskDate($ctasknum)
	{
		$this->load(array('task_num = ?', $ctasknum));
		return $this->query;
	}
	
	public function getNotesByTaskNumJoin($task_num)
	{
		try {
		    //$this->load(array('task_num = ?', $task_num));		    
            return $this->find(['task_num = ?', $task_num]);
		    //return $this->query;
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
	}	

	public function add( $unsanitizeddata )
	{
		try {	    
		    $data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
	        $data['note_createdat']=$this->getCurrentdate();
		    $data['note_updatedat']=$this->getCurrentdate();
		    $this->copyFrom($data);
		    $this->save();
		    return $this->dry() ? false : true;
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
	}

    public function getByPersonNumJoin($person_num)
	{
		// Execute a query
		try {
            		$sql = "SELECT t.person_num, t.task_date, n.* FROM note n INNER JOIN task t ON n.task_num = t.task_num WHERE t.person_num = :person_num ORDER BY STR_TO_DATE( t.task_date , '%d/%m/%Y' ) DESC";
			$params = array(':person_num' => $person_num);
			$result = $this->db->exec($sql, $params);
			return $result;
			// Handle the query result
			if (!$result) {
				// Handle the query failure appropriately, for example, log the error or display an error message to the user
				error_log("Query failed");
				$result = '';
			}
			return $result;
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
	}

    public function existsnote($tasknum)
    {
	$this->load(array('task_num=?', $tasknum));
        return $this->dry() ? false : true;
    }

	public function getByCodHistoria($cod_histo)
	{
		$this->load(array('CodHistoria=?', $cod_histo));
	}

	public function getByTaskDateJoin($cdate)
	{
		// Execute a query
		try {
			//$sql = 'SELECT t.task_num, t.task_date, t.task_time, t.person_num, t.username, t.task_type, t.task_title, t.task_desc, t.task_status, t.task_content, p.person_num, p.person_name, p.person_lname FROM task t INNER JOIN person p ON t.person_num = p.person_num WHERE t.task_date = :cdate';
			$sql = 'SELECT t.*, p.person_num, p.person_name, p.person_lname, COUNT(n.note_num) AS note_count FROM task t JOIN person p ON t.person_num = p.person_num LEFT JOIN note n ON t.task_num = n.task_num WHERE t.task_date = :cdate GROUP BY t.task_num, t.person_num, p.person_name';
			$params = array(':cdate' => $cdate);
			$result = $this->db->exec($sql, $params);
			//return $result;
			// Handle the query result
			if (!$result) {
				// Handle the query failure appropriately, for example, log the error or display an error message to the user
				error_log("Query failed");
				$result = '';
			}
			return $result;
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
	}


	public function existsTaskNoteJoin($person_num)
	{
		// Execute a query
		try {
			$sql = 'SELECT n.note_num, n.task_num, t.task_num, t.person_num FROM task t INNER JOIN note n ON t.task_num = n.task_num WHERE t.person_num = :person_num';
			$params = array(':person_num' => $person_num);
			//$result = $this->db->exec($sql, $params);
			// Handle the query result
			if ($this->db->exec($sql, $params)) {
				// Handle the query failure appropriately, for example, log the error or display an error message to the user
                return true; // Record deleted successfully
            } else {
                return false; // Record not found
            }
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
	}

	public function existsTaskNotesJoinTaskNum($tasknum)
	{
		// Execute a query
		try {
			$sql = 'SELECT n.note_num, n.task_num, t.task_num, t.person_num FROM task t INNER JOIN note n ON t.task_num = n.task_num WHERE t.task_num = :tasknum';
			$params = array(':tasknum' => $tasknum);
			//$result = $this->db->exec($sql, $params);
			// Handle the query result
			if ($this->db->exec($sql, $params)) {

				// Handle the query failure appropriately, for example, log the error or display an error message to the user
                return true; // Record deleted successfully
            } else {
                return false; // Record not found
            }
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
	}

	public function updateTaskStatus($tasknum,$status){

		//$this->update();

		try {
			$this->load(array('task_num = ?', $tasknum));
			//die($this->CondicionCita);
			$this->task_status=$status;
			// Handle the query result
			if ($this->save()) {
				return true;
			}else{
				return false;
			}
		} catch (Exception $e) {
				// Handle the exception appropriately, for example, log the error or display an error message to the user
				error_log("Exception: " . $e->getMessage());
				die("An error occurred while processing the request");
		}		
	}

	public function countByDate($fecha){
		try {
			$sql = 'SELECT CondicionCita,COUNT(*) as count FROM cita where FechCita = :fechcita GROUP BY CondicionCita ORDER BY count DESC';
			//$sql = 'SELECT CondicionCita FROM cita WHERE FechCita = :fechcita';
			$params = array(':fechcita' => $fecha);
			if ($this->db->exec($sql, $params)){
				$condicionCitas = $this->db->exec($sql, $params);
				//foreach ($condicionCitas as $obj)
                	//die($obj['CondicionCita'].', '.$obj['count']);
				return $condicionCitas;
			}else{
				return null;
			}


		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
		
		//return $condicionCitaCounts;
	}
	
public function updateNoteStatus($note_num, $new_status)
{
    $sql = "UPDATE note SET note_status = :status WHERE note_num = :note_num";
    $params = array(':status' => $new_status, ':note_num' => $note_num);
    $this->db->exec($sql, $params);
    return true;
}
	

    public function edit($note_num)
	{
		$this->load(array('note_num=?',$note_num));
        $this->copyFrom('POST');
		$this->note_updatedat=$this->getCurrentdate();  
		//$this->note_status = $note_status;
		$this->update();
	}

	public function delete($notenum) 
	{
		try {
			$this->load(array('note_num=?',$notenum));
			// Handle the query result
			if ($this->erase()) {
				return true;
			}else{
				return false;
			}
		} catch (Exception $e) {
				// Handle the exception appropriately, for example, log the error or display an error message to the user
				error_log("Exception: " . $e->getMessage());
				die("An error occurred while processing the request");
		}
	}

	public function deleteall($cod_histo) 
	{
		$this->erase(['CodHistoria=?',$cod_histo]);
	}

}
