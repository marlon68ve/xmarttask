<?php

class PageController extends Controller {
	
	public function homepage()
	{

	    $task = new Task($this->db);
	    $project_id = $this->f3->get('SESSION.project_id');
	    
	    $this->f3->set('taskCount',$task->getAllTasksCount($project_id));
	    // Asignar una funcion a una variable global F3 para que sea accedida desde una vista
        $this->f3->set('hasPermission', function($action, $userRole) {
            // *** hasPermission() esta definida dentro de la clase base Controller   ***
            return $this->hasPermission($action, $userRole); // Assuming `hasPermission` is globally accessible
        });
        
		$this->f3->set('view','page/homepage.htm');
	}

	public function responsinput()
	{
		$this->f3->set('view','page/inputs.htm');
	}
}