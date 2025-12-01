<?php

class UserController extends Controller {
    
    public function list_user()
    {
        $user = new User($this->db);    	
	    $q = 0;
	    $limit = 8;
	    $username = $this->f3->get('SESSION.username');
	    $this->f3->set('message', '');
        $this->f3->set('alertType', '');        
        $this->f3->set('page_head', '');
        $this->f3->set('project_id','');
	    if(($this->f3->get('PARAMS.UserId') != "") || ($this->f3->get('PARAMS.token') != ""))
	    {
	        // *** Aca entra cuando se pulsa el lapiz para invitar al usuario especifico, y aparece el Modal ***
	        //die('entra1'.$this->f3->get('PARAMS.ProjectId'));
	        if ($this->f3->get('PARAMS.UserId') != "")
	        {// ** Aca entre si se pulsa el icono de accion 'Agendar' **
	        // *** Aca entra inmediatamente despues de lo anterior cuando se pulsa el lapiz para invitar al usuario especifico, y aparece el Modal ***	        
	        //die('entra2');
		        $token = $this->f3->get('PARAMS.UserId');
		        $this->f3->set('modal', 'fromlistusr');

	        }else{
	            die('entra3');
		        //echo '<script>console.log($this->f3->get('PARAMS.PersonNum')); </script>';
		        $token = $this->f3->get('PARAMS.token');
		        $this->f3->set('modal', 'fromlistusr');
	        }
	        // Obtener los datos del usuario especifico
	        $selected_user = new User($this->db);
            $selected_user->getById($token);
		        //die('aqui entra'.$token);            
            $this->f3->set('token', $token);
            $this->f3->set('selected_user',$selected_user);
        }
           /* $user = new User($this->db);
            $users = $user->allButMyself($this->f3->get('SESSION.user_id'));
	        $total_count = ceil($user->count());*/

	    if($this->f3->exists('GET.q')){

	        die('entra4');
	        // Si se pulso el boton "Buscar" se enviara el formulario y debe existir q via GET
            $q = $this->f3->get('GET.q'); 
	        $users = $user->exctendSearch($username, $q, $limit);
        } else {
	        // *** Aca entra la primera vez cuando se pulsa el boton "+" para invitar colaboradores ***            
	        //die('entra5');            
	        // Si el formulario se cargo normalmente simplemente traer los primeros $limitLoad registros
	        // de la tabla users
            $users = $user->allButMyself($this->f3->get('SESSION.user_id'));
    	}	        
	    $total_count = ceil($user->count());	        
  	    $this->f3->set('users',$users);        
 
        //$this->f3->set('page_head',$this->f3->get('ui.i18n_projects'));
        //die('entra'.$this->f3->get('PARAMS.ProjectId'));        
        $this->f3->set('project_id',$this->f3->get('PARAMS.ProjectId'));
    	$this->f3->set('total_users',$total_count);        
   	    $this->f3->set('view','user/list_user.htm');        
    }


