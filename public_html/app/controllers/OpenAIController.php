<?php

class OpenAIController extends Controller {
    private $openAIService;
    //private $table;
    //private $field;

    public function __construct() {
        parent::__construct();  // Call parent constructor to ensure proper setup
	$openaiKey = getenv('OPENAI_API_KEY');
        //$this->openAIService = new OpenAIService($this->f3->get('OPENAI_API_KEY'));// Initialize OpenAIService with API key
	$this->openAIService = new OpenAIService($openaiKey);// Initialize OpenAIService with API key
    }

    // Method to display the input form
    public function showForm() {
        $this->f3->set('view', 'openai_form.htm'); // Set the view to the OpenAI form template
    }

    // Method to handle form submission and process input using OpenAI
    public function handleRequest() {
        $input = $this->f3->get('POST.input'); // Get user input from the POST request
        $response = $this->openAIService->analyzeData($input, 'Analyze and respond.'); // Call OpenAI to analyze the input
        $this->f3->set('response', $response); // Set the response for rendering in the view
        $this->f3->set('view', 'admin/openai_result.htm'); // Set the view to display the result
    }

    // Method to analyze data from the database and process it with OpenAI
    public function analyzeData() {
        //$task = new Task($this->db); // Create a Task instance to interact with the database
        $table = $this->f3->get('TO_ANALYZE_TABLE');
        //$field = $this->f3->get('TO_ANALYZE_FIELD');
        //$filter = $this->f3->get('TO_ANALYZE_FILTER_FIELD');
        //$value = $this->f3->get('TO_ANALYZE_FILTER_VALUE');
        $sql = $this->f3->get('TO_ANALYZE_QUERY');
        $params = $this->f3->get('TO_ANALYZE_PARAMS');        
//die($table.'  '. $field.'  '. $filter.'  '. $value);
//die($sql.'  '. $params);
        $analyzeTable = new TableManager($this->db, $table);
        //$result = $task->getAll(); // Retrieve all task records from the database
        //$result = $analyzeTable->getData($table, $field); // Retrieve all task records from the database
        //$result = $analyzeTable->getFilteredData($table, $field, $filter, $value); // Retrieve all task records from the database
        $result = $analyzeTable->getCustomQueryData($sql, $params);
        if (empty($result)) { // Check if the result is empty
            $this->f3->set('response', 'No data retrieved from the database.'); // Set a no-data response
            return; // Exit the method early
        }

        $jsonData = json_encode($result, JSON_PRETTY_PRINT); // Convert the task data to JSON format

        //      O J O       A Q U I
        //****** Aca es donde se indica el prompt_key para elegir el prompt de la BD que sera ejecutado
        // La idea seria definir una lista de promt_key con sus respectivos prompts para que puedan ser
        // seleccionados desde opciones de menu o desde drop down list. Por supuesto los drop down list
        // tendran la etiqueta asociada al prompt_key para facil identificacion, y en la BD estara
        // lamacenado el prompt correspondiente para el analisis, alertas, reportes, etc, que seran
        // realizados por la AI. El prompt_key seria pasado como parametro y enrutado hacia aca.
        // Cuando tenga el drop down list listo, debo descomentar la linea que tiene el PARAMS.prompt_key.
        // NOTA: como el prompt_key se mostrara por la URL, tratar de que no sea tan explicito.
        // Ejemplo: En la BD:   prompt_key=tk1ad
        //          En el href del drop down list:  href="{{ prompt_key }}"
        // En el controlador que popula el drop down, obtener los posibles prompts segun categoria
        // por ejemplo: categorias(analisis, alertas, quotes, reportes, notificaciones)

        $promptManager = new PromptManager($this->db); // Create a PromptManager instance to retrieve prompts
        
        //$prompt = $promptManager->getPrompt($this->f3->get('PARAMS.prompt_key')); // Fetch a specific prompt by its key
        $prompt = $promptManager->getPrompt('aN7fd'); // Fetch a specific prompt by its key
        
       // $prompt = $promptManager->getPrompt('Fact'); // Fetch a specific prompt by its key

        $response = $this->openAIService->analyzeData($jsonData, $prompt); // Use OpenAI to analyze the data with the prompt

        $taskFrequencies = $this->parseTaskFrequencies($response); // Parse the response to extract task frequencies
        $json_taskFrequencies = json_encode($taskFrequencies);
        $this->f3->set('taskFrequencies', json_encode($taskFrequencies)); // Set task frequencies for rendering in the view
        $this->f3->set('response', $response); // Set the OpenAI response for rendering
        $this->f3->set('view', 'admin/openai_result.htm'); // Set the view to display the results
    }

    // Helper method to parse task frequencies from OpenAI's response
    private function parseTaskFrequencies($data) {
        $pattern = '/- ([^:]+): (\d+) tareas?/'; // Regex pattern to match task frequencies in the response
        $taskFrequencies = []; // Initialize an empty array to store parsed frequencies

        // Use regex to extract task types and their frequencies
        if (preg_match_all($pattern, $data, $matches)) {
            foreach ($matches[1] as $index => $taskType) { // Loop through the matched task types
                $taskFrequencies[$taskType] = (int)$matches[2][$index]; // Store the frequency as an integer
            }
        }

        return $taskFrequencies; // Return the parsed task frequencies
    }
}