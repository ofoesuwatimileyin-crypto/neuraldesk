<?php
// NEXUS CORE ENGINE V3.5
$notes = $_POST['notes'] ?? '';
$question = $_POST['question'] ?? '';
$imageBase64 = $_POST['image'] ?? null;
$imageMime = $_POST['mime'] ?? null;
$actionType = $_POST['actionType'] ?? 'chat';
$persona = $_POST['persona'] ?? 'chill';

// This pulls the key from the Render Vault you just set up
$apiKey = getenv('GEMINI_API_KEY'); 

if (!$apiKey) {
    echo "System Error: Nexus cannot find your API key in Render settings.";
    exit;
}

// THE STABLE URL
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=" . $apiKey;

if ($actionType === 'mindmap') {
    $systemInstruction = "You are a visualizer. Output ONLY a Mermaid.js 'graph TD' block. Wrap in ```mermaid blocks.";
    $question = "Create a mindmap.";
} else {
    $lecturer = "You are a strict Nigerian Lecturer. Tone: Harsh, using slang like 'Omo'. Answer based on: " . $notes;
    $tutor = "You are Nexus AI, a supportive study peer. Answer based on: " . $notes;
    $systemInstruction = ($persona === 'strict') ? $lecturer : $tutor;
}

$parts = [["text" => $systemInstruction . "\n\nUser: " . $question]];
if ($imageBase64 && $imageMime) {
    $parts[] = ["inlineData" => ["mimeType" => $imageMime, "data" => $imageBase64]];
}

$data = ["contents" => [["parts" => $parts]]];
$json_data = json_encode($data);

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode !== 200) {
    echo "API Error ($httpCode). Make sure your key in Render is fresh!";
} else {
    $result = json_decode($response, true);
    echo $result['candidates'][0]['content']['parts'][0]['text'] ?? "Omo, I hit a snag.";
}
curl_close($ch);
?>
