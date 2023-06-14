<?php

function parseKindleClippings($clippingsFile) {
    $clippings = file_get_contents($clippingsFile);
    $clippings = str_replace("\r\n", "\n", $clippings);

    $matches = preg_split("/==========\n/", $clippings, -1, PREG_SPLIT_NO_EMPTY);
    $parsedClippings = [];

    foreach ($matches as $match) {
        $lines = explode("\n", trim($match));

        if (count($lines) >= 3) {
            $title = $lines[0];
            $metadata = $lines[1];
            $content = implode("\n", array_slice($lines, 2));

            if (isset($parsedClippings[$title])) {
                // If the title already exists, append the content to the existing clipping
                $parsedClippings[$title]['content'][] = $content;
            } else {
                // If the title doesn't exist, create a new clipping entry
                $parsedClippings[$title] = [
                    'title' => $title,
                    'metadata' => $metadata,
                    'content' => [$content],
                ];
            }
        }
    }

    // Convert associative array to indexed array
    $parsedClippings = array_values($parsedClippings);

    return $parsedClippings;
}

$clippingsFile = 'My Clippings.txt';
$parsedClippings = parseKindleClippings($clippingsFile);

$jsonArray = json_encode($parsedClippings, JSON_PRETTY_PRINT);
$data = json_decode($jsonArray, true);

echo "<pre>";
var_dump($jsonArray);
echo "</pre>";

?>
