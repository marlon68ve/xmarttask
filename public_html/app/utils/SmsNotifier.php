<?php

class SmsNotifier {
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function sendSms($phoneNumber, $message)
    {
        $url = 'https://textbelt.com/text'; // Textbelt API endpoint

        // Data to send in the POST request
        $data = [
            'phone' => '+1' . $phoneNumber,
            'message' => $message,
            'key' => $this->apiKey,
            'sender' => 'xmart101 services LLC',
        ];

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, 1); // HTTP POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Send data as x-www-form-urlencoded
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response instead of outputting it

        // Execute the request and capture the response
        $response = curl_exec($ch);

        // Handle errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("cURL error while sending SMS: $error");
        }

        // Close cURL session
        curl_close($ch);

        // Decode the response (JSON)
        $responseData = json_decode($response, true);

        // Check if the SMS was successfully sent
        if (isset($responseData['success']) && $responseData['success']) {
            return true; // SMS sent successfully
        } else {
            // Handle error from Textbelt API
            $errorMessage = $responseData['error'] ?? 'Unknown error.';
            throw new \Exception("Textbelt error: $errorMessage");
        }
    }
}