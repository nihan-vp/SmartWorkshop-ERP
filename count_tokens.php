<?php
$tokens = token_get_all(file_get_contents('compiled_app.php'));
$ifs = 0;
$endifs = 0;
foreach($tokens as $t) {
    if(is_array($t)) {
        if($t[0] === T_IF || $t[0] === T_ELSEIF) $ifs++;
        if($t[0] === T_ENDIF) $endifs++;
    }
}
echo "ifs/elseifs: $ifs\n";
echo "endifs: $endifs\n";
