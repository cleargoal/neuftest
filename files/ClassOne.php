<?php
// we will count sum here
class ClassOne
{
    function __construct($file)
    {
        $this->start();
        $fp = fopen($file, "r");
        $row = 1;
        while (($data = fgetcsv($fp, 1000, ";")) !== FALSE) {
            if($this->isGood($data[0], $data[1])) {
                $this->result($data[0], $data[1]);
            } else {
                $this->wrongNumbers($data[0], $data[1]);
            }
        }
        fclose($fp);
        $this->stop();
    }

    function isGood($a, $b)
    {
        if($a < 0 && $b < 0) return false;
        if($a < 0 && (abs($a) > $b)) return false;
        if($b < 0 && (abs($b) > $a)) return false;
        return true;
    }

    function wrongNumbers($a, $b)
    {
        $fp = fopen("log.txt", "a+");
        fwrite($fp, "numbers ".$a . " and ". $b." are wrong \r\n");
        fclose($fp);
    }
    
    function start() {
        $fp = fopen("log.txt", "w+");
        fwrite($fp, "Started plus operation \r\n");
        fclose($fp);
    }

    function stop() {
        $fp = fopen("log.txt", "a+");
        fwrite($fp, "Finished plus operation \r\n");
        fclose($fp);
    }

    function result($a, $b)
    {
        $a = intval($a);
        $b = intval($b);
        $result = $a + $b;
        $fp = fopen("result.csv", "a+");
        $data = $a.";".$b.";".$result."\r\n";
        fwrite($fp, $data);
        fclose($fp);
    }
}
?>