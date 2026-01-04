<?php
$files = glob('*.html');

foreach ($files as $file) {
    if ($file == 'replace_links.php' || $file == 'fix_html.php') continue;
    
    $content = file_get_contents($file);
    $original = $content;
    
    // Fix product references not followed by quote
    // matches href="product.html NOT followed by "
    $content = preg_replace('/href="product\.html(?!["])/', 'href="product.html">', $content);
    
    // Same for shop.html
    $content = preg_replace('/href="shop\.html(?!["])/', 'href="shop.html">', $content);
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Fixed $file\n";
    }
}
?>
