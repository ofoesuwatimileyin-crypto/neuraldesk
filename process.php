<?php
$notes = $_POST['notes'] ?? '';
$question = $_POST['question'] ?? '';
$imageBase64 = $_POST['image'] ?? null;
$imageMime = $_POST['mime'] ?? null;
$actionType = $_POST['actionType'] ?? 'chat';
$persona = $_POST['persona'] ?? 'chill';

// PASTE YOUR KEY HERE. KEEP THE QUOTES! -> "API_KEY"
$apiKey = "YOUR_GEMINI_API_KEY_HERE"; 

if ((empty($notes) && empty($imageBase64))) {
    echo "Omo, I need data (text or image) to work!";
    exit;
}

$apiUrl = "[https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=](https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=)" . $apiKey;

// PERSONA & ACTION ROUTER
if ($actionType === 'mindmap') {
    $systemInstruction = "You are an expert data visualizer. Analyze the provided notes/image and output a complex, highly detailed 'Mermaid.js' flowchart or mindmap summarizing the core concepts. 
    Rules:
    1. Start the chart with 'graph TD' or 'mindmap'.
    2. NO parentheses () or brackets [] inside node names.
    3. Wrap the code strictly inside standard markdown ```mermaid [code here] ``` blocks.
    4. Do not output any conversational text, only the code block.";
    $question = "Generate the Mermaid visual.";
} else {
    if ($persona === 'strict') {
        $systemInstruction = "You are a highly intelligent but extremely strict Nigerian University Lecturer. Tone: Harsh, demanding excellence, using Nigerian slang (e.g., 'Omo', 'Are you playing?', 'Will you be serious?'). Goal: Answer the user's question based strictly on the provided text notes or image. If their question is lazy, roast them briefly before answering.";
    } else {
        $systemInstruction = "You are Nexus Tutor, an authentic, supportive, and brilliant AI peer. Tone: Friendly, encouraging, highly intelligent. Goal: Answer the user's question clearly based strictly on the provided text notes or image. Break down complex topics so they are easy to understand.";
    }
    $systemInstruction .= " Current Context: " . $notes;
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
    echo "Network blockage detected on server.";
} else {
    $result = json_decode($response, true);
    if (isset($result['error'])) {
        echo "API ERROR: " . $result['error']['message'];
    } else {
        echo $result['candidates'][0]['content']['parts'][0]['text'] ?? "I hit a snag while processing that.";
    }
}
?>
