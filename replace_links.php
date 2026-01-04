<?php
$files = [
    'index.html', 'shop.html', 'product.html', 'cart.html', 'checkout.html',
    'login.html', 'register.html', 'profile.html', 'my_orders.html', 
    'order_view.html', 'wallet.html', 'categories.html', 'contact.html', 
    'order_success.html', 'about.html', 'privacy.html', 'refund.html'
];

$replacements = [
    '/index\.php/' => 'index.html',
    '/shop\.php(\?[^"\'\s>]*)?/' => 'shop.html',
    '/product\.php(\?[^"\'\s>]*)?/' => 'product.html',
    '/cart\.php/' => 'cart.html',
    '/checkout\.php/' => 'checkout.html',
    '/login\.php/' => 'login.html',
    '/register\.php/' => 'register.html',
    '/profile\.php/' => 'profile.html',
    '/my_orders\.php/' => 'my_orders.html',
    '/order_view\.php(\?[^"\'\s>]*)?/' => 'order_view.html',
    '/wallet\.php/' => 'wallet.html',
    '/categories\.php/' => 'categories.html',
    '/contact\.php/' => 'contact.html',
    '/order_success\.php(\?[^"\'\s>]*)?/' => 'order_success.html',
    '/page\.php\?name=about/' => 'about.html',
    '/page\.php\?name=privacy/' => 'privacy.html',
    '/page\.php\?name=refund/' => 'refund.html',
    '/window\.location\.href=\'([^\']+)\.php\'/' => "window.location.href='$1.html'"
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    foreach ($replacements as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }
    file_put_contents($file, $content);
    echo "Processed $file\n";
}
?>
