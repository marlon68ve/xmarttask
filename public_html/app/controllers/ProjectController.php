<?php
class ProjectController extends Controller {

    public function listproject() 
    {
       // die('entro'.$this->f3->get('POST.radioProject'));
        //die('hola'.$this->f3->get('SESSION.favorite_project'));
	    //EXCELENTE PARA DEPURACION: con la proxima line escribo en la consola del navegador
	    //echo '<script>console.log("Welcome to GeeksforGeeks!"); </script>';
	   // $q = 0;
	   
	    $this->f3->set('message', '');
        $this->f3->set('alertType', '');	   
	    $limitSearch = 300;
	    $limitLoad = 15;
        // Crear una instancia de la clase modelo Persona
        $user = new User($this->db);
        $project = new Project($this->db);
	    $total_count = ceil($project->count());
        //$projects = $project->searchAll($limitSearch);
        // Definir las variables globales de f3

		$this->f3->set('modal', 'fromlistproj');
		$this->f3->set('favorite_project', '');
		
        if($this->f3->exists('PARAMS.ProjectId')) 
        {
            //die('hola'.'  '.$this->f3->get('PARAMS.ProjectId'));
            $users = $user->getUsersByProjectId($this->f3->get('PARAMS.ProjectId'));
            $this->f3->set('users',$users);
            //die('entro'.'    '.$this->f3->get('PARAMS.ProjectId'));
            // Aca entra al agendar una tarea al proyecto o al borrralo
            if($this->f3->exists('PARAMS.Action'))
            {
                // aca entra para borrar el proyecto
			    $project2 = new Project($this->db);
			    $project_id = $this->f3->get('PARAMS.ProjectId');
                if ($project2->delete($project_id))
                {
				    $this->f3->set('alertType','success');
				    $this->f3->set('message', $this->f3->get('i18n_projectdeleted'));
                }else{
                    // OJO puede que un registro no fue borrado no por algun error sino porque no existia. Filtrar esto mejor ******
				    $this->f3->set('alertType','error');
				    $this->f3->set('message', $this->f3->get('i18n_cantdeleteproject'));
                }  
            }else{
                // Esto es lo que sigue al agendar tarea al proyecto
                $this->f3->set('entity_num', $this->f3->get('PARAMS.ProjectId'));	        
	            $this->f3->set('modal', 'taskproject');	 
            }
	    }


    	if($this->f3->exists('POST.q')){
			// Si se pulso el boton "Buscar" se enviara el formulario y debe existir q via GET
            $q = $this->f3->get('POST.q'); 
                	   // die('entro0'.'   '.$q);
			// Buscar los $limit primeros pacientes que cumplan con el criterio de busqueda q
            //$projects = $project->search($q, $limitSearch);
            $projects = $project->search($this->f3->get('SESSION.user_id'), $q, $limitSearch);
            //die('entro0'.'   '.$this->f3->get('SESSION.user_id'));
           //$projects = $project->getAllByUserId($limitLoad, $this->f3->get('SESSION.id'));
        } else {
            //die('entro1');
			// Si el formulario se cargo normalmente simplemente traer los primeros $limitLoad registros
			// de la tabla Pacientes
        	//$projects = $project->find(null, array('limit' => $limitLoad));
        	$projects = $project->getAllByUserId($limitLoad, $this->f3->get('SESSION.id'));
        	//die('entro1'.'   '.$this->f3->get('SESSION.user_id'));
    	}

        	
    	if($this->f3->exists('POST.radioProject')){    	
            $cproject = new Project($this->db);
            $project1 = $cproject->getJoinById($this->f3->get('POST.radioProject'), $this->f3->get('SESSION.user_id'));
            //die('entro');
            $this->f3->set('SESSION.project_name',$project1['project_name']);
            $this->f3->set('SESSION.project_id',$project1['project_id']);
            $this->f3->set('SESSION.project_type',$project1['project_type']);
            $this->f3->set('SESSION.user_rol',$project1['user_rol']); 
    	}

        // Hacer que la funcion definida en controlador base este disponible para la vista
        $this->f3->set('getStatusName', function($statusId) {
            return $this->getStatusName($statusId);
        });

        // set the default timezone to use.
        $mytimezone = $this->f3->get('timezone');  // obtener el timezone desde el archivo de configuracion
        date_default_timezone_set($mytimezone);
	    $todaydate = date('d/m/Y');
	    $todaytime = date('h:i:sa');
	    $this->f3->set('user_id', $this->f3->get('SESSION.user_id'));
        $this->f3->set('typeentity', 'project');	    
        $this->f3->set('todaydate', $todaydate);
        $this->f3->set('todaytime', $todaytime);
        $this->f3->set('datepicker',$todaydate);
        $this->f3->set('types', $this->f3->get('project_task_type'));
  	    $this->f3->set('projects',$projects);
    	$this->f3->set('total_projects',$total_count);
        $this->f3->set('page_head',$this->f3->get('i18n_projects'));
   	    $this->f3->set('view','project/list_project.htm');
    }
// *************************************************************************************************************

