<?php

$suspectMethods = array(
    "/in_array\(/" => "in_array",
    "/array_key_exists\(/" => "array_key_exists",
    "/array_merge\(/" => "array_merge"
);

$modifiedFilesCommand = "svn status | grep ^M | awk '{print $2;}'";
$output = shell_exec($modifiedFilesCommand);
$output = explode("\n", $output);

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

?>
