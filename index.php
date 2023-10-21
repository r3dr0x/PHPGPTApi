<?php

$url = 'https://talkai.info/chat/send2/';
$message = isset($_GET['message']) ? $_GET['message'] : 'default message';

if (!isset($_GET['message'])) {
    header('Content-type:application/json;charset=utf-8');
    echo json_encode(['error' => 'Lütfen bir mesaj yazınız.'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

$payload = '{"type":"chat","message":"'.$message.'","messagesHistory":[{"from":"you","content":"'.$message.'"}],"model":"gpt-3.5-turbo","max_tokens":256,"temperature":1,"top_p":1,"presence_penalty":0,"frequency_penalty":0}';

$headers = array(
    'accept: application/json',
    'accept-encoding: gzip, deflate, br',
    'accept-language: tr,en;q=0.9,en-GB;q=0.8,en-US;q=0.7',
    'content-length: '.strlen($payload),
    'content-type: application/json',
    'cookie: WtNSAv=KcFDhZpOdwtCYjzfQvPlaXIGykrVJN; KcFDhZpOdwtCYjzfQvPlaXIGykrVJN=cf040e3db0de84b5e3edbafe4d7d0230-1697904364; _csrf-front=e55760466eaf2f75a88191f0ea7dadb12ffa78d9903c04a9ccfd3e9f26baa0b9a%3A2%3A%7Bi%3A0%3Bs%3A11%3A%22_csrf-front%22%3Bi%3A1%3Bs%3A32%3A%22pfSck9uQ0EAiSFPpX0qjKoV9qxxwH08_%22%3B%7D; WtNSAv_hits=1',
    'origin: https://talkai.info',
    'referer: https://talkai.info/chat/',
    'sec-ch-ua: "Not_A Brand";v="99", "Microsoft Edge";v="109", "Chromium";v="109"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.140'
);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$lines = explode("\n", $result);

$data = "";
foreach ($lines as $line) {
    if (str_starts_with($line, "data: ")) {
        $data .= substr($line, 6); // "data: " ifadesini kaldırır
    }
}

$message_data = [
    'your_query' => $message, // GET ile alınan message
    'GPT_response' => $data // Sunucudan gelen yanıt
];

header('Content-type:application/json;charset=utf-8');
echo json_encode($message_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); 

curl_close($ch);

?>
