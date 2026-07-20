<?php
$directory = new RecursiveDirectoryIterator(__DIR__);
$iterator = new RecursiveIteratorIterator($directory);

foreach ($iterator as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    $normalizedPath = str_replace('\\', '/', $path);
    
    // Skip vendor, storage, node_modules, .git, and this script itself
    if (
        strpos($normalizedPath, '/vendor/') !== false ||
        strpos($normalizedPath, '/storage/') !== false ||
        strpos($normalizedPath, '/node_modules/') !== false ||
        strpos($normalizedPath, '/.git/') !== false ||
        strpos($normalizedPath, '/public/') !== false ||
        strpos($normalizedPath, 'clean_comments.php') !== false
    ) {
        continue;
    }

    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if ($ext !== 'php') {
        continue;
    }

    $content = file_get_contents($path);
    $original = $content;

    // 1. Remove HTML comments
    $content = preg_replace('/<!--.*?-->/s', '', $content);
    
    // 2. Remove Blade comments
    $content = preg_replace('/\{\{--.*?--\}\}/s', '', $content);

    // 3. Remove inline // comments and block /* */ comments
    if (strpos($path, '.blade.php') === false) {
        // Pure PHP file: use tokenizer
        $tokens = token_get_all($content);
        $newContent = '';
        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (in_array($token[0], [T_COMMENT, T_DOC_COMMENT])) {
                    // Keep newlines from comments to avoid breaking formatting entirely
                    $newContent .= str_repeat("\n", substr_count($token[1], "\n"));
                    continue;
                }
                $newContent .= $token[1];
            } else {
                $newContent .= $token;
            }
        }
        $content = $newContent;
    } else {
        // Blade file
        $lines = explode("\n", $content);
        $newLines = [];
        foreach ($lines as $line) {
            // Find // that has whitespace before it or is at the start of line, 
            // and isn't part of an http:// URL
            if (preg_match('/(^|\s)\/\/(.*)/', $line, $matches, PREG_OFFSET_CAPTURE)) {
                // Ensure it's not a url
                if (!preg_match('/https?:\/\//', $line)) {
                    $line = rtrim(substr($line, 0, $matches[0][1]));
                }
            }
            $newLines[] = $line;
        }
        $content = implode("\n", $newLines);
    }

    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Cleaned: $normalizedPath\n";
    }
}
echo "Selesai membersihkan komentar!\n";
