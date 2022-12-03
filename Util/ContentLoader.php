<?php
namespace Util;

class ContentLoader
{
    public function loadTask(int $day): string
    {
        $rawData = file_get_contents($this->constructTaskUrl($day), false, $this->constructContext());
        $pattern = "#<article class=\"day-desc\">.*</article>#s";
        preg_match($pattern, $rawData, $matches);
        return $matches[0];
    }

    public function loadInput(int $day): array
    {
        $rawData = file_get_contents($this->constructInputUrl($day), false, $this->constructContext());
        return explode("\n", $rawData);
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
            ]
        ]);
    }

    public function getAvailableDays(): float
    {
        $now = time();
        $startDate = strtotime("2022-12-01");
        $diff = $now - $startDate;
        $daysFromStart = round($diff / (60 * 60 * 24));

        return min([$daysFromStart, 25]);
    }

    public function getActiveDay(): float {
        if (time() > strtotime('2022-12-25'))
            return 1;

        return date('d');
    }
}