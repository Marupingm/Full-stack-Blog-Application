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
    }
}

// Create a default profile image
$width = 200;
$height = 200;
$profile_image = imagecreatetruecolor($width, $height);
$bg_color = imagecolorallocate($profile_image, 238, 238, 238);
$text_color = imagecolorallocate($profile_image, 150, 150, 150);
imagefill($profile_image, 0, 0, $bg_color);
imagestring($profile_image, 5, 50, 90, "No Profile", $text_color);
imagestring($profile_image, 5, 65, 110, "Image", $text_color);
imagepng($profile_image, 'uploads/profiles/default.jpg');
imagedestroy($profile_image);

// Create a default post image
$width = 800;
$height = 400;
$post_image = imagecreatetruecolor($width, $height);
$bg_color = imagecolorallocate($post_image, 238, 238, 238);
$text_color = imagecolorallocate($post_image, 150, 150, 150);
imagefill($post_image, 0, 0, $bg_color);
imagestring($post_image, 5, 350, 190, "No Post Image", $text_color);
imagepng($post_image, 'uploads/posts/default.jpg');
imagedestroy($post_image);

echo "Default images created successfully!";
?> //  
