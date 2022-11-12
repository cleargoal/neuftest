<?php

namespace app;

class Messaging
{
    /**
     * Handle echo messages
     *
     * @param $message
     * @return void
     */
    public function echoMessage($message): void
    {
        echo $message . PHP_EOL;
    }

}