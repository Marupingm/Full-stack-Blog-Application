<?php
// Create uploads directories if they don't exist
$dirs = [
    'uploads',
    'uploads/posts',
    'uploads/profiles'
];

foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        echo "Created directory: $dir\n";
    } else {
        echo "Directory already exists: $dir\n";
    }
}

// Create .htaccess to allow image access
$htaccess = <<<'EOT'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^.*$ - [NC,L]
</IfModule>

<FilesMatch "\\.(jpg|jpeg|png|gif)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>
EOT;

file_put_contents('uploads/.htaccess', $htaccess);
echo "Created .htaccess file in uploads directory\n";

echo "\nSetup completed successfully!"; 