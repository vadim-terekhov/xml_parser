<?php
require (__DIR__.'\ParceXMLClass.php');
use ParceXML\ParceXMLClass;

$res = new ParceXMLClass();
$res->set_path_XML('data.xml');
$res->insert_in_base();
$mas = $res->sort_mas();
//var_dump($mas);
$res->view_catalog($mas);
?>