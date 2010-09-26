<?php

    require_once 'GrammarParcer2SQLToken.php';

    $input = '(aa&bb)^(!cc^!(dd^ee))';
    $parcer = new GrammarParcer2SQLToken();
    echo $parcer->parce($input);
    echo "\n".$parcer->getError();

?>