
echo "Start Copy-Script`n`r";

$fileToCopy = Get-ChildItem $PSScriptRoot\MuehleOnline\dist\index.html;
$fileDestination = Get-ChildItem $PSScriptRoot\Backend\resources\views\main.blade.php;

echo "Deleting old files";
$folderToCopy = Get-ChildItem $PSScriptRoot\MuehleOnline\dist\assets\*;
Get-ChildItem $PSScriptRoot\Backend\public\assets\* | ForEach-Object { 
    echo "Delete File: $($_.FullName)";    
    $_.Delete();
}
echo "`n";
$folderDestination = Get-Item $PSScriptRoot\Backend\public\assets;

echo "Copy index file";
Copy-Item $fileToCopy -Destination $fileDestination;
echo "Copy asset files";
Copy-Item -Path $folderToCopy -Destination $folderDestination -Recurse;

echo "End Copy-Script";