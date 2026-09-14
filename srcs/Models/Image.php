<?php

class Image {
    
    public static function createImage(int $userId, string $imagePath): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'INSERT INTO images (user_id, file_path) VALUES (:userId, :imagePath)';
        $createImage = $pdo->prepare($sqlQuery);
        return $createImage->execute([
            'userId' => $userId,
            'imagePath' => $imagePath,
        ]);
    }

    public static function paginateImages(int $page, int $perPage): array
    {
        $pdo = Database::getInstance()->getConnection();
        $offset = ($page - 1) * $perPage;
        $sqlQuery = 'SELECT images.id AS image_id, images.file_path, images.created_at,
                            users.id AS user_id, users.username
                    FROM images
                    JOIN users ON images.user_id = users.id
                    ORDER BY images.created_at DESC
                    LIMIT :limit OFFSET :offset';
        $paginateImages = $pdo->prepare($sqlQuery);
        $paginateImages->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $paginateImages->bindValue(':offset', $offset, PDO::PARAM_INT);
        $paginateImages->execute();
        return $paginateImages->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countImages(): int
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'SELECT COUNT(*) as total FROM images';
        $stmt = $pdo->prepare($sqlQuery);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $row['total'];
    }

    public static function getImageById(int $imageId): ?array
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'SELECT images.id AS image_id, images.file_path, images.created_at,
                            users.id AS user_id, users.username, users.email, users.notify_on_comment
                    FROM images
                    JOIN users ON images.user_id = users.id
                    WHERE images.id = :imageId';
        $getImage = $pdo->prepare($sqlQuery);
        $getImage->execute(['imageId' => $imageId]);
        return $getImage->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function getImagesByUserId(int $userId): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'SELECT * FROM images WHERE user_id = :userId ORDER BY created_at DESC';
        $getImages = $pdo->prepare($sqlQuery);
        $getImages->execute(['userId' => $userId]);
        return $getImages->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteImage(int $imageId): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'DELETE FROM images WHERE id = :imageId';
        $deleteImage = $pdo->prepare($sqlQuery);
        return $deleteImage->execute(['imageId' => $imageId]);
    }
}
?>