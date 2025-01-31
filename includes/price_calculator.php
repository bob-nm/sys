<?php
function calculatePrice($basePrice, $currencyRate, $markupPercentage) {
    $priceInLocalCurrency = $basePrice * $currencyRate;
    $finalPrice = $priceInLocalCurrency * (1 + $markupPercentage / 100);
    return $finalPrice;
}
?>
