<?php

class Notification extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
        "id",
        "user_id",
        "message",
        "is_read",
        "created_at"        
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
		parent::__construct($db,'notifications');
	}


	public function all() 
	{ //get all users, admin only!
		$this->load();
		return $this->query;
	}

	public function allById($id) 
	{
		$sql = "SELECT n.*, TRIM(SUBSTRING_INDEX(SUBSTRING(n.message, LOCATE(', ', n.message) + 2), ' ', 1)) AS username, u.userpicture FROM notifications n LEFT JOIN users u ON u.username = TRIM(SUBSTRING_INDEX(SUBSTRING(n.message, LOCATE(', ', n.message) + 2), ' ', 1)) WHERE n.user_id = :id";	    
        $params = array(':id' => $id);

        // Execute the query
        $result = $this->db->exec($sql, $params);
        // Return the result set
        return $result;
	}

	public function getUnreadCount($id) 
	{
	    $count = $this->count(array('user_id=? AND is_read=0', $id));
        return $count;	
	}
	
	
	public function updateIsRead($is_read, $notificationId)
    {
    try {
        $sql ="UPDATE notifications SET is_read = :is_read WHERE id = :notificationId";	
	        $params = array(':is_read' => $is_read, ':notificationId' => $notificationId);

        // Execute the query
        $this->db->exec($sql, $params);
        // Return the result set
        return true;

    } catch (\PDOException $e) {
        // Log the error for debugging
        $logger = new \Log('logs/notifications_error.log');
        $logger->write("Database error in getNotifications: " . $e->getMessage() . " | SQL: " . $sql);

        // Optionally throw a new generic exception to the calling code
        throw new \Exception("Failed to retrieve notifications. Please try again later.");
    } catch (\Exception $e) {
        // Log unexpected errors
        $logger = new \Log('logs/notifications_error.log');
        $logger->write("Unexpected error in getNotifications: " . $e->getMessage());

        // Re-throw the exception
        throw $e;
    }        
    }
	
	

public function getNotifications($user_id)
{
    try {
        // SQL query to fetch unread notifications
        $sql = "SELECT * FROM notifications WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC";  
        $params = array(':user_id' => $user_id);

        // Execute the query
        $result = $this->db->exec($sql, $params);
        // Return the result set
        return $result;

    } catch (\PDOException $e) {
        // Log the error for debugging
        $logger = new \Log('logs/notifications_error.log');
        $logger->write("Database error in getNotifications: " . $e->getMessage() . " | SQL: " . $sql);

        // Optionally throw a new generic exception to the calling code
        throw new \Exception("Failed to retrieve notifications. Please try again later.");
    } catch (\Exception $e) {
        // Log unexpected errors
        $logger = new \Log('logs/notifications_error.log');
        $logger->write("Unexpected error in getNotifications: " . $e->getMessage());

        // Re-throw the exception
        throw $e;
    }
}

    public function deleteNotif($notif_id)
    {
        try {
            $this->load(array('id=?', $notif_id));
            if (!$this->dry()) { // Check if record exists before deleting
                $this->erase();
                return true;
            } else {
                throw new Exception("Notification with ID $notif_id not found.");
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }
	
	public function updateNotifications($user_id) 
	{
        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id";  
		$params = array(':user_id' => $user_id);
		$result = $this->db->exec($sql, $params); 
        return $result;
	}
	
public function add($user_id, $message)
{
    try {
        // SQL query to insert a new notification
        $sql = "INSERT INTO notifications (user_id, message) VALUES (:user_id, :message)";
        $params = array(
            ':user_id' => $user_id,
            ':message' => $message
        );

        // Execute the query
        $this->db->exec($sql, $params);

        // Return success if insertion is successful
        return true;

    } catch (\PDOException $e) {
        // Log the error for debugging (optional)
        $logger = new \Log('logs/notifications_error.log');
        $logger->write("Error adding notification: " . $e->getMessage() . " | SQL: " . $sql);

        // Handle the error gracefully
        // Optionally rethrow the exception if you want to handle it elsewhere
        throw new \Exception("Failed to add notification. Please try again later.");
    }
}
	
	
}
