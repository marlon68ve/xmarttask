<?php

class Person extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
	"person_num",
	"person_id",
	"person_name",
	"person_lname",
	"person_gender",
	"person_dob",
	"person_age",
	"person_maritals",
	"person_phone",
	"person_email",
	"person_addr",
	"person_city",
	"person_state",
	"person_zip",
	"person_company",
	"person_position",
	"person_anual",
	"person_language",
	"person_role",
	"person_status",
	"person_media"

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
		parent::__construct($db,'person');
	}

	public function all() 
	{ //get all users, admin only!
		$this->load();
		return $this->query;
	}

    public function search($term,$limit)
    {
	// Execute a query
	try {
            //Busqueda fuzzy  (Excdelente!)
            //$query = "SELECT * FROM paciente WHERE CONCAT(CodHistoria, NomPaci, ApePaci) LIKE :term OR CONCAT(CodHistoria, NomPaci, ApePaci) LIKE :term LIMIT 50";
	    //$query = "SELECT * FROM person WHERE CONCAT(person_id, person_name, person_lname) LIKE :term OR CONCAT(otros campos separados por coma) LIKE :term LIMIT :limit";
	    $query = "SELECT * FROM person WHERE CONCAT(person_id, person_name, person_lname, person_phone) LIKE :term LIMIT :limit";
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

/*
    public function searchWithTaskCount($term,$limit)
    {
	// Execute a query
	try {
            // Obtener los $limit registros que contengan una cadena $term en la concatenacion de 3 campos de la tabla 'person'
	    // La cadena $term puede estar en cualquier lugar de los campos que esten concatenados
	    $query = "SELECT person.*, COUNT(task.task_num) AS task_count FROM person LEFT JOIN task ON person.person_num = task.person_num WHERE CONCAT(person.person_id, person.person_name, person.person_lname, person.person_phone) LIKE :term GROUP BY person.person_num LIMIT :limit";
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
*/

    public function searchWithTaskCount($term,$limit, $project_id, $type_entity)
    {
	// Execute a query
	try {
            // Obtener los $limit registros que contengan una cadena $term en la concatenacion de 3 campos de la tabla 'person'
	    // La cadena $term puede estar en cualquier lugar de los campos que esten concatenados
	   /* $query = "SELECT p.*, COUNT(ppt.task_num) AS task_count FROM person p JOIN project_person pp ON p.person_num = pp.person_num LEFT JOIN project_person_task ppt ON pp.person_num = ppt.entity_num AND ppt.type_entity = :type_entity WHERE pp.project_id = :project_id AND CONCAT(p.person_name, p.person_lname, p.person_phone) LIKE :term GROUP BY p.person_name LIMIT :limit";  */
	    
       // $query = "SELECT pp.person_num, p.*, COUNT(t.task_num) AS task_count FROM person p JOIN project_person pp ON p.person_num = pp.person_num JOIN task t ON ppt.task_num = t.task_num WHERE pp.project_id = :project_id  AND CONCAT(p.person_name, p.person_lname, p.person_phone) LIKE :term GROUP BY pp.person_num, p.person_phone, p.person_name, p.person_lname LIMIT :limit";
       
       //$query = "SELECT p.*, COUNT(pt.task_num) AS task_count FROM person p JOIN project_person pp ON p.person_num = pp.person_num LEFT JOIN person_task pt ON p.person_num = pt.person_num WHERE pp.project_id = :project_id AND CONCAT(p.person_name, p.person_lname, p.person_phone) LIKE :term GROUP BY p.person_num, p.person_name LIMIT :limit";
       
        $query = "SELECT p.*, COUNT(pt.task_num) AS task_count FROM person p LEFT JOIN project_person pp ON p.person_num = pp.person_num LEFT JOIN person_task pt ON p.person_num = pt.person_num AND pp.project_id = pt.project_id WHERE pp.project_id = :project_id AND CONCAT(p.person_name, p.person_lname, p.person_phone) LIKE :term GROUP BY p.person_num, p.person_name LIMIT :limit";       
       
	    
	    $params = array(':type_entity' => $type_entity, ':project_id' => $project_id, ':term' => '%' . $term . '%', ':limit' => $limit);
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
    
    public function searchAllWithTaskCount($limit, $project_id, $type_entity)
    {
	// Execute a query
	try {
            //Busqueda fuzzy  (Excdelente!)
            
            
        /*$query = "SELECT p.*, COUNT(ppt.task_num) AS task_count FROM person p JOIN project_person pp ON p.person_num = pp.person_num LEFT JOIN project_person_task ppt ON pp.person_num = ppt.entity_num AND ppt.type_entity = :type_entity WHERE pp.project_id = :project_id GROUP BY p.person_num, p.person_name LIMIT :limit"; */   

        //$query = "SELECT pp.person_num, p.*, COUNT(t.task_num) AS task_count FROM person p JOIN project_person pp ON p.person_phone = pp.person_phone LEFT JOIN project_person_task ppt ON pp.person_num = ppt.entity_num AND ppt.type_entity = :type_entity LEFT JOIN task t ON ppt.task_num = t.task_num WHERE pp.project_id = :project_id GROUP BY pp.person_num, p.person_phone, p.person_name, p.person_lname LIMIT :limit";  

        $query = "SELECT p.*, COUNT(pt.task_num) AS task_count FROM person p LEFT JOIN project_person pp ON p.person_num = pp.person_num LEFT JOIN person_task pt ON p.person_num = pt.person_num AND pp.project_id = pt.project_id WHERE pp.project_id = :project_id GROUP BY p.person_num, p.person_name LIMIT :limit";
        

            //$params = array(':type_entity' => $type_entity, ':project_id' => $project_id, ':limit' => $limit);
            $params = array(':project_id' => $project_id, ':limit' => $limit);
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

	public function getByPersonPhone($per_phone)
	{
		$this->load(array('person_phone=?', $per_phone));
		//return $this->query;
		$this->copyTo('POST');
	}

    public function getAllByUserId($user_id, $limit){
	try {
	    	    //die("hola".' '.$user_id.' '.$limit);
        $query = "SELECT person.*, COUNT(task.task_num) AS task_count FROM person JOIN user_person ON person.person_num = user_person.person_num JOIN task ON person.person_num = task.person_num WHERE user_person.user_id = :user_id GROUP BY person.person_num LIMIT :limit";
        $params = array(':user_id' => $user_id, ':limit' => $limit);
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


    public function getAllByProjectId($project_id, $limit){
	try {
	    	    //die("hola".' '.$user_id.' '.$limit);
        $query = "SELECT person.*, COUNT(task.task_num) AS task_count FROM person JOIN project_person ON person.person_num = project_person.person_num JOIN task ON person.person_num = task.person_num WHERE user_person.user_id = :user_id GROUP BY person.person_num LIMIT :limit";
        $params = array(':user_id' => $user_id, ':limit' => $limit);
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





    
    public function searchByUserId($user_id, $term, $limit){
	try {
	    //die("hola".' '.$user_id.' '.$term.' '.$limit);
        $query = "SELECT person.*, COUNT(task.task_num) AS task_count FROM person JOIN user_person ON person.person_num = user_person.person_num JOIN task ON person.person_num = task.person_num WHERE user_person.user_id = :user_id AND CONCAT(person.person_id, person.person_name, person.person_lname, person.person_phone) LIKE :term GROUP BY person.person_num LIMIT :limit";
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
    

    public function existsByPersonPhone($per_phone)
    {
        $this->load(array('person_phone = ?', $per_phone));
        return $this->dry() ? false : true;
    }

    public function existsByPersonNum($personnum)
    {
        //echo '<script>console.log("Entro a verificar()"); </script>';
        $this->load(array('person_num = ?', $personnum));
        return $this->dry() ? false : true;
    }

	public function add( $unsanitizeddata )
	{
		$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		//check if username already exists in db
		$this->load(array('person_phone=?',$data['person_phone']));
	
		if(!$this->dry())
		{
		    //die('existe');
			    return 10;
		}
		//die('no existe');
		$this->copyFrom($data);
		$this->save();
		return 1;
	}


	public function getByPhone($per_phone) 
	{
		$this->load(array('person_phone=?',$per_phone));
		//return $this->query;
		//$this->copyTo('POST');
		
	}
	
	public function getByProPerNum($proper_num) 
	{
		$this->load(array('person_num=?',$per_num));
		$this->copyTo('POST');
	}	
	
    public function getByPersonNum($per_num) 
    //public function getByProjectpersonNum($per_num)
    {
        try {
	        $query = "SELECT p.person_num, p.person_name, p.person_lname, p.person_addr, p.person_phone FROM person p JOIN project_person pp ON p.person_phone = pp.person_phone WHERE pp.person_num = :per_num";
            $params = array(':per_num' => $per_num);
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

	public function getPersonCountByProjectId($project_id) 
	{
	    
	    $query = "SELECT COUNT(DISTINCT person_num) AS person_count FROM project_person WHERE project_id = :project_id";
        $params = array(':project_id' => $project_id);
	    $result = $this->db->exec($query, $params);
        // Handle the query result

        if (!$result) {
            // Handle the query failure appropriately, for example, log the error or display an error message to the user
            error_log("Query failed");
	        $result = 0;
        }
        return $result[0]['person_count'];
        
	}
	
	//public function edit($p_num, $unsanitizeddata)
    public function edit($per_phone)
	{
	   // die('entro');
		//$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);

		//$data['updated_at']=$this->getCurrentdate();
		$this->load(array('person_phone=?',$per_phone));
		//$this->copyFrom($data);
                $this->copyFrom('POST');
		$this->update();
	}

	public function delete1($per_phone) 
	{
		try {
            // Find the record by ID
            if ($this->load(array('person_phone=?',$per_phone))) {
                // Delete the record
                $this->erase();
                return true; // Record deleted successfully
            } else {
                return false; // Record not found
            }
        } catch (\Exception $e) {
            // Handle the exception (you can log, display an error message, etc.)
            // For example, you can log the error using $e->getMessage() and $e->getTrace()
           // error_log('Error deleting record: ' . $e->getMessage());
            return false; // Error deleting record
        }
	}
	
	public function delete($project_id, $person_phone) 
	{
        $this->db->begin();

        try {
            //die('entro');
            // Step 1: Delete related notes
            //$this->db->exec('DELETE FROM person WHERE person_phone = ?', $per_phone);
            $this->db->exec('DELETE FROM project_person WHERE person_phone = ? AND project_id = ?', [$person_phone, $project_id]);
            // Step 3: Delete from task
            //$this->db->exec('DELETE FROM task WHERE task_num = ?', $task_num);
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
	
}
