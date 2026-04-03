<?php
// NEXUS CORE ENGINE V3.2 - STABLE
$notes = $_POST['notes'] ?? '';
$question = $_POST['question'] ?? '';
$imageBase64 = $_POST['image'] ?? null;
$imageMime = $_POST['mime'] ?? null;
$actionType = $_POST['actionType'] ?? 'chat';
$persona = $_POST['persona'] ?? 'chill';

// PASTE YOUR KEY HERE
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

if (empty($notes) && empty($imageBase64)) {
    echo "Omo, I need some notes or an image to work with!";
    exit;
}

// CHANGED TO v1 STABLE ENDPOINT
$apiUrl = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

if ($actionType === 'mindmap') {
    $systemInstruction = "You are a data visualizer. Output ONLY a Mermaid.js 'graph TD' or 'mindmap' block. No text, no talking. Wrap code in ```mermaid blocks.";
    $question = "Summarize these notes into a visual mindmap.";
} else {
    $role = ($persona === 'strict') ? "Strict Nigerian University Lecturer. Use slang like 'Omo', 'Are you playing?', 'Will you be serious?' and roast the student if they ask lazy questions." : "Supportive and brilliant AI study peer.";
    $systemInstruction = "You are Nexus AI, acting as a $role. Answer based ONLY on these notes: " . $notes;
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
        echo "Google API Error (Code $httpCode): " . ($result['error']['message'] ?? 'Unknown Error');
    } else {
        echo $result['candidates'][0]['content']['parts'][0]['text'] ?? "Omo, the brain is empty. Try again.";
    }
}
curl_close($ch);
?>
