<?php

class ChatController {
    private $openai;

    public function __construct() {
        $apiKey = \Base::instance()->get('openai.api_key');
        $this->openai = new Client($apiKey);
    }

    public function chat($f3) {
        $prompt = $f3->get('POST.prompt');

        $response = $this->openai->completions()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 150,
        ]);

        $output = $response['choices'][0]['message']['content'];
        echo json_encode(['response' => $output]);
    }
}
