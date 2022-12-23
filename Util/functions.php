<?php

use Util\ExecutionTime;

function getVal($value, $array = null, $default = null)
{
    if ($array !== null) {
        return $array[$value] ?? $default;
    } else {
        return $_REQUEST[$value] ?? $default;
    }
}

function constructNamespace(int $day): string
{
    return "Days\Day" . str_pad($day, 2, "0", STR_PAD_LEFT);
}

function constructDayClassName(int $day, int $part): string
{
    $partName = $part == 1 ? "First" : "Second";
    return "Day$day" . $partName;
}

function constructDayClassFullName(int $day, int $part): string
{
    $namespace = constructNamespace($day);
    return $namespace . "\\" . constructDayClassName($day, $part);
}

/**
 * create file with content, and create folder structure if doesn't exist
 * @param String $filepath
 * @param String $message
 */
function forceFilePutContents(string $filepath, string $message): void
{
    try {
        $isInFolder = preg_match("/^(.*)\/([^\/]+)$/", $filepath, $filepathMatches);
        if ($isInFolder) {
            $folderName = $filepathMatches[1];
            $fileName = $filepathMatches[2];
            if (!is_dir($folderName)) {
                mkdir($folderName, 0777, true);
            }
        }
        file_put_contents($filepath, $message);
    } catch (Exception $e) {
        echo "ERR: error writing '$message' to '$filepath', " . $e->getMessage();
    }
}

function sizeOfInterval(array $interval)
{
    return $interval[1] - $interval[0] + 1;
}

function everyNthChar(string $str, int $nth, int $offset = 0): array
{
    $count = mb_strlen($str);
    if ($count - $offset < $offset)
        return [];

    $range = range($offset, $count - $offset, $nth);
    return array_reduce($range, function ($res, $i) use ($str) {
        return [...$res, $str[$i]];
    }, []);
}

function transpose(array $array): array
{
    return array_map(null, ...$array);
}

function findIndex($array, $callback): int|string
{
    foreach ($array as $k => $v)
        if ($callback($v, $k, $array))
            return $k;

    return -1;
}

function reduce(iterable $iterator, callable $callback, $initial = null)
{
    foreach ($iterator as $item) {
        $initial = $callback($initial, $item);
    }
    return $initial;
}

function measureExecTime(callable $func, ...$args): array
{
    $executionTime = new ExecutionTime();
    $executionTime->start();
    $result = call_user_func($func, ...$args);
    $executionTime->end();
    return [
        'result' => $result,
        'execTime' => "$executionTime"
    ];
}
