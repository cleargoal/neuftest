<?php
//here we will get minus
class ClassTwo
{
    private $file;
    private $action;

    public function __construct($file, $action = "minus")
    {
        $this->file = $file;
        $this->action = $action;
    }

    public function start()
    {
        $fp = fopen("log.txt", "w+");
        fwrite($fp, "Started minus operation \r\n");

        $data = fopen($this->file, "r");
        if(!$data) throw new \Exception("File cannot be openned");

        if(file_exists("result.csv")) {
            unlink("result.csv");
        }

        while (($line = fgets($data)) !== false) {
            $line = explode(";", $line);
            $line[0] = intval($line[0]);
            $line[1] = intval($line[1]);
            $result = $line[0] - $line[1];
            if($result < 0) {
                fwrite($fp, "numbers ".$line[0] . " and ". $line[1]." are wrong \r\n");
            } else {
                $resultHandle = fopen("result.csv", "a+");
                $result = $line[0].";".$line[1].";".$result."\r\n";
                fwrite($resultHandle, $result);
                fclose($resultHandle);
            }
        }

        fwrite($fp, "Finished minus operation \r\n");
        fclose($fp);
        fclose($data);
    }
}