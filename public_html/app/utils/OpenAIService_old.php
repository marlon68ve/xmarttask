<?php

class OpenAIService {
    private $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getChatCompletion($messages) {
        $url = 'https://api.openai.com/v1/chat/completions';
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages
        ];
        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . 'Bearer ' . $this->apiKey
        ];

        $curl = curl_init($url);
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        
        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            throw new Exception('Curl error: ' . curl_error($curl));
        }
        $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpStatusCode != 200) {
            throw new Exception('API request failed with status code ' . $httpStatusCode);
        }
        curl_close($curl);
        return json_decode($response, true);
    }

    // ----------------------------------------------------------------------------
    public function getOpenAIAnalysis($data) {
        //$apiKey = 'your_openai_api_key';
        $url = 'https://api.openai.com/v1/chat/completions';
        $postData = json_encode([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert data analyst.'],
                ['role' => 'user', 'content' => 'Analyze the following task data and provide statistics about the different task types. Specifically, I want to know which task type is the most frequent, the total time spent on each task type, and any patterns or insights you can identify. Keep always the same formatting of the data, for example to enumerate all the task frequency do it like: Task Type Frequency:\n- Ajustes: 7 tasks\n- Presentación: 4 tasks\n , and so on. Tanslate an give your response only in spanish. Here is the data:'],
                ['role' => 'user', 'content' => $data]
            ],
            'max_tokens' => 300,
        ]);
        // Initialize cURL session
        $ch = curl_init($url);
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        // Execute the cURL request and get the response
        $response = curl_exec($ch);
        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            curl_close($ch);
            return;
        }
        // Close the cURL session
        curl_close($ch);
        //var_dump($response.'   HOLA    ');
        // Decode the response
        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'];
    }
}