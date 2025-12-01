<?php

class AdminController extends Controller {
	//protected $f3;
	//protected $db;
		
	public function users()
	{
        $this->f3->set('page_head', $this->f3->get('i18n_users'));
		$this->f3->set('alertType', "");
		$this->f3->set('message', '');        
        $users = new User($this->db);
		$this->f3->set('users',$users->all());
		$this->f3->set('view','admin/users.htm');
	}

	public function compactUsers()
	{	
        $users = new User($this->db);
        if ($users->compactUsers() == 1){
		    $this->f3->set('alertType', "success");
		    $this->f3->set('message', $this->f3->get('i18n_users_already_compacted'));
        }else if ($users->compactUsers() == 10){
		    $this->f3->set('alertType', "success");
		    $this->f3->set('message', $this->f3->get('i18n_users_compacted'));  
            }else{
		    $this->f3->set('alertType', "fail");
		    $this->f3->set('message', $this->f3->get('i18n_users_not_compacted'));                
        }
        $this->f3->set('page_head', $this->f3->get('i18n_users'));
        $users1 = new User($this->db);
		$this->f3->set('users',$users1->all());
		$this->f3->set('view','admin/users.htm');
	}

	public function compactTableForm()
	{
	    $this->f3->set('view','admin/compact_table.htm');   
	}
	public function compactTable()
	{	
	    $table = $this->f3->get('POST.tableName');
	    $primaryKey = $this->f3->get('POST.primaryKey');
        $admin = new Admin($this->db);
        $result = $admin->compactTable($table, $primaryKey);
        if ($result == 1){
		    $this->f3->set('alertType', "fail");
		    $this->f3->set('message', 'Table dos not exist');
        }else if ($result == 2){
		    $this->f3->set('alertType', "fail");
		    $this->f3->set('message', 'Primary key does not exist');  
            }else{
                if($result == 10){
                    $this->f3->set('alertType', 'success');
		            $this->f3->set('message', $this->f3->get('i18n_table_already_compacted'));  
                }else{
                    if($result == 20){
                        $this->f3->set('alertType', 'success');
		                $this->f3->set('message', $this->f3->get('i18n_table_compacted'));  
                    }else{                      
		                $this->f3->set('alertType', 'fail');
		                $this->f3->set('message', $this->f3->get('i18n_table_not_compacted'));
                    }
                }
        }
        $this->f3->set('page_head', $this->f3->get('i18n_users'));
        $users = new User($this->db);
		$this->f3->set('users',$users->all());
		$this->f3->set('view','admin/users.htm');
	}
	
	public function openai()
	{	
		$this->f3->set('completion','');
		$this->f3->set('view','admin/openai_form.htm');
	}
	
	public function chat()
	{	
	   // die('entra');	    
		$this->f3->set('completion','');
		$this->f3->set('view','admin/chat.htm');
	}	
	
	private function check_password($pw, $confirm)
	{
		if(strlen($pw) < 8)
		{
			return $this->f3->get('i18n_password_too_short');
		}
		else if($pw != $confirm)
		{
			return $this->f3->get('i18n_user_wrong_confirm');
		}
		else 
		{
			return "";
		}		
	}
	
	public function show_user() 
	{
		$codusuario = $this->f3->get('PARAMS.codusuario'); 
		//$this->f3->set('alertType', "");
		//$this->f3->set('message', "");
		if($this->f3->exists('POST.edit'))
        {
			$users = new User($this->db);
			$pw = $this->f3->get('POST.claveusuario');
			if(strlen($pw)===0)
			{ //do not change password, reset to hash in database
				$this->f3->set('POST.claveusuario',$this->f3->get('POST.pw'));
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
					//$crypt = \Bcrypt::instance();
					//$password = $crypt->hash($this->f3->get('POST.claveusuario'));
					$password = password_hash($this->f3->get('POST.claveusuario'), PASSWORD_BCRYPT);
					//$this->f3->set('POST.claveusuario', $password);
					$hash = $this->createHash();
					//$this->f3->set('POST.hash', $hash);
					$users->updatepwd($codusuario, $password, $hash);
					$this->f3->set('alertType', "success");
					$this->f3->set('message', "Contrasena cambiada");
					$this->f3->set('POST.claveusuario', $password);
				}
			}
			// actualizar status de usuario
			//$this->f3->set('message', "Estado de la activacion actualizada.");
			//$this->f3->set('alertType', "success");
			$users->updateactivate($codusuario,$this->f3->get('POST.activated'));
			$users->edit($id, $this->f3->get('POST'));
		}
		//else
		//{
			$users = new User($this->db);
			$users->getById($codusuario);

