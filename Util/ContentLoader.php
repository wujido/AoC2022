<?php

namespace Util;

class ContentLoader
{
    static int $TEST_CODE_BLOCK_NUMBER = 0;

    public function loadTask(int $day): string
    {
        $rawData = file_get_contents($this->constructTaskUrl($day), false, $this->constructContext());
        $pattern = "#<main>.*</main>#s";
        preg_match($pattern, $rawData, $matches);
        return $this->deleteForm($matches[0]);
    }

    public function loadInput(int $day): array
    {
        $rawData = file_get_contents($this->constructInputUrl($day), false, $this->constructContext());
        return $this->createArrayFromInput($rawData);
    }

    public function loadTestInput(int $day): array
    {
        $data = $this->loadTask($day);
        $pattern = "#<pre><code>(.*)</code></pre>#sU";
        preg_match_all($pattern, $data, $matches);

        return $this->createArrayFromInput($matches[1][self::$TEST_CODE_BLOCK_NUMBER]);
    }

    private function constructTaskUrl(int $day): string
    {
        return "https://adventofcode.com/2022/day/$day";
    }

    private function constructInputUrl(int $day): string
    {
        return $this->constructTaskUrl($day) . '/input';
    }

    private function constructContext()
    {
        return stream_context_create([
            "http" => [
                "method" => "GET",
                "header" => "Accept-languange: en-US,en;q=0.9\r\n" .
                    "Cookie: session=" . getenv("AOC_SESSION") . "\r\n"
            ]
        ]);
    }

    public function getAvailableDays(): float
    {
        $now = time();
        $startDate = strtotime("2022-12-01 00:00:00");
        $diff = $now - $startDate;
        $daysFromStart = ceil($diff / (60 * 60 * 24));

        return min([$daysFromStart, 25]);
    }

    public function getActiveDay(): float
    {
        if (time() > strtotime('2022-12-25'))
            return 1;

        return date('d');
    }

    private function createArrayFromInput($input): array
    {
        $input = explode("\n", $input);
        if (count($input) > 1)
            array_pop($input);

        return count($input) === 1 ? mb_str_split($input[0]) : $input;

    }

    private function deleteForm(string $content): array|string|null
    {
        $pattern = "#<form.*</form>#s";
        return preg_replace($pattern, '', $content);
    }
}