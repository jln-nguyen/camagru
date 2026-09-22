<?php

class EditController
{
    public function showEditImagesPage(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
 
        $userId = $_SESSION['user_id'];
        $images = Image::getImagesByUserId($userId);

        require __DIR__ . '/../Views/edit/edit_images.php';
    }

    public function deleteImage(string $imageId): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $imageId = (int)$imageId;

        $image = Image::getImageById($imageId);

        if (!$image || (int)$image['user_id'] !== $userId) {
            header('Location: /edit-images');
            exit;
        }

        Image::deleteImage($imageId);

        header('Location: /edit-images');
        exit;
    }

    public function newPost(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $userId = $_SESSION['user_id'];
        $photo = $_POST['photo'] ?? null;
        $overlays = $_POST['overlays'] ?? [];

        if (!$photo || !$overlays) {
            http_response_code(400);
            echo "No Photo Sent";
            return ;
        }

        $data = str_replace('data:image/png;base64,', '', $photo);
        $data = base64_decode($data);

        $im = imagecreatefromstring($data);
        $filename = "/uploads/" . uniqid() . ".png";
        if ($im !== false) {
            foreach ($overlays as $overlay_file) {
                $overlay_path = __DIR__ . "/../../public" . $overlay_file;
                $overlay_im = imagecreatefrompng($overlay_path);
                $new_overlay = imagecreatetruecolor(imagesx($im), imagesy($im));
                imagealphablending($new_overlay, false);
                imagesavealpha($new_overlay, true);
                imagecopyresized($new_overlay, $overlay_im, 0, 0, 0, 0, imagesx($new_overlay), imagesy($new_overlay), imagesx($overlay_im), imagesy($overlay_im));
                imagesavealpha($im, true);
                imagecopy($im, $new_overlay, 0, 0, 0, 0, imagesx($im), imagesy($im));
            }
            $im_path = __DIR__ . "/../../public" . $filename;
            imagepng($im, $im_path);
        }
        else {
            echo 'An error occurred.';
            return ;
        }
        Image::createImage($userId, $filename);
    }
}