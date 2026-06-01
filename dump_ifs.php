<?php
$tokens = token_get_all(file_get_contents('compiled_app.php'));
foreach($tokens as $t) {
    if(is_array($t)) {
        if(in_array($t[0], [T_IF, T_ELSEIF, T_ENDIF])) {
            echo "Line {$t[2]}: " . token_name($t[0]) . " - {$t[1]}\n";
        }
    }
}
