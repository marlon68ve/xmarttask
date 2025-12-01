<?php
class PersonController extends Controller {

    public function listperson() 
    {
	    //EXCELENTE PARA DEPURACION: con la proxima line escribo en la consola del navegador
	    //echo '<script>console.log("Welcome to GeeksforGeeks!"); </script>';
	    $q = 0;
	    $limitSearch = 300;
	    $limitLoad = 12;
        // Crear una instancia de la clase modelo Persona
        $person = new Person($this->db);
	    $total_count = ceil($person->getPersonCountByProjectId($this->f3->get('SESSION.project_id')));
        /* Verificar si se esta realizando una busqueda al ingresar texto en campo busqueda
	    * y luego pulsar el boton buscar, lo cual envia el parametro q con el texto, via Get
	    * El campo de busqueda en la vista list-person, tiene id="searchInput" y name="q"
	    * Al pulsar el boton del campo busqueda se envia el formulario con metodo GET y el ruteo
	    * es manejado por routes.ini de la siguiente forma:
	    * GET /person/listperson/@q=PersonController->listperson */
    	if($this->f3->exists('GET.q')){
    	    die('entro');
	        // Si se pulso el boton "Buscar" se enviara el formulario y debe existir q via GET
            $q = $this->f3->get('GET.q'); 
	        // Buscar los $limit primeros persons que cumplan con el criterio de busqueda q
            //$persons = $person->search($q, $limitSearch);
	        //$persons = $person->searchWithTaskCount($q, $limitSearch);
	        //$persons = $person->searchByUserId($this->f3->get('SESSION.id'), $q, $limitSearch);
	        $persons = $person->searchWithTaskCount($q, $limitSearch, $this->f3->get('SESSION.project_id'), 'person');
        } else {
           
	        // Si el formulario se cargo normalmente simplemente traer los primeros $limitLoad registros
	        // de la tabla persons
	        //$persons = $person->searchAllWithTaskCount($limitSearch);
	        //die('hola'.$this->f3->get('SESSION.id'));
	        //$persons = $person->getAllByUserId($this->f3->get('SESSION.id'), $limitSearch);
	        //$persons = $person->getAllByProjectId($this->f3->get('SESSION.project_id'), $limitSearch);
	        $persons = $person->searchAllWithTaskCount($limitSearch, $this->f3->get('SESSION.project_id'), 'person');
	         //die('entra'.'  '.$limitSearch.'  '.$this->f3->get('SESSION.project_id').'  '.'person');
            //$persons = $person->find(null, array('limit' => $limitLoad));
    	}
	    /* Verificar si el codigo de historia de person es pasado como parametro. Este proceso inicia
	    * al pulsar el icono de agendar cita en al fila de un pacinete, ya que es direccionado hacia aca por routes.ini mediante:
	    * GET /person/buscar/@CodHistoria=personController->listperson
	    * Al efectuar el render de la vista nuevamente se ejecuta las funciones de javascript y en particular
	    * hay una que verifica si el campo oculto CodHistoria tiene algun valor, con lo cual mostrara el modal id="CitaHoyModal"
	    * Este modal tiene definido el formulario id="citaForm", por lo cual al pulsar el boton "Agendar" se dispara el evento On Submit
	    * ubicado en script.js, que prepara los datos para ser enviados via request de Ajax a la ruta nuevacita 
	    * GET|POST /nuevacita=CitaController->crear
	    * Ajax luego redirecciona a cita/listcita, que el enrutador traduce a:   
	    * GET|POST /cita/listcita=CitaController->list_cita       
	    *
	    * En la siguiente linea se verifica si se paso CodHistoria desde la vista de cita o desde la vista de crea person
	    * GET /person/buscar/@CodHistoria=personController->listperson
	    * o
	    * GET /successperson/@message/@token=personController->listperson
	    */
	    //if(($this->f3->get('PARAMS.PersonNum') != "") || ($this->f3->get('PARAMS.token') != ""))
	    if(($this->f3->get('PARAMS.PersonPhone') != "") || ($this->f3->get('PARAMS.token') != ""))
	    {// *** Aca entra en primer lugar al momento de crear una nueva persona ***
	        // Almacenar el valor de CodHistoria segun desde donde fue enviado, para luego pasarlo al modal junto al resto de datos
	        //if ($this->f3->get('PARAMS.PersonNum') != "")
	        if ($this->f3->get('PARAMS.PersonPhone') != "")
	        {// ** Aca entre si se pulsa el icono de accion 'Agendar' **
		        $token = $this->f3->get('PARAMS.PersonPhone');	        
	            // Obteber el projectperson_num
	            $projectperson = new ProjectPerson($this->db);
	            //$proper = $projectperson->getByPersonPhone($token, $this->f3->get('SESSION.project_id'));
	            $projectperson->getByPersonPhone($token, $this->f3->get('SESSION.project_id'));
	                   // die('aqui'.'  '.$projectperson->projectperson_num);
		        //$token = $this->f3->get('PARAMS.PersonNum');
	            //$this->f3->set('entity_num', $projectperson->projectperson_num);
	            $this->f3->set('entity_num', $projectperson->person_num);
		        $this->f3->set('modal', 'fromlistper');
		        //die('entro2'.'  '.$projectperson->projectperson_num);
	        }else{
	        die('entro1');
		        // *** Aca entra en segundo lugar al momento de crear una nueva persona ***
		        //echo '<script>console.log($this->f3->get('PARAMS.PersonNum')); </script>';
		        $token = $this->f3->get('PARAMS.token');
		        $this->f3->set('entity_num', $token);
		        $this->f3->set('modal', 'fromlistper');
	        }
            // set the default timezone to use.
            $mytimezone = $this->f3->get('timezone');  // obtener el timezone desde el archivo de configuracion
            date_default_timezone_set($mytimezone);
	        $todaydate = date('d/m/Y');
	        $todaytime = date('h:i:sa');
	        // Obtener los datos del person especifico
	        $selected_person = new Person($this->db);
            $selected_person->getByPhone($token);
	        //$this->f3->set('modal', 'fromlistper');
            $this->f3->set('token', $token);
            $this->f3->set('todaydate', $todaydate);
            $this->f3->set('todaytime', $todaytime);
            $this->f3->set('datepicker',$todaydate);
            $this->f3->set('selected_person',$selected_person);
            $this->f3->set('age',$this->calculate_age($selected_person->person_dob));
        }
        // Definir las variables globales de f3
  	    $this->f3->set('persons',$persons);
    	$this->f3->set('total_persons',$total_count);

	    // Determinar permisologia del usuario
        $this->f3->set('hasPermission', function($action, $userRole) {
            // *** hasPermission() esta definida dentro de la clase base Controller   ***
            return $this->hasPermissionCollab($action, $userRole); // Assuming `hasPermission` is globally accessible
        });	        
        
  	    $this->f3->set('granted_create',$this->grantedCreate($this->f3->get('SESSION.user_rol')));
  	    $this->f3->set('granted_edit',$this->grantedEdit($this->f3->get('SESSION.user_rol')));
  	    $this->f3->set('granted_delete',$this->grantedDelete($this->f3->get('SESSION.user_rol')));  	    
	    if($this->f3->exists('GET.q')){
            $this->f3->set('q', $this->f3->get('GET.q'));
	    }else{
            $this->f3->set('q', '');
	    }
	    if($this->f3->exists('GET.q')){
            $this->f3->set('q', $this->f3->get('GET.q'));
	    }else{
            $this->f3->set('q', '');
	    }
	      
	    if($this->f3->exists('PARAMS.message')){
	        $this->f3->set('message', $this->f3->get('PARAMS.message'));
            $this->f3->set('alertType', $this->f3->get('PARAMS.alertType'));
	    }else{
	        $this->f3->set('message', '');
            $this->f3->set('alertType', '');
	    }
	    //die('entro');
        //
        $this->f3->set('typeentity', 'person');        
        $this->f3->set('source','listper');
        $this->f3->set('types', $this->f3->get('person_task_type'));
        $this->f3->set('page_head',$this->f3->get('i18n_persons'));
   	    $this->f3->set('view','person/list_person.htm');
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

    	public function prueba() {
    	    $person = new Person($this->db);
    	    $person_phone = '8139657955';
    	    
    	    $data = array();
    	        	    //die('entra');
    	    //$person->copyTo('data');
    	    $person->getByPersonPhone($person_phone);
    	    $person->copyTo('POST');
    	    $data['person_name'] = $person->person_name;
    	    //die($this->f3->get('POST.person_name'));
    	    die('entro'.'  '.$data['person_name']);
    	    //die('entro'.'  '.var_dump($data));
    	}

    	public function verify() {
	    $person = new Person($this->db);
	    $person1 = new Person($this->db);
            $person_phone = $this->f3->get('POST.person_phone');
            // Check if the CodHistoria already exists
 	        $exists = $person->existsByPersonPhone($person_phone);
			//$data = array();
			$data = array();
			$person1->getByPersonPhone($person_phone);
            $data['person_num'] = $person->person_num;			
			$data['nperson_phone'] = $person->person_phone;
            $data['person_name'] = $person->person_name;
            $data['person_lname'] = $person->person_lname;
            $data['person_dob'] = $person->person_dob;
            $data['person_gender'] = $person->person_gender;
            $data['person_maritals'] = $person->person_maritals;
            $data['person_addr'] = $person->person_addr;

			$data['csrf'] = $this->f3->get('SESSION.csrf');
            if ($exists) {	
				$data['message'] = $this->f3->get('i18n_existsphonenum');
				//$data['message'] = $data['person_name'];
				$data['exists'] = true;
				$data['person_phone'] = $person_phone;				
				// Ojo la respuesta para Ajax no funciona con return o echo, solo con die()
				die(json_encode($data));
            } else {			 
				$data['message'] = $this->f3->get('i18n_continuenewperson');
				$data['exists'] = false;
				die(json_encode($data));
            }
    	}

    public function addperson()
    {
        die('entro');
    }
    
    public function update()
    {
        $person_phone = $this->f3->get('PARAMS.person_phone');
	$this->f3->set('modal', '');
        $person = new Person($this->db);
        if($this->f3->exists('POST.update'))
        {
            //die('entro aqui'.'  '.$this->f3->get('POST.vperson_phone'));
         	$person->edit($this->f3->get('POST.vperson_phone'));
	 	$this->f3->reroute('/result/Persona Actualizada/success');
        } else{
           // die('entro'.'   '.$this->f3->get('PARAMS.person_phone'));
         	$person->getByPersonPhone($person_phone);
         	
         	$this->f3->set('person',$person);
        	$this->f3->set('age',$this->calculate_age($person->person_dob));
         	$this->f3->set('page_head','Editar persona');
         	$this->f3->set('view','person/update.htm');
        }
    }

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
		if($this->f3->exists('PARAMS.PersonPhone'))
		{
			$person = new Person($this->db);
			$task = new Task($this->db);
			$person_phone = $this->f3->get('PARAMS.PersonPhone');

			//if ($task->existsTaskNoteJoin($person_num)){
			if ($task->existsTask($person_phone, $this->f3->get('SESSION.project_id'))){
			    //die('entro si existe');
				$alertype= 'error';
				$message = $this->f3->get('i18n_cantdeletepersonhastasks');
			}else{
			    //die('entro no existe');
				if ($person->delete($this->f3->get('SESSION.project_id'), $person_phone)){
					$alertype= 'success';
					$message = $this->f3->get('i18n_persondeleted');
				}else{
					$alertype= 'error';
					$message = $this->f3->get('i18n_cantdeleteperson');
				}
			}
		}
		$this->f3->reroute('/result/'.$message.'/'.$alertype);
	}
	//*********************************************************************************************************************
	public function createperson()
	{
	if($this->f3->exists('POST.createperson'))
	{
	    
	    $projectperson = new ProjectPerson($this->db);
		    //die('entra');
		if ($this->f3->get('POST.nperson_phone') <> ''){
		    //die('entro'.'  '.$this->f3->get('POST.person_num'));
		    $person_num = $this->f3->get('POST.person_num');
		    $per_phone = $this->f3->get('POST.nperson_phone');
		    $project_id = $this->f3->get('SESSION.project_id');		    
		    //Entra aca si la persona existe en la BD
		    //Verificar si no existe en el proyecto, en cuyo caso lo agrega al proyecto. Si existe, solo regresa
		    if (!$projectperson->existsByPersonPhone($per_phone, $project_id)){
		        //die('entro'.'  '.$per_phone);
		        $projectperson->add($person_num, $per_phone, $project_id);
		    }
		    $this->f3->reroute('/person/listperson');
		}else{
		    $per_phone = $this->f3->get('POST.person_phone');
		  //  die('entra'.'  '.$this->f3->get('POST.person_phone'));
		    $project_id = $this->f3->get('SESSION.project_id');		    
		    //Entra aca si la persona no existe en la BD		    
		    //die ('entra'.$this->f3->get('POST.nperson_phone'));
			//$this->f3->set('nperson_phone', '');
			$person = new Person($this->db);
			//$person_added=$person->add($this->f3->get('POST'));
			$person->add($this->f3->get('POST'));
			//die('here');
			$projectperson->add($person->person_num, $per_phone, $project_id);
			$this->f3->reroute('/person/listperson');
			/*if($person_added==1)
			{			
				$person1 = new Person($this->db);
				$person1->getByPersonPhone($this->f3->get('POST.person_phone'));
				$token = $person1->person_num;
				$this->f3->set('alertType','success');
				//$this->f3->reroute('/person/listperson');
			}
			*/
		}
	}
	else
	{
		   // die('entra');
		    	$this->f3->set('person_num','');
		    	$this->f3->set('nperson_phone','');
		    	$this->f3->set('alertType','');
				$this->f3->set('message', '');
				$this->f3->set('view','person/create_person.htm');
	}		
		//-------------------------------- final
	/*	
		if($this->f3->exists('POST.createperson'))
		{
			$person = new Person($this->db);
			$person_added=$person->add($this->f3->get('POST'));
			if($person_added==1)
			{
				$person1 = new Person($this->db);
				$person1->getByPersonPhone($this->f3->get('POST.person_phone'));
				$token = $person1->person_num;
				$this->f3->set('alertType','success');
				$this->f3->reroute('/successPerson/Nueva Persona Registrada/' . $token. '/success');
			}
			else if($person_added==10) //paciente taken
			{
			    die('la persona ya existe');
				$this->f3->set('alertType','error');
				//$this->f3->set('message', $this->f3->get('ui.i18n_persontaken'));
				$this->f3->set('message', 'hola');
				$this->f3->set('view','person/create_person.htm');
			}
		} 
		else
		{
		    die('entro');
		    	$this->f3->set('person_num','');
		    	$this->f3->set('alertType','');
				$this->f3->set('message', '');
				$this->f3->set('view','person/create_person.htm');
		} */
		
		
	}
	//*********************************************************************************************************************
	
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


}