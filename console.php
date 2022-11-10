<?php

$shortopts = "a:f:";
$longopts  = array(
    "action:",
    "file:",
);

$options = getopt($shortopts, $longopts);

if(isset($options['a'])) {
    $action = $options['a'];
} elseif(isset($options['action'])) {
    $action = $options['action'];
} else {
    $action = "xyz";
}

if(isset($options['f'])) {
    $file = $options['f'];
} elseif(isset($options['file'])) {
    $file = $options['file'];
} else {
    $file = "notexists.csv";
}

try {
    if ($action == "plus") {
        include 'files/ClassOne.php';
        $classOne = new ClassOne($file);
    } elseif ($action == "minus") {
        include 'files/ClassTwo.php';
        $classTwo = new ClassTwo($file, "minus");
        $classTwo->start();
    } elseif ($action == "multiply") {
        include 'files/Classthree.php';
        $classThree = new Classthree();
        $classThree->setFile($file);
        $classThree->execute();
    } elseif ($action == "division") {
        include 'files/classFour.php';
        $classFouyr = new classFour($file);
    } else {
        throw new \Exception("Wrong action is selected");
    }
} catch (\Exception $exception) {}