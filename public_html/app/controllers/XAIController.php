<?php
class XAIController extends Controller {
    public function analyzeData() {
        //$promptId = $this->f3->get('GET.id'); // Assuming the prompt ID is passed in the URL
        $promptKey = 'Fact';
        
        $promptManager = new PromptManager($this->db);
        $prompt = $promptManager->getPrompt($promptKey);

        if (!$prompt) {
            $this->f3->set('error', 'Prompt not found');
            $this->f3->error(403);
            return;
        }

	$xaiKey = getenv('XAI_API_KEY');
        //$xaiService = new XAIService($this->f3->get('X_API_KEY'), $this->db);
	$xaiService = new XAIService($xaiKey, $this->db);
        
        $insights = $xaiService->analyzeTaskData($prompt);
die('entro'.'  '.$this->f3->get('X_API_KEY'));        
        $taskCategories = $xaiService->getTaskCategoryFrequencies();

        $this->f3->set('insights', $insights);
        $this->f3->set('categories', $taskCategories);
        $this->f3->set('view', 'admin/analyze.htm'); // Set the view to display the result
    }
}