	public function confirm_registration()
	{
		$user = new User($this->db);
		$this->f3->set('POST.hash', '');
		$user->getByHash($this->f3->get('GET.h'));
//die('entra'.'   '.strcmp($this->f3->get('POST.hash'),$this->f3->get('GET.h')));
		if(strcmp($this->f3->get('POST.hash'),$this->f3->get('GET.h')) == 0)
		{
		    //die('aqui'.'   '.$this->f3->get('POST.id'));
		    //die('entra'.'   '.$this->id);
			$user->activate($this->f3->get('POST.id'));
		   // die('entro'.'  '.$this->f3->get('GET.h').'  '.$user->id);				    
			$this->f3->set('POST.registration_ok',true);
			$this->f3->set('view','user/confirm_registration.htm');
		}
		else 
		{ //check if account is already activated
		 die('entro0'.'  '.$this->f3->get('GET.h'));
			$user->checkActivatedHash($this->f3->get('GET.h'));
			if(strcmp($this->f3->get('POST.hash'),$this->f3->get('GET.h'))==0)
			{
			    //die('entro1');
				$this->f3->set('message',$this->f3->get('i18n_alreadyactivated') );
				$this->f3->set('page_head',$this->f3->get('i18n_registration'));
				$this->f3->set('view','page/message.htm');
			}
			else
			{
			    // die('entro2');
				$this->f3->set('message',$this->f3->get('i18n_reg_conf_failed') );
				$this->f3->set('page_head',$this->f3->get('i18n_registration'));
				$this->f3->set('view','page/message.htm');
			}
		}
	}

public function invite()
{
    
    $projectuser = new ProjectUser($this->db);
    $user = new User($this->db);
    $notif = new Notification($this->db);
    $username = $this->f3->get('SESSION.username');

    // Store the presence of POST variables in an associative array
    $permissions = [
        'create' => $this->f3->exists('POST.create'),
        'update' => $this->f3->exists('POST.update'),
        'delete' => $this->f3->exists('POST.delete'),
    ];

    // Calculate a unique role based on the combination of permissions
    $binaryString = ($permissions['create'] ? '1' : '0') .
                    ($permissions['update'] ? '1' : '0') .
                    ($permissions['delete'] ? '1' : '0');

    // Map the binary string to a role number
    $roles = [
        '111' => 2, // Create, Update, Delete
        '100' => 4, // Create only
        '010' => 5, // Update only
        '110' => 6, // Create and Update
        '001' => 7, // Delete only
        '101' => 8, // Create and Delete
        '011' => 9, // Update and Delete
    ];

    // Default role if none of the above match
    $rol = $roles[$binaryString] ?? 3;

    // Set the calculated role in POST
    $this->f3->set('POST.user_rol', $rol);
     
    // Invite user to the project
    $projectuser_added = $projectuser->inviteuser($this->f3->get('POST'));
    // Handle success or failure of the invitation
    if ($projectuser_added) {
        $apiKey = getenv('TEXTBELT_API_KEY');
        $smsNotifier = new SmsNotifier($apiKey);
        $userId = $this->f3->get('POST.collab_id');
        $user->getCollaborator($userId);

        //$this->f3->set('message', $this->f3->get('i18n_conf_mail_sent'));
        $phoneNumber = $user->phone;
        //$message = $username.' '.$this->f3->get('i18n_invited').'  '.$this->f3->get('SESSION.project_name');
        $message = "{$user->name}, {$username} {$this->f3->get('i18n_invited')} '{$this->f3->get('SESSION.project_name')}'.";
        $notif->add($userId, $message);
        // Send SMS notification
        
        try {
            $smsNotifier->sendSms($phoneNumber, $message);
            $this->f3->set('sms_message', 'Collaborator added and SMS notification sent successfully.');
        } catch (\Exception $e) {
            $this->f3->set('sms_message', 'Collaborator added, but SMS notification failed: ' . $e->getMessage());
        }

    } else {
        $this->f3->set('message', $this->f3->get('i18n_username_taken'));
    }

    // Redirect back to the project update page
    $this->f3->reroute('/project/update/' . $this->f3->get('POST.project_id'));
}

    public function terms()
    {
		$this->f3->set('view','user/terms.htm');        
    }


// ********************************************************************************
    public function updategrants()
    {
        $projectuser = new ProjectUser($this->db); 
        die('entro');
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
            die('EB');        
            $rol = 9;
            break;         
        default:
            $rol = 3;
            break;
        }
        die('hey'.$this->f3->get('POST.project_id'));
        $this->f3->set('POST.user_rol', $rol);
        $projectuser_updated=$projectuser->updategrants($this->f3->get('POST'));
        if($projectuser_updated)
        {
            $this->f3->set('message', $this->f3->get('i18n_conf_mail_sent'));
        }else{ //user taken{
	        $this->f3->set('message', $this->f3->get('i18n_username_taken'));
        }
        die('entro'.$this->f3->get('POST.project_id'));
    	$this->f3->reroute('/project/update/'.$this->f3->get('POST.project_id'));
    }
