<?php

class ProjectPerson extends DB\SQL\Mapper {
    protected $f3;

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
    	"projectperson_num",
    	"person_num",     	
    	"person_phone", 
		"project_id"   	
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
	    $f3=Base::instance();
	    $this->f3=$f3;
		parent::__construct($db,'project_person');
	}

	public function all() 
	{
		$this->load();
		return $this->query;
	}

    public function existsByPersonPhone($per_phone, $project_id)
    {
        //$this->load(array('person_phone = ?', $per_phone));
        $this->load(array('person_phone=? AND project_id=?', $per_phone, $project_id));
        return $this->dry() ? false : true;
    }

    public function getByPersonPhone($per_phone, $project_id)
    {
        //$this->load(array('person_phone = ?', $per_phone));
        $this->load(array('person_phone=? AND project_id=?', $per_phone, $project_id));
        return $this->query;
    }
    
	public function add($person_num, $person_phone, $project_id )
	{
	    try {
            // Prepare the SQL statement for inserting into the project_user table
            $sqlProjectPerson = 'INSERT INTO project_person (person_num, person_phone, project_id) VALUES (:person_num, :person_phone, :project_id)';
            // Bind parameters for the project_user table
            $paramsProjectPerson = array(
                ':person_num' => $person_num,                
                ':person_phone' => $person_phone,
                ':project_id' => $project_id
            );

            // Execute the SQL statement for inserting into the project_user table
            if ($this->db->exec($sqlProjectPerson, $paramsProjectPerson)){
		        return true;
            }
	    } catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}		    
	}
	

	public function edit($id, $prid, $unsanitizeddata)
	{
		$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		$this->load(array('user_id=? AND project_id=?',$id,$prid));
		$this->copyFrom($data);
		$this->update();
	}

	public function delete($id, $prid) 
	{
		$this->load(array('user_id=? AND project_id=?',$id,$prid));
		$this->erase();
	}

}