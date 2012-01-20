<?php

$suspectMethods = array(
    "/in_array\(/" => "in_array",
    "/array_search\(/" => "array_search",
    "/array_merge\(/" => "array_merge"
);

$modifiedFiles = getFiles('M');
$addedFiles = getFiles('A');

$output = array_merge($modifiedFiles, $addedFiles);

$currentDir = dirname ( __FILE__ ); 

foreach ($output as $line) {
    $file = $currentDir.DIRECTORY_SEPARATOR.$line;
    if (is_dir($file)) {
        continue;
    }

    $extension = pathinfo($file, PATHINFO_EXTENSION);

    if ($extension === 'php') {
        $diffCommand = "svn diff $file";
        $diffOutput = shell_exec($diffCommand);

        $diffOutput = explode("\n", $diffOutput);

        $matches = array();

        foreach ($diffOutput as $diffLine) {
            if (preg_match("/^\+{1}/", $diffLine)) {
                foreach ($suspectMethods as $suspectMethod => $opMethodName) {
                    if (preg_match($suspectMethod, $diffLine)) {
                        echo $file." =====> ".$diffLine."\n";
                    }
                }
            }
        }
    }
}

function getFiles($char) {
    $command = "svn status | grep ^$char | awk '{print $2;}'";
    $output = shell_exec($command);
    $output = explode("\n", $output);
    return $output;
}

?>
