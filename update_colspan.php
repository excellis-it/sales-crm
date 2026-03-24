<?php

$viewsDir = __DIR__ . '/resources/views';

$projectDirs = [
    'account_manager/project',
    'admin/bdm-project',
    'admin/project',
    'bde/project',
    'bdm/project',
    'sales_excecutive/project',
    'sales_manager/project'
];

foreach ($projectDirs as $dir) {
    echo "Processing $dir\n";
    $tableFile = $viewsDir . '/' . $dir . '/table.blade.php';

    if (file_exists($tableFile)) {
        $content = file_get_contents($tableFile);

        // Find colspan="\d+" and increment it
        $content = preg_replace_callback(
            '/(colspan=[\'"])(\d+)([\'"])/',
            function($matches) {
                // we added 1 column to the table
                $newVal = intval($matches[2]) + 1;
                return $matches[1] . $newVal . $matches[3];
            },
            $content
        );

        file_put_contents($tableFile, $content);
        echo "Updated colspan in table.blade.php\n";
    }
}
echo "Done.\n";
