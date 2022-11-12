<?php

namespace app;

class ValidatingResources
{
    private Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;

    }

    /**
     * Validate options
     *
     * @param $options
     *
     * @return array|void
     */
    public function validateOptions($options)
    {
        if(isset($options['a'])) {
            $action = $options['a'];
        } elseif(isset($options['action'])) {
            $action = $options['action'];
        } else {
            $this->messaging->echoMessage('Action missed! Fatal error!');
            $this->messaging->echoMessage('Start again with correct parameters.');
            die();
        }

        if(isset($options['f'])) {
            $file = $options['f'];
        } elseif(isset($options['file'])) {
            $file = $options['file'];
        } else {
            $this->messaging->echoMessage('File missed! Fatal error!');
            $this->messaging->echoMessage('Start again with correct parameters.');
            die();
        }

        return ['action' => $action, 'srcFile' => $file];
    }

    /**
     * Validate action
     *
     * @param $srcAction
     */
    public function validateAction($srcAction): void
    {
        $actions = ['plus', 'minus', 'multiply', 'division',];
        if (!in_array($srcAction, $actions, $strict = true)) {
            $this->messaging->echoMessage('Operation __' . $srcAction . '__ not supported.');
            $this->messaging->echoMessage('Please, use these operations: plus, minus, multiply, division');
            $this->messaging->echoMessage('Please, try again');
            die();
        }
    }

    /**
     * @param $srcFile
     *
     * @return void
     */
    public function validateResourceFile($srcFile): void
    {
        $this->messaging->echoMessage('Validating Source CSV file.');
        if($srcFile === null) {
            $this->messaging->echoMessage("Please, define file with data.\n Abort!");
            exit();
        }

        if(!file_exists($srcFile)) {
            $this->messaging->echoMessage("File not exist.\n Abort!");
            exit();
        }

        if(!is_readable($srcFile)) {
            $this->messaging->echoMessage("We have not rights to read this file.\n Abort!");
            exit();
        }
    }

    /**
     * check and delete main operations before execution
     *
     * @param $logFile
     * @param $resFile
     */
    public function cleanReportFiles($logFile, $resFile): void
    {
        //delete log file if it is already exists
        if (file_exists($logFile)) {
            unlink($logFile);
        }

        //delete result file if it already exists
        if (file_exists($resFile)) {
            unlink($resFile);
        }
    }

    /**
     * Clean BOM from resource file (if exists)
     *
     */
    public function cleanBom($srcFile): void
    {
        $content = file_get_contents($srcFile);
        $bom = pack('CCC', 0xEF, 0xBB, 0xBF);

        if (strncmp($content, $bom, 3) === 0) {
            $body = substr($content, 3);
            file_put_contents($srcFile, $body);
        }
    }

    /**
     * Prepare Result handler to writing
     *
     * @param $fileName
     *
     * @return bool
     */
    public function validateOutputFile($fileName): bool
    {
        $outputFile = true;
        if (is_writable($fileName) === false) {
            $this->messaging->echoMessage("We can't save data. $fileName file not writeable.");
            $outputFile = false;
        }

        return $outputFile;
    }
}