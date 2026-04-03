<?php
// NEXUS CORE ENGINE V3.7 - THE BLINDFOLD OFF
$notes = $_POST['notes'] ?? '';
$question = $_POST['question'] ?? '';
$imageBase64 = $_POST['image'] ?? null;
$imageMime = $_POST['mime'] ?? null;
$actionType = $_POST['actionType'] ?? 'chat';
$persona = $_POST['persona'] ?? 'chill';

// Pull the key securely
$apiKey = trim(getenv('GEMINI_API_KEY')); 

if (empty($apiKey)) {
    echo "System Error: Nexus cannot find your API key in Render settings.";
    exit;
}

// Targeting the absolute latest flash model
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
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode !== 200) {
    // THIS IS THE FIX: Print Google's EXACT error message so we aren't guessing.
    $errorData = json_decode($response, true);
    $actualError = $errorData['error']['message'] ?? "Raw response: " . htmlspecialchars($response);
    echo "Google API Error (Code $httpCode): " . $actualError;
} else {
    $result = json_decode($response, true);
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        echo $result['candidates'][0]['content']['parts'][0]['text'];
    } else {
        echo "Omo, the brain returned an empty response. Try a different question.";
    }
}
curl_close($ch);
?>