			if($users->dry()) { //throw a 404, order does not exist
				$this->f3->error(404);
			}
		//}

		$this->f3->set('view','admin/userdetails.htm');
	}

	private function createHash()
	{ //this should be somewhat unpredictible 
		return md5( str_shuffle(time(). $this->f3->get('POST.nomusuario') . $this->f3->get('POST.emailusuario') ) );
	}
	
	public function projectBackup()
	{
	    $admin = new User($this->db);
        // Fetch projects related to the given user_id
        $user_id = $this->f3->get('SESSION.user_id');
        $projects = $admin->backupProject($user_id);
        $output = fopen("users.csv","w");
        foreach ($projects as $project) {
            fputcsv($output, array($project['id'], $project['username'], $project['email']));
        }
        fclose($output);
        $file_path = 'users.csv';
        // Check if the file exists
        if (file_exists($file_path)) {
            // Set headers to force download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Content-Length: ' . filesize($file_path));
            // Read the file and output its contents
            readfile($file_path);
            // Exit to prevent further output
            exit;
        } else {
            // File not found, display an error message or redirect as needed
            echo 'File not found';
        }
		$this->f3->reroute('/page/homepage');
	}

    public function backupDb() {
        $backupFile = 'backup.sql';
        if ($this->backupDatabase($backupFile)) {
            echo "Database backup successful.";
        } else {
            echo "Database backup failed.";
        }
        $this->f3->reroute('/page/homepage');
    }

    public function backupDatabase($outputFile) {
        // Database credentials
        $dbHost = $this->f3->get('db_dns_short');
        $dbUser = $this->f3->get('db_user');
        $dbPass = $this->f3->get('db_pass');
        $dbName = $this->f3->get('db_name');
        $dbTable = 'users';
        // Create backup command
        $command = "mysqldump -u {$dbUser} -p{$dbPass} {$dbName} {$dbTable} --where='id > 5 AND lname = \"Ortiz\"' > {$outputFile}";
        // Execute backup command
        exec($command, $output, $returnCode);
        if ($returnCode === 0) {
            return true; // Backup successful
        } else {
            return false; // Backup failed
        }
    }
    
//function updateI18nStrings($directory) {
function updateI18nStrings() {
    // Usage: Specify the root directory of your web application
    //$rootDirectory = __DIR__; // Or the path to your web app's directory
    //updateI18nStrings($rootDirectory);    
    $directory = 'app';
    
    // Recursively scan the directory for all files
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    
    foreach ($files as $file) {
        // Skip directories
        if ($file->isDir()) {
            continue;
        }
        
        // Only process specific file types (e.g., .php, .html)
        $filePath = $file->getPathname();
        //if (!preg_match('/\.(php|html|js)$/', $filePath)) {
        if (!preg_match('/\.(txt)$/', $filePath)) {
            continue;
        }

        // Read the file content
        $content = file_get_contents($filePath);

        // Replace all occurrences of "i18n.ui.i18n_" with "i18n.ui.i18n.ui.i18n_"
        //$updatedContent = preg_replace('/\bi18n_/', 'i18n.ui.i18n.ui.i18n_', $content);
        $updatedContent = preg_replace('/\bi18n\.ui\.i18n_/', 'i18n_', $content);

        // If changes were made, write back to the file
        if ($content !== $updatedContent) {
            file_put_contents($filePath, $updatedContent);
            //echo "Updated: $filePath\n";
			$this->f3->set('alertType', "success");
			$this->f3->set('message', "Updated: $filePath\n");            
        }
    }
    $this->f3->reroute('/page/homepage');
}


}