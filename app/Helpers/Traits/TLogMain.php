<?php

namespace App\Helpers\Traits;

use Illuminate\Support\Facades\Log;

trait TLogMain
{
    private string $FileLog = "system";

    /**
     * @param string $process
     * @param mixed $data
     * @param string|null $fileLog
     */
    public function logProcess(string $process , mixed $data , string $fileLog = null):void{
        $LogData = null;
        $LogData["process"] = $process;

        $LogData ["request"] = [
            "url" => request()->getUri(),
            "method" => request()->getMethod(),
            "body" => request()->all(),
        ];

        $LogData["body"] = $data;

        $fileLog = is_null($fileLog) ? $this->FileLog : $fileLog;

        Log::channel($fileLog)->info(json_encode($LogData));
    }
}
