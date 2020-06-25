<?php
require_once ("session.php");
require_once ("genera_pagina.php");

$contenuto = file_get_contents("../html/index.html");
$pagina = Genera_pagina::genera("../html/base.html", "index", $contenuto);
echo $pagina;

?>