// ***********************************************************************************

	
	public function checkUserExists() {
        // Get phone number from POST request
        $phoneNumber = $this->f3->get('POST.phone');

        // Query the database to check if the user exists
        $person = new Person($this->db);
        if ($exists = $person->existsByPersonPhone($phoneNumber)){
        // Prepare response
                $response = $exists;
		        die(json_encode($response));
        }else{
                $response = $notexists;
		        die(json_encode($response));  
        }
    }
	
	
	public function update_registration()
	{
		// first activation posted
		$user = new User($this->db);
		$sessionlogin = false;
		
		$this->f3->set('POST.activado',1); 
		
		$user->edit($this->f3->get('POST.id'));

		$this->f3->copy('POST','SESSION');
		$this->f3->set('SESSION.login_message',$this->f3->get('i18n_reg_update_success') );
		$this->f3->reroute('/login');		
	}
	
	public function pw_reset()
	{
		$user = new User($this->db);
		$user->checkActivatedHash($this->f3->get('GET.h'));
		$this->f3->set('SESSION.user_id',$this->f3->get('POST.id'));
		if($this->f3->exists('POST.reset_pw')){

			$pwcheck = $this->check_password( $this->f3->get('POST.new_password') , $this->f3->get('POST.confirm'));

			if (strlen($pwcheck) > 0) //pwcheck error message returned
			{
				$this->f3->set('message', $pwcheck);
				$this->f3->set('view','user/change-pw.htm');
			}
			else{
				if($this->setpw( $this->f3->get('POST.new_password'), $this->f3->get('POST.id')))
				{
					$this->f3->reroute('/');
				}
				else{
					$this->f3->error(403);
				}
			}
		}
		else if(strcmp($this->f3->get('POST.hash'),$this->f3->get('GET.h'))===0)
		{
			$this->f3->set('view','user/change-pw.htm');
		}
		else 
		{
			$this->f3->set('page_head',$this->f3->get('i18n_error'));
			$this->f3->set('message',$this->f3->get('i18n_register_oops') );
			$this->f3->set('view','page/message.htm');
		}
	}
	
	private function setpw( $newpw, $id )
	{
	    //die('entro'.'   '.$newpw.'   '.$this->f3->get('SESSION.user_id'));
		$user = new User($this->db);
		$user->getById($id);
		
		$password = password_hash($newpw, PASSWORD_BCRYPT);
		
		//check if user id = session id for security
		if($id == $this->f3->get('SESSION.user_id'))
		{				
			$this->f3->set('POST.password', $password);
			$user->edit($id, $this->f3->get('POST'));
			return true;
		}
		else { 
			return false;
		}
	}
	
	public function edit_registration()
	{
		if($this->f3->VERB==="POST")
		{
			$user_id=$this->f3->get('SESSION.id');
			if($this->f3->get('POST.id') == $id)
			{
				if(null!==$this->f3->get('POST.password'))
				{
					$passwordcheck = $this->check_password($this->f3->get('POST.password'), $this->f3->get('POST.confirm'));
					if( $passwordcheck==="" && $this->setpw( $this->f3->get('POST.password'), $this->f3->get('POST.user_id')) )
					{
						$this->f3->set('alert_type',"success");
						$this->f3->set('message',$this->f3->get('ui.i18n_password_changed'));
					}
					else
					{
						$this->f3->set('alert_type',"danger");
						$this->f3->set('message',$passwordcheck);
					}
				}
				
				$user = new User($this->db);
				$user->getById($user_id);
				$user->edit($user_id, $this->f3->get('POST'));
				$this->f3->set('SESSION.logged_in', 1);
				$user->login($user->id);
			}
			else { 
				$this->f3->error(403);
			}
		}
		$this->f3->copy('SESSION','POST');
		
		$this->f3->set('view','user/editregistration.htm');	
	}
	
	public function success()
	{
		$this->f3->set('view','user/success.htm');
	}
	
	public function sendactmail($email, $hash)
	{
		$confirmation_link = $this->f3->get('SCHEME')."://".$this->f3->get('HOST').$this->f3->get('BASE')."/confirm_registration?h=".$hash;
		$mail = new Mail();
		$mail->send( // sender, recipient, subject, msg
			$this->f3->get('from_email') , 
			$email, 
			$this->f3->get('i18n_confirmation_mail_subject') . " " . $this->f3->get('HOST'),
			$this->f3->get('i18n_confirmation_mail_message')."<a href=\"".$confirmation_link."\">".$confirmation_link . "</a>"
		);
	}

	private function pw_reset_mail($email, $hash)
	{
		$confirmation_link = $this->f3->get('SCHEME')."://".$this->f3->get('HOST').$this->f3->get('BASE')."/pw_reset?h=".$hash;
		$mail = new Mail();
		$mail->send( // sender, recipient, subject, msg
			$this->f3->get('from_email') , 
			$email, 
			$this->f3->get('i18n_confirmation_mail_subject') . " " . $this->f3->get('HOST'),
			$this->f3->get('i18n_reset_pw_mail_message')."<a href=\"".$confirmation_link."\">".$confirmation_link . "</a>"
		);
		
	}
	
	public function sendactivationmail()
	{
		if($this->f3->exists('POST.sendmail'))
		{
			$hash=$this->createHash();
			$user = new User($this->db);
			$user->getByEmail($this->f3->get('POST.email'));
			//die('entro'.'   '.$user->id);
			$this->f3->set('POST.hash', $hash);
			$user->edit($user->id, $this->f3->get('POST'));			
			$this->sendactmail($this->f3->get('POST.email'), $hash);
			$this->f3->set('page_head',$this->f3->get('ui.i18n_registration'));
			$this->f3->set('message', $this->f3->get('ui.i18n_conf_mail_sent'));
			$this->f3->set('view','page/message.htm');
		}
		else
		{
			$this->f3->set('view','user/send_activation_mail.htm');
		}
	}
	
	private function check_password($pw, $confirm)
	{
		if(strlen($pw) < 8)
		{
			return $this->f3->get('ui.i18n_password_too_short');
		}
		else if($pw !== $confirm)
		{
			return $this->f3->get('ui.i18n_user_wrong_confirm');
		}
		else 
		{
			return "";
		}		
	}

	public function create()
	{
		if($this->f3->exists('POST.create'))
		{
			$pwcheck = $this->check_password( $this->f3->get('POST.password'), $this->f3->get('POST.confirm'));
			if (strlen($pwcheck) > 0)
			{ 
				$this->f3->set('message', $pwcheck);
				$this->f3->set('view','user/create.htm');
			}
			else{
				$password = password_hash($this->f3->get('POST.password'), PASSWORD_BCRYPT);
				$this->f3->set('POST.password', $password);
			
				$hash = $this->createHash();
				$this->f3->set('POST.hash', $hash);
				$user = new User($this->db);
				$user_added=$user->add($this->f3->get('POST'));
				if($user_added==1)
				{
				    // si no esta logueado se enviara el mensaje por correo. Si esta logueado y puede acceder 
				    // a crear usuario, es un administrador y no se requiere el correo
		            if( !$this->f3->get('SESSION.logged_in'))
		            {				    
					    $this->sendactmail($this->f3->get('POST.email'), $hash);
		            }
					$this->f3->set('page_head',$this->f3->get('i18n_registration'));
					$this->f3->set('message', $this->f3->get('i18n_conf_mail_sent'));
					$this->f3->set('view','page/message.htm');
				}
				else if($user_added==10) //user taken
				{
					$this->f3->set('message', $this->f3->get('i18n_username_taken'));
					$this->f3->set('view','user/create.htm');
				}
				else if($user_added==11) //email taken
				{
					if($user->activado==0)
					{
						$this->f3->set('message', $this->f3->get('i18n_not_activated'));	
					}
					else
					{
						$this->f3->set('message', $this->f3->get('i18n_email_taken'));						
					}
					$this->f3->set('view','user/create.htm');
				}
			}
		} 
		else
		{
			$this->f3->set('view','user/create.htm');
		}
	}

	public function login()
	{
		// No estoy seguro el efecto de la proxima linea, ya que si estoy logged in al forzar un ruteo a una vista especifica
		// se dirijira a dicha vista. Revisar esto con detenimiento para proxima version
		if( $this->f3->get('SESSION.logged_in'))
		{
			$this->f3->reroute('/home');
		}
		else if($this->f3->exists('POST.login')) // OR $this->f3->VERB=='POST'
		{
		    // **** Aca entra cuando se pulsa el boton ingresar   ***
            // Retrieve the selected language from the form data
            $selectedLanguage = $this->f3->get('POST.language');
          
            // Validate the selected language (optional but recommended)
            if (in_array($selectedLanguage, ['en', 'sp'])) {
                // Set the selected language in session
                $this->f3->set('SESSION.language', $selectedLanguage);
            } else {
                // Default to English if validation fails
                $this->f3->set('SESSION.language', 'en');
            }		    

			$ip = $_SERVER['REMOTE_ADDR'];
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}

			$user_id="not logged in";
			
			$user = new User($this->db);

			$user->getByUsername( $this->f3->get('POST.username') );

			if($user->dry() || ! password_verify($this->f3->get('POST.password'), $user->password))
			{
				// OJO: La siguiente linea la estoy comentando porque no funciona en infinityfree.
				// Para proxima version debo saber como incluir registro de log en el servidor
				// *****************************************
				$this->f3->logger->write( "LOG IN: ".$this->f3->get('POST.username')." login fallido (ip: " .$ip .")",'r'  );

				sleep(2);

				$this->f3->set('message', $this->f3->get('i18n_wrong_login'));
				$this->f3->set('page_head','Login');
                 		$this->f3->set('view','user/signin.htm');
			}
			else if ($user->activated===0)
			{
			    // ********************************************
				$this->f3->logger->write( "LOG IN: ".$this->f3->get('POST.username')." no activado (ip: " .$ip .")",'r'  );
				$this->f3->set('message',  $this->f3->get('i18n_not_activated'));
				$this->f3->set('page_head','Login');
                 		$this->f3->set('view','user/signin.htm');
			}
			else 
			{
			    // *** Continuacion de proceso de Ingreso/ login   ******

				$this->f3->set('SESSION.id', $user->id);
				$user->login($user->id);
				/* 
				* La siguiente linea de registro de LOG no esta funcionando en infinityfree. Debo comentarla 
				* cada vez que transfiera estos archivos al servidor de infinityfree.
				*/
				// **********************************************
				$this->f3->logger->write( "LOG IN: ".$this->f3->get('POST.username')." login exitoso (ip: " .$ip .")",'r'  );
				$this->f3->set('SESSION.logged_in', 'true');
				$this->f3->set('SESSION.timestamp', time());
                
                // Aca se hace la consulta de los datos de usuario. Esta consulta debe incluir ahora los settings
                // por lo que debo ejecutar un JOIN de users con setting
				//$user->getByUsername( $this->f3->get('SESSION.username') );
				
				
				// ************ NOTA ***************************
				// Aca debo tratar de que el resultado del Join al traerlo aca me permita usar la notacion -> para continuar con el esquema que tenia
				// y no tener que cambiarlo todo (De ser posible).
				$result = $user->getByUsernameJoinSetting( $this->f3->get('SESSION.username') );

				//$user->getByUsername( $this->f3->get('SESSION.username') );
				//if (!$user->dry()){
				
				if ($result){
				    $project = new Project($this->db);
				    $notif = new Notification($this->db);
				    //die('entro'.$result['favorite_project']);
				/*	$this->f3->set('filename',$user->userpicture);
					$this->f3->set('SESSION.filename', $user->userpicture);
					$this->f3->set('SESSION.username', $this->f3->get('POST.username'));
					$this->f3->set('SESSION.nombre', $user->name);
					$this->f3->set('SESSION.apellido', $user->lname);
					$this->f3->set('SESSION.emailusuario', $user->email); */
					$this->f3->set('SESSION.user_id', $result['id']);					
					$this->f3->set('filename',$result['userpicture']);
					$this->f3->set('SESSION.filename', $result['userpicture']);
					
					$this->f3->set('SESSION.unreadNotif', $notif->getUnreadCount($user->id));
					
					$this->f3->set('SESSION.username', $this->f3->get('POST.username'));
					$this->f3->set('SESSION.nombre', $result['name']);
					$this->f3->set('SESSION.apellido', $result['lname']);
					$this->f3->set('SESSION.emailusuario',$result['email']);
					$this->f3->set('SESSION.sitelang',$result['language']);	
					//die('dict/' . $this->f3->get('SESSION.sitelang') . '.php');
				//	$this->f3->set('LANGUAGE',$result['language']);	
				
                    $preferred_language = $result['language'];
                    $this->f3->set('SESSION.language', $preferred_language);
                    // Optionally set a cookie for the preferred language
                    setcookie('lang', $preferred_language, time() + 3600 * 24 * 30, '/'); 				
				
				//	$this->f3->config('dict/' . $this->f3->get('SESSION.sitelang') . '.php');
					
					
					//$this->f3->set('sitelang', $result['language']);
					//die($result['language']);
					
					$this->f3->set('SESSION.favorite_project',$result['favorite_project']);
					$project->getById($result['favorite_project']);
					if ($result['favorite_project'] != 0)
					{
					    $current_project = $project->getJoinById($result['favorite_project'], $result['id']);
					    $this->f3->set('SESSION.project_name',$current_project['project_name']);
					    $this->f3->set('SESSION.project_id',$current_project['project_id']);
					    $this->f3->set('SESSION.project_num',$current_project['project_num']);
					    $this->f3->set('SESSION.project_type',$current_project['project_type']);					    
					    $this->f3->set('SESSION.project_status',$current_project['project_status']);					    
					    $this->f3->set('SESSION.user_rol',$current_project['user_rol']);					    
					}else{
					    $this->f3->set('SESSION.project_name', '');
					    $this->f3->set('SESSION.project_id', 0);
					    $this->f3->set('SESSION.user_rol', 0);					    
					}

				}
				//$this->f3->reroute('/cita/listcita');
				    // Pass the data to the view

				$this->f3->reroute('/page/homepage');
			}
		} 
		else
		{
		    // Aca entra cuando hace logout
			$this->f3->set('page_head','Login');
            $this->f3->set('view','user/signin.htm');
		}
	}
	
	public function logout()
	{
	    // Guardar ultimo Proyecto activo como preferido en 'setting' 
		$seti = new Csetting($this->db);
		$seti->edit($this->f3->get('SESSION.user_id'), $this->f3->get('SESSION.project_id'));
		$this->f3->clear('SESSION');
		$this->f3->set('page_head','Logout');
		$this->f3->reroute('/');
	}

	public function profile()
	{
	    $this->f3->set('alertType', "");
		$this->f3->set('message', "");
		$user = new User($this->db);
		$tuser = new User($this->db);		
		//$id = $this->f3->get('PARAMS.id'); 
		$username = $this->f3->get('SESSION.username'); 
		//die($username);
		if($this->f3->exists('POST.edit'))
		{
			//die($this->f3->get('POST.language'));
			//$users = new User($this->db);
			$tuser->getByUsername($username);
			$user_id = $tuser->id;
		    $setting = new Csetting($this->db);			
			$lang = $this->f3->get('POST.language');
			$pw = $this->f3->get('POST.password');
			$setting->updateLanguage($user_id, $lang);
			if(strlen($pw)===0)
			{ //do not change password, reset to hash in database
				//$this->f3->set('POST.password',$this->f3->get('POST.pw'));
			}
			else
			{
				$pwcheck = $this->check_password( $pw , $this->f3->get('POST.confirm'));
				if (strlen($pwcheck) > 0)
				{
					$this->f3->set('alertType', "fail");
					$this->f3->set('message', $pwcheck);
				}
				else
				{
					// 2 formas de guardar el password: 1.- Encriptado sin guardar el hash 2.-Encriptado guardando tambien el hash
					// 1.-
					//$crypt = \Bcrypt::instance();
					//$password = $crypt->hash($this->f3->get('POST.password'));
					// 2.-
					$password = password_hash($this->f3->get('POST.password'), PASSWORD_BCRYPT);
					//$this->f3->set('POST.password', $password);
					$hash = $this->createHash();
					//$this->f3->set('POST.hash', $hash);

					$user->updatepwdByUsername($username, $password, $hash);
					$this->f3->set('alertType', "success");
					$this->f3->set('message', "Contrasena cambiada");
					$this->f3->set('POST.password', $password);
				}
			}

		}
			
		$user->getByUsername( $this->f3->get('SESSION.username') );
		if (!$user->dry()){
			$this->f3->set('filename',$user->userpicture);
			$this->f3->set('SESSION.filename', $user->userpicture);
		}
		$this->f3->set('page_head','Perfil');
        $this->f3->set('view','user/profile.htm');

	}

	public function loadphoto(){
		$user = new User($this->db);
		if($this->f3->exists('POST.update')) 
	   {
				$filename = $this->f3->get('FILES.uploadfile.name');
			    $tempname = $this->f3->get('FILES.uploadfile.tmp_name');
			    $folder = 'ui/img/' . $filename;
			    $this->f3->set('POST.userpicture', $folder);
			    // Mover el archivo desde el cliente al servidor web
			    if (move_uploaded_file($tempname, $folder)) {
				   $message = ' and uploaded successfully moved to server!';
			    } else {
				   $message = ' and uploaded failed to move to server!';
			    }
				if ($user->saveFoto($this->f3->get('SESSION.username'), $folder ))
			    {
				   $this->f3->set('message', 'Image uploaded successfully!');
			    } else {
				   $this->f3->set('message', 'Failed to upload image!');
			    }
			    $this->f3->set('filename',$folder);
	   }
	   $this->f3->set('page_head','Perfil');
	   //$this->f3->set('view','user/profile.htm');
	   $this->f3->reroute('/user/profile');
	}

	public function update()
	{
		$user = new User($this->db);

		if($this->f3->exists('POST.update'))
		{
			$user->edit($this->f3->get('POST.id'));
			$this->f3->reroute('/success/User Updated');
		} 
		else
		{
			$user->getById($this->f3->get('PARAMS.id'));
			$this->f3->set('user',$user);
			$this->f3->set('page_head',$this->f3->get('i18n_changepassword'));
			$this->f3->set('view','admin/update.htm');
		}
	}

	public function lostpassword()
	{
		if($this->f3->exists('POST.reset_pw'))
		{
			$hash=$this->createHash();
			$user = new User($this->db);
			$user->getByEmail($this->f3->get('POST.email'));
			if(! $user->dry()){
				$this->f3->set('POST.hash', $hash);
				$user->edit($user->id, $this->f3->get('POST'));
				$this->pw_reset_mail($this->f3->get('POST.email'), $hash);
			}
			$this->f3->set('page_head', $this->f3->get('i18n_new_password_request_header'));
			$this->f3->set('message', $this->f3->get('i18n_new_password_request'));
			$this->f3->set('view','page/message.htm');
		} 
		else
		{
			$this->f3->set('view','user/reset-pw.htm');
		}
	}

	private function createHash()
	{ //this should be somewhat unpredictible 
		return md5( str_shuffle(time(). $this->f3->get('POST.username') . $this->f3->get('POST.emailusuario') ) );
	}

	public function delete()
	{
		if($this->f3->exists('PARAMS.id'))
		{
			$user = new User($this->db);
			$user->delete($this->f3->get('PARAMS.id'));
		}
		$this->f3->reroute('/success/Usuario eliminado');
	}

    public function deletenotif()
    {
        $data = array();
        $data['success'] = true;
        $notif = new Notification($this->db);
        $notif_id = $this->f3->get('POST.notif_id');
        if ($notif->deleteNotif($notif_id)){
            //echo json_encode(['success' => true]);
            die(json_encode($data));
        }else{
            die(json_encode(["success" => false, "error" => "Missing parameters"]));
            exit;
        }
    }

	private function updatepwd()
	{
	$id = $this->f3->get('PARAMS.id'); 
	if($this->f3->exists('POST.edit'))
	{
		$users = new User($this->db);
		$pw = $this->f3->get('POST.password');
		if(strlen($pw)===0)
		{ //do not change password, reset to hash in database
			$this->f3->set('POST.password',$this->f3->get('POST.pw'));
		}
		else
		{
			$pwcheck = $this->check_password( $pw , $this->f3->get('POST.confirm'));
			if (strlen($pwcheck) > 0)
			{
				$this->f3->set('alertType', "fail");
				$this->f3->set('message', $pwcheck);
			}
			else
			{
				$crypt = \Bcrypt::instance();
				$password = $crypt->hash($this->f3->get('POST.password'));
				$this->f3->set('alertType', "success");
				$this->f3->set('message', "Contrasena cambiada");
				$this->f3->set('POST.password', $password);
			}
		}
		// actualizar status de usuario
		//$this->f3->set('message', "Estado de la activacion actualizada.");
		//$this->f3->set('alertType', "success");
		$users->updateactivate($id,$this->f3->get('POST.activated'));
		$users->edit($id, $this->f3->get('POST'));
	}
	//else
	//{
		$users = new User($this->db);
		$users->getById($id);

		if($users->dry()) { //throw a 404, order does not exist
			$this->f3->error(404);
		}
	//}

	$this->f3->set('view','admin/userdetails.htm');
}

	public function notifications()
	{
	    $notif = new Notification($this->db);
	    $notifications = $notif->allById($this->f3->get('SESSION.user_id'));
	    $this->f3->set('notifications', $notifications);
		$this->f3->set('view','user/notifications.htm');
	}

//*********************************************************************************************************************
	public function createperson()
	{
		if($this->f3->exists('POST.create'))
		{
				$person = new Person($this->db);
				$person_added=$person->add($this->f3->get('POST'));
				if($person_added==1)
				{
					$this->f3->set('page_head',$this->f3->get('i18n_registrolead'));
					$this->f3->set('message', $this->f3->get('i18n_confreglead'));
					$this->f3->set('view','page/message.htm');
				}
				else if($person_added==10) //user taken
				{
					$this->f3->set('message', $this->f3->get('i18n_persontaken'));
					$this->f3->set('view','user/create_person.htm');
				}
		} 
		else
		{
			$this->f3->set('view','user/create_person.htm');
		} 
	}

}