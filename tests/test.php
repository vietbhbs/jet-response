<?php
require_once './vendor/autoload.php';

use Viettqt\JetResponse\Response;

$response = new Response();

$response->setStatus(200);
$response->setBody('test');
$response->sendWithJson();

