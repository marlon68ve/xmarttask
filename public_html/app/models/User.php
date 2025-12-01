<?php

class User extends DB\SQL\Mapper {
    protected $f3;

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
    	"id",
    	"username",
    	"name",
    	"lname",    		
		"password",
		"email",
		"activated",
		"hash",
		"user_type",
		"language"
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
		parent::__construct($db,'users');
	}

	public function all() 
	{
		$this->load();
		return $this->query;
	}
	
	public function allButMyself($id) 
	{
		$this->load(array('id!=?', $id));	    
		return $this->query;
	}	

    public function getCollaborator($userId)
    {
        // Use F3's Data Mapper to find the record
        $this->load(['id = ?', $userId]);

        if (!$this->dry()) {
            // Return the 'phone' column value
            return;
        } else {
            // Handle the case where no user is found
            return null; // Or throw an exception if preferred
        }
    }
    
    public function getCollaboratorByUserName($username)
    {
        // Use F3's Data Mapper to find the record
        $this->load(['username = ?', $username]);

        if (!$this->dry()) {
            // Return the 'phone' column value
            return;
        } else {
            // Handle the case where no user is found
            return null; // Or throw an exception if preferred
        }
    }    
    
    

public function compactUsers() 
{
    try {
        // Fetch all user IDs sorted by id
        $users = $this->db->exec('SELECT id FROM users ORDER BY id ASC');
        $expectedId = 1;
        $isCompacted = true;

        // Check if IDs are already sequential
        foreach ($users as $user) {
            if ($user['id'] != $expectedId++) {
                $isCompacted = false;
                break;
            }
            $expectedId++;
        }

        if ($isCompacted) {
            return 1; // Users table is already compacted
        }

        // Begin a transaction
        $this->db->begin();

        // Create ID mapping and update the `users` table
        $idMapping = [];
        $newId = 1;
        foreach ($users as $user) {
            $oldId = $user['id'];
            $idMapping[$oldId] = $newId;

            $sql = 'UPDATE users SET id = :newId WHERE id = :oldId';
            $params = [':newId' => $newId, ':oldId' => $oldId];
            $this->db->exec($sql, $params);
            $newId++;
        }

        // Fetch related tables dynamically from the INFORMATION_SCHEMA
        $sql = "SELECT TABLE_NAME, COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_NAME = 'users' AND REFERENCED_COLUMN_NAME = 'id'";
        $relatedTables = $this->db->exec($sql);

        // Update related tables with new user IDs
        foreach ($relatedTables as $relation) {
            $relatedTable = $relation['TABLE_NAME'];
            $foreignKey = $relation['COLUMN_NAME'];

            foreach ($idMapping as $oldId => $newId) {
                $sql = "UPDATE `$relatedTable` SET `$foreignKey` = :newId WHERE `$foreignKey` = :oldId";
                $params = [':newId' => $newId, ':oldId' => $oldId];
                $this->db->exec($sql, $params);
            }
        }

        // Reset AUTO_INCREMENT to the next ID
        $sql = "ALTER TABLE users AUTO_INCREMENT = :newId";
        $params = [':newId' => $newId];
        $this->db->exec($sql, $params);

        // Commit the transaction
        $this->db->commit();
        return 10; // Success
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $this->db->rollback();
        echo 'Error compacting users table: ' . $e->getMessage();
        return 99; // Error code
    }
}

	public function add( $unsanitizeddata )
	{
		$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		//check if username already exists in db
		
		//$this->createDefaultProject($data['username']);
		$this->load(array('username=?',$data['username']));		
		if(!$this->dry())
		{
			return 10;
		}
		//check if email already exists in db
		$this->load(array('email=?',$data['email']));
		if(!$this->dry())
		{
			return 11;
		}
		$data['created_at']=$this->getCurrentdate();
		$data['updated_at']=$this->getCurrentdate();
		$this->copyFrom($data);
		$this->save();
		$this->createDefaultProject($data['username'], $data['language']);
		return 1;
	}

    public function createDefaultProject($username, $language) {
        // Este metodo toma el nombree de usuario como parametro, lo usa para obtener el usuario recien creado y en consecuencia su id
        // Luego establece los valores por defecto para el proyecto raiz y crea el proyecto raiz. Posteriormente crea un registro nuevo
        // en la tabla project_user con la relacion entre el proyecto y el usuario
        
        
        // Definir consulta para obtener el id del usuario recien creado
        $lastUserId = $this->db->exec(
            'SELECT MAX(id) AS last_user_id FROM users'
        );
        // Access the last_project_id from the result
        $lastUserId = $lastUserId[0]['last_user_id'];         
        
        // Set the new project number in the data array
        $project_num = 0;
        $project_name = $this->f3->get('i18n_defaultProjectName');
        $project_status = $this->f3->get('i18n_defaultProjectStatus');        

        // Prepare the SQL statement for inserting into the project table
        $sqlProject = 'INSERT INTO project (project_num, project_name, project_status) VALUES (:project_num, :project_name, :project_status)';

        // Bind parameters for the project table
        $paramsProject = array(
            ':project_num' => $project_num,
            ':project_name' => $project_name,
            ':project_status' => $project_status
        );

        // Execute the SQL statement for inserting into the project table
        $this->db->exec($sqlProject, $paramsProject);
        
        // Definir consulta para obtener el project_id del proyecto recien creado
        $lastProjectId = $this->db->exec(
            'SELECT MAX(project_id) AS last_project_id FROM project'
        );
        // Access the last_project_id from the result
        $lastProjectId = $lastProjectId[0]['last_project_id'];        

        // Prepare the SQL statement for inserting into the project_user table
        $sqlProjectUser = 'INSERT INTO project_user (project_id, user_id, user_rol) VALUES (:project_id, :user_id, 1)';

        // Bind parameters for the project_user table
        $paramsProjectUser = array(
            ':project_id' => $lastProjectId,
            ':user_id' => $lastUserId
        );

        // Execute the SQL statement for inserting into the project_user table
        $this->db->exec($sqlProjectUser, $paramsProjectUser);

        $this->addSetting($lastUserId, $lastProjectId, $language);
        
    }

	public function getByUsername($username)
	{
		$this->load(array('username=?', $username));
	}

    public function getUsersByProjectId($project_id) {
        $sql = "SELECT u.id, u.username FROM users u INNER JOIN project_user pu ON u.id = pu.user_id WHERE pu.project_id = :project_id";
	    try {
            $params = array(':project_id' => $project_id);
            $result = $this->db->exec($sql, $params);
            if ($result) {
                // Assuming there's only one row per user or you just need the first result.
                return $result;
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

	public function exctendSearch($username, $term, $limit)
	{
		/*$this->load(array(), array('group'=>'username','limit'=>$limit));
		$this->load(array('username!=?', 'alvaro'), array('like'=>'%marlon%', 'group'=>'username','limit'=>$limit));
		return $this->query;*/
		
	    $query = "SELECT * FROM users WHERE (username != :username AND CONCAT(username, name, lname, email) LIKE :term) GROUP BY username LIMIT :limit";
	    $params = array(':username' => $username, ':term' => '%' . $term . '%', ':limit' => $limit);
	    $result = $this->db->exec($query, $params);		
		return $result;
		
	}

    public function getByUsernameJoinSetting($username) {
        $sql = "SELECT u.*, s.* FROM users u JOIN setting s ON u.id = s.user_id WHERE u.username = :username";
	    try {
            $params = array(':username' => $username);
            $result = $this->db->exec($sql, $params);
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
	
	public function getByEmail($email)
	{
		$this->load(array('email=?', $email));
		$this->copyTo('POST');
	}
	
	public function getById($id) 
	{
		$this->load(array('id=?',$id));
		$this->copyTo('POST');
	}

	public function getByProjectId($project_id) 
	{
	    $sql = "SELECT u.*, pu.* FROM users u JOIN project_user pu ON u.id = pu.user_id WHERE pu.project_id = :project_id AND pu.user_rol != 1";
        //$sql = "SELECT * FROM project_user WHERE project_id = :project_id";
	    try {
            $params = array(':project_id' => $project_id);
            $result = $this->db->exec($sql, $params);
            if ($result) {
                // Assuming there's only one row per user or you just need the first result.
                return $result;
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
	
	public function login($id) 
	{
		$this->load(array('id=?',$id));
		$this->copyTo('SESSION');
	}

	
	public function getByHash($hash) 
	{
		$this->load(array('hash=? AND activated=0',$hash));
		$this->copyTo('POST');
	}
	
	public function checkActivatedHash($hash) 
	{
		$this->load(array('hash=? AND activated=1',$hash));
		$this->copyTo('POST');
	}

    public function isActive($user_id) {
        $this->load(array('id=? AND activated?', $user_id, 1));
        return !$this->dry();
    }
	
	public function edit($id, $unsanitizeddata)
	{
		$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		$data['updated_at']=$this->getCurrentdate();
		$this->load(array('id=?',$id));
		$this->copyFrom($data);
		$this->update();
	}

	public function activate($id)
	{
		$data['updated_at']=$this->getCurrentdate();
		$this->load(array('id=?',$id));
		$this->updated_at=$this->getCurrentdate();
		$this->activated=1;
		$this->update();
	}


	public function updatepwd($id,$password,$hash)
	{
		//die($codusuario . '  ' . $password . '  ' . $hash);
		$this->load(array('id=?',$id));
		// Update the password
		$this->set('password', $password);
		$this->set('hash', $hash);
		$this->save();
	}

	public function updatepwdByUsername($username,$password,$hash)
	{
		//die($codusuario . '  ' . $password . '  ' . $hash);
		$this->load(array('username=?',$username));
		// Update the password
		$this->set('password', $password);
		$this->set('hash', $hash);
		$this->save();
	}

	public function updateactivate($id,$activated)
	{
		$this->load(array('id=?',$id));
		$this->activated=$activated;
		$this->update();
	}

	public function delete($id) 
	{
		$this->load(array('id=?',$id));
		$this->erase();
	}

	public function saveFoto($username, $userpicture)
	{
		try {
			$result =  $this->load(array('username=?',$username));
			$this->userpicture = $userpicture;
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

    public function addSetting($user_id, $favorite_project, $language) {
        // Set the new project number in the data array
        //$language = 'sp';
        //$favorite_project = 0;        

        // Prepare the SQL statement for inserting into the project table
        $sqlSetting = 'INSERT INTO setting (user_id, language, favorite_project) VALUES (:user_id, :language, :favorite_project)';

        // Bind parameters for the project table
        $paramsSetting = array(
            ':user_id' => $user_id,
            ':language' => $language,
            ':favorite_project' => $favorite_project
        );

        // Execute the SQL statement for inserting into the project table
        $result = $this->db->exec($sqlSetting, $paramsSetting);
        if ($result) {
            // Assuming there's only one row per user or you just need the first result.
            return $result;
        } else {
            // Handle case where no data is found or other error
            return null;
        }        
    }
    
    public function backupProject($user_id){
        $sql = "SELECT * FROM users WHERE id != :user_id";
        $params = array(':user_id' => $user_id);
        $result = $this->db->exec($sql, $params);
        if ($result) {
            // Assuming there's only one row per user or you just need the first result.
            return $result;
        } else {
            // Handle case where no data is found or other error
            return null;
        }
    }

}