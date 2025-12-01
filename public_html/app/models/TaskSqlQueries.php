<?php

class TaskSqlQueries {
    private $db;

    public function __construct(DB\SQL $db) {
        $this->db = $db;
    }

	private function getCurrentdate()
	{
		return date("Y-m-d H:i:s");
	}

    public function existsTask($personPhone, $projectId) {
        try {
            $sql = "SELECT ppt.* 
                    FROM project_person_task AS ppt
                    JOIN project_person AS pp ON pp.projectperson_num = ppt.entity_num
                    WHERE pp.person_phone = :person_phone 
                    AND pp.project_id = :project_id 
                    AND ppt.type_entity = 'person'";
            $params = array(
                ':person_phone' => $personPhone,
                ':project_id' => $projectId
            );
            $result = $this->db->exec($sql, $params);
            return !empty($result);
        } catch (Exception $e) {
            error_log("Exception: " . $e->getMessage());
            throw new RuntimeException("Error processing the request");
        }
    }
    
    public function taskCountByStatus($project_id)
    {
        // Retrieve task count grouped by type
        //$sql = "SELECT task_status, COUNT(*) as count FROM task AS t JOIN project_person_task AS ppt ON t.task_num = ppt.task_num WHERE ppt.entity_num = :project_id AND ppt.type_entity ='project' GROUP BY task_status";
        
        $sql = "SELECT task_status, COUNT(*) AS count FROM ( SELECT t.task_status FROM task AS t JOIN project_task AS pt ON t.task_num = pt.task_num WHERE pt.project_id = :project_id UNION ALL SELECT t.task_status FROM task AS t JOIN person_task AS pt ON t.task_num = pt.task_num WHERE pt.project_id = :project_id ) AS combined GROUP BY task_status";  
        //$result = $this->db->exec($sql);
		$params = array(':project_id' => $project_id);
		$result = $this->db->exec($sql, $params); 
        return $result;
    }    
    
}
