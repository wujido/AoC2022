<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;
use Util\ContentLoader;
use Util\ExecutionTime;

class App
{
    private ContentLoader $contentLoader;

    public function __construct()
    {
        $this->contentLoader = new ContentLoader();
    }

    #[NoReturn] public function run(): void
    {
        if (!isset($_GET['day'])) {
            $this->badRequest("Please specify required argument `day`");
        }

        $day = getVal('day');
        $test = getVal('test');

        try {
            $this->executeDay($day, !is_null($test));
        } catch (\Throwable $e) {
            $message = $e->getMessage() . " in <i>" . $e->getFile() . "</i> on line <i>" . $e->getLine() . "</i>";
            $trace = $e->getTraceAsString();
            $msg = <<<EOD
                    <p>
                      <strong>$message</strong>
                    </p>
                    <p>$trace</p>
                    EOD;
            $this->send($msg, 500);
        }
    }

    #[NoReturn] private function executeDay(int $day, bool $test): void
    {
        $classNotFound = "Class dont exists";
        $partOneClass = constructDayClassFullName($day, 1);
        if (class_exists($partOneClass)) {
            $partOne = new $partOneClass();
        }

        $input = $test
            ? $this->contentLoader->loadTestInput($day)
            : $this->contentLoader->loadInput($day);

        $res = [
            ['result' => $classNotFound, 'execTime' => ''],
            ['result' => $classNotFound, 'execTime' => ''],
        ];

        if (isset($partOne)) {
            $res[0] = ExecutionTime::measure([$partOne, 'run'], $input);
        }

        $partTwoClass = constructDayClassFullName($day, 2);
        if (class_exists($partTwoClass)) {
            $partTwo = new $partTwoClass();
            $res[1] = ExecutionTime::measure([$partTwo, 'run'], $input);
        }

        $this->send($res);
    }


    #[NoReturn] private function send($msg, int $code = 200): void
    {
        http_response_code($code);
        exit(json_encode($msg));
    }

    #[NoReturn] private function badRequest($msg): void
    {
        $this->send($msg, 400);
    }
}