	public function checkPersonExists() {
        // Get phone number from POST request
        $phoneNumber = $this->f3->get('POST.phone');

        // Query the database to check if the user exists
        $person = new Person($this->db);
        $objPersons = $person->getByPersonPhone($phoneNumber);

			//$data = array();
			//$data['csrf'] = $this->f3->get('SESSION.csrf');
            if ($objPersons) {
                $data = array();
                $data['person_name'] = $person->person_name;
                $data['person_lname'] = $person->person_lname;
                $data['person_age'] = $this->calculate_age($person->person_dob);
                $data['person_addr'] = $person->person_addr;
                $data['person_phone'] = $person->person_phone;
                $data['person_num'] = $person->person_num;
                $data['csrf'] = $this->f3->get('SESSION.csrf');
				$data['message'] = 'lo encontro';
				$data['exists'] = true;
				// Ojo la respuesta para Ajax no funciona con return o echo, solo con die()
				die(json_encode($data));
            } else {
				$data['message'] = $this->f3->get('i18n_continuenewperson');
				$data['exists'] = false;
				die(json_encode($data));
            }
    }

    	public function verify() {
	    $person = new Person($this->db);
            $person_phone = $this->f3->get('POST.person_phone');
            // Check if the CodHistoria already exists
 	        $exists = $person->existsByPersonPhone($person_phone);
			$data = array();
			$data['csrf'] = $this->f3->get('SESSION.csrf');
            if ($exists) {	
				$data['message'] = $this->f3->get('i18n_existsphonenum');
				$data['exists'] = true;
				// Ojo la respuesta para Ajax no funciona con return o echo, solo con die()
				die(json_encode($data));
            } else {			 
				$data['message'] = $this->f3->get('i18n_continuenewperson');
				$data['exists'] = false;
				die(json_encode($data));
            }
    	}
    
