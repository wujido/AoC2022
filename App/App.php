<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;
use Util\ContentLoader;

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
        $this->executeDay($day);
    }

    #[NoReturn] private function executeDay(int $day): void
    {
        $partOneClass = constructDayClassFullName($day, 1);

        $classNotFound = "Class dont exists";

        if (class_exists($partOneClass)) {
            $partOne = new $partOneClass();
            $partOneRes = $partOne->run($this->contentLoader->loadInput($day));
        } else {
            $partOneRes = $classNotFound;
        }

        $partTwoClass = constructDayClassFullName($day, 2);
        if (class_exists($partTwoClass)) {
            $partTwo = new $partTwoClass();
            $partTwoRes = $partTwo->run($this->contentLoader->loadInput($day));
        } else {
            $partTwoRes = $classNotFound;
        }

        $this->send([
            $partOneRes,
            $partTwoRes
        ]);
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