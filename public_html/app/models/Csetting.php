<?php

class Csetting extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
        "set_num",
        "user_id",
        "language",
        "favorite_project"
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
		parent::__construct($db,'setting');
	}


	public function all() 
	{ //get all users, admin only!
		$this->load();
		return $this->query;
	}

	public function getByUserId($user_id) 
	{
		$this->load(array('user_id=?',$user_id));
		$this->copyTo('POST');
	}

	public function add( $unsanitizeddata )
	{
		try {	    
		    $data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
	        /*$data['note_createdat']=$this->getCurrentdate();
		    $data['note_updatedat']=$this->getCurrentdate();*/
		    $this->copyFrom($data);
		    $this->save();
		    return $this->dry() ? false : true;
		} catch (Exception $e) {
			// Handle the exception appropriately, for example, log the error or display an error message to the user
			error_log("Exception: " . $e->getMessage());
			die("An error occurred while processing the request");
		}
	}

    public function edit($user_id, $favorite_project)
	{
	    //die('hola'.$favorite_project);
		$this->load(array('user_id=?',$user_id));
        $this->favorite_project = $favorite_project;
		$this->update();
	}

    public function updateLanguage($user_id, $lang)
	{
	    //die('hola'.$favorite_project);
		$this->load(array('user_id=?',$user_id));
        $this->language = $lang;
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
