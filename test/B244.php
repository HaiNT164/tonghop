<?php

$arr=[1,2,3,4,5,6];

// ============================================
function xoa1PT(&$arr,$viTri) {
    $n=count($arr);
    for ($i=$viTri;$i<$n-1;$i++)
        $arr[$i]=$arr[$i+1];
    unset($arr[$n-1]);
}

// ===========================================
xoa1PT($arr,2);

echo "<pre>";
print_r($arr);
echo "<pre>";