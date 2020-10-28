<?php
require_once __DIR__.'/ApiEmailCreci.php';

$api = new ApiEmailCreci(14);
$x = $api->verificarEmail('suporte@crecims.gov.br');
var_dump($x);