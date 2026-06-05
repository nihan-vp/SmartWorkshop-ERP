<?php
$directory = new RecursiveDirectoryIterator('resources/views');
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$count = 0;
foreach ($regex as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Using regex to replace onsubmit="return confirm('...')" with onsubmit="if(!confirm('...')) { event.preventDefault(); return false; }"
    // Be careful with quotes. Sometimes it's return confirm("...")
    
    $newContent = preg_replace_callback('/onsubmit="return confirm\(([\'"])(.*?)\1\)"/s', function($matches) {
        $quote = $matches[1];
        $msg = $matches[2];
        return 'onsubmit="if(!confirm(' . $quote . $msg . $quote . ')) { event.preventDefault(); return false; }"';
    }, $content);
    
    if ($newContent !== $content && $newContent !== null) {
        file_put_contents($path, $newContent);
        $count++;
    }
}
echo "Replaced in $count files.";
