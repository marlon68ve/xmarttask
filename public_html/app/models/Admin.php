<?php

class Admin extends DB\SQL\Mapper {
    protected $f3;

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
    		"id",
    		"username",
		"password",
		"email",
		"activated",
		"hash",
		"user_type" 
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
		$this->createDefaultProject($data['username']);
		return 1;
	}

    public function createDefaultProject($username) {
        // Este metodo toma el nombree de usuario como parametro, lo usa para obtener el usuario recien creado y en consecuencia su id
        // Luego establece los valores por defecto para el proyecto raiz y crea el proyecto raiz. Posteriormente crea un registro nuevo
        // en la tabla project_user con la relacion entre el proyecto y el usuario

		$this->load(array('username=?',$username));        
        $user_id = $this->id;

        // Set the new project number in the data array
        $project_num = 0;
        $project_name = $this->f3->get('i18n.ui.i18n_defaultProjectName');
        $project_status = $this->f3->get('i18n.ui.i18n_defaultProjectStatus');        

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

        // Prepare the SQL statement for inserting into the project_user table
        $sqlProjectUser = 'INSERT INTO project_user (project_num, user_id) VALUES (:project_num, :user_id)';

        // Bind parameters for the project_user table
        $paramsProjectUser = array(
            ':project_num' => $project_num,
            ':user_id' => $user_id
        );

        // Execute the SQL statement for inserting into the project_user table
        $this->db->exec($sqlProjectUser, $paramsProjectUser);


        $this->addSetting($user_id);
        
    }

	public function getByUsername($username)
	{
		$this->load(array('username=?', $username));
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

    public function addSetting($user_id) {
        // Set the new project number in the data array
        $language = 'sp';
        $favorite_project = 0;        
        // Prepare the SQL statement for inserting into the project table
        $sqlSetting = 'INSERT INTO setting (user_id, language, favorite_project) VALUES (:user_id, :language, :favorite_project)';
        // Bind parameters for the project table
        $paramsSetting = array(
            ':user_id' => $user_id,
            ':language' => $language,
            ':favorite_project' => $favorite_project
        );
        // Execute the SQL statement for inserting into the project table
        $this->db->exec($sqlSetting, $paramsSetting);
    }		

    public function compactTable1($table, $primaryKey) 
    {
        // Excelente metodo, obtiene un nombre de tabla y su campo clave y primero compacta la secuancia de numeracion
        // comenzando desde 1 consecutivamente hasta el ultimo sin saltar ninguno. Luego actualiza todas las tablas
        // relacionadas a esta mediante clave foranea, revisando el esquena de la base de datos. Es muy eficiente.
        try {
            // Validate table and primary key inputs
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $table) || !preg_match('/^[a-zA-Z0-9_]+$/', $primaryKey)) {
                throw new Exception("Invalid table or primary key name.");
            }
            // Check if the table exists
            $sql = "SHOW TABLES LIKE :table";
            $params = [':table' => $table];
            if (!$this->db->exec($sql, $params)) {
                return 1; // Table does not exist
            }
            // Check if the primary key exists
            $sql = "SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY' AND Column_name = :primaryKey";
            $params = [':primaryKey' => $primaryKey];
            if (!$this->db->exec($sql, $params)) {
                return 2; // Primary key does not exist
            }
            // Fetch all rows sorted by the primary key
            $rows = $this->db->exec("SELECT `$primaryKey` FROM `$table` ORDER BY `$primaryKey` ASC");
            $expectedId = 1;
            $isCompacted = true;
            // Check if IDs are already sequential
            foreach ($rows as $row) {
                if ($row[$primaryKey] != $expectedId++) {
                    $isCompacted = false;
                    break;
                }
            }
            if ($isCompacted) {
                return 10; // Table already compacted
            }
            // Begin transaction
            $this->db->begin();
            // Create ID mapping and update rows
            $idMapping = [];
            $newId = 1;
            foreach ($rows as $row) {
                $oldId = $row[$primaryKey];
                $idMapping[$oldId] = $newId;
                // Batch update rows
                $sql = "UPDATE `$table` SET `$primaryKey` = :newId WHERE `$primaryKey` = :oldId";
                $params = [':newId' => $newId, ':oldId' => $oldId];
                $this->db->exec($sql, $params);
                $newId++;
            }
            // Fetch and update related tables
            $sql = "SELECT TABLE_NAME, COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_NAME = :table AND REFERENCED_COLUMN_NAME = :primaryKey";
            $params = [':table' => $table, ':primaryKey' => $primaryKey];
            $foreignKeyTables = $this->db->exec($sql, $params);
            foreach ($foreignKeyTables as $fk) {
                $relatedTable = $fk['TABLE_NAME'];
                $foreignKey = $fk['COLUMN_NAME'];
                foreach ($idMapping as $oldId => $newId) {
                    $sql = "UPDATE `$relatedTable` SET `$foreignKey` = :newId WHERE `$foreignKey` = :oldId";
                    $params = [':newId' => $newId, ':oldId' => $oldId];
                    $this->db->exec($sql, $params);
                }
            }
            // Reset AUTO_INCREMENT
            $sql = "ALTER TABLE `$table` AUTO_INCREMENT = :newId";
            $params = [':newId' => $newId];
            $this->db->exec($sql, $params);
            // Commit transaction
            $this->db->commit();
            return 20; // Success
        } catch (Exception $e) {
            $this->db->rollback();
            echo "Error compacting table '$table': " . $e->getMessage();
            return 99; // Error code
        }
    }
    
    
    public function compactTable($table, $primaryKey) {
        try {
            // Fetch all rows sorted by the primary key
            $rows = $this->db->exec("SELECT $primaryKey FROM $table ORDER BY $primaryKey ASC");

            $idMapping = [];
            $newId = 1;

            // Begin transaction
            $this->db->begin();

            // Update the table with new primary keys
            foreach ($rows as $row) {
                $oldId = $row[$primaryKey];
                $idMapping[$oldId] = $newId;

               // $this->db->exec("UPDATE $table SET $primaryKey = ? WHERE $primaryKey = ?", [$newId, $oldId]);
               $sql = "UPDATE $table SET $primaryKey = :newId WHERE $primaryKey = :oldId";
               // $sql = "UPDATE `$relatedTable` SET `$foreignKey` = :newId WHERE `$foreignKey` = :oldId";
                $params = [':newId' => $newId, ':oldId' => $oldId];
                $this->db->exec($sql, $params);                
                
                
                $newId++;
            }

            // Update related tables
            foreach ($idMapping as $oldId => $newId) {
            /*    $foreignKeyTables = $this->db->exec("
                    SELECT TABLE_NAME, COLUMN_NAME 
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE REFERENCED_TABLE_NAME = ? AND REFERENCED_COLUMN_NAME = ?", 
                    [$table, $primaryKey]
                );
            */
                $sql = "SELECT TABLE_NAME, COLUMN_NAME 
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE REFERENCED_TABLE_NAME = :table AND REFERENCED_COLUMN_NAME = :primaryKey";
                $params = [':table' => $table, ':primaryKey' => $primaryKey];
                $foreignKeyTables = $this->db->exec($sql, $params);



                foreach ($foreignKeyTables as $fk) {
                    $relatedTable = $fk['TABLE_NAME'];
                    $foreignKeyColumn = $fk['COLUMN_NAME'];
                    
                    $this->db->exec("UPDATE $relatedTable SET $foreignKeyColumn = ? WHERE $foreignKeyColumn = ?", [$newId, $oldId]);
                }
            }

            // Reset auto-increment
            $sql = "ALTER TABLE $table AUTO_INCREMENT = :newId";
            $params = [':newId' => $newId];
            $this->db->exec($sql, $params);
            // Commit transaction
            $this->db->commit();
            //return "Table '$table' compacted successfully.";
            return 20;
        } catch (Exception $e) {
            $this->db->rollback();
            echo "Error compacting table '$table': " . $e->getMessage();
            return 99;
        }
    }    
    
}