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
    $listFile = $viewsDir . '/' . $dir . '/list.blade.php';
    $tableFile = $viewsDir . '/' . $dir . '/table.blade.php';

    // Process list.blade.php
    if (file_exists($listFile)) {
        $content = file_get_contents($listFile);
        
        // Find Due Amount column and prepend Paid Milestone
        if (!str_contains($content, '<th> Paid Milestone </th>') && !str_contains($content, '<th>Paid Milestone</th>')) {
            $content = preg_replace(
                '/(<th[^>]*>)\s*Due Amount/i',
                "<th data-tippy-content=\"Cant't sort by Paid Milestone\" style=\"cursor: pointer\"> Paid Milestone </th>\n                                    $1 Due Amount",
                $content
            );
            file_put_contents($listFile, $content);
            echo "Updated list.blade.php\n";
        }
    }

    // Process table.blade.php
    if (file_exists($tableFile)) {
        $content = file_get_contents($tableFile);

        // Find Due Amount column content and prepend Paid Milestone content
        // In the table, Due Amount is typically: {{ (int) $project->project_value - (int) $project->project_upfront }}
        // Or similar variations
        
        $paidMilestoneCol = "<td class=\"edit-route\" data-route=\"{{ route(str_replace('.index', '.edit', Route::currentRouteName()), \$project->id) }}\">\n                {{ \$project->projectMilestones->where('payment_status', 'Paid')->sum('milestone_value') }}\n            </td>";

        // Try to replace the Due Amount formula
        $replaced = false;
        
        $patterns = [
            '/(\{\{\s*\(int\)\s*\$project->project_value\s*-\s*\(int\)\s*\$project->project_upfront\s*\}\})/i',
            '/(\{\{\s*\(int\)\$project->project_value\s*-\s*\(int\)\$project->project_upfront\s*\}\})/i'
        ];

        foreach($patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                
                // First update the due amount calculation
                $newDueAmount = "{{ (int) \$project->project_value - ((int) \$project->project_upfront + (int) \$project->projectMilestones->where('payment_status', 'Paid')->sum('milestone_value')) }}";
                $content = preg_replace($pattern, $newDueAmount, $content);
                
                // Then insert the new paid milestone TD before it
                // We know this due amount usually sits inside a <td class="edit-route"... 
                // We will just do a regex to find the TD containing the newDueAmount
                
                $tdPattern = '/(<td[^>]*>\s*)' . preg_quote($newDueAmount, '/') . '/i';
                
                // We need the data-route from the original td. Let's just use the original td's class and data-route.
                // It's safer to extract it.
                if (preg_match('/(<td[^>]*edit-route[^>]*>)\s*' . preg_quote($newDueAmount, '/') . '/i', $content, $matches)) {
                    $tdElement = $matches[1];
                    $newTd = $tdElement . "\n                {{ \$project->projectMilestones->where('payment_status', 'Paid')->sum('milestone_value') }}\n            </td>\n            ";
                    
                    $content = preg_replace(
                        '/(<td[^>]*edit-route[^>]*>)\s*' . preg_quote($newDueAmount, '/') . '/i',
                        $newTd . "$1\n                " . $newDueAmount,
                        $content
                    );
                    $replaced = true;
                    break;
                }
            }
        }

        if ($replaced) {
            file_put_contents($tableFile, $content);
            echo "Updated table.blade.php\n";
        } else {
            echo "Could not match pattern in table.blade.php\n";
        }
    }
}
echo "Done.\n";
