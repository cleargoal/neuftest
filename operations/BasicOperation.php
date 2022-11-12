<?php

namespace operations;

use app\Messaging;

/**
 * Basic class for all operations
 */
class BasicOperation
{
    private Messaging $messaging;
    private string $operation;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }


    /**
     * Calculation, Get source data into array and dispatch operation
     *
     * @param $srcFile
     * @param $action
     *
     * @return bool|array
     */
    public function calculation($srcFile, $action): bool|array
    {
        $this->operation = $action;
        $srcArray = file($srcFile);
        $this->messaging->echoMessage('Src file length is: ' . count($srcArray));

        $goodResult = [];
        $logResult = [];

        foreach ($srcArray as $key => $value) {
            $rowResult = $this->oneRowOperation($value);
            if ($rowResult['valR'] > 0) {
                $goodResult[] = $rowResult;
            }
            else {
                $logResult[] = $rowResult;
            }
        }

        return ['goodResult' => $goodResult, 'logResult' => $logResult];
    }

    /**
     * Operating 1 current row of data
     *
     * @param $row
     *
     * @return mixed
     */
    private function oneRowOperation($row): mixed
    {
        $values = explode(';', $row);
        $a = intval(trim($values[0]));
        $b = intval(trim($values[1]));

        $oMethod = $this->operation;
        $rowResult = $this->$oMethod($a, $b);
        return ['valA' => $a, 'valB' => $b, 'valR' => $rowResult];
    }

    /**
     * Addition
     *
     * @param $a
     * @param $b
     *
     * @return mixed
     */
    private function plus($a, $b): mixed
    {
        return $a + $b;
    }

    /**
     * Extraction
     *
     * @param $a
     * @param $b
     *
     * @return mixed
     */
    private function minus($a, $b): mixed
    {
        return $a - $b;
    }

    /**
     * Multiply
     *
     * @param $a
     * @param $b
     *
     * @return float|int
     */
    private function multiply($a, $b): float|int
    {
        return $a * $b;
    }

    /**
     * Division
     *
     * @param $a
     * @param $b
     *
     * @return string|int|float
     */
    private function division($a, $b): string|int|float
    {
        return $b !== 0 ? ($a / $b) : 'is not allowed';
    }


}