    public function update()
    {
	    $this->f3->set('modal', '');
        $project = new Project($this->db);
        $user = new User($this->db);
      
        $project_id = $this->f3->get('POST.project_id');
        if($this->f3->exists('POST.update'))
        {
            //  *** Aca entra al pulsar boton "Actualizar" para actualizar los datos basicos del proyecto 
	        // Get array from file and define the global variable
            $project_statuses = $this->f3->get('project_status');
            $this->f3->set('project_statuses', $project_statuses);
	        $project_types = $this->f3->get('project_type');
	        $this->f3->set('project_types', $project_types); 

            
            // Obtener fecha y hora del formulario y convertirlo a datetime
            $duedate = $this->f3->get('POST.duedate');
            $duetime = $this->f3->get('POST.duetime');
            $this->f3->set('POST.project_duedate', $this->convertToMySQLDateTime($duedate, $duetime));
            $this->f3->set('SESSION.project_type',$this->f3->get('POST.project_type'));
            // Actualizar los datos del proyecto, y queda apuntando al registro con los nuevos datos
         	$project->edit($this->f3->get('POST.project_id'));
         	
         	// OJO creo que no hace falta ejecutar esto si la linea anterior uso data mapper
         	$project->getById($this->f3->get('POST.project_id'));

         	$current_project_duedate = $this->convertFromMySQLDateTime($project->project_duedate);
         	//die('entro0'.'   '.$current_project_duedate);
            $this->f3->set('current_project_duedate', $current_project_duedate);
            $current_project_duetime = substr($project->project_duedate, 11); // This will give you '14:30:00'
            $this->f3->set('current_project_duetime', $current_project_duetime);

            $current_project_type_id = $this->f3->get('POST.project_type'); // Task type as stored in DB
            $current_project_type_name = $project_types[$current_project_type_id] ?? 'Unknown';         	
            $this->f3->set('current_project_type_name', $current_project_type_name);           	

            // Convert project status ID to its corresponding name
            $current_project_status_id = $this->f3->get('POST.project_status'); // Task type as stored in DB
            $current_project_status_name = $project_statuses[$current_project_status_id] ?? 'Unknown';
            // Pass data to the view
            $this->f3->set('current_project_status_name', $current_project_status_name);           	
        } else if ($this->f3->exists('PARAMS.UserId')){
                // *** Aca entra al pulsar el icono para editar datos del colaborador seleccionado
             
	        // Get array from file and define the global variable
            $project_statuses = $this->f3->get('project_status');
            $this->f3->set('project_statuses', $project_statuses);
	        $project_types = $this->f3->get('project_type');
	        $this->f3->set('project_types', $project_types); 

/*
            // Obtener fecha y hora del formulario y convertirlo a datetime
            $duedate = $this->f3->get('POST.duedate');
            $duetime = $this->f3->get('POST.duetime');
            $this->f3->set('POST.project_duedate', $this->convertToMySQLDateTime($duedate, $duetime));
*/

           
         	    $project_id = $this->f3->get('PARAMS.ProjectId');     
         	    $project->getById($project_id);   
                $token = $this->f3->get('PARAMS.UserId');
                //die('entro'.$token);
	            $selected_user = new User($this->db);
                $selected_user->getById($token);
                
         	$current_project_duedate = $this->convertFromMySQLDateTime($project->project_duedate);
         	//die('entro0'.'   '.$current_project_duedate);
            $this->f3->set('current_project_duedate', $current_project_duedate);
            $current_project_duetime = substr($project->project_duedate, 11); // This will give you '14:30:00'
            $this->f3->set('current_project_duetime', $current_project_duetime);
   //die('entro'); 
            $current_project_type_id = $this->f3->get('POST.project_type'); // Task type as stored in DB
            $current_project_type_name = $project_types[$current_project_type_id] ?? 'Unknown';         	
            $this->f3->set('current_project_type_name', $current_project_type_name);           	

            // Convert project status ID to its corresponding name
            $current_project_status_id = $this->f3->get('POST.project_status'); // Task type as stored in DB
            $current_project_status_name = $project_statuses[$current_project_status_id] ?? 'Unknown';
            // Pass data to the view
            $this->f3->set('current_project_status_name', $current_project_status_name);                 
                
                
                $this->f3->set('token', $token);
                $this->f3->set('selected_user',$selected_user);
		        $this->f3->set('modal', 'fromlistusr'); 
        }else{
            // +++ Aca entra al pulsar el icono de editar proyecto
	        // Get array from file and define the global variable
            $project_statuses = $this->f3->get('project_status');
            $this->f3->set('project_statuses', $project_statuses);
	        $project_types = $this->f3->get('project_type');
	        $this->f3->set('project_types', $project_types);              
            
         	$project_id = $this->f3->get('PARAMS.project_id');            
         	$project->getById($project_id);
         	$current_project_duedate = $this->convertFromMySQLDateTime($project->project_duedate);
         	//die('entro0'.'   '.$current_project_duedate);
            $this->f3->set('current_project_duedate', $current_project_duedate);
            $current_project_duetime = substr($project->project_duedate, 11); // This will give you '14:30:00'
            $this->f3->set('current_project_duetime', $current_project_duetime);

            $current_project_type_id = $this->f3->get('POST.project_type'); // Task type as stored in DB
            $current_project_type_name = $project_types[$current_project_type_id] ?? 'Unknown';         	
            $this->f3->set('current_project_type_name', $current_project_type_name);           	
         	
            // Convert task type ID to its corresponding name
            $current_project_status_id = $this->f3->get('POST.project_status'); // Task type as stored in DB
            $current_project_status_name = $project_statuses[$current_project_status_id] ?? 'Unknown';
            // Pass data to the view
            $this->f3->set('current_project_status_name', $current_project_status_name);          	
        }
        
        
        
        
        
         	$this->f3->set('project_id',$project_id);         
         	$users = $user->getByProjectId($project_id);
         	$this->f3->set('project',$project);
         	$this->f3->set('collabs',$users);         	
         	$this->f3->set('page_head','Editar proyecto');
         	$this->f3->set('view','project/update.htm');
       // }
    }

// ********************************************************************************
    public function updategrants()
    {
        $projectuser = new ProjectUser($this->db);        
        switch (true) {
        case ($this->f3->exists('POST.create') && $this->f3->exists('POST.update') && $this->f3->exists('POST.delete')):
            $rol = 2;
            break;
        case ($this->f3->exists('POST.create') && !$this->f3->exists('POST.update') && !$this->f3->exists('POST.delete')):
            $rol = 4;
            break;
        case (!$this->f3->exists('POST.create') && $this->f3->exists('POST.update') && !$this->f3->exists('POST.delete')):
            $rol = 5;
            break;
        case ($this->f3->exists('POST.create') && $this->f3->exists('POST.update') && !$this->f3->exists('POST.delete')):
            $rol = 6;
            break;
        case (!$this->f3->exists('POST.create') && !$this->f3->exists('POST.update') && $this->f3->exists('POST.delete')):
            $rol = 7;
            break; 
        case ($this->f3->exists('POST.create') && !$this->f3->exists('POST.update') && $this->f3->exists('POST.delete')):
            $rol = 8;
            break;
        case (!$this->f3->exists('POST.create') && $this->f3->exists('POST.update') && $this->f3->exists('POST.delete')):
            //die('EB');        
            $rol = 9;
            break;         
        default:
            $rol = 3;
            break;
        }
        $this->f3->set('POST.user_rol', $rol);
        //$projectuser_updated=$projectuser->updategrants($this->f3->get('POST'));
        $projectuser_updated=$projectuser->edit($this->f3->get('POST.user_id'), $this->f3->get('POST.project_id'), $this->f3->get('POST'));
        if($projectuser_updated)
        {
            $this->f3->set('message', $this->f3->get('i18n_conf_mail_sent'));
        }else{ //user taken{
	        $this->f3->set('message', $this->f3->get('i18n_username_taken'));
        }
    	$this->f3->reroute('/project/update/'.$this->f3->get('POST.project_id'));
    }
// ***********************************************************************************

