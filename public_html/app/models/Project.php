<?php

class Project extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
	"project_num",
	"project_id",
	"project_name",
	"project_desc",
	"project_status",
	"project_type",	
	"project_duedate"	

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
		parent::__construct($db,'project');
	}

	public function all() 
	{ //get all users, admin only!
		$this->load();
		return $this->query;
	}

    public function search($user_id, $term,$limit)
    {
	// Execute a query
	try {
            //Busqueda fuzzy  (Excdelente!)
            //$query = "SELECT * FROM paciente WHERE CONCAT(CodHistoria, NomPaci, ApePaci) LIKE :term OR CONCAT(CodHistoria, NomPaci, ApePaci) LIKE :term LIMIT 50";
	    //$query = "SELECT * FROM person WHERE CONCAT(person_id, person_name, person_lname) LIKE :term OR CONCAT(otros campos separados por coma) LIKE :term LIMIT :limit";
	    //$query = "SELECT * FROM project WHERE CONCAT(project_id, project_num, project_name) LIKE :term LIMIT :limit";
	    $query = "SELECT DISTINCT p.*,pu.*,  u.username AS username, (SELECT u2.username FROM project_user pu2 JOIN users u2 ON pu2.user_id = u2.id WHERE pu2.project_id = p.project_id AND pu2.user_rol = 1 LIMIT 1) AS admin_username FROM project p JOIN project_user pu ON p.project_id = pu.project_id JOIN users u ON pu.user_id = u.id WHERE pu.user_id = :user_id AND ( p.project_name LIKE :term OR p.project_desc LIKE :term OR p.project_status LIKE :term ) LIMIT :limit";
            $params = array(':user_id' => $user_id, ':term' => '%' . $term . '%', ':limit' => $limit);
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

    public function searchWithTaskCount($term,$limit)
    {
	// Execute a query
	try {
            // Obtener los $limit registros que contengan una cadena $term en la concatenacion de 3 campos de la tabla 'person'
	    // La cadena $term puede estar en cualquier lugar de los campos que esten concatenados
	    $query = "SELECT person.*, COUNT(task.task_num) AS task_count FROM person LEFT JOIN task ON person.person_num = task.person_num WHERE CONCAT(person.person_id, person.person_name, person.person_lname) LIKE :term GROUP BY person.person_num LIMIT :limit";
            $params = array(':term' => '%' . $term . '%', ':limit' => $limit);
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

    public function searchAllWithTaskCount($limit)
    {
	// Execute a query
	try {
            //Busqueda fuzzy  (Excdelente!)
	    $query = "SELECT person.*, COUNT(task.task_num) AS task_count FROM person LEFT JOIN task ON person.person_num = task.person_num GROUP BY person.person_num LIMIT :limit";
            $params = array(':limit' => $limit);
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

    public function searchAll($limit)
    {
	// Execute a query
	try {
            //Busqueda fuzzy  (Excdelente!)
	    $query = "SELECT * FROM project GROUP BY project_num LIMIT :limit";
            $params = array(':limit' => $limit);
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
    
    public function getAllByUserId($limit, $user_id)
    {
	// Execute a query
	try {
            //Busqueda fuzzy  (Excdelente!)
            
            //$query = "SELECT p.*, pu.* FROM project p JOIN project_user pu ON p.project_id = pu.project_id WHERE pu.user_id = :user_id GROUP BY p.project_name LIMIT :limit";

            $query = "SELECT p.*, pu.*, u.username, admins.admin_username FROM project p INNER JOIN project_user pu ON p.project_id = pu.project_id INNER JOIN users u ON pu.user_id = u.id LEFT JOIN ( SELECT pu2.project_id, u2.username AS admin_username FROM project_user pu2 INNER JOIN users u2 ON pu2.user_id = u2.id WHERE pu2.user_rol = 1 ) AS admins ON p.project_id = admins.project_id WHERE pu.user_id = :user_id GROUP BY p.project_name LIMIT :limit";
            
	    //$query = "SELECT * FROM project GROUP BY project_num LIMIT :limit";
            $params = array(':limit' => $limit, ':user_id' => $user_id);
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

	public function getMaxProjectNum($user_id)
	{
        $query = "SELECT MAX(p.project_num) AS max_project_num FROM project p JOIN project_user pu ON p.project_id = pu.project_id WHERE pu.user_id = :user_id";
        $params = array(':user_id' => $user_id);
	    $result = $this->db->exec($query, $params);        
        $lastProjectNum = $result[0]['max_project_num'];
        return $lastProjectNum;
	}

	public function getByPersonPhone($per_phone)
	{
		$this->load(array('person_phone=?', $per_phone));
		return $this->query;
	}

    public function existsByPersonPhone($per_phone)
    {
        //echo '<script>console.log("Entro a verificar()"); </script>';
        $this->load(array('person_phone = ?', $per_phone));
        return $this->dry() ? false : true;
    }

    public function existsByPersonNum($personnum)
    {
        //echo '<script>console.log("Entro a verificar()"); </script>';
        $this->load(array('person_num = ?', $personnum));
        return $this->dry() ? false : true;
    }

	public function add($maxprojectnum, $unsanitizeddata )
	{
		try {	    
		$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
	    $data['project_createdat']=$this->getCurrentdate();
		$data['project_updatedat']=$this->getCurrentdate();		
		$data['project_num']=$maxprojectnum;
		$this->copyFrom($data);
		//var_dump($data);
		//die('debug');
		$this->save();
		return true;
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
	}

	public function getById($project_id) 
	{
		$this->load(array('project_id=?',$project_id));
		$this->copyTo('POST');
	}
	
	public function getProjectById($project_id) 
	{
		$this->load(array('project_id=?',$project_id));
		//die('aqui '.$this->project_type);
		//return $this->query;
	}	
	
    public function getJoinById($project_id, $user_id)
    {
        //die('hola'.$user_id);
	// Execute a query
	try {
            $query = "SELECT p.* , pu.* FROM project p JOIN project_user pu ON p.project_id = pu.project_id WHERE pu.project_id = :project_id AND pu.user_id = :user_id";
            $params = array(':project_id' => $project_id, ':user_id' => $user_id);
	        $result = $this->db->exec($query, $params);
            if ($result) {
                // Assuming there's only one row per user or you just need the first result.
                return $result[0];
            } else {
                // Handle case where no data is found or other error
                return null;
            }
	} catch (Exception $e) {
    	    // Handle the exception appropriately, for example, log the error or display an error message to the user
    	    error_log("Exception: " . $e->getMessage());
    	    die("An error occurred while processing the request");
	}
    } 	
	
	//public function edit($p_num, $unsanitizeddata)
    public function edit($project_id)
	{
		//$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);

		//$data['updated_at']=$this->getCurrentdate();
		$this->load(array('project_id=?',$project_id));
		//$this->copyFrom($data);
        $this->copyFrom('POST');
		$this->update();
	}

	public function delete($project_id)
	{
    // ***************   Borrar todo lo relacionado a una persona m********
    // Step 1: Find all project_person records for the given project_id
    $projectPersons = $this->db->exec('SELECT projectperson_num FROM project_person WHERE project_id = ?', $project_id);

    // Step 2: Find all project_person_task records related to these projectperson_num and delete them
    foreach ($projectPersons as $projectPerson) {
        //die('si funciono');
        $projectperson_num = $projectPerson['projectperson_num'];

        // Find all tasks related to this projectperson_num in project_person_task
        $projectPersonTasks = $this->db->exec('SELECT task_num FROM project_person_task WHERE entity_num = ? AND type_entity = "person"', $projectperson_num);
        
        //die('hola'.$projectPersonTasks[0]['task_num']);
        
        // Step 3: Delete all notes related to these tasks
        foreach ($projectPersonTasks as $projectPersonTask) {
            $task_num = $projectPersonTask['task_num'];
            $this->db->exec('DELETE FROM note WHERE task_num = ?', $task_num);
        }

        // Step 4: Delete the tasks themselves
        foreach ($projectPersonTasks as $projectPersonTask) {
            $task_num = $projectPersonTask['task_num'];
            $this->db->exec('DELETE FROM task WHERE task_num = ?', $task_num);
        }

        // Step 5: Delete the project_person_task entries
        $this->db->exec('DELETE FROM project_person_task WHERE entity_num = ? AND type_entity = "person"', $projectperson_num);
    }

    // Step 6: Delete the project_person entries
    $this->db->exec('DELETE FROM project_person WHERE project_id = ?', $project_id);

    // ***************   Borrar todo lo relacionado a un proyecto m********
    // Delete notes related to tasks of the project
    $this->db->exec('
        DELETE n
        FROM note n
        INNER JOIN task t ON n.task_num = t.task_num
        INNER JOIN project_person_task ppt ON t.task_num = ppt.task_num
        WHERE ppt.entity_num = ? AND ppt.type_entity = "project"
    ', $project_id);

    // Delete tasks related to the project
    $this->db->exec('
        DELETE t
        FROM task t
        INNER JOIN project_person_task ppt ON t.task_num = ppt.task_num
        WHERE ppt.entity_num = ? AND ppt.type_entity = "project"
    ', $project_id);

    // Delete entries in project_person_task related to the project
    $this->db->exec('
        DELETE FROM project_person_task
        WHERE entity_num = ? AND type_entity = "project"
    ', $project_id);

    // Delete entries in project_user related to the project
    $this->db->exec('
        DELETE FROM project_user
        WHERE project_id = ?
    ', $project_id);

        try {
            // Finally, delete the project itself
            $result = $this->db->exec('
                DELETE FROM project
                WHERE project_id = ?
                ', $project_id);
	        if ($result)
	        {
                return true; // Record deleted successfully
            } else {
                return false; // Record not found
            }
        } catch (\PDOException $e) {
            // Handle any exceptions that occur during the execution of the query
            $f3->set('error', 'Error deleting record: ' . $e->getMessage());
        }
	}

public function projectInProgress($projectId) {
    try {
        $this->load(array('project_id = ?', $projectId));
        if (!$this->dry()) {
            $this->project_activatedat = $this->getCurrentDate();
            $this->project_status = 2;
            $this->update();
            return true;
        } else {
            return false; // Project not found
        }
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        throw new RuntimeException("Error updating activation date.");
    }
}


	public function deleteProjectUser($project_id)
	{
        try {
            // Prepare and execute the delete query
	        $query = "DELETE FROM project_user WHERE project_id = :project_id";
            $params = array(':project_id' => $project_id);
	        $result = $this->db->exec($query, $params);
	        if ($result)
	        {
                return true; // Record deleted successfully
            } else {
                return false; // Record not found
            }
        } catch (\PDOException $e) {
            // Handle any exceptions that occur during the execution of the query
            $f3->set('error', 'Error deleting record: ' . $e->getMessage());
        }
	}
	
	public function deleteProjectPerson($project_id)
	{
        try {
            // Prepare and execute the delete query
	        $query = "DELETE FROM project_person WHERE project_id = :project_id";
            $params = array(':project_id' => $project_id);
	        $result = $this->db->exec($query, $params);
	        if ($result)
	        {
                return true; // Record deleted successfully
            } else {
                return false; // Record not found
            }
        } catch (\PDOException $e) {
            // Handle any exceptions that occur during the execution of the query
            $f3->set('error', 'Error deleting record: ' . $e->getMessage());
        }
	}
	
	public function deleteProjectPersonTask($entity_num, $task_num, $type_entity)
	{
	    $this->load(array('entity_num =? and task_num =? and type_entity =?', $entity_num, $task_num, $type_entity));
			// Handle the query result
			if ($this->erase()) {
				return true;
			}else{
				return false;
			}
	}
	
public function getUnderOverEstimateLapse($project_id)
{
    try {
        // Define the query to calculate the lapse
        $query = "SELECT DATEDIFF(`project_duedate`, `project_completedat`) AS UnderOverEstimateLapse 
                  FROM project 
                  WHERE `project_id` = :project_id";

        // Define the parameter for the query
        $params = [
            ':project_id' => $project_id
        ];

        // Execute the query
        $result = $this->db->exec($query, $params);

        // Check if a result was returned
        if (!empty($result)) {
            // Return the lapse (integer days)
            return $result[0]['UnderOverEstimateLapse'];
        } else {
            // Return null if no result is found
            return null;
        }
    } catch (\PDOException $e) {
        // Log database errors
        error_log("Database error in getUnderOverEstimateLapse: " . $e->getMessage());
        throw new \Exception("Failed to retrieve lapse data for project ID: $project_id");
    } catch (\Exception $e) {
        // Log any other errors
        error_log("Unexpected error in getUnderOverEstimateLapse: " . $e->getMessage());
        throw $e;
    }
}

public function getInactiveLapse($project_id)
{
    try {
        $query = "SELECT DATEDIFF(NOW(), `project_createdat`) AS InactiveLapse 
                  FROM project 
                  WHERE `project_id` = :project_id";

        $params = [':project_id' => $project_id];
        $result = $this->db->exec($query, $params);

        return !empty($result) ? $result[0]['InactiveLapse'] : null;
    } catch (\PDOException $e) {
        error_log("Database error in getInactiveLapse: " . $e->getMessage());
        throw new \Exception("Failed to retrieve inactive lapse for project ID: $project_id");
    } catch (\Exception $e) {
        error_log("Unexpected error in getInactiveLapse: " . $e->getMessage());
        throw $e;
    }
}

public function getActivationLapse($project_id)
{
    try {
        $query = "SELECT DATEDIFF(`project_activatedat`, `project_createdat`) AS ActivationLapse 
                  FROM project 
                  WHERE `project_id` = :project_id";

        $params = [':project_id' => $project_id];
        $result = $this->db->exec($query, $params);

        return !empty($result) ? $result[0]['ActivationLapse'] : null;
    } catch (\PDOException $e) {
        error_log("Database error in getActivationLapse: " . $e->getMessage());
        throw new \Exception("Failed to retrieve activation lapse for project ID: $project_id");
    } catch (\Exception $e) {
        error_log("Unexpected error in getActivationLapse: " . $e->getMessage());
        throw $e;
    }
}

public function getCompletionLapse($project_id)
{
    try {
        $query = "SELECT DATEDIFF(`project_completedat`, `project_activatedat`) AS CompletionLapse 
                  FROM project 
                  WHERE `project_id` = :project_id";

        $params = [':project_id' => $project_id];
        $result = $this->db->exec($query, $params);

        return !empty($result) ? $result[0]['CompletionLapse'] : null;
    } catch (\PDOException $e) {
        error_log("Database error in getCompletionLapse: " . $e->getMessage());
        throw new \Exception("Failed to retrieve completion lapse for project ID: $project_id");
    } catch (\Exception $e) {
        error_log("Unexpected error in getCompletionLapse: " . $e->getMessage());
        throw $e;
    }
}

}
