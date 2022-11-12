<?php
include_once 'app/OperationsManager.php';
include_once 'app/ValidatingResources.php';
include_once 'app/Messaging.php';
include_once 'operations/BasicOperation.php';

use app\ValidatingResources;
use app\Messaging;
use app\OperationsManager;
use operations\BasicOperation;

$shortOpts = "a:f:";
$longOpts  = array(
    "action:",
    "file:",
);

$options = getopt($shortOpts, $longOpts);

$operaMng = new OperationsManager(
    new ValidatingResources(new Messaging()),
    new BasicOperation(new Messaging()),
    new Messaging()
);
$operaMng->execute($options);
