<?php

$n=123456;
$arr=[];
// ========================================
function tachSo($n, $arr) {
    while ($n>0) {
        $du=$n%10;
        array_push($arr,$du);
        $n = intdiv($n,10);
    }

    return $arr;
}
// =========================================
function timSoLN($arr) {
    $max=$arr[0];
    for ($i=1;$i<count($arr);$i++) {
        if ($arr[$i]>$max) $max=$arr[$i];
    }

    return $max;
}
// =========================================
$arr=tachSo($n, $arr);

$max=timSoLN($arr);

echo "<pre>";
print_r($arr);
echo "<pre>";

echo $max;