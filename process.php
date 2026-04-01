<?php
$notes = $_POST['notes'] ?? '';
$question = $_POST['question'] ?? '';
$imageBase64 = $_POST['image'] ?? null;
$imageMime = $_POST['mime'] ?? null;
$actionType = $_POST['actionType'] ?? 'chat';

// PASTE YOUR GEMINI API KEY HERE
$apiKey = "AIzaSyBkvskPKmdo8PCcMmkiAteBIkDT8T4dRHM; 

if ((empty($notes) && empty($imageBase64))) {
    echo "Omo, I need data (text or image) to work!";
    exit;
}

$apiUrl = "[https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=](https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=)" . $apiKey;

// THE SMART PROMPT SWITCHER
if ($actionType === 'mindmap') {
    $systemInstruction = "You are an expert data visualizer. Your ONLY job is to analyze the provided notes/image and output a complex, highly detailed 'Mermaid.js' flowchart or mindmap that perfectly summarizes the core concepts. 
    Rules:
    1. Start the chart with 'graph TD' or 'mindmap'.
    2. Do NOT use parentheses () or brackets [] inside the node text names as it breaks mermaid syntax.
    3. You MUST wrap the entire code strictly inside standard markdown ```mermaid [code here] ``` blocks.
    4. Do not output any conversational text or greetings, just the code block.";
    
    $question = "Generate the Mermaid visual.";
} else {
    $systemInstruction = "You are NeuralDesk AI. Tone: Peer-like, highly intelligent, casual Nigerian-English. Goal: Answer the user's question based strictly on the provided text notes and/or the uploaded image. Current Notes Context: " . $notes;
}

$parts = [
    ["text" => $systemInstruction . "\n\nUser Prompt: " . $question]
];

if ($imageBase64 && $imageMime) {
    $parts[] = [
        "inlineData" => [
            "mimeType" => $imageMime,
            "data" => $imageBase64
        ]
    ];
}

$data = [
    "contents" => [["role" => "user", "parts" => $parts]]
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
    echo "Network blockage.";
} else {
    $result = json_decode($response, true);
    if (isset($result['error'])) {
        echo "API ERROR: " . $result['error']['message'];
    } else {
        echo $result['candidates'][0]['content']['parts'][0]['text'] ?? "Omo, I hit a snag while processing that.";
    }
}
?>
