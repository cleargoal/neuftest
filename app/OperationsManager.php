<?php

namespace app;

use operations\BasicOperation;

class OperationsManager
{
    private const LOG_FILE = "log.txt";
    private const RESULT_FILE = "result.csv";
    private array $logData;
    private array $resultData;

    private BasicOperation $operation;
    private ValidatingResources $validator;
    private Messaging $messaging;

    public function __construct(ValidatingResources $validator, BasicOperation $operation, Messaging $messaging)
    {
        $this->validator = $validator;
        $this->operation = $operation;
        $this->messaging = $messaging;
    }

    /**
     * Start of operation
     *
     * @param $options
     *
     * @return bool
     */
    public function execute($options): bool
    {
        $parsedOpts = $this->validator->validateOptions($options);
        $this->validator->validateAction($parsedOpts['action']);
        $srcFile = $parsedOpts['srcFile'];
        $this->validator->cleanBom($srcFile);
        $this->validator->validateResourceFile($srcFile);
        $resultData = $this->operation->calculation($srcFile, $parsedOpts['action']);
        $this->messaging->echoMessage('Result. Correct data is: ' . count($resultData['goodResult']));
        $this->messaging->echoMessage('Result. Wrong data is: ' . count($resultData['logResult']));

        $this->validator->cleanReportFiles(self::LOG_FILE, self::RESULT_FILE);
        $this->saveCorrect($resultData['goodResult'], $parsedOpts['action']);
        $this->saveWrong($resultData['logResult'], $parsedOpts['action']);

        return true;
    }

    /**
     * Save correct results to file 'result.csv'
     *
     * @param array $correctData
     * @param       $action
     *
     * @return bool
     */
    public function saveCorrect(array $correctData, $action): bool
    {
        if (count($correctData) <= 0) {
            $this->messaging->echoMessage('No correct results!');
            return false;
        }

        $resultHandler = fopen(self::RESULT_FILE, "a+");

        if (!$this->validator->validateOutputFile(self::RESULT_FILE)) {
            return false;
        }

        fwrite($resultHandler, "Start __ $action __ operation at " . date('Y-m-d h:i:s') . PHP_EOL);
        foreach ($correctData as $item) {
            fputcsv($resultHandler, $item, ";");
        }
        fwrite($resultHandler, "Finish at " . date('Y-m-d h:i:s') . PHP_EOL);
        fclose($resultHandler);

        return true;
    }

    /**
     * Save wrong results to file 'log.txt'
     *
     * @param array $wrongData
     * @param       $action
     *
     * @return bool
     */
    public function saveWrong(array $wrongData, $action): bool
    {
        if (count($wrongData) <= 0) {
            $this->messaging->echoMessage('No wrong results!');
            return false;
        }

        $logHandler = fopen(self::LOG_FILE, "a+");

        if (!$this->validator->validateOutputFile(self::LOG_FILE)) {
            return false;
        }

        fwrite($logHandler, "Start log __ $action __ operation at " . date('Y-m-d h:i:s') . PHP_EOL);
        foreach ($wrongData as $item) {
            $line = "Numbers " . $item['valA'] . " and " . $item['valB']
                . " produced wrong result: " . $item['valR']
                . PHP_EOL;
            fwrite($logHandler, $line);
        }
        fwrite($logHandler, "Finish at " . date('Y-m-d h:i:s') . PHP_EOL);
        fclose($logHandler);

        return true;
    }


}