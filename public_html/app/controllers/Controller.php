<?php

class Controller {

	protected $f3;
	protected $db;

    function beforeroute() {
        
        //die('entro');
        // Usar var_dump($var);   para testear contenido de variables
        // Set the language based on the session or default
        $session = $this->f3->get('SESSION');
        $language = $session['language'] ?? $this->f3->get('LANGUAGE');
        $this->f3->set('LANGUAGE', $language);

        $this->f3->set('taskFrequencies', '');

        if (!empty($session['logged_in'])) {
            if ($this->isSessionExpired($session)) {
                $this->handleLogout();
            } else {
                $this->f3->set('SESSION.timestamp', time());
            }
        }
        
        $this->f3->set('CSRF', '');

/*
// CSRF protection
$csrfToken = $this->f3->get('POST.session_csrf');
$sessionCsrfToken = $this->f3->get('MYSESSION.csrf');

// If no session token exists, initialize it
if (!$sessionCsrfToken) {
    $newCsrfToken = bin2hex(random_bytes(32)); // Generate a secure random token
    $newCsrfToken = $this->f3->session->csrf();
    $this->f3->set('MYSESSION.csrf', $newCsrfToken);
    $this->f3->set('CSRF', $newCsrfToken); // Make it accessible for templates
}

// Validate CSRF token for POST requests
if ($this->f3->VERB === 'POST') {
    die('entro'.'    '.$csrfToken.'    '.$sessionCsrfToken.'    '.$newCsrfToken);
    if (!$csrfToken || $csrfToken !== $sessionCsrfToken) {
        $this->f3->error(403, 'Invalid CSRF token');
    }

    // Regenerate token after successful validation for better security
    $newCsrfToken = bin2hex(random_bytes(32));
    $this->f3->set('MYSESSION.csrf', $newCsrfToken);
    $this->f3->set('CSRF', $newCsrfToken);
}
*/


	// Access control policy setup
    $access = Access::instance();
    $access->policy(strtolower($this->f3->get('ACCESS.policy')));

    $rules = $this->f3->get('ACCESS.rules');
    if (is_array($rules)) {
        foreach ($rules as $rule => $value) {
            list($action, $path) = explode(' ', $rule, 2);
            $roles = array_map('trim', explode(',', $value));

            if (strtoupper($action) === 'ALLOW') {
                foreach ($roles as $role) {
                    $access->allow($path, $role);
                }
            } elseif (strtoupper($action) === 'DENY') {
                $access->deny($path);
            }
        }
    }

    $userType = $session['user_type'] ?? 0;
    $access->authorize($userType);



    }

	function afterroute() {
		if($this->f3->get('SESSION.logged_in'))
		{
			echo Template::instance()->render('layout.htm');
		}else{
			//Este layout es especificamente para la vista inicial de Login
			echo Template::instance()->render('layout1.htm');
		}
	}

	function __construct() {
		$f3=Base::instance();
        // La siguiente es una forma segura de almacenar informacion sensible de la appa, en variables de ambiente
        //fuera del directorio raiz de la app. Se ubica fuera de public_html (en servidor Hostinger) variables.env
        $db = new DB\SQL(
            getenv('DB_HOST') . getenv('DB_NAME'),
            getenv('DB_USER'),
            getenv('DB_PASS')
        );
		$this->f3=$f3;
		$this->db=$db;
		
	}


    private function updateSessionTimestamp()
    {
        $currentTime = time();
        $lastActivity = $this->f3->get('SESSION.last_activity') ?? 0;
        $timeout = 900; // 15 minutes in seconds
        if (($currentTime - $lastActivity) > $timeout) {
            //die('expired');
            // Session expired due to inactivity
            //$this->handleSessionTimeout();
            $this->handleLogout();
        } else {
            die('aqui');
            // Update timestamp
            $this->f3->set('SESSION.last_activity', $currentTime);
        }
    }
// **********************************************************************************************

    private function isSessionExpired($session) {
        $currentTime = time();
        $lastTimestamp = $session['timestamp'] ?? 0;
        $autoLogoutTime = $this->f3->get('auto_logout');
        return ($currentTime - $lastTimestamp > $autoLogoutTime);
    }

    private function handleLogout() {
        //$settings = new Csetting($this->db);
        //$settings->edit($this->f3->get('SESSION.user_id'), $this->f3->get('SESSION.project_id'));
        $this->f3->clear('SESSION');
        $this->f3->reroute('/login');
    }	

    protected function hasPermission($action, $userRole)
    {
        // obtener tabla de permisologia del archivo config.ini
        $permissions = $this->f3->get('permissions');
        // Check if user's role exists in the permissions config
        if (!isset($permissions[$userRole])) {
            return false;
        }
        // Convert the permission string into an array
        $allowedActions = explode(',', $permissions[$userRole]);
        // Check if the required action is within the allowed actions for this role
        return in_array($action, $allowedActions);
    }
    
    protected function hasPermissionCollab($action, $userRole)
    {
        // obtener tabla de permisologia del archivo config.ini
        $permissions = $this->f3->get('permissions_tbl');
        // Check if user's role exists in the permissions config
        if (!isset($permissions[$userRole])) {
            return false;
        }
        // Convert the permission string into an array
        $allowedActions = explode(',', $permissions[$userRole]);
        // Check if the required action is within the allowed actions for this role
        return in_array($action, $allowedActions);
    }
    
protected function getStatusName($statusId)
{
    // Retrieve the project status mapping from the config file
    $statusMapping = $this->f3->get('project_status');
    
    // Return the corresponding status name or "Unknown" if the status ID is invalid
    return $statusMapping[$statusId] ?? 'Unknown';
} 

protected function getTaskStatusName($statusId)
{
    // Retrieve the project status mapping from the config file
    $statusMapping = $this->f3->get('task_status');
    
    // Return the corresponding status name or "Unknown" if the status ID is invalid
    return $statusMapping[$statusId] ?? 'Unknown';
}

protected function getProjectTypeName($projectTypeId)
{
    // Retrieve the project status mapping from the config file
    $pTypeMapping = $this->f3->get('project_type');
    
    // Return the corresponding status name or "Unknown" if the status ID is invalid
    return $pTypeMapping[$projectTypeId] ?? 'Unknown';
}

}
