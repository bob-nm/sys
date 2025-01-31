<?php
function getExchangeRate($fromCurrency, $toCurrency) {
    $apiKey = 'your_api_key';
    $url = "https://api.exchangerate-api.com/v4/latest/$fromCurrency";  // Example URL, change it according to the API you're using
    
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if (isset($data['rates'][$toCurrency])) {
        return $data['rates'][$toCurrency];
    } else {
        return false;  // Return false if exchange rate is not found
    }
}
?>
