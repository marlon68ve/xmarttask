<?php

class TableManager extends DB\SQL\Mapper {

	public function __construct(DB\SQL $db, $table) 
	{
		parent::__construct($db, $table);
	}

	public function getData($table, $field) 
	{
        	$data = $this->db->exec("SELECT $field FROM $table");
        	return $data;
	}
	
	public function getFilteredData($table, $field, $filter, $value) 
	{
        $stmt = $this->db->prepare("SELECT $field FROM $table WHERE $filter = :value");
        $stmt->bindParam(':value', $value, PDO::PARAM_STR); // Use appropriate data type
        $stmt->execute();

        // Fetch the result
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;        	
	}
	
	public function getCustomQueryData($query, $paramsJson = null) 
	{
        try {
            // Decode JSON parameters if provided
            $params = $paramsJson ? json_decode($paramsJson, true) : [];
            // Prepare the SQL statement
            $stmt = $this->db->prepare($query);

            // Bind parameters dynamically
            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            // Execute the statement
            $stmt->execute();
            // Fetch and return the results
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle errors (log them, rethrow, etc.)
            throw new Exception("Database query error: " . $e->getMessage());
        }     	
	}	
}