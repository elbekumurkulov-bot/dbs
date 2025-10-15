<?php
// send.php - basic Telegram bridge for form -> Telegram
// Important: replace BOT_TOKEN and CHAT_ID before using in production.
// Consider moving token to environment variable for security.

$BOT_TOKEN = '6081424639:AAFt1JtDWhq4klkiakJ0XijOwqWsaALAyAE';
$CHAT_ID = '818910455';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$seller_name = trim($_POST['seller_name'] ?? $_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$from_city = trim($_POST['from_city'] ?? 'Tashkent');
$to_city = trim($_POST['to_city'] ?? '');
$items = trim($_POST['items'] ?? $_POST['message'] ?? '');

if(!$seller_name || !$phone || !$items){
    http_response_code(400);
    echo 'Missing required fields';
    exit;
}

$msg = "📦 <b>Новая заявка — DBS Delivery</b>%0A";
$msg .= "👤 Селлер: " . rawurlencode($seller_name) . "%0A";
$msg .= "📞 Телефон: " . rawurlencode($phone) . "%0A";
$msg .= "📍 Откуда: " . rawurlencode($from_city) . "%0A";
$msg .= "📦 Куда: " . rawurlencode($to_city) . "%0A";
$msg .= "📝 Товары: " . rawurlencode($items) . "%0A";

$api = "https://api.telegram.org/bot{$BOT_TOKEN}/sendMessage?chat_id={$CHAT_ID}&text={$msg}&parse_mode=HTML";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if($http === 200){
    echo 'OK';
    exit;
}else{
    http_response_code(500);
    echo 'Telegram API error: '.$result;
    exit;
}
?>