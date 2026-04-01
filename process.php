<?php
$notes = $_POST['notes'] ?? '';
$question = $_POST['question'] ?? '';
$imageBase64 = $_POST['image'] ?? null;
$imageMime = $_POST['mime'] ?? null;

// PUT YOUR REAL KEY HERE, NOT THE PLACEHOLDER
$apiKey = "YOUR_GEMINI_API_KEY_HERE";

if ((empty($notes) && empty($imageBase64)) || empty($question)) {
    echo "Omo, I need data (text or image) and a question to work!";
    exit;
}

$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

$systemInstruction = "You are NeuralDesk AI. 
Tone: Peer-like, highly intelligent, casual Nigerian-English. 
Goal: Answer the user's question based strictly on the provided text notes and/or the uploaded image. If analyzing an image, describe relevant details accurately.
Current Notes Context: " . $notes;

// Build the API payload parts
$parts = [
    ["text" => $systemInstruction . "\n\nUser Question: " . $question]
];

// If the user attached an image, inject it into the Gemini payload
if ($imageBase64 && $imageMime) {
    $parts[] = [
        "inlineData" => [
            "mimeType" => $imageMime,
            "data" => $imageBase64
        ]
    ];
}

$data = [
    "contents" => [
        [
            "role" => "user",
            "parts" => $parts
        ]
    ]
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'ignore_errors' => true
    ]
];

$context  = stream_context_create($options);
$response = @file_get_contents($apiUrl, false, $context);

if ($response === FALSE) {
    echo "Network blockage. InfinityFree killed the connection to Google's API again.";
} else {
    $result = json_decode($response, true);
    
    // Check for API Key errors specifically
    if (isset($result['error'])) {
        echo "API ERROR: " . $result['error']['message'] . " (Did you paste your real API key?)";
    } else {
        echo $result['candidates'][0]['content']['parts'][0]['text'] ?? "Omo, I hit a snag while processing that.";
    }
}
?>