    public function calculate_age($date){
        $dob = explode('/', $date);
        $dobday   = intval($dob[0]);
        $dobmonth = intval($dob[1]);
        $dobyear  = intval($dob[2]);
        $todaydate = explode('/', date('d/m/Y'));
        $todayday   = intval($todaydate[0]);
        $todaymonth = intval($todaydate[1]);
        $todayyear  = intval($todaydate[2]);  
        $years = $todayyear - $dobyear;
        if ($todaymonth > $dobmonth) {
           $months = $todaymonth - $dobmonth;
        }else{
            $months = (12-$dobmonth) + $todaymonth;
            $years = $years - 1;
        }
        if ($todayday > $dobday) {
            $days = $todayday - $dobday;
        }else{
            if ($todaymonth == $dobmonth){
                $months = 11;
            }else{
                $months = $months - 1;
            }
            $days = (30-$dobday) + $todayday;
        }
        //return $years.' anos, '.$months.' meses y '.$days.' dias.';
	return $years;
    }

	public function delete()
	{
		/* Este caso funciona diferente a Agendar cita, ya que no se usa Ajax sino que el icono de la fila del paciente, es asociado
		* directamente al modal id="confirm-delete". Luego al pulsar el boton "Borrar" en este modal, Se efectua el request a la ruta
		* especificada en la misma linea del paciente para el icono de borrado :
		* data-href="{{ @BASE.'/persona/delete/'. @persona.persona_num }}"
		* lo cual es direccionado hacia aca para realizar el borrado.
		 */
		if($this->f3->exists('PARAMS.ProjectId'))
		{
			$project = new Project($this->db);
			$project_id = $this->f3->get('PARAMS.ProjectId');
            if ($project->delete($project_id))
            {
		        die('fue borrado');
            }else{
                // OJO puede que un registro no fue borrado no por algun error sino porque no existia. Filtrar esto mejor ******
                die('no fue borrado');
            }
/*
			//if ($task->existsTaskNoteJoin($person_num)){
			if ($task->existsTask($person_num)){
				$alertype= 'error';
				$message = $this->f3->get('i18n.ui.i18n_cantdeletepersonhastasks');
			}else{
				if ($project->delete($project_id)){
					$alertype= 'success';
					$message = $this->f3->get('i18n.ui.i18n_projectdeleted');
				}else{
					$alertype= 'error';
					$message = $this->f3->get('i18n.ui.i18n_cantdeleteproject');
				}
			}
*/			
		}

		$this->f3->reroute('/result/'.$message.'/'.$alertype);
	}
	//*********************************************************************************************************************
	
