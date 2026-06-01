<?php
$lines = file('e:/Suhaim Soft Work Shop/suhaimsoftworkshop/storage/framework/views/525dbe464b436a4cf60c0bb2c6a0414e.php');
foreach ($lines as $i => $line) {
    if (strpos($line, '<?php if') !== false || strpos($line, '<?php endif') !== false) {
        echo ($i + 1) . ': ' . trim($line) . "\n";
    }
}
