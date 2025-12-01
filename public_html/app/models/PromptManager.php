<?php

class PromptManager extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
		"id",
		"prompt_key",
		"content",
		"created_at",
		"updated_at"		
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
		parent::__construct($db,'prompts');
	}


    public function getPrompt($key) {
        // Example query; ensure a `prompts` table exists in the database
        $query = "SELECT content FROM prompts WHERE prompt_key = :key";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['content'] : null;
    }

    public function savePrompt($key, $content) {
        $query = "INSERT INTO prompts (prompt_key, content) VALUES (:key, :content)
                  ON DUPLICATE KEY UPDATE content = :content";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':key' => $key, ':content' => $content]);
    }
}