	public function deletepu()
	{
	    $this->f3->set('modal', '');
        $project = new Project($this->db);
        $user = new User($this->db);
		if($this->f3->exists('PARAMS.ProjectId') && $this->f3->exists('PARAMS.UserId'))
		{
            $projectuser = new ProjectUser($this->db); 
			$project_id = $this->f3->get('PARAMS.ProjectId');
            $user_id = $this->f3->get('PARAMS.UserId'); 
			//if ($task->existsTaskNoteJoin($person_num)){
			if ($projectuser->delete($user_id, $project_id)){
				$alertype= 'success';
				$message = $this->f3->get('i18n_projectdeleted');
			}else{
				$alertype= 'error';
				$message = $this->f3->get('i18n_cantdeleteproject');
			}
		}
	        // Get array from file and define the global variable
            $project_statuses = $this->f3->get('project_status');
            $this->f3->set('project_statuses', $project_statuses);
	        $project_types = $this->f3->get('project_type');
	        $this->f3->set('project_types', $project_types); 		
		
		
		    $project->getById($project_id);
         	$this->f3->set('project_id',$project_id);         
         	$users = $user->getByProjectId($project_id);
         	
         	
         	$current_project_duedate = $this->convertFromMySQLDateTime($project->project_duedate);
         	//die('entro0'.'   '.$current_project_duedate);
            $this->f3->set('current_project_duedate', $current_project_duedate);
            $current_project_duetime = substr($project->project_duedate, 11); // This will give you '14:30:00'
            $this->f3->set('current_project_duetime', $current_project_duetime);
   //die('entro'); 
            $current_project_type_id = $this->f3->get('POST.project_type'); // Task type as stored in DB
            $current_project_type_name = $project_types[$current_project_type_id] ?? 'Unknown';         	
            $this->f3->set('current_project_type_name', $current_project_type_name);           	

            // Convert project status ID to its corresponding name
            $current_project_status_id = $this->f3->get('POST.project_status'); // Task type as stored in DB
            $current_project_status_name = $project_statuses[$current_project_status_id] ?? 'Unknown';
            // Pass data to the view
            $this->f3->set('current_project_status_name', $current_project_status_name);           	
         	
         	
         	
         	$this->f3->set('project',$project);
         	$this->f3->set('collabs',$users);         	
         	$this->f3->set('page_head','Editar proyecto');
         	$this->f3->set('view','project/update.htm');
	}
	//*********************************************************************************************************************	

