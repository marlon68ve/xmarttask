<?php

class Task extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
		"task_num",
		"person_phone",
		"person_num",
		"username",
		"task_createdat",
		"task_date",
		"task_time",
		"task_type",
		"task_title",
		"task_desc",
		"task_status",
		"task_hours",
		"task_plannedstart",
		"task_plannedend",
		"task_actualstart",
		"task_actualend",
		"task_content"
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
		parent::__construct($db,'task');
	}

   public function getTaskCategoriesFrequency() {
        $stmt = $this->db->prepare("SELECT task_type, COUNT(*) as count FROM task GROUP BY task_type");
        $stmt->execute();
        return $stmt->fetchAll();
    }

public function getAllTasksCount($project_id)
{
    $sql = "SELECT COUNT(DISTINCT t.task_num) AS total_tasks
            FROM task t
            LEFT JOIN project_task pt ON t.task_num = pt.task_num AND pt.project_id = ?
            LEFT JOIN person_task pnt ON t.task_num = pnt.task_num AND pnt.project_id = ?
            WHERE pt.project_id IS NOT NULL OR pnt.project_id IS NOT NULL";
    
    $result = $this->db->exec($sql, [$project_id, $project_id]);
    return $result[0]['total_tasks'] ?? 0;
}


	public function all() 
	{ //get all users, admin only!
		$this->load();
		return $this->query;
	}

	public function getall() 
	{ //get all users, admin only!
	    try {        
	        $result = $this->db->exec('SELECT * FROM task');
            if ($result) {
                return $result;
            } else {
                return null;
            }
	} catch (Exception $e) {
    	    error_log("Exception: " . $e->getMessage());
    	    die("An error occurred while processing the request");
	}
	}

    public function existsByTaskNum($tasknum, $taskdate)
    {
	    $this->load(array('task_num=? AND task_date=?', $tasknum, $taskdate));
        return $this->dry() ? false : true;
    }

    public function existsByPersonPhone($personphone, $taskdate)
    {
	    $this->load(array('person_phone=? AND task_date=?', $personphone, $taskdate));
        return $this->dry() ? false : true;
    }

    public function search($project_id, $term,$limit)
    {
	// Execute a query
	try {
            //Busqueda fuzzy  (Excdelente!)
            //$query = "SELECT * FROM paciente WHERE CONCAT(CodHistoria, NomPaci, ApePaci) LIKE :term OR CONCAT(CodHistoria, NomPaci, ApePaci) LIKE :term LIMIT 50";
	    //$query = "SELECT * FROM person WHERE CONCAT(person_id, person_name, person_lname) LIKE :term OR CONCAT(otros campos separados por coma) LIKE :term LIMIT :limit";
	    //$query = "SELECT * FROM project WHERE CONCAT(project_id, project_num, project_name) LIKE :term LIMIT :limit";
	    $query = "SELECT t.*, CASE WHEN pt.person_task_id IS NOT NULL THEN 'person' WHEN prt.project_task_id IS NOT NULL THEN 'project' ELSE 'none' END AS type_entity, CASE WHEN pt.person_task_id IS NOT NULL THEN pt.person_num WHEN prt.project_task_id IS NOT NULL THEN prt.project_id ELSE NULL END AS entity_num FROM task t LEFT JOIN person_task pt ON t.task_num = pt.task_num LEFT JOIN project_person pp ON pt.person_num = pp.person_num LEFT JOIN project_task prt ON t.task_num = prt.task_num WHERE (pp.project_id = :project_id OR prt.project_id = :project_id) AND ( (t.username LIKE :term) OR (t.task_date LIKE :term) OR (t.task_time LIKE :term) OR (t.task_title LIKE :term) OR (t.task_status LIKE :term) ) GROUP BY t.task_title LIMIT :limit";
            $params = array(':project_id' => $project_id, ':term' => '%' . $term . '%', ':limit' => $limit);
	    $result = $this->db->exec($query, $params);
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

    public function taskCountByStatus($project_id)
    {
        // Retrieve task count grouped by type
        //$sql = "SELECT task_status, COUNT(*) as count FROM task AS t JOIN project_person_task AS ppt ON t.task_num = ppt.task_num WHERE ppt.entity_num = :project_id AND ppt.type_entity ='project' GROUP BY task_status";
        
        $sql = "SELECT task_status, COUNT(*) AS count FROM ( SELECT t.task_status FROM task AS t JOIN project_task AS pt ON t.task_num = pt.task_num WHERE pt.project_id = :project_id UNION ALL SELECT t.task_status FROM task AS t JOIN person_task AS pt ON t.task_num = pt.task_num WHERE pt.project_id = :project_id ) AS combined GROUP BY task_status";  
        //$result = $this->db->exec($sql);
		$params = array(':project_id' => $project_id);
		$result = $this->db->exec($sql, $params); 
        return $result;
    }

    public function existsTask1($personphone)
    {
	    $this->load(array('person_phone=?', $personphone));
        return $this->dry() ? false : true;
    }

	public function existsTask($person_phone, $project_id) 
	{ //get all users, admin only!
	    try {        
	        $sql = "SELECT ppt.* FROM project_person_task AS ppt JOIN project_person AS pp ON pp.projectperson_num = ppt.entity_num WHERE pp.person_phone = :person_phone AND pp.project_id = :project_id AND ppt.type_entity ='person'";
			$params = array(':person_phone' => $person_phone, ':project_id' => $project_id);
			$result = $this->db->exec($sql, $params);	        
            if ($result) {
                return true;
            } else {
                return false;
            }
	} catch (Exception $e) {
    	    error_log("Exception: " . $e->getMessage());
    	    die("An error occurred while processing the request");
	}
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


    public function getByPersonNumJoin($person_num, $type_entity)
	{
		// Execute a query
		try {
            		//$sql = "SELECT t.person_num, t.task_date, n.* FROM note n INNER JOIN task t ON n.task_num = t.task_num WHERE t.person_num = :person_num ORDER BY STR_TO_DATE( t.task_date , '%d/%m/%Y' ) DESC";
            $sql = "SELECT t.* FROM task t JOIN project_person_task ppt ON t.task_num = ppt.task_num WHERE ppt.entity_num = :person_num AND ppt.type_entity = :type_entity";

			//$sql = "SELECT * FROM task WHERE person_num = :person_num ORDER BY STR_TO_DATE( task_date , '%d/%m/%Y' ) DESC";
			
			
			//$sql = "SELECT t.*, p.person_name, p.person_lname, n.*, COUNT(n.note_num) AS note_count FROM task t JOIN person p ON t.person_num = p.person_num LEFT JOIN note n ON t.task_num = n.task_num WHERE t.person_num = :person_num GROUP BY t.task_num, t.person_num, p.person_name ORDER BY STR_TO_DATE( task_date , '%d/%m/%Y' ) DESC";
			$params = array(':person_num' => $person_num, ':type_entity' => $type_entity);
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
	                
	public function getTaskByPersonNumJoin($person_num, $task_num)
	{
		try {
		$this->load(array('person_num=? AND task_num=?', $person_num, $task_num));
		$this->copyTo('POST');
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
	}
	
   // public function getTaskByProjectpersonNum($per_num, $task_num)
    public function getTaskByPersonNum($project_id, $per_num, $task_num) 
    {
       // die('hola'.'  '.$project_id.'  '.$per_num.'  '.$task_num);
        try {
	    //$query = "SELECT t.*, ppt.entity_num, ppt.type_entity FROM task t JOIN project_person_task ppt ON t.task_num = ppt.task_num WHERE ppt.entity_num = :per_num AND ppt.task_num = :task_num AND ppt.type_entity = 'person'";
	    //$query = "SELECT t.*, pt.person_num FROM task t JOIN person_task pt ON t.task_num = pt.task_num WHERE pt.person_num = :per_num AND pt.task_num = :task_num";
	    $query = "SELECT t.*, p.person_name, p.person_lname, p.person_phone, p.person_addr FROM task t JOIN person_task pt ON t.task_num = pt.task_num JOIN person p ON pt.person_num = p.person_num WHERE pt.project_id = :project_id AND pt.person_num = :per_num AND t.task_num = :task_num";
            $params = array(
            ':project_id' => $project_id,                
            ':per_num' => $per_num,
            ':task_num' => $task_num
        );
	    $result = $this->db->exec($query, $params);
            if ($result) {
                return $result[0];
            } else {
                return null;
            }

        } catch (\Exception $e) {
            // Rollback the transaction on error
            $this->db->rollback();
            throw $e;
        }
    }	

	public function getTaskByTaskNum($task_num)
	{
	    //die('entro'.$person_num.'  '.$task_num);
		// Execute a query
		try {
		$this->load(array('task_num=?', $task_num));
		$this->copyTo('POST');
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
	}
	
	public function getTasksByProjectId($limit, $project_id)
	{	
	    try {        
            //$query = "SELECT t.*, ppt.entity_num, ppt.type_entity FROM task t JOIN project_person_task ppt ON t.task_num = ppt.task_num LEFT JOIN project_person pp ON ppt.entity_num = pp.person_num AND ppt.type_entity = 'person' WHERE (ppt.entity_num = :project_id AND ppt.type_entity = 'project') OR (pp.project_id = :project_id AND ppt.type_entity = 'person');";
            
            // $query = "SELECT t.*, CASE WHEN pt.person_task_id IS NOT NULL THEN 'person' WHEN prt.project_task_id IS NOT NULL THEN 'project' ELSE 'none' END AS type_entity, CASE WHEN pt.person_task_id IS NOT NULL THEN pt.person_num WHEN prt.project_task_id IS NOT NULL THEN prt.project_id ELSE NULL END AS entity_num FROM task t LEFT JOIN person_task pt ON t.task_num = pt.task_num LEFT JOIN project_person pp ON pt.person_num = pp.person_num LEFT JOIN project_task prt ON t.task_num = prt.task_num WHERE pp.project_id = :project_id OR prt.project_id = :project_id GROUP BY t.task_title LIMIT :limit";  
            
            
            $query = "SELECT t.*, COALESCE(active_notes.active_count, 0) AS active_count, COALESCE(done_notes.done_count, 0) AS done_count, CASE WHEN pt.project_id IS NOT NULL THEN 'project' WHEN ptt.project_id IS NOT NULL THEN 'person' ELSE NULL END AS type_entity, CASE WHEN pt.project_id IS NOT NULL THEN pt.project_id WHEN ptt.project_id IS NOT NULL THEN ptt.person_num ELSE NULL END AS entity_num FROM task t LEFT JOIN project_task pt ON t.task_num = pt.task_num AND pt.project_id = :project_id LEFT JOIN person_task ptt ON t.task_num = ptt.task_num AND ptt.project_id = :project_id LEFT JOIN ( SELECT n.task_num, COUNT(*) AS active_count FROM note n WHERE n.note_status = 0 GROUP BY n.task_num ) active_notes ON t.task_num = active_notes.task_num LEFT JOIN ( SELECT n.task_num, COUNT(*) AS done_count FROM note n WHERE n.note_status = 1 GROUP BY n.task_num ) done_notes ON t.task_num = done_notes.task_num WHERE pt.project_id IS NOT NULL OR ptt.project_id IS NOT NULL GROUP BY t.task_title LIMIT :limit";          

            $params = array(':limit' => $limit, ':project_id' => $project_id);
	        $result = $this->db->exec($query, $params);
	        
            if ($result) {
                return $result;
            } else {
                return null;
            }
	        } catch (Exception $e) {
    	        error_log("Exception: " . $e->getMessage());
    	        die("An error occurred while processing the request");
	        }
    }

	public function add($project_id, $username, $entity_num, $type_entity, $unsanitizeddata )
	{
		try {	    
		//$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		$data = $unsanitizeddata;
		//$data['task_status'] = 'Registrada';
		$data['username'] = $username;
	    $data['task_createdat']=$this->getCurrentdate();
		$data['task_updatedat']=$this->getCurrentdate();		
		$this->copyFrom($data);
		$this->save();
		
		$lastTaskNum = $this->task_num;
		
		// El proximo procedimiento es innecesario ya que luego de ejecutar save(), tengo de una vez el recien creado task_num
        // Definir consulta para obtener task_num recien creado
/*        $lastTaskNum = $this->db->exec(
            'SELECT MAX(task_num) AS last_task_num FROM task'
        );
        $lastTaskNum = $lastTaskNum[0]['last_task_num']; 
 */
 
 
		$this->addProPerTask($project_id, $entity_num, $lastTaskNum, $type_entity);

        return true; // Indicate success
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}        
	}
	
	public function addProPerTask($project_id, $entity_num, $task_num, $type_entity )
	{
        try {
            // Prepare the SQL statement for inserting into the project table
            // $sql = 'INSERT INTO project_person_task (entity_num, task_num, type_entity) VALUES (:entity_num, :task_num, :type_entity)';
            //die('entro'.'    '.$type_entity);      
            if ($type_entity == 'person'){
                $sql = "INSERT INTO person_task (person_num, project_id, task_num) VALUES (:entity_num, :project_id, :task_num)";
                // Bind parameters for the project table
                $params = array(
                    ':entity_num' => $entity_num,                    
                    ':project_id' => $project_id,                    
                    ':task_num' => $task_num
                    //':type_entity' => $type_entity            
                );
            }else{
                $sql = "INSERT INTO project_task (project_id, task_num) VALUES (:entity_num, :task_num)"; 
                // Bind parameters for the project table
                $params = array(
                    ':entity_num' => $entity_num,
                    ':task_num' => $task_num
                    //':type_entity' => $type_entity            
                );
            }
            // Execute the SQL statement for inserting into the project table
            $this->db->exec($sql, $params);	            
            } catch (PDOException $e) {
                // Log the error
                error_log($e->getMessage());
                // Return a user-friendly error message
                return ['success' => false, 'message' => 'Database error occurred.'];
            }           
	}
	
    public function taskCountByProjectId($project_id)
    {
	    try {        
            $query = "SELECT COUNT(*) AS task_count FROM task t JOIN project_person_task ppt ON t.task_num = ppt.task_num LEFT JOIN  project_person pp ON ppt.entity_num = pp.projectperson_num AND ppt.type_entity = 'person' WHERE (ppt.entity_num = :project_id AND ppt.type_entity = 'project') OR (pp.project_id = :project_id AND ppt.type_entity = 'person');";
            $params = array(':project_id' => $project_id);
	        $result = $this->db->exec($query, $params);
            if ($result) {
                return $result[0]['task_count'];
            } else {
                return null;
            }
	} catch (Exception $e) {
    	    error_log("Exception: " . $e->getMessage());
    	    die("An error occurred while processing the request");
	}
    }

    public function getProjectTasks($project_id)
    {
	try {
            $query = "SELECT ppt.*, t.* FROM project_person_task ppt JOIN task t ON ppt.task_num = t.task_num WHERE ppt.entity_num = :project_id AND ppt.type_entity = 'project'";
            $params = array(':project_id' => $project_id);
	        $result = $this->db->exec($query, $params);
            if ($result) {
                return $result;
            } else {
                return null;
            }
	} catch (Exception $e) {
    	    error_log("Exception: " . $e->getMessage());
    	    die("An error occurred while processing the request");
	}
    } 
    
    //public function getPersonTasks($project_id, $projectperson_num)
    public function getPersonTasks($project_id, $entity_num)
    {
	try {
            $query = "SELECT p.*, t.*, 'person' AS type_entity, p.person_num AS entity_num FROM person p LEFT JOIN project_person pp ON p.person_num = pp.person_num JOIN person_task pt ON p.person_num = pt.person_num AND pp.project_id = pt.project_id JOIN task t ON pt.task_num = t.task_num WHERE pp.project_id = :project_id AND p.person_num = :entity_num";
        
        
           // $query = "SELECT t.*, p.*, 'person' AS type_entity, p.person_num AS entity_num FROM task t JOIN person_task pt ON t.task_num = pt.task_num JOIN person p ON pt.person_num = p.person_num JOIN project_person pp ON p.person_num = pp.person_num WHERE pp.project_id = :project_id AND p.person_num = :entity_num";
            
            //$params = array(':project_id' => $project_id, ':projectperson_num' => $projectperson_num);
            $params = array(':project_id' => $project_id, ':entity_num' => $entity_num);
	        $result = $this->db->exec($query, $params);

            if ($result) {
                return $result;
            } else {
                return null;
            }
	} catch (Exception $e) {
    	    error_log("Exception: " . $e->getMessage());
    	    die("An error occurred while processing the request");
	}
    } 

	public function getByTheTaskDateJoin($cdate)
	{
		// Execute a query
		try {
			//$sql = 'SELECT t.task_num, t.task_date, t.task_time, t.person_num, t.username, t.task_type, t.task_title, t.task_desc, t.task_status, t.task_content, p.person_num, p.person_name, p.person_lname FROM task t INNER JOIN person p ON t.person_num = p.person_num WHERE t.task_date = :cdate';
			$sql = 'SELECT t.*, p.*, COUNT(n.note_num) AS note_count FROM task t JOIN person p ON t.person_num = p.person_num LEFT JOIN note n ON t.task_num = n.task_num WHERE t.task_date = :cdate GROUP BY t.task_num, t.person_num, p.person_name';
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

	public function getPerByTaskDateJoin($project_id, $type_entity, $task_date)
	{
		// Execute a query
		try {
            $sql = "SELECT t.*, p.*, pp.projectperson_num FROM task t JOIN person_task pt ON t.task_num = pt.task_num JOIN project_person pp ON pt.person_num = pp.person_num JOIN person p ON pp.person_num = p.person_num WHERE  pp.project_id = :project_id AND t.task_date = :task_date";
            //$sql = "SELECT t.*, p.*, pp.projectperson_num FROM task t JOIN person_task pt ON t.task_num = pt.task_num JOIN person p ON pt.person_num = p.person_num JOIN project_person pp ON p.person_num = pp.person_num WHERE pp.project_id = :project_id AND t.task_date = :task_date";
            $params = array(
                //':type_entity' => $type_entity,
                ':project_id' => $project_id,
                ':task_date' => $task_date
            );			
			$result = $this->db->exec($sql, $params);
			if (!$result) {
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


	public function getProByTaskDateJoin($project_id, $type_entity, $task_date)
	{
	    //die('entro'.'  '.$project_id.'  '.$type_entity.'  '.$task_date);
		// Execute a query
		try {
            $sql = "SELECT t.*,p.project_id, COUNT(n.note_num) AS note_count FROM task t LEFT JOIN note n ON t.task_num = n.task_num JOIN project_task pt ON t.task_num = pt.task_num JOIN project p ON pt.project_id = p.project_id WHERE p.project_id = :project_id AND t.task_date = :task_date GROUP BY t.task_num";
            $params = array(
               // ':type_entity' => $type_entity,
                ':project_id' => $project_id,
                ':task_date' => $task_date
            );			
			$result = $this->db->exec($sql, $params);
			if (!$result) {
				error_log("Query failed");
				$result = '';
			}
			//die('entro'.'   '.$task_date);
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

    public function edit($task_num)
	{
        try {
            $this->load(array('task_num = ?', $task_num));
            $this->copyFrom('POST');
            if (!$this->dry()) {
                $this->save();
                return true; // Successfully updated
            }
            return false; // Task not found
        } catch (\Exception $e) {
            error_log("Exception: " . $e->getMessage());
            throw new \Exception("Failed to update task type.");
        }		
	}

	public function delete($tasknum) 
	{
		try {
			$this->load(array('task_num=?',$tasknum));
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
	
    public function deleteTask($entity_num, $task_num, $type_entity) 
    {
        //die('entro'.$entity_num.' '. $task_num.' '. $type_entity);
        $this->db->begin();

        try {
            //die('entro');
            // Step 1: Delete related notes
            $this->db->exec('DELETE FROM note WHERE task_num = ?', $task_num);
            
            if ($type_entity === 'project'){
                //die('entro1');
                // Step 2: Delete from project_person_task
                $this->db->exec('DELETE FROM project_person_task WHERE entity_num = ? AND task_num = ? AND type_entity = "project"', [$entity_num, $task_num]);
            }else{
                //die('entro2'.$type_entity);
                // Step 2: Delete from project_person_task
                $this->db->exec('DELETE FROM project_person_task WHERE entity_num = ? AND task_num = ? AND type_entity = "person"', [$entity_num, $task_num]);
            }
            // Step 3: Delete from task
            $this->db->exec('DELETE FROM task WHERE task_num = ?', $task_num);
//die('entro8');
            if($this->db->commit()) {
                //die('entro1');
				return true;
			}else{
			    //die('entro2');
				return false;
			}
            //die('entro'.'  '.$project_id.'  '. $task_num.'  '. $type_entity);
        } catch (\Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }      	
	

	public function deleteall($pernum) 
	{
		$this->erase(['person_num=?',$pernum]);
	}
	
    /**
     * Count the number of tasks related to persons and projects for a given date and project.
     *
     * @param string $taskDate Date in 'dd/mm/YY' format
     * @param int $projectId Project ID
     * @return array Counts of person-related and project-related tasks
     */
    public function countTasksByDateAndProject($taskDate, $projectId)
    {

        // Convert taskDate to the format 'YYYY-MM-DD'
        //$formattedDate = DateTime::createFromFormat('d/m/Y', $taskDate)->format('Y-m-d');
        $formattedDate = $taskDate;
        // Count tasks related to persons in the project
        $personTaskQuery = "SELECT COUNT(*) as person_task_count
                            FROM task t
                            JOIN person_task pt ON t.task_num = pt.task_num
                            JOIN project_person pp ON pt.person_num = pp.person_num
                            WHERE pp.project_id = :project_id AND t.task_date = :task_date";
        $personTaskParams = array(':project_id' => $projectId, ':task_date' => $formattedDate);
        $personTaskCount = $this->db->exec($personTaskQuery, $personTaskParams)[0]['person_task_count'];

        // Count tasks related to the project directly
        $projectTaskQuery = "SELECT COUNT(*) as project_task_count
                             FROM task t
                             JOIN project_task pt ON t.task_num = pt.task_num
                             WHERE pt.project_id = :project_id AND t.task_date = :task_date";
        $projectTaskParams = array(':project_id' => $projectId, ':task_date' => $formattedDate);
        $projectTaskCount = $this->db->exec($projectTaskQuery, $projectTaskParams)[0]['project_task_count'];

        // Return results
        return [
            'person_task_count' => (int)$personTaskCount,
            'project_task_count' => (int)$projectTaskCount
        ];
    }	

public function taskCountByType($project_id)
{
    $sql = "SELECT combined_tasks.task_type, COUNT(*) AS count FROM ( SELECT task.task_type FROM task JOIN project_task ON task.task_num = project_task.task_num WHERE project_task.project_id = :project_id UNION ALL SELECT task.task_type FROM task JOIN person_task ON task.task_num = person_task.task_num WHERE person_task.project_id = :project_id ) AS combined_tasks GROUP BY combined_tasks.task_type";

    $params = array(':project_id' => $project_id);
    $result = $this->db->exec($sql, $params); 

    return $result;
}

public function usernameCompletedTasks($project_id)
{
    $sql = "SELECT t.username, COUNT(DISTINCT CASE WHEN t.task_status = 1 THEN t.task_num END) AS completed_tasks FROM task t LEFT JOIN project_task pt ON t.task_num = pt.task_num LEFT JOIN person_task ptask ON t.task_num = ptask.task_num LEFT JOIN project_person pp ON ptask.person_num = pp.person_num AND ptask.project_id = pp.project_id WHERE (pt.project_id = :project_id OR pp.project_id = :project_id) GROUP BY t.username HAVING completed_tasks > 0 ORDER BY completed_tasks DESC";

    $params = array(':project_id' => $project_id);
    $result = $this->db->exec($sql, $params); 

    return $result;
}

public function getUserNotesCount($project_id)
{
    $sql = "
        SELECT 
            t.username, 
            SUM(CASE WHEN n.note_status = 0 THEN 1 ELSE 0 END) AS active_notes,
            SUM(CASE WHEN n.note_status = 1 THEN 1 ELSE 0 END) AS done_notes
        FROM task t
        LEFT JOIN note n ON t.task_num = n.task_num
        LEFT JOIN project_task pt ON t.task_num = pt.task_num
        LEFT JOIN person_task ptask ON t.task_num = ptask.task_num
        LEFT JOIN project_person pp ON ptask.person_num = pp.person_num AND ptask.project_id = pp.project_id
        WHERE (pt.project_id = :project_id OR pp.project_id = :project_id)
        GROUP BY t.username
        ORDER BY done_notes DESC, active_notes DESC
    ";

    $params = [':project_id' => $project_id];
    return $this->db->exec($sql, $params);
}








/*
    public function savePicture($task_num, $task_picture)
    {
        try {
            $sql = "INSERT INTO pictures (task_num, task_picture) VALUES (:task_num, :task_picture)";
            $params = array(
                ':task_num' => $task_num,
                ':task_picture' => $task_picture
            );
            $this->db->exec($sql, $params);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred.'];
        }
    }

    public function getNextPictureNumber($task_num)
    {
        $sql = "SELECT COUNT(*) AS total FROM pictures WHERE task_num = :task_num";
        $result = $this->db->exec($sql, [':task_num' => $task_num]);
        return isset($result[0]['total']) ? $result[0]['total'] + 1 : 1;
    }
*/


    public function savePicture($task_num, $task_picture)
    {
        try {
            $sql = "INSERT INTO pictures (task_num, task_picture) VALUES (:task_num, :task_picture)";
            $params = array(
                ':task_num' => $task_num,
                ':task_picture' => $task_picture
            );
            $this->db->exec($sql, $params);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'Database error occurred.'];
        }
    }

    public function getNextPictureNumber($task_num)
    {
        $sql = "SELECT COUNT(*) AS total FROM pictures WHERE task_num = :task_num";
        $result = $this->db->exec($sql, [':task_num' => $task_num]);
        return isset($result[0]['total']) ? $result[0]['total'] + 1 : 1;
    }

    public function deletePicture($picture_id)
    {
        try {
            $sql = "SELECT task_picture FROM pictures WHERE taskpicture_num = :picture_id";
            $result = $this->db->exec($sql, [':picture_id' => $picture_id]);
            if (!empty($result)) {
                $filePath = $result[0]['task_picture'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $sql = "DELETE FROM pictures WHERE taskpicture_num = :picture_id";
                $this->db->exec($sql, [':picture_id' => $picture_id]);
                return true;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }



    public function getPicturesByTaskNum($task_num)
    {
	try {
            $query = "SELECT * FROM `pictures` WHERE task_num = :task_num";
            $params = array(':task_num' => $task_num);
	        $result = $this->db->exec($query, $params);
            if ($result) {
                return $result;
            } else {
                return null;
            }
	} catch (Exception $e) {
    	    error_log("Exception: " . $e->getMessage());
    	    die("An error occurred while processing the request");
	}
    } 

}