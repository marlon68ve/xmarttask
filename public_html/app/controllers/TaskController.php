<?php
require 'lib/TCPDF/CustomPdfGenerator.php';

class TaskController extends Controller {

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
public function list_task()
{
    // Set up initial page title and URL
    $pagetitle = $this->f3->get('i18n_project') . ' ' . $this->f3->get('i18n_tasks');
    $url = 'task/list_task.htm';
    // Initialize dependencies and default settings
    $task = new Task($this->db);
    $task_count = new Task($this->db);
    $this->f3->set('token', '');
    $this->f3->set('message', '');
    $this->f3->set('alertType', '');
    $this->f3->set('modal', '');
    
    $project_id = $this->f3->get('SESSION.project_id');
    date_default_timezone_set($this->f3->get('timezone')); // Set timezone from configuration
    // Determine the target date
    $taskDate = $this->f3->get('PARAMS.TaskDate');
    $todayDate = $taskDate ? $this->formatDate($taskDate) : date('d/m/Y');
    $this->f3->set('todaydate', $taskDate ?: date('d-m-Y'));
    // Handle messaging
    if ($this->f3->exists('PARAMS.msg')) {
        $this->f3->set('message', $this->f3->get('PARAMS.msg'));
        $this->f3->set('alertType', $this->f3->get('PARAMS.alert'));
    }
    // Handle task deletion logic
    if ($this->f3->exists('PARAMS.TaskNum')) {
        $this->handleTaskDeletion($task, $taskDate);
    }
    // Load tasks for the selected date
    $protasks = $task->getProByTaskDateJoin($project_id, 'project', $todayDate);
    $pertasks = $task->getPerByTaskDateJoin($project_id, 'person', $todayDate);
    //$protasks = $task->getProByTaskDateJoin($project_id, $todayDate);
    //$pertasks = $task->getPerByTaskDateJoin($project_id, $todayDate);    


	    // Determinar permisologia del usuario
        $this->f3->set('hasPermission', function($action, $userRole) {
            // *** hasPermission() esta definida dentro de la clase base Controller   ***
            return $this->hasPermissionCollab($action, $userRole); // Assuming `hasPermission` is globally accessible
        });

        // Hacer que la funcion definida en controlador base este disponible para la vista
        $this->f3->set('getTaskStatusName', function($statusId) {
            return $this->getStatusName($statusId);
        }); 

    $counts = $task_count->countTasksByDateAndProject($todayDate, $project_id);
    // Set view variables
    $this->f3->set('types', $this->f3->get('person_task_type'));
    $this->f3->set('pertasks', $pertasks);
    $this->f3->set('protasks', $protasks);
    $this->f3->set('pertasks_count', $counts['person_task_count']);
    $this->f3->set('protasks_count', $counts['project_task_count']);    
    $this->f3->set('datepicker', $todayDate);
    $this->f3->set('page_head', $pagetitle);
    $this->f3->set('view', $url);
}

/**
 * Formats a date string to "d/m/Y"
 */
private function formatDate($date)
{
    return date("d/m/Y", strtotime(str_replace('-"', '/', $date)));
}

/**
 * Handles task deletion logic
 */
private function handleTaskDeletion($task, $taskDate)
{
    $taskNum = $this->f3->get('PARAMS.TaskNum');
    $entityNum = $this->f3->get('PARAMS.EntityNum');
    $typeEntity = $this->f3->get('PARAMS.TypeEntity');

    if ($task->existsTaskNotesJoinTaskNum($taskNum)) {
        //die('entro'.$entityNum.' '. $taskNum.' '. $typeEntity);
        $this->setAlert('error', 'Esta tarea no puede ser eliminada porque tiene notas asociadas a ella.');
    } else {
        if ($task->deleteTask($entityNum, $taskNum, $typeEntity)) {
            //die('entro'.$entityNum.' '. $taskNum.' '. $typeEntity);
            $this->setAlert('success', 'La tarea fue borrada exitosamente.');
        } else {
            $this->setAlert('error', 'La tarea no pudo ser borrada.');
        }
    }

    // Update date after deletion
    $this->f3->set('todaydate', $taskDate);
}

/**
 * Sets an alert message and type
 */
private function setAlert($type, $message)
{
    $this->f3->set('alertType', $type);
    $this->f3->set('message', $message);
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    public function list_tasks()
    {
        // Este es el metodo que se aplica a la opcion de menu "Tasks"
        $pagetitle = $this->f3->get('i18n_project').'  '.$this->f3->get('i18n_tasks');
        $user = new User($this->db);
        $task = new Task($this->db);
	    $limitSearch = 300;
	    $limitLoad = 15;        
        $this->f3->set('token','');
        $this->f3->set('message','');
        $this->f3->set('alertType', '');
        $this->f3->set('modal', '');
        
	    if($this->f3->exists('POST.q')){
	        die('existe q');
            $q = $this->f3->get('POST.q');
            //die('entro'.'   '.$q);
            $projecttasks = $task->search($this->f3->get('SESSION.project_id'), $q, $limitSearch);            
	    }else{
            $this->f3->set('q', '');
            $projecttasks = $task->getTasksByProjectId($limitLoad, $this->f3->get('SESSION.project_id'));
	    }
        $users = $user->getUsersByProjectId($this->f3->get('SESSION.project_id'));
        $this->f3->set('users',$users);	    
        // set the default timezone to use.
        $mytimezone = $this->f3->get('timezone');  // obtener el timezone desde el archivo de configuracion
        date_default_timezone_set($mytimezone);
	    $todaydate = date('d/m/Y');
	    $todaytime = date('h:i:sa');
	    $this->f3->set('entity_num', $this->f3->get('SESSION.project_id'));
        $this->f3->set('typeentity', 'project');	    
        $this->f3->set('todaydate', $todaydate);
        $this->f3->set('todaytime', $todaytime);
        $this->f3->set('datepicker',$todaydate);
        $this->f3->set('source','listtasks');
        
            $projectType = $this->getProjectTypeName($this->f3->get('SESSION.project_type'));
            $task_types = $this->f3->get(strtolower($projectType) . '_task_type');             
            $this->f3->set('types', $task_types);          
        
        //$this->f3->set('types', $this->f3->get('project_task_type'));



  	    $this->f3->set('granted_create',$this->grantedCreate($this->f3->get('SESSION.user_rol')));
  	    $this->f3->set('granted_edit',$this->grantedEdit($this->f3->get('SESSION.user_rol')));
  	    $this->f3->set('granted_delete',$this->grantedDelete($this->f3->get('SESSION.user_rol')));

	    // Determinar permisologia del usuario
        $this->f3->set('hasPermission', function($action, $userRole) {
            // *** hasPermission() esta definida dentro de la clase base Controller   ***
            return $this->hasPermissionCollab($action, $userRole); // Assuming `hasPermission` is globally accessible
        });

        // Hacer que la funcion definida en controlador base este disponible para la vista
        $this->f3->set('getTaskStatusName', function($statusId) {
            return $this->getStatusName($statusId);
        });        

        $count = $task->taskCountByProjectId($this->f3->get('SESSION.project_id'));            
	    $this->f3->set('total_tasks',$count);
        //$projecttasks = $task->getProjectTasks($this->f3->get('SESSION.project_id'));

        $this->f3->set('projecttasks', $projecttasks);
        $this->f3->set('page_head',$pagetitle);
        $this->f3->set('myview','listtasks');        
        $this->f3->set('view','task/list_tasks.htm');
    }
// ***********************************************************************************************


    public function pruebamodal()
    {
        $this->f3->set('token','hola');
        $this->f3->set('modal','pruebamodal');
        $this->f3->set('view','task/pruebamodal.htm');
    }

    public function persontask() 
    {
        $user = new User($this->db);   
        $project_id = $this->f3->get('SESSION.project_id');
        $users = $user->getUsersByProjectId($project_id);
        $this->f3->set('users',$users);	        
        $this->f3->set('token','');
        $this->f3->set('message','');
        $this->f3->set('alertType', '');
        $this->f3->set('modal', '');        
	    $person = new Person($this->db);
        $task = new Task($this->db);
        $person_num = $this->f3->get('PARAMS.PersonNum');
	    if($this->f3->exists('GET.q')){
            $this->f3->set('q', $this->f3->get('GET.q'));
	    }else{
            $this->f3->set('q', '');
	    }  
	    $person = $person->getByPersonNum($person_num);
		if($this->f3->exists('PARAMS.TaskNum'))
		{
			$task1 = new Task($this->db);
			$tasknum = $this->f3->get('PARAMS.TaskNum');
			if ($task1->existsTaskNotesJoinTaskNum($tasknum)){
				$this->f3->set('alertType','error');
				$this->f3->set('message',$this->f3->get('i18n_cantdeletetasknotes'));
			}else{
				if ($task1->delete($tasknum)){
					$this->f3->set('alertType','success');
					$this->f3->set('message',$this->f3->get('i18n_taskdeleted'));
				}else{
					$this->f3->set('alertType','error');
					$this->f3->set('message',$this->f3->get('i18n_cantdeletetask'));
				}
			}
		}
		
	    // Determinar permisologia del usuario
        $this->f3->set('hasPermission', function($action, $userRole) {
            // *** hasPermission() esta definida dentro de la clase base Controller   ***
            return $this->hasPermissionCollab($action, $userRole); // Assuming `hasPermission` is globally accessible
        });		


        // Hacer que la funcion definida en controlador base este disponible para la vista
        $this->f3->set('getTaskStatusName', function($statusId) {
            return $this->getStatusName($statusId);
        }); 
        
            $projectType = $this->getProjectTypeName($this->f3->get('SESSION.project_type'));
            $task_types = $this->f3->get(strtolower($projectType) . '_task_type');             
            $this->f3->set('types', $task_types);  

   
        $persontasks = $task->getPersonTasks($project_id, $person_num);
        $mytimezone = $this->f3->get('timezone');
        date_default_timezone_set($mytimezone);
	    $todaydate = date('d/m/Y');
	    $todaytime = date('h:i:sa');
	    $this->f3->set('entity_num', $person_num);
        $this->f3->set('typeentity', 'person');	    
        $this->f3->set('todaydate', $todaydate);
        $this->f3->set('todaytime', $todaytime);
        $this->f3->set('datepicker',$todaydate);
        // la siguiente linea evita que se coloque la linea negra encima de la tabla de datos REVISAR
        $this->f3->set('source','listper');
        //$this->f3->set('types', $this->f3->get('person_task_type'));
  	    $this->f3->set('granted_create',$this->grantedCreate($this->f3->get('SESSION.user_rol')));
  	    $this->f3->set('granted_edit',$this->grantedEdit($this->f3->get('SESSION.user_rol')));
  	    $this->f3->set('granted_delete',$this->grantedDelete($this->f3->get('SESSION.user_rol')));        
        $this->f3->set('myview','persontask');
        $this->f3->set('person', $person);
        $this->f3->set('persontasks', $persontasks);
        $this->f3->set('page_head',$this->f3->get('i18n.ui.i18n_tasks'));
        $this->f3->set('view','task/person_task.htm');
    }

    public function createnote()
    {
        $note = new Note($this->db);
        $task_num = $this->f3->get('POST.task_num');
        $entity_num = $this->f3->get('POST.entity_num');    
        $type_entity = $this->f3->get('POST.type_entity');         
	    $data = array();
	    $data['task_num'] = $task_num;
	    $data['entity_num'] = $entity_num;
	    $data['type_entity'] = $type_entity;	    
        if($note->add($this->f3->get('POST'))){
            $data['success'] = true;
		    die(json_encode($data));
        } else {
            $data['success'] = false;
		    die(json_encode($data));            
            //echo json_encode(['success' => false, 'message' => 'Failed to add note']);
        }
        exit;
    }
    
    public function editnote()
    {
        $note = new Note($this->db);
        $task_num = $this->f3->get('POST.task_num');
        $entity_num = $this->f3->get('POST.entity_num');    
        $type_entity = $this->f3->get('POST.type_entity');   

    	if($this->f3->exists('POST.note_status')){  
    	    $this->f3->set('POST.note_status', 1);
    	}else{
    	    $this->f3->set('POST.note_status', 0);    	    
    	}

        
	    $data = array();
	    $data['task_num'] = $task_num;
	    $data['entity_num'] = $entity_num;
	    $data['type_entity'] = $type_entity;	    
	    //$data['note_status'] = $note_status;
	    
	    
	    
	    
        if($note->edit($this->f3->get('POST.note_num'))){
            $data['success'] = true;
		    die(json_encode($data));
        } else {
            $data['success'] = false;
		    die(json_encode($data));            
            //echo json_encode(['success' => false, 'message' => 'Failed to add note']);
        }
        exit;
    }    
    
        //var_dump($this->f3->get('PARAMS'));
        //var_dump($this->f3->get('POST')); 

    public function edittask() 
    {
        //die('hola'.'  '.$this->f3->get('PARAMS.EntityNum').'  '.$this->f3->get('PARAMS.TaskNum').'  '.$this->f3->get('PARAMS.TypeEntity'));
	    $person = new Person($this->db);
        $user = new User($this->db);	    
        $task = new Task($this->db);
        $taskpicture = new Task($this->db);
        $notes = new Note($this->db); 
        $this->f3->set('isNotOwner',$this->isNotOwner($this->f3->get('SESSION.user_rol')));
        if($this->f3->exists('filename')){
            die('picture');
        }else{
            //die('picture1');            
            //$this->f3->set('filename','');
            //$this->f3->set('taskpictures',$taskpicture->getPicturesByTaskNum($task_num));
        }
        $this->f3->set('message','');
        $this->f3->set('alertType', '');
        $this->f3->set('modal', '');
        $this->f3->set('statuses', $this->f3->get('task_status'));
	    if(($this->f3->get('PARAMS.NoteNum') != ""))
	    {
	        $note_num = $this->f3->get('PARAMS.NoteNum');
            $entity_num = $this->f3->get('PARAMS.EntityNum');
            $task_num = $this->f3->get('PARAMS.TaskNum');	   
            $type_entity = $this->f3->get('PARAMS.TypeEntity');
	        	       // die('hola'.$note_num.'  '.$this->f3->get('PARAMS.EntityNum').'  '.$this->f3->get('PARAMS.TaskNum').'  '.$this->f3->get('PARAMS.TypeEntity'));
	        if($this->f3->exists('PARAMS.Target'))
		    {
		        // Aca entra al borrar una accion
                $entity_num = $this->f3->get('PARAMS.EntityNum');
                $task_num = $this->f3->get('PARAMS.TaskNum');		    
			    $note1 = new Note($this->db);
			    if ($note1->delete($note_num)){
				    $this->f3->set('alertType','success');
				    $this->f3->set('message','La nota fue borrada exitosamente.');
			    }else{
				    $this->f3->set('alertType','error');
				    $this->f3->set('message','La nota no pudo ser borrada.');
			    }
		    }else{
	            $selected_note = new Note($this->db);
                $selected_note->getByNum($note_num);
	            $this->f3->set('modal', 'editnote');
                $this->f3->set('selected_note',$selected_note);
		    }
        }

        if($this->f3->exists('PARAMS.EntityNum'))
        {
            $entity_num = $this->f3->get('PARAMS.EntityNum');
            $task_num = $this->f3->get('PARAMS.TaskNum');
            $type_entity = $this->f3->get('PARAMS.TypeEntity');
        }
        //die('hola'.'  '.$entity_num.'  '.$task_num.'  '.$type_entity);
        //if($this->f3->exists('POST.update'))
        if ($this->f3->get('POST.action') == 'update')
        {
            $user1 = new User($this->db);
            //die('update');
            //die('entro'.'   '.$this->f3->get('POST.entity_num').'   '.$this->f3->get('POST.task_num').'   '.$this->f3->get('POST.type_entity'));
            //die('entro'.'   '.$this->f3->get('POST.task_type'));
            $currentResponsible = $this->f3->get('POST.currentResponsible');
            $username = $this->f3->get('SESSION.username');
            $entity_num = $this->f3->get('POST.entity_num');
            $task_num = $this->f3->get('POST.task_num');
            $type_entity = $this->f3->get('POST.type_entity');
            //$task_type_id = $this->f3->get('POST.task_type'); 
            //$task_status_id = $this->f3->get('POST.task_status'); 
         	//if($task->edit($task_num, $task_type_id, $task_status_id)){
         	if($task->edit($task_num)){
         	    $newCollab = $this->f3->get('POST.username');
         	    if ($currentResponsible !== $newCollab || $currentResponsible !== $username){
                    $notif = new Notification($this->db);
	                // Obtener API Key para envoi de mensaje de texto via Textbelt
                    $apiKey = getenv('TEXTBELT_API_KEY');
	                // Instanciar objeto de utilidad para envio de SMS
                    $smsNotifier = new SmsNotifier($apiKey);
	                // Obtener todos los datos del colaborador
                    $user1->getCollaboratorByUserName($newCollab);
                    // get new collaborator user id
                    $userId = $user1->id;
	                // Obtener numero de tlf del colaborador para poder enviar SMS
                    $phoneNumber = $user1->phone;
	                // Componer el mensaje de la notificacion
	                // primero el nombre del colaborador y luego el nombre de usuario del dueno del proyecto
                    $message = "{$user1->name}, {$username} {$this->f3->get('i18n_task_assigned')} '{$this->f3->get('POST.task_title')}'.";
	                // Insertar la notificacion en la tabla de notificaciones
                    $notif->add($userId, $message);
                    // Send SMS notification
                    try {
                        $smsNotifier->sendSms($phoneNumber, $message);
                        $this->f3->set('sms_message', 'Collaborator added and SMS notification sent successfully.');
                    } catch (\Exception $e) {
                        $this->f3->set('sms_message', 'Collaborator added, but SMS notification failed: ' . $e->getMessage());
                    }
         	        
         	    }

         	    $this->f3->set('message',$this->f3->get('i18n_taskmodified'));
                $this->f3->set('alertType', 'success');
         	}else{
         	    $this->f3->set('message',$this->f3->get('i18n_cantmodifytask'));
                $this->f3->set('alertType', 'fail');
         	} 
        } elseif ($this->f3->get('POST.action') == 'report'){
            $this->generar_pdf($this->f3->get('POST'));
           
        }

        if ($type_entity === 'project')
        {
            $projectType = $this->getProjectTypeName($this->f3->get('SESSION.project_type'));
            $task_types = $this->f3->get(strtolower($projectType) . '_task_type');             
            $this->f3->set('types', $task_types);        
            $task_status = $this->f3->get('task_status');
            $this->f3->set('statuses', $task_status);
            $task->getTaskByTaskNum($task_num); 

            // Convert task type ID to its corresponding name
            $current_task_type_id = $this->f3->get('POST.task_type'); // Task type as stored in DB
            $current_task_type_name = $task_types[$current_task_type_id] ?? 'Unknown';
            //die($current_task_type_name);
            // Pass data to the view
            $this->f3->set('current_task_type_name', $current_task_type_name);   
            
            // Convert task type ID to its corresponding name
            $current_task_status_id = $this->f3->get('POST.task_status'); // Task type as stored in DB
            $current_task_status_name = $task_status[$current_task_status_id] ?? 'Unknown';
            //die($current_task_type_name);
            // Pass data to the view
            $this->f3->set('current_task_status_name', $current_task_status_name);            
            
            $task->task_time = $this->convertTo24h($task->task_time).':'.substr($task->task_time,3,2);
            $this->f3->set('person', '');
        }else if ($type_entity === 'person'){
            //die('entro');
            $project_id = $this->f3->get('SESSION.project_id');  
            $projectType = $this->getProjectTypeName($this->f3->get('SESSION.project_type'));
            $task_types = $this->f3->get(strtolower($projectType) . '_task_type');               
            //$task_types = $this->f3->get('personal_task_type');
            $this->f3->set('types', $task_types);
            $task_status = $this->f3->get('task_status');
            $this->f3->set('statuses', $task_status);            
            $person = $person->getByPersonNum($entity_num);
            //$task = $task->getTaskByProjectpersonNum($entity_num, $task_num);
            $task = $task->getTaskByPersonNum($project_id, $entity_num, $task_num);

            // Convert task type ID to its corresponding name
            $current_task_status_id = $task['task_status']; // Task type as stored in DB
            $current_task_status_name = $task_status[$current_task_status_id] ?? 'Unknown';
            //die($current_task_type_name);
            // Pass data to the view
            $this->f3->set('current_task_status_name', $current_task_status_name);             

            // Convert task type ID to its corresponding name
            $current_task_type_id = $task['task_type']; // Task type as stored in DB
            $current_task_type_name = $task_types[$current_task_type_id] ?? 'Unknown';
            //die($current_task_type_name);
            // Pass data to the view
            $this->f3->set('current_task_type_name', $current_task_type_name); 
             
            
            //die('person'.'   '.$task['task_time']);
            //$task->task_time = $this->convertTo24h($task->task_time).':'.substr($task->task_time,3,2);            
            $task['task_time'] = $this->convertTo24h($task['task_time']).':'.substr($task['task_time'],3,2);
                               // die('person');
            $this->f3->set('person', $person);
        }
        $this->f3->set('type_entity', $type_entity);
        $this->f3->set('entity_num', $entity_num);
	    $notesn = $notes->getNotesByTaskNumJoin($task_num);

        $this->f3->set('taskpictures',$taskpicture->getPicturesByTaskNum($task_num));

	    $inprogres = $notes->count(array('task_num = ? AND note_status = ?', $task_num, 0));
	    $done = $notes->count(array('task_num = ? AND note_status = ?', $task_num, 1));

	    // Determinar permisologia del usuario
        $this->f3->set('hasPermission', function($action, $userRole) {
            // *** hasPermission() esta definida dentro de la clase base Controller   ***
            return $this->hasPermissionCollab($action, $userRole); // Assuming `hasPermission` is globally accessible
        });


        $users = $user->getUsersByProjectId($this->f3->get('SESSION.project_id'));
        $this->f3->set('inprogres', $inprogres);
        $this->f3->set('done', $done);        
        $this->f3->set('users',$users);	 
        $this->f3->set('notes', $notesn);  

        //die('finalizo'.$person['person_name'].'  '.$task['task_date']);        
        $this->f3->set('task', $task);
        //die(substr($task->task_time,0,2).'  '.substr($task->task_time,3,2).'  '.substr($task->task_time,5,3).' '.$this->convertTo24h($task->task_time));
        $this->f3->set('page_head','');

        $this->f3->set('view','task/edit_task.htm');
    }        
        
    public function edittask2() 
    {
        //die('entro'.$this->f3->get('PARAMS.EntityType'));
	    $person = new Person($this->db);
        $task = new Task($this->db);
        $notes = new Note($this->db);        
        $this->f3->set('message','');
        $this->f3->set('alertType', '');
        $this->f3->set('modal', '');
        $this->f3->set('statuses', $this->f3->get('task_status'));
        if($this->f3->exists('POST.update'))
        {
            //die('hola1'.$this->f3->get('POST.person_num'));
            // Aca entra para actualizar la Tarea
            $task_num = $this->f3->get('POST.task_num');
            $person_num = $this->f3->get('POST.person_num'); 
            
         	if($task->edit($task_num)){
         	    $this->f3->set('message',$this->f3->get('i18n_taskmodified'));
                $this->f3->set('alertType', 'success');
         	}else{
         	    $this->f3->set('message',$this->f3->get('i18n_cantmodifytask'));
                $this->f3->set('alertType', 'fail');
         	}  
        }else{
            //die('hola'.$this->f3->get('PARAMS.EntityNum'));
            //$person_num = $this->f3->get('PARAMS.PersonNum');
             // $person_num = $this->f3->get('PARAMS.EntityNum');
              $task_num = $this->f3->get('PARAMS.TaskNum');           
        }  
/*        
		if($this->f3->exists('PARAMS.NoteNum'))
		{
            $person_num = $this->f3->get('PARAMS.PersonNum');
            $task_num = $this->f3->get('PARAMS.TaskNum');		    
			$note1 = new Note($this->db);
			$notenum = $this->f3->get('PARAMS.NoteNum');
			if ($note1->delete($notenum)){
				$this->f3->set('alertType','success');
				$this->f3->set('message','La nota fue borrada exitosamente.');
			}else{
				$this->f3->set('alertType','error');
				$this->f3->set('message','La nota no pudo ser borrada.');
			}
		}      
*/        
/*        
        if($this->f3->exists('POST.createnote'))
        {
            $note = new Note($this->db);
            $task_num = $this->f3->get('PARAMS.task_num');
            $person_num = $this->f3->get('PARAMS.person_num');            
         	if($note->add($this->f3->get('POST'))){
         	    $this->f3->set('message',$this->f3->get('i18n.ui.i18n_notecreated'));
                $this->f3->set('alertType', 'success');
	 	    //$this->f3->reroute('/result/Tarea Actualizada/success');
         	}else{
         	    $this->f3->set('message',$this->f3->get('i18n.ui.i18n_cantcreatenote'));
                $this->f3->set('alertType', 'fail');
         	}            
        }
*/        

	    if(($this->f3->get('PARAMS.NoteNum') != ""))
	    {
	        $notenum = $this->f3->get('PARAMS.NoteNum');
	        if($this->f3->exists('PARAMS.Target'))
		    {
                $person_num = $this->f3->get('PARAMS.EntityNum');
                $task_num = $this->f3->get('PARAMS.TaskNum');		    
			    $note1 = new Note($this->db);
			    if ($note1->delete($notenum)){
				    $this->f3->set('alertType','success');
				    $this->f3->set('message','La nota fue borrada exitosamente.');
			    }else{
				    $this->f3->set('alertType','error');
				    $this->f3->set('message','La nota no pudo ser borrada.');
			    }
		    }else{
	            $selected_note = new Note($this->db);
                $selected_note->getByNum($notenum);
	            $this->f3->set('modal', 'editnote');
                $this->f3->set('selected_note',$selected_note);
		    }
        }

        if ($this->f3->get('PARAMS.EntityType') == 'project')
        {
            //die('project'.$this->f3->get('PARAMS.TaskNum'));
            $this->f3->set('types', $this->f3->get('project_task_type'));
            $task->getTaskByProjectIdJoin($this->f3->get('PARAMS.TaskNum')); 
             $this->f3->set('person', '');
            $task->task_time = $this->convertTo24h($task->task_time).':'.substr($task->task_time,3,2);
        }else if ($this->f3->get('PARAMS.EntityType') == 'person'){
            //die('entro');
            $projectperson_num = $this->f3->get('PARAMS.EntityNum');
            $this->f3->set('types', $this->f3->get('person_task_type'));
            $person = $person->getByProjectpersonNum($projectperson_num);
            //die('hola'.'  '.$person['person_num']);
            $this->f3->set('person', $person);
            $task = $task->getTaskByProjectpersonNum($projectperson_num, $task_num);

            $task['task_time'] = $this->convertTo24h($task['task_time']).':'.substr($task['task_time'],3,2);
            //die('finalizo'.$person['person_name'].'  '.$task['task_date']);
        }


        $task['task_time'] = $this->convertTo24h($task['task_time']).':'.substr($task['task_time'],3,2);

	    $notesn = $notes->getNotesByTaskNumJoin($task_num);

        $this->f3->set('notes', $notesn);  

        //die('finalizo'.$person['person_name'].'  '.$task['task_date']);        
        $this->f3->set('task', $task);
        //die(substr($task->task_time,0,2).'  '.substr($task->task_time,3,2).'  '.substr($task->task_time,5,3).' '.$this->convertTo24h($task->task_time));
        $this->f3->set('page_head','');

        $this->f3->set('view','task/edit_task.htm');
    }

    public function viewtask() 
    {
        //die('hola'.'  '.$this->f3->get('PARAMS.EntityNum').'  '.$this->f3->get('PARAMS.TaskNum').'  '.$this->f3->get('PARAMS.TypeEntity'));
	    $person = new Person($this->db);
        $user = new User($this->db);	    
        $task = new Task($this->db);
        $notes = new Note($this->db); 
        if($this->f3->exists('filename')){
            die('picture');
        }else{
           // die('picture1');            
            $this->f3->set('filename','');
        }
        

        $this->f3->set('message','');
        $this->f3->set('alertType', '');
        $this->f3->set('modal', '');
        $this->f3->set('statuses', $this->f3->get('task_status'));
	    if(($this->f3->get('PARAMS.NoteNum') != ""))
	    {
	        $note_num = $this->f3->get('PARAMS.NoteNum');
            $entity_num = $this->f3->get('PARAMS.EntityNum');
            $task_num = $this->f3->get('PARAMS.TaskNum');	   
            $type_entity = $this->f3->get('PARAMS.TypeEntity');
	        	       // die('hola'.$note_num.'  '.$this->f3->get('PARAMS.EntityNum').'  '.$this->f3->get('PARAMS.TaskNum').'  '.$this->f3->get('PARAMS.TypeEntity'));
	        if($this->f3->exists('PARAMS.Target'))
		    {
                $entity_num = $this->f3->get('PARAMS.EntityNum');
                $task_num = $this->f3->get('PARAMS.TaskNum');		    
			    $note1 = new Note($this->db);
			    if ($note1->delete($note_num)){
				    $this->f3->set('alertType','success');
				    $this->f3->set('message','La nota fue borrada exitosamente.');
			    }else{
				    $this->f3->set('alertType','error');
				    $this->f3->set('message','La nota no pudo ser borrada.');
			    }
		    }else{
	            $selected_note = new Note($this->db);
                $selected_note->getByNum($note_num);
	            $this->f3->set('modal', 'editnote');
                $this->f3->set('selected_note',$selected_note);
		    }
        }

        if($this->f3->exists('PARAMS.EntityNum'))
        {
            $entity_num = $this->f3->get('PARAMS.EntityNum');
            $task_num = $this->f3->get('PARAMS.TaskNum');
            $type_entity = $this->f3->get('PARAMS.TypeEntity');
        }

        //if($this->f3->exists('POST.update'))
        if ($this->f3->get('POST.action') == 'update')
        {
            die('entro'.'   '.$this->f3->get('POST.entity_num').'   '.$this->f3->get('POST.task_num').'   '.$this->f3->get('POST.type_entity'));
            $entity_num = $this->f3->get('POST.entity_num');
            $task_num = $this->f3->get('POST.task_num');
            $type_entity = $this->f3->get('POST.type_entity');
         	if($task->edit($task_num)){
         	    $this->f3->set('message',$this->f3->get('i18n_taskmodified'));
                $this->f3->set('alertType', 'success');
         	}else{
         	    $this->f3->set('message',$this->f3->get('i18n_cantmodifytask'));
                $this->f3->set('alertType', 'fail');
         	} 
        } elseif ($this->f3->get('POST.action') == 'report'){
            $this->generar_pdf($this->f3->get('POST'));
           
        }


        if ($type_entity === 'project')
        {
            //die('project');
            $this->f3->set('types', $this->f3->get('project_task_type'));
            $task->getTaskByProjectIdJoin($task_num); 
            $task->task_time = $this->convertTo24h($task->task_time).':'.substr($task->task_time,3,2);
            $this->f3->set('person', '');
        }else if ($type_entity === 'person'){
            //die('person');            
            $this->f3->set('types', $this->f3->get('person_task_type'));
            $person = $person->getByProjectpersonNum($entity_num);
            $task = $task->getTaskByProjectpersonNum($entity_num, $task_num);
            $task['task_time'] = $this->convertTo24h($task['task_time']).':'.substr($task['task_time'],3,2);
            $this->f3->set('person', $person);
        }
        $this->f3->set('type_entity', $type_entity);
        $this->f3->set('entity_num', $entity_num);
	    $notesn = $notes->getNotesByTaskNumJoin($task_num);

	    $inprogres = $notes->count(array('task_num = ? AND note_status = ?', $task_num, 0));
	    $done = $notes->count(array('task_num = ? AND note_status = ?', $task_num, 1));

        $users = $user->getUsersByProjectId($this->f3->get('SESSION.project_id'));
        $this->f3->set('inprogres', $inprogres);
        $this->f3->set('done', $done);        
        $this->f3->set('users',$users);	 
        $this->f3->set('notes', $notesn);  

        //die('finalizo'.$person['person_name'].'  '.$task['task_date']);        
        $this->f3->set('task', $task);
        //die(substr($task->task_time,0,2).'  '.substr($task->task_time,3,2).'  '.substr($task->task_time,5,3).' '.$this->convertTo24h($task->task_time));
        $this->f3->set('page_head','');

        $this->f3->set('view','task/view_task.htm');
    }







    public function convertTo12h($time)
    {
        $minutes = substr($time,5,3);
        if(intval(substr($time,0,2)) > 11)
        {
            $suffix = 'pm';
            if(intval(substr($time,0,2)) == 12){
                $horas = intval(substr($time,0,2));
            }else{
                $horas = intval(substr($time,0,2)) - 12;                
            }
        }else{
            $suffix = 'am';
            $horas = substr($time,0,2);            
        }
        return $horas.':'.$minutes.' '.$suffix;
    }

    public function convertTo24h($time)
    {
        if(substr($time,6,3) == 'pm')
        {
            $horas = substr($time,0,2) + 12;
            return $horas;
        }else{
            return substr($time,0,2);
        }
        
    }

    public function dashboard()
    {
        $this->f3->set('page_head','Dashboard');
        $this->f3->set('message', $this->f3->get('PARAMS.message'));
        $this->f3->set('view','cita/dashboard1.htm');
    }

    public function nuevacita()
    {
        $this->f3->set('page_head','Nueva Cita');
        $this->f3->set('view','paciente/dashboard.htm');
    }

    public function create()
    {
        $task = new Task($this->db);
        $project = new Project($this->db);        
        $taskdate = $this->f3->get('POST.TaskDate');
        $source = $this->f3->get('POST.source');        
        $typeentity = $this->f3->get('POST.type_entity'); 
        $entitynum = $this->f3->get('POST.entity_num'); 
        $newuser = $this->f3->get('POST.username');;
        $username = $this->f3->get('SESSION.username');
        $project_id = $this->f3->get('SESSION.project_id');
	    $data = array();
        $newdate = date("d/m/Y", strtotime($taskdate));
        $this->datetime($newdate);
        if ($this->datetime($taskdate) === 'past'){ 
            $data['alertType'] = 'error';
            $data['message'] = $this->f3->get('i18n_reject_task_creation.');
            die(json_encode($data));
        }else{
            if ($task->add($project_id , $newuser, $entitynum, $typeentity, $this->f3->get('POST'))) {
                // Si el proyecto esta como Created quiere decir que aun no se creaba la primera tarea
                // como ya se creo la primera tarea entonces pasa a In Progress
                if ($this->f3->get('SESSION.project_status') == 4 ){
                    $project->projectInProgress($this->f3->get('SESSION.project_id'))  ;
                    $this->f3->set('SESSION.project_status', 2);
                }
          
                $user1 = new User($this->db);
                $notif = new Notification($this->db);
	            // Obtener API Key para envoi de mensaje de texto via Textbelt
                $apiKey = getenv('TEXTBELT_API_KEY');
	            // Obtener todos los datos del colaborador
                $user1->getCollaboratorByUserName($newuser);
	            // Obtener numero de tlf del colaborador para poder enviar SMS
                $phoneNumber = $user1->phone;
	            // Componer el mensaje de la notificacion
	            // primero el nombre del colaborador y luego el nombre de usuario del dueno del proyecto
                $message = "{$user1->name}, {$username} {$this->f3->get('i18n_task_assigned')} '{$this->f3->get('POST.task_title')}' / '{$this->f3->get('SESSION.project_name')}'.";
                $notif->add($user1->id, $message);
                $data['source'] = $source; 
                $data['entitynum'] = $entitynum;                
                $data['alertType'] = 'success';
		        $data['message'] = 'La tarea fue creada';
		        die(json_encode($data));
            } else {	
                $data['alertType'] = 'error';		 
		        $data['message'] = 'La tarea no fue creada';
		        die(json_encode($data));
            } 
        }   
    }

    public function update()
    {
        //die('entro'.' '.$this->f3->get('POST.task_num'));
	    $this->f3->set('modal', '');
        $task = new Task($this->db);
        if($this->f3->exists('POST.update'))
        {
            //die('entro');
         	$task->edit($this->f3->get('POST.task_num'));
	 	    $this->f3->reroute('/result/Tarea Actualizada/success');
        } else{
         	$task->getByNum($this->f3->get('PARAMS.task_num'));
         	$this->f3->set('task',$task);
        //	$this->f3->set('age',$this->calculate_age($person->person_dob));
         	$this->f3->set('page_head','Editar tarea');
         	$this->f3->set('view','task/update.htm');
        }
    }

    public function delete()
	{
		if($this->f3->exists('PARAMS.TaskNum'))
		{

			$task = new Task($this->db);
			$tasknum = $this->f3->get('PARAMS.TaskNum');
			$entitynum = $this->f3->get('PARAMS.EntityNum');
			$typeentity = $this->f3->get('PARAMS.TypeEntity');
		    $myview = $this->f3->get('PARAMS.MyView');	
			if ($task->existsTaskNotesJoinTaskNum($tasknum)){
				$this->f3->set('alertType','error');
				$this->f3->set('message',$this->f3->get('i18n_cantdeletetask'));
			}else{
			    if ($task->delete($tasknum, $entitynum, $typeentity)){
                    //die('entro'.'  '.$tasknum.'  '. $propernum .'  '. $view);	
					$this->f3->set('alertType','success');
					$this->f3->set('message',$this->f3->get('i18n_taskdeleted'));
					//$this->f3->reroute('/task/persontask/'.$propernum);
				}else{
					$this->f3->set('alertType','error');
					$this->f3->set('message',$this->f3->get('i18n_couldnotdeletetask'));
				}
			}
			if ($myview == 'persontask'){
			    $this->f3->reroute('/task/persontask/'.$entitynum);
			}else{
			    $this->f3->reroute('/task/listtasks/');
			}
		}
	}

	public function datetime($date){
        $datei = explode('/', $date);
        $dayci   = intval($datei[0]);
        $monthci = intval($datei[1]);
        $yearci  = intval($datei[2]);
        $todaydate = explode('/', date('d/m/Y'));
        $todayday   = intval($todaydate[0]);
        $todaymonth = intval($todaydate[1]);
        $todayyear  = intval($todaydate[2]);
		if ($yearci >= $todayyear && $monthci >= $todaymonth && $dayci >= $todayday)
		{
			return 'future';
		}else{
			return 'past';
		}
	}

    public function grantedCreate($rol)
    {
        /*   Rol= 1     Grant = 'CEB'
        *    Rol= 2     Grant = 'L'
        *    Rol= 3     Grant = 'C'
        *    Rol= 4     Grant = 'E'
        *    Rol= 5     Grant = 'CE
        */
        if ($rol == 1 OR $rol == 3 OR $rol == 5){
            return true;
        }else{
            return false;
        }
    }

    public function grantedEdit($rol)
    {
        /*   Rol= 1     Grant = 'CEB'
        *    Rol= 2     Grant = 'L'
        *    Rol= 3     Grant = 'C'
        *    Rol= 4     Grant = 'E'
        *    Rol= 5     Grant = 'CE
        */
        if ($rol == 1 OR $rol == 4 OR $rol == 5){
            return true;
        }else{
            return false;
        }
    }
    
    public function grantedDelete($rol)
    {
        /*   Rol= 1     Grant = 'CEB'
        *    Rol= 2     Grant = 'L'
        *    Rol= 3     Grant = 'C'
        *    Rol= 4     Grant = 'E'
        *    Rol= 5     Grant = 'CE
        */
        if ($rol == 1){
            return true;
        }else{
            return false;
        }
    } 
 
    public function isNotOwner($rol)
    {
        /*   Rol= 1     Grant = 'CEB'
        *    Rol= 2     Grant = 'L'
        *    Rol= 3     Grant = 'C'
        *    Rol= 4     Grant = 'E'
        *    Rol= 5     Grant = 'CE
        */
        if ($rol == 1){
            return false;
        }else{
            return true;
        }
    }


// **************** Start Load Task images *************************** 
/*
    public function loadimage()
    {
        $task = new Task($this->db);
        $entity_num = $this->f3->get('POST.entity_num');
        $task_num = $this->f3->get('POST.task_num');
        $type_entity = $this->f3->get('POST.type_entity');
        $maxImagesPerTask = 5; // Define the maximum number of images allowed per task

        // Count existing images
        $existingImages = $task->getNextPictureNumber($task_num) - 1;
        if ($existingImages >= $maxImagesPerTask) {
            $this->f3->reroute("/task/edit/{$entity_num}/{$task_num}/{$type_entity}");
            return;
        }

        if (!empty($_FILES['uploadfile']['tmp_name'])) {
            // Get next available picture number
            $nextPictureNum = $task->getNextPictureNumber($task_num);
            $filename = "{$task_num}_{$nextPictureNum}.jpg";
            $tempname = $_FILES['uploadfile']['tmp_name'];
            $folder = "ui/task_images/" . $filename;

            if ($this->resizeAndSaveImage($tempname, $folder)) {
                $task->savePicture($task_num, $folder);
            }
        }
        $this->f3->reroute("/task/edit/{$entity_num}/{$task_num}/{$type_entity}");
    }

    private function resizeAndSaveImage($source, $destination, $maxWidth = 800, $maxHeight = 800) {
        list($width, $height, $type) = getimagesize($source);

        if ($width > $maxWidth || $height > $maxHeight) {
            $scale = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = ceil($width * $scale);
            $newHeight = ceil($height * $scale);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $image = imagecreatefromjpeg($source);

        // Correct orientation if EXIF data is present
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($source);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $image = imagerotate($image, 180, 0);
                        break;
                    case 6:
                        $image = imagerotate($image, -90, 0);
                        break;
                    case 8:
                        $image = imagerotate($image, 90, 0);
                        break;
                }
            }
        }

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        return imagejpeg($newImage, $destination, 80);
    }
*/    
    
 
 
 
 
 
     public function loadimage()
    {
        $task = new Task($this->db);
        $entity_num = $this->f3->get('POST.entity_num');
        $task_num = $this->f3->get('POST.task_num');
        $type_entity = $this->f3->get('POST.type_entity');
        $maxImagesPerTask = 5; // Define the maximum number of images allowed per task

        // Count existing images
        $existingImages = $task->getNextPictureNumber($task_num) - 1;
        if ($existingImages >= $maxImagesPerTask) {
            $this->f3->reroute("/task/edit/{$entity_num}/{$task_num}/{$type_entity}");
            return;
        }

        if (!empty($_FILES['uploadfile']['tmp_name'])) {
            // Get next available picture number
            $nextPictureNum = $task->getNextPictureNumber($task_num);
            $filename = "{$task_num}_{$nextPictureNum}.jpg";
            $tempname = $_FILES['uploadfile']['tmp_name'];
            $folder = "ui/task_images/" . $filename;

            if ($this->resizeAndSaveImage($tempname, $folder)) {
                $task->savePicture($task_num, $folder);
            }
        }
        //$this->f3->reroute("/task/edit/{$entity_num}/{$task_num}/{$type_entity}");
        $this->f3->reroute("/task/edit/{$entity_num}/{$task_num}/{$type_entity}?tab=images");

    }

    public function deleteimage()
    {
        $entity_num = $this->f3->get('POST.entity_num');
        $task_num = $this->f3->get('POST.task_num');
        $type_entity = $this->f3->get('POST.type_entity');
        $data = array();
        $data['entity_num'] = $entity_num;                
        $data['task_num'] = $task_num;
		$data['type_entity'] = $type_entity;
        $data['success'] = true;
        $task = new Task($this->db);
        $picture_id = $this->f3->get('POST.picture_id');
        if ($task->deletePicture($picture_id)){
            //echo json_encode(['success' => true]);
            die(json_encode($data));
        }else{
            die(json_encode(["success" => false, "error" => "Missing parameters"]));
            exit;
        }
    }

    private function resizeAndSaveImage($source, $destination, $maxWidth = 800, $maxHeight = 800) {
        list($width, $height, $type) = getimagesize($source);

        if ($width > $maxWidth || $height > $maxHeight) {
            $scale = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = ceil($width * $scale);
            $newHeight = ceil($height * $scale);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $image = imagecreatefromjpeg($source);

        // Correct orientation if EXIF data is present
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($source);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $image = imagerotate($image, 180, 0);
                        break;
                    case 6:
                        $image = imagerotate($image, -90, 0);
                        break;
                    case 8:
                        $image = imagerotate($image, 90, 0);
                        break;
                }
            }
        }

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        return imagejpeg($newImage, $destination, 80);
    }
 
 
 
 
 
    
    


    public function loadimage1(){
        $entity_num=$this->f3->get('POST.entity_num');
        $task_num=$this->f3->get('POST.task_num');
        $type_entity=$this->f3->get('POST.type_entity');
        if($this->f3->exists('POST.upload')){  
            if ($this->f3->exists('FILES.image')) {  
                $file = $this->f3->get('FILES.image.name');
                // Call the uploadImage method from the Library class
                $result = $this->uploadImage($file);
                // If the image was successfully uploaded, set the image path
                if ($result['status']) {
                    die('entro3');
                    $this->f3->set('imagePath', $result['path']);
                    die($this->f3->get('imagePath'));
                }
            }
        } 
	    $this->f3->reroute('/task/edit/'.$entity_num.'/'.$task_num.'/'.$type_entity);
    }
 
    public function uploadImage($file) {
        //die('aqui');
        $uploadDir = $this->f3->get('UPLOADS');
                die('aqui  '. $uploadDir);
        $targetFile = $uploadDir . basename($file['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($file['tmp_name']);
        if ($check !== false) {
            $this->f3->set('uploadStatus', "File is an image - " . $check['mime']);
            $uploadOk = 1;
        } else {
            $f3->set('uploadStatus', "File is not an image.");
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($targetFile)) {
            $this->f3->set('uploadStatus', "Sorry, file already exists.");
            $uploadOk = 0;
        }

        // Check file size (limit: 500KB)
        if ($file['size'] > 500000) {
            $this->f3->set('uploadStatus', "Sorry, your file is too large.");
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedFormats = ['jpg', 'png', 'jpeg', 'gif'];
        if (!in_array($imageFileType, $allowedFormats)) {
            $this->f3->set('uploadStatus', "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            $uploadOk = 0;
        }

        // Attempt to upload the file
        if ($uploadOk === 1) {
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                $this->f3->set('uploadStatus', "The file " . htmlspecialchars(basename($file['name'])) . " has been uploaded.");
                return ['status' => true, 'path' => $targetFile];
            } else {
                $this->f3->set('uploadStatus', "Sorry, there was an error uploading your file.");
                return ['status' => false, 'path' => null];
            }
        }

        return ['status' => false, 'path' => null];
    }

    public function taskSummary() {
        $this->f3->set('view', 'task/task_summary.htm');
    } 

    // Metodo para obtener y pasar datos para grafico via Ajax. El Script de Ajax esta en xmartcrm.js 
    public function fetchTaskData() {
        $task = new Task($this->db);
        $project_id = $this->f3->get('SESSION.project_id');

        // Fetch task counts grouped by status (status stored as integers)
        $result = $task->taskCountByStatus($project_id);

        // Get the status mapping from the configuration file
        $statusMapping = $this->f3->get('project_status');

        // Format data for the chart
        $taskData = [
            'labels' => array_map(function ($row) use ($statusMapping) {
                return $statusMapping[$row['task_status']] ?? 'Unknown'; // Translate status ID to name
            }, $result),
            'counts' => array_column($result, 'count'),
        ];

        // Output the task data as JSON
        die(json_encode($taskData));
    }

    // Metodo para obtener y pasar datos para grafico via Ajax. El Script de Ajax esta en xmartcrm.js 
    public function fetchActionData() {
        $action = new Note($this->db);
        $project_id = $this->f3->get('SESSION.project_id');

        // Fetch task counts grouped by status (status stored as integers)
        $result = $action->actionsCountByStatus($task_num);

        // Get the status mapping from the configuration file
        $statusMapping = $this->f3->get('action_status');

        // Format data for the chart
        $actionData = [
            'labels' => array_map(function ($row) use ($statusMapping) {
                return $statusMapping[$row['action_status']] ?? 'Unknown'; // Translate status ID to name
            }, $result),
            'counts' => array_column($result, 'count'),
        ];

        // Output the task data as JSON
        die(json_encode($actionData));
    }

    // Metodo para obtener y pasar datos para grafico via Ajax. El Script de Ajax esta en xmartcrm.js 
    public function fetchTaskData1() {
        $task = new Task($this->db);
        $project_id = $this->f3->get('SESSION.project_id');
        $result = $task->taskCountByStatus($project_id);
        // Format data for the chart
        $taskData = [
            'labels' => array_column($result, 'task_status'),
            'counts' => array_column($result, 'count'),
        ];  
        die(json_encode($taskData));
        exit;
    }

public function fetchTaskDistribution() {
    $task = new Task($this->db);
    $project_id = $this->f3->get('SESSION.project_id');

    // Get project details (including project_type)
    $project = new Project($this->db);
    $project->getProjectById($project_id);

    $project_type_id = $project->project_type;

    // Load project type names from setup_en.cfg
    $this->f3->config('setup_en.cfg');
    $projectTypeName = $this->f3->get("project_type.$project_type_id");

    // Determine correct task type array key
    $taskTypeKey = strtolower($projectTypeName) . '_task_type';
    
    // Get task count by type for the given project
    $result = $task->taskCountByType($project_id);

    // Convert task type integers to their corresponding names
    $labels = [];
    foreach ($result as $row) {
        $taskTypeId = $row['task_type'];
        $taskTypeName = $this->f3->get("$taskTypeKey.$taskTypeId") ?? "Unknown Type"; 
        $labels[] = $taskTypeName;
    }

    // Format data for Chart.js
    $taskData = [
        'projectType' => $projectTypeName,
        'labels' => $labels,
        'counts' => array_column($result, 'count'),
    ];  

    die(json_encode($taskData));
    exit;
}

public function fetchTeamProductivity() {
    $task = new Task($this->db);
    $project_id = $this->f3->get('SESSION.project_id');

    // Get task count by type for the given project
    $result = $task->usernameCompletedTasks($project_id);

    // Format data for Chart.js
    $taskData = [
        'labels' => array_column($result, 'username'),
        'counts' => array_column($result, 'completed_tasks'),
    ];  

    die(json_encode($taskData));
    exit;
}

public function fetchUserNotesCount()
{
    $task = new Task($this->db);
    $project_id = $this->f3->get('SESSION.project_id');

    // Fetch data from the model
    $result = $task->getUserNotesCount($project_id);

    // Format data for Chart.js
    $taskData = [
        'labels' => array_column($result, 'username'),
        'activeNotes' => array_column($result, 'active_notes'),
        'doneNotes' => array_column($result, 'done_notes')
    ];

    die(json_encode($taskData));
    exit;
}




	// Method to generate PDF report for a selected paciente
public function generar_pdf($DATA)
{
    $note = new Note($this->db);
    $task_num = $this->f3->get('POST.task_num');
    $notes = $note->getNotesByTaskNumJoin($task_num);

    $pdf = new CustomPdfGenerator('Task Management');
    $this->initializePdfSettings($pdf);

    // Add Task Details
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 5, 'Task Report', 0, 1, 'C');
    $pdf->Ln(4);

    $this->renderTaskDetails($pdf);

    // Group Notes
    $inProgressNotes = array_filter($notes, fn($n) => $n->note_status == 0);
    $doneNotes = array_filter($notes, fn($n) => $n->note_status != 0);

    // Add Notes
    $this->renderNotes($pdf, "In Progress", $inProgressNotes, [255, 165, 0]);
    $this->renderNotes($pdf, "Done", $doneNotes, [0, 128, 0]);

    $pdf->Output('TaskReport.pdf', 'I');
    exit();
}

private function initializePdfSettings($pdf)
{
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->setFontSubsetting(true);
}

private function renderTaskDetails($pdf)
{
    $fields = [
        'Date' => 'task_date',
        'Title' => 'task_title',
        'Status' => 'task_status',
        'Responsible' => 'username',
        'Type' => 'task_type',
        'Description' => 'task_desc',
    ];

    foreach ($fields as $label => $field) {
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(strlen($label) * 2, 5, "$label: ", 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell(0, 5, $this->f3->get("POST.$field"), 0, 'L');
    }
    $pdf->Ln(4);
}

private function renderNotes($pdf, $sectionTitle, $notes, $bgColor)
{
    if (count($notes) > 0) {
        $pdf->SetFillColor(...$bgColor);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, $sectionTitle, 0, 1, 'L', true);
        $pdf->Ln(5);

        foreach ($notes as $note) {
            $this->renderNoteDetails($pdf, $note);
        }
    }
}

private function renderNoteDetails($pdf, $note)
{
    $fields = [
        'Date' => date('d/m/Y', strtotime($note->note_createdat)),
        'Title' => $note->note_title,
        'Detail' => $note->note_content,
    ];

    foreach ($fields as $label => $value) {
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(20, 5, "$label: ", 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell(0, 5, $value, 0, 'L');
    }
    $pdf->Ln(3);
}
	    
}