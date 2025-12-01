<?php

class NotificationController extends Controller {
    /**
     * Fetch unread notifications for the logged-in user
     */
    public function fetchNotifications()
    {
        $userId = $this->f3->get('SESSION.user_id');
        //$lastActivity = $this->f3->get('SESSION.last_activity');
        
        $lastActivity = $this->f3->get('SESSION.timestamp');
        if (!$userId || !$lastActivity) {
            echo json_encode(['notifications' => [], 'message' => 'Session inactive or expired']);
            return;
        }

        // Fetch unread notifications
        $notif = new Notification($this->db);
        $notifications = $notif->getNotifications($userId);
        /*$notifications = $this->db->exec(
            'SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC',
            [$userId]
        );*/

        // Return notifications as JSON
        header('Content-Type: application/json');
        //echo json_encode(['notifications' => $notifications]);
        die(json_encode(['notifications' => $notifications]));
    }
    
        // Metodo para obtener y pasar datos para grafico via Ajax. El Script de Ajax esta en xmartcrm.js 
    public function fetch() {
        
        $userId = $this->f3->get('SESSION.user_id');        
        $notif = new Notification($this->db);
        $result = $notif->getNotifications($userId);

        // Check if any rows were found
        if (empty($result)) {
            // Format data for the chart
            $taskData = [
                'notif_found' => 0, 
                'message' => array_column($result, 'message'),
                'created_at' => array_column($result, 'created_at'),
            ];
        }else{        
            // Format data for the chart
            $taskData = [
                'notif_found' => 1,                  
                'message' => array_column($result, 'message'),
                'created_at' => array_column($result, 'created_at'),
            ]; 
        }
        //die('entro');
        die(json_encode($taskData));
        exit;
    }

    /**
     * Mark notifications as read
     */
    public function markAsRead()
    {
        $userId = $this->f3->get('SESSION.user_id');
        $notif = new Notification($this->db);
        $result = $notif->updateNotifications($userId);
        die(json_encode(['status' => 'success']));
    }  
    
    public function toggleStatus()
    {  
        $notif = new Notification($this->db);
        $is_read = json_decode($this->f3->get('BODY'), true)['is_read'];
        $notif->updateIsRead($is_read, $this->f3->get('PARAMS.notificationId'));
        
        
        $this->f3->set('SESSION.unreadNotif', $notif->getUnreadCount($this->f3->get('SESSION.user_id')));
        die(json_encode(['status' => 'success']));
    }
    
    
}