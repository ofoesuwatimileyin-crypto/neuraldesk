<?php
$notes = $_POST['notes'] ?? '';
$question = $_POST['question'] ?? '';
$imageBase64 = $_POST['image'] ?? null;
$imageMime = $_POST['mime'] ?? null;
$actionType = $_POST['actionType'] ?? 'chat';
$persona = $_POST['persona'] ?? 'chill';

// PASTE YOUR KEY HERE. KEEP THE QUOTES!
$apiKey = "AIzaSyBkvskPKmdo8PCcMmkiAteBIkDT8T4dRHM"; 

if ((empty($notes) && empty($imageBase64))) {
    echo "Please provide data (text or image) to work with.";
    exit;
}

$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=" . $apiKey;

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

$json_data = json_encode($data);

// ==========================================
// NEW: cURL ENGINE (Bypasses network blocks)
// ==========================================
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json_data)
]);
// This stops SSL verification errors on strict/free servers
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Server Connection Error: " . curl_error($ch);
} else {
    $result = json_decode($response, true);
    if (isset($result['error'])) {
        echo "API ERROR: " . $result['error']['message'];
    } else {
        echo $result['candidates'][0]['content']['parts'][0]['text'] ?? "I hit a snag while processing that.";
    }
}

curl_close($ch);
?>