	public function createproject()
	{
	    $user_id = $this->f3->get('SESSION.user_id');
	    $project_types = $this->f3->get('project_type');
	    $this->f3->set('project_types', $project_types);
		if($this->f3->get('POST.createproject') === 'createproject')
		{
		    //die('entro1'.$this->f3->get('POST.user_id'));
		    //die('entro'.$user_id);
			$project = new Project($this->db);
			$maxprojectnum = $project->getMaxProjectNum($user_id) + 1;
			//die('hola'.'   '.$maxprojectnum);

//die('aqui'.'   '.$this->f3->get('POST.duedate').'   '.$this->f3->get('POST.duetime'));
$mysqlDateTime = $this->convertToMySQLDateTime('24/01/2025', '05:30:23');
$this->f3->set('POST.project_duedate', $mysqlDateTime);
$newdate = $this->f3->get('POST.project_duedate');


			$project_added = $project->add($maxprojectnum, $this->f3->get('POST'));
			
			//die('entra'.'  '.$project->project_id);
			$projectuser = new ProjectUser($this->db); 
			$user_rol = 1;
			$projectuser_added=$projectuser->add($user_id, $user_rol, $project->project_id);			
			//$user_id
			
			if($project_added)
			{
			    //die('entro2');
				//$project1 = new Project($this->db);
				//$project1->getByProjectNum($this->f3->get('POST.project_id'));
				//$token = $project1->project_id;
				$this->f3->set('alertType','success');
				$this->f3->reroute('/project/listproject');
			}
			else if(!$project_added) //paciente taken
			{
			    die('entro3');
				$this->f3->set('alertType','error');
				$this->f3->set('message', $this->f3->get('i18n_projecttaken'));
				$this->f3->set('view','project/create_project.htm');
			}
		} 
		else
		{
		    	//die('entro4');
		    	$this->f3->set('user_id', $user_id);
		    	$this->f3->set('project_num','');
		    	$this->f3->set('alertType','');
				$this->f3->set('message', '');
				$this->f3->set('view','project/create_project.htm');
		} 
	}
	//*********************************************************************************************************************

public function convertToMySQLDateTime($date, $time = '00:00:00')
{
    try {
        // Create a DateTime object from the input date
        $dateTime = DateTime::createFromFormat('d/m/Y', $date);
        
        // Check if the conversion was successful
        if (!$dateTime) {
            throw new Exception("Invalid date format: $date");
        }

        // Format the date to MySQL DATETIME format and append time
        return $dateTime->format('Y-m-d') . " $time";

    } catch (Exception $e) {
        // Handle any errors
        error_log("Error converting date: " . $e->getMessage());
        return null; // Return null on error
    }
}

public function convertFromMySQLDateTime($mysqlDateTime, $outputFormat = 'd/m/Y')
{
    try {
        // Create a DateTime object from the input MySQL datetime
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $mysqlDateTime);
        
        // Check if the conversion was successful
        if (!$dateTime) {
            throw new Exception("Invalid MySQL datetime format: $mysqlDateTime");
        }

        // Format the date to the desired output format
        return $dateTime->format($outputFormat);

    } catch (Exception $e) {
        // Handle any errors
        error_log("Error converting date: " . $e->getMessage());
        return null; // Return null on error
    }
}



    public function grantedCreate($rol)
    {
        /*   Rol= 1 y 2    Grant = 'CEB'
        *    Rol= 3     Grant = 'L'
        *    Rol= 4     Grant = 'C'
        *    Rol= 5     Grant = 'E'
        *    Rol= 6     Grant = 'CE
        */
        if ($rol == 1 OR $rol == 3 OR $rol == 5){
            return true;
        }else{
            return false;
        }
    }

    public function grantedEdit($rol)
    {
        /*   Rol= 1 y 2     Grant = 'CEB'
        *    Rol= 3     Grant = 'L'
        *    Rol= 4     Grant = 'C'
        *    Rol= 5     Grant = 'E'
        *    Rol= 6     Grant = 'CE
        */
        if ($rol == 1 OR $rol == 4 OR $rol == 5){
            return true;
        }else{
            return false;
        }
    }
    
    public function grantedDelete($rol)
    {
        /*   Rol= 1 y 2    Grant = 'CEB'
        *    Rol= 3     Grant = 'L'
        *    Rol= 4     Grant = 'C'
        *    Rol= 5     Grant = 'E'
        *    Rol= 6     Grant = 'CE
        *    Rol= 7     Grant = 'B
        *    Rol= 8     Grant = 'CB
        *    Rol= 9     Grant = 'EB        
        */
        if ($rol == 1){
            return true;
        }else{
            return false;
        }
    }


}