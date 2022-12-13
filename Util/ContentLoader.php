<?php

namespace Util;

class ContentLoader
{
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
        return $this->createFinalInput($rawData);
    }

    public function loadTestInput(int $day): array
    {
        $data = $this->loadTask($day);
        $pattern = "#<pre><code>(.*)</code></pre>#sU";
        preg_match($pattern, $data, $matches);

        return $this->createFinalInput($matches[1]);
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

    private function createFinalInput($input): array|string
    {
        $input = explode("\n", $input);
        if (count($input) > 1)
            array_pop($input);

        return count($input) === 1 ? $input[0] : $input;

    }

    private function deleteForm(string $content): array|string|null
    {
        $pattern = "#<form.*</form>#s";
        return preg_replace($pattern, '', $content);
    }
}