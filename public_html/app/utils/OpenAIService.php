<?php

class OpenAIService {
    private $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function sendRequest($messages, $model = 'gpt-3.5-turbo', $maxTokens = 300) {
        $url = 'https://api.openai.com/v1/chat/completions';
        $data = [
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => $maxTokens
        ];
        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . 'Bearer ' . $this->apiKey
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpStatusCode != 200) {
            throw new Exception('API request failed with status code ' . $httpStatusCode);
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    public function analyzeData($data, $prompt) {
        $messages = [
            ['role' => 'system', 'content' => 'You are an expert data analyst.'],
            ['role' => 'user', 'content' => $prompt],
            ['role' => 'user', 'content' => $data]
        ];
        
        $response = $this->sendRequest($messages);
        return $response['choices'][0]['message']['content'];
    }
}
