<?php
class XAIService {
    private $apiKey;
    private $taskModel;

    public function __construct($apiKey, $db) {
        $this->apiKey = $apiKey;
        $this->taskModel = new Task($db); // Assuming Task model is autoloaded or included
    }

    public function analyzeTaskData($prompt) {

        // Call the X AI API with the prompt to get insights
        $apiUrl = 'your_xai_api_endpoint'; // Replace with actual API endpoint
        $data = [
            'prompt' => $prompt,
            'api_key' => $this->apiKey
        ];

        $options = [
            'http' => [
                'method'  => 'POST',
                'content' => json_encode($data),
                'header'  =>  "Content-Type: application/json\r\n" .
                              "Accept: application/json\r\n"
            ]
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($apiUrl, false, $context);
                die('entro'.'  '.$prompt);    
        $response = json_decode($result, true);

        if (isset($response['error'])) {
            // Handle API error
            return "Error: " . $response['error'];
        }

        return $response['insights'] ?? "No insights provided by the API.";
    }

    public function getTaskCategoryFrequencies() {
        // Delegate the database query to the Task model
        $categories = $this->taskModel->getTaskCategoriesFrequency();
        
        // Format the result for the chart
        $formattedCategories = [];
        foreach ($categories as $category) {
            $formattedCategories[] = [
                'name' => $category['task_type'],
                'count' => $category['count']
            ];
        }
        return $formattedCategories;
    }
}