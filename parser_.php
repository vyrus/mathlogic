<?php

    require_once 'Operator.php';
    require_once 'Expression.php';
    require_once 'Decorator/Interface.php';

    require_once 'Parser.php';
    require_once 'Parser/Rule/Abstract.php';
    require_once 'Parser/Rule/Regex.php';
    require_once 'Parser/Rule/Complex.php';
    require_once 'Parser/Rule/Or.php';

    $expr = '(P | Q) -> ((!P & Q) | (P & Q))';
    $expr = '(!P & Q) | (P & Q)';
    $expr = 'P | Q';
    $expr = '!P & Q';
    //$expr = 'P & Q';
    //$expr = '!P';
    //$expr = 'P';

    $parser = Parser::create();
    $expr = $parser->parse($expr);
    print_r($expr);

?>