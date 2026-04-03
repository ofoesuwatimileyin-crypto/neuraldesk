<?php
// NEXUS CORE ENGINE V3.5 - THE BULLETPROOF VERSION
$notes = $_POST['notes'] ?? '';
$question = $_POST['question'] ?? '';
$imageBase64 = $_POST['image'] ?? null;
$imageMime = $_POST['mime'] ?? null;
$actionType = $_POST['actionType'] ?? 'chat';
$persona = $_POST['persona'] ?? 'chill';

// Pull the key from Render's secret vault
$apiKey = getenv('GEMINI_API_KEY'); 

if (!$apiKey) {
    echo "System Error: Nexus cannot find your API key in Render Environment settings.";
    exit;
}

if (empty($notes) && empty($imageBase64)) {
    echo "Context Error: Please provide notes or an image first.";
    exit;
}

$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

if ($actionType === 'mindmap') {
    $systemInstruction = "You are a visualizer. Output ONLY a Mermaid.js 'graph TD' block. No talk. Wrap in ```mermaid blocks.";
    $question = "Create a mindmap.";
} else {
    $lecturer = "You are a strict Nigerian University Lecturer. Tone: Harsh, using slang like 'Omo', 'Are you playing?', 'Will you be serious?'. Answer based ONLY on these notes: " . $notes;
    $tutor = "You are Nexus AI, a supportive study peer. Answer clearly based on these notes: " . $notes;
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

if (curl_errno($ch)) {
    echo "Connection Error: " . curl_error($ch);
} else {
    $result = json_decode($response, true);
    if ($httpCode !== 200) {
        echo "Google API Error (Code $httpCode): " . ($result['error']['message'] ?? 'Check your API Key in Render');
    } else {
        echo $result['candidates'][0]['content']['parts'][0]['text'] ?? "The brain is empty. Try again.";
    }
}
curl_close($ch);
?>
