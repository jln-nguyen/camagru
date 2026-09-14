<?php

class GalleryController
{
    public function index(): void
    {
        $perPage = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $totalImages = Image::countImages();
        $totalPages = max(1, (int)ceil($totalImages / $perPage));

        if ($page < 1) {
            $page = 1;
        } elseif ($page > $totalPages) {
            $page = $totalPages;
        }

        $images = Image::paginateImages($page, $perPage);
        $likesCount = [];
        $userLiked = [];
        $commentsCount = [];

        foreach ($images as $image) {
            $likesCount[$image['image_id']] = Like::countLikes($image['image_id']);
            $commentsCount[$image['image_id']] = Comment::countComments($image['image_id']);

            if (isset($_SESSION['user_id'])) {
                $userLiked[$image['image_id']] = Like::hasLiked($_SESSION['user_id'], $image['image_id']);
            } else {
                $userLiked[$image['image_id']] = false;
            }
        }

        require __DIR__ . '/../Views/gallery/index.php';
    }

    public function show(string $imageId): void
    {
        $imageId = (int)$imageId;
        echo $imageId;
        $image = Image::getImageById($imageId);
        if (!$image) {
            header('Location: /');
            exit;
        }

        $likesCount = Like::countLikes($imageId);

        if (isset($_SESSION['user_id'])) {
            $userLiked = Like::hasLiked($_SESSION['user_id'], $imageId);
        } else {
            $userLiked = false;
        }

        $commentsCount = Comment::countComments($imageId);
        $comments = Comment::getCommentsByImageId($imageId);

        require __DIR__ . '/../Views/gallery/show.php';
    }

    public function like(string $imageId): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $imageId = (int)$imageId;

        Like::toggleLike($userId, $imageId);

        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: /');
        }
        exit;
    }

    public function comment(string $imageId): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $imageId = (int)$imageId;
        $commentText = trim($_POST['comment']);

        if (!empty($commentText)) {
            Comment::addComment($imageId, $userId, $commentText);
            $info_author = Image::getImageById($imageId);
            if ($info_author && $info_author['notify_on_comment'] && $info_author['user_id'] != $userId) {
                $authorEmail = $info_author['email'];
                $subject = "New Comment on Your Image";
                $message = "Hello " . $info_author['username'] . ",\n\nYou have a new comment on your image:\n\n" . $commentText . "\n\nYou can unsubscribe from notifications in your profile settings.";
                $headers = "From: no-reply@camagru.com";
                mail($authorEmail, $subject, $message, $headers);
            }
        }

        header('Location: /gallery/' . $imageId);
        exit;
    }
}

?>