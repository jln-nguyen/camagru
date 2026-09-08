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

        if (!$image || $image['user_id'] !== $userId) {
            header('Location: /edit-images');
            exit;
        }

        Image::deleteImage($imageId);

        header('Location: /edit-images');
        exit;
    }
}