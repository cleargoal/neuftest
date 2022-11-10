<?php
// here we will make multiplication
class Classthree {

    private $file = null;
    private $resultHandler;
    private $logHandler;
    private const LOG_FILE = "log.txt";
    private const RESULT_FILE = "result.csv";

    /**
     * Classthree constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->prepareFiles();
        $this->prepareHanders();
    }

    /**
     * destructor
     */
    public function __destruct()
    {
        $this->closeHandlers();
    }

    /**
     * main function, execute main code
     */
    public function execute(): void
    {
        $this->validateResourceFile();

        $this->logInfo("Started multiply operation");

        $handle = fopen($this->getFile(),'r');
        while ( ($line = fgetcsv($handle) ) !== FALSE ) {
            list($value1, $value2) = $this->prepareValues($line[0]);
            $result = $this->countResult($value1, $value2);
            if($this->isResultValid($result)) {
                $this->writeSuccessResult($value1, $value2, $result);
            } else {
                $this->wrongResultLog($value1, $value2);
            }
        }

        $this->logInfo("Finished multiply operation");
    }

    /**
     * write in logs if numbers give wrong result
     * @param int $value1
     * @param int $value2
     * @throws Exception
     */
    private function wrongResultLog(int $value1, int $value2) : void
    {
        $message = "numbers ".$value1 . " and ". $value2." are wrong";
        $this->logInfo($message);
    }

    /**
     * validate if result is valid
     * @param int $result
     * @return bool
     */
    private function isResultValid(int $result) : bool
    {
        if($result > 0)
            return true;

        return false;
    }

    /**
     * count result
     * @param int $value1
     * @param int $value2
     * @return int
     */
    private function countResult(int $value1, int $value2) : int
    {
        return $value2 * $value1;
    }

    /**
     * prepare numbers before action, explode it from csv string
     * @param string $line
     * @return array
     */
    private function prepareValues(string $line) : array
    {
        $line = explode(";", $line);
        $value1 = $this->prepareNumber($line[0]);
        $value2 = $this->prepareNumber($line[1]);
        return [$value1, $value2];

    }

    /**
     * prepare number before action
     * @param string $value
     * @return int
     */
    private function prepareNumber(string $value) : int
    {
        $value = trim($value);
        $value = intval($value);
        return $value;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function validateResourceFile() : void {
        if($this->getFile() === null) {
            throw new \Exception("Please define file with data");
        }

        if(!file_exists($this->getFile())) {
            throw new \Exception("Please define file with data");
        }

        if(!is_readable($this->getFile())) {
            throw new \Exception("We have not rights to read this file");
        }
    }


    /**
     * check and delete main files before execution
     */
    private function prepareFiles() : void
    {
        //delete log file if it is already exists
        if($this->isLogFileExists()) {
            unlink(self::LOG_FILE);
        }

        //delete result file if it already exists
        if($this->isResultFileExists()) {
            unlink(self::RESULT_FILE);
        }
    }

    /**
     * @param string $file
     */
    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFile() : string
    {
        return $this->file;
    }

    /**
     * check if result file already exists
     * @return bool
     */
    private function isResultFileExists() : bool
    {
        return file_exists(self::RESULT_FILE);
    }

    /**
     * @return bool
     */
    private function isLogFileExists() : bool
    {
        return file_exists(self::LOG_FILE);
    }

    /**
     * write messages in log file
     * @param string $message
     * @throws Exception
     */
    private function logInfo(string $message) : void
    {
        $message = $message."\r\n";
        fwrite($this->logHandler, $message);
    }

    /**
     * write message in result file
     * @param string $message
     */
    private function successInfo(string $message) : void
    {
        $message = $message."\r\n";
        fwrite($this->resultHandler, $message);
    }

    /**
     * prepare info and save it in result file
     * @param int $value1
     * @param int $value2
     * @param int $result
     */
    private function writeSuccessResult(int $value1, int $value2, int $result) : void
    {
        $message = implode(";", [$value1, $value2, $result]);
        $this->successInfo($message);
    }

    /**
     * prepare handlers to writing
     * @throws Exception
     */
    private function prepareHanders() : void
    {
        $this->logHandler = fopen(self::LOG_FILE, "a+");

        if($this->logHandler === false) {
            throw new \Exception("Log File cannot be open for writing");
        }

        $this->resultHandler = fopen(self::RESULT_FILE, "a+");

        if($this->resultHandler === false) {
            throw new \Exception("Result File cannot be open for writing");
        }
    }

    /**
     * close opened handlers
     */
    private function closeHandlers() : void
    {
        fclose($this->logHandler);
        fclose($this->resultHandler);
    }
}
?>