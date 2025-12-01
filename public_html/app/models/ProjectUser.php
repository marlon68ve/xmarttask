<?php

class ProjectUser extends DB\SQL\Mapper {
    protected $f3;

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
    	"projectuser_num",
		"project_id",    	
    	"user_id",
		"user_rol" 
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
		parent::__construct($db,'project_user');
	}

	public function all() 
	{
		$this->load();
		return $this->query;
	}

	public function add($user_id, $user_rol, $project_id )
	{
        // Prepare the SQL statement for inserting into the project_user table
        $sqlProjectUser = 'INSERT INTO project_user (project_id, user_id, user_rol) VALUES (:project_id, :user_id, :user_rol)';

        // Bind parameters for the project_user table
        $paramsProjectUser = array(
            ':project_id' => $project_id,
            ':user_id' => $user_id,
            ':user_rol' => $user_rol            
        );

        // Execute the SQL statement for inserting into the project_user table
        $this->db->exec($sqlProjectUser, $paramsProjectUser);
		return true;
	}
	
	
public function inviteuser($unsanitizeddata)
{
    try {
        // Sanitize input data
        $data = $this->sanitizeInput($unsanitizeddata, $this->allowed_fields);

        // Load existing record to check if user is already invited
        $this->load(array('user_id = ? AND project_id = ?', $data['user_id'], $data['project_id']));

        // If no existing record is found, add the new record
        if ($this->dry()) {
            $this->copyFrom($data);
            $this->save();
            return true; // Successfully invited user
        } else {
            // User is already invited
            return false; // Indicates the user was already added
        }
    } catch (\PDOException $e) {
        // Log database-related errors
        $logger = new \Log('logs/inviteuser_error.log');
        $logger->write("Database error in inviteuser: " . $e->getMessage());

        // Throw a generic error message to be handled elsewhere
        throw new \Exception("Failed to invite user due to a database error. Please try again later.");
    } catch (\Exception $e) {
        // Log unexpected errors
        $logger = new \Log('logs/inviteuser_error.log');
        $logger->write("Unexpected error in inviteuser: " . $e->getMessage());

        // Rethrow the exception to ensure proper handling in the calling code
        throw $e;
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