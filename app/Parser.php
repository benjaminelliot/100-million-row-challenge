<?php

namespace App;

final class Parser
{
    public function parse(string $inputPath, string $outputPath): void
    {
        $file = fopen($inputPath, 'r');
        $finalMap = [];

        while($csv = fgetcsv(stream: $file, escape: "\\")) {
            if ($csv) {
                $url = $csv[0];
                $baseUrl = "https://stitcher.io";
                $path = str_replace($baseUrl, '', $url);
                $date = substr($csv[1], 0, 10);

                if (!array_key_exists($path, $finalMap)) {
                    $finalMap[$path] = [];
                }

                if (!array_key_exists($date, $finalMap[$path])) {
                    $finalMap[$path][$date] = 1;
                }
                else {
                    $finalMap[$path][$date] += 1;
                }
            }
        }

        fclose($file);

        $outputFile = fopen($outputPath, 'w');
        fwrite($outputFile, json_encode($finalMap, JSON_PRETTY_PRINT));
        fclose($outputFile);
    }
}
