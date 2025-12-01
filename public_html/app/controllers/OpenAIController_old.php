<?php

class OpenAIController extends Controller {
    private $openAIService;

    public function __construct() {
        parent::__construct();
        $this->openAIService = new OpenAIService($this->f3->get('OPENAI_API_KEY'));
    }

    public function showForm() {
        $this->f3->set('view', 'openai_form.htm');
    }

    public function handleRequest() {
        $input = $this->f3->get('POST.input');
        $messages = [
            [
                'role' => 'user',
                'content' => $input
            ]
        ];
        try {
            $response = $this->openAIService->getChatCompletion($messages);
            $this->f3->set('response', $response['choices'][0]['message']['content']);
        } catch (Exception $e) {
            $this->f3->set('response', 'Error: ' . $e->getMessage());
        }

        $this->f3->set('view', 'admin/openai_result.htm');
    }

    // ***************************************************************
    public function analyzeData() {
        $task = new Task($this->db);
        // Fetch data from MySQL database
        $result = $task->getall();
        //var_dump($result);
        //die('entro');
        // Check if the result is not empty
        if (empty($result)) {
            echo 'No data retrieved from the database.';
            return;
        }
        // Format the data as JSON
        $jsonData = json_encode($result, JSON_PRETTY_PRINT);
        // Send data to OpenAI API for analysis
        $response = $this->openAIService->getOpenAIAnalysis($jsonData);
        // Parse the string data
        $taskFrequencies = $this->parseTaskFrequencies($response);
        //var_dunp($taskFrequencies);
        //die('entro1');
        $this->f3->set('taskFrequencies', json_encode($taskFrequencies));
        // Set the variables in F3 framework
        $this->f3->set('response', $response);
        // Output the analysis result
        //echo $analysis;
        $this->f3->set('view', 'admin/openai_result.htm');
    }

    // Method to parse the string data
    private function parseTaskFrequencies($data) {
        //$pattern = '/. ([\wáéíóúñÁÉÍÓÚÑ]+): (\d+) tasks?/u';
        $pattern = '/- ([^:]+): (\d+) tareas?/';
        $taskFrequencies = [];
        if (preg_match_all($pattern, $data, $matches)) {
            foreach ($matches[1] as $index => $taskType) {
                //print_r($taskType);
                $taskFrequencies[$taskType] = (int)$matches[2][$index];
                // print_r( $taskFrequencies[$taskType]);
            }
        }
        return $taskFrequencies;
    }
}
