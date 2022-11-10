<?php
// here we will make division
class classFour{

    public function __construct($file)
    {
        if(file_exists("log.txt")) {
            unlink("log.txt");
        }

        $fp = fopen("log.txt", "w+");
        fwrite($fp, "Started division operation \r\n");

        $data = fopen($file, "r");

        if(file_exists("result.csv")) {
            unlink("result.csv");
        }

        while (($line = fgets($data)) !== false) {
            $line = explode(";", $line);
            $line[0] = intval($line[0]);
            $line[1] = intval($line[1]);
            if($line[1] === 0) {
                fwrite($fp, "numbers ".$line[0] . " and ". $line[1]." are wrong \r\n");
                continue;
            }
            $result = $line[0] / $line[1];
            if($result < 0) {
                fwrite($fp, "numbers ".$line[0] . " and ". $line[1]." are wrong \r\n");
            } else {
                $resultHandle = fopen("result.csv", "a+");
                $result = $line[0].";".$line[1].";".$result."\r\n";
                fwrite($resultHandle, $result);
                fclose($resultHandle);
            }
        }

        fwrite($fp, "Finished division operation \r\n");
        fclose($fp);
        fclose($data);
    }
}