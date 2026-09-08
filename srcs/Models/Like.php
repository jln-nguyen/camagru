<?php

class Like
{
    public static function toggleLike(int $userId, int $imageID): bool
    {
        $pdo = Database::getInstance()->getConnection();
        if (self::hasLiked($userId, $imageID)) {
            $sqlQuery = 'DELETE FROM likes WHERE user_id = :userId AND image_id = :imageID';
            $deleteLike = $pdo->prepare($sqlQuery);
            $deleteLike->execute([
                'userId' => $userId,
                'imageID' => $imageID,
            ]);
            return false;
        } else {
            $sqlQuery = 'INSERT INTO likes (user_id, image_id) VALUES (:userId, :imageID)';
            $insertLike = $pdo->prepare($sqlQuery);
            $insertLike->execute([
                'userId' => $userId,
                'imageID' => $imageID,
            ]);
            return true;
        }
    }

    public static function hasLiked(int $userId, int $imageID): bool
    {
        $sqlQuery = 'SELECT id FROM likes WHERE user_id = :userId AND image_id = :imageID';
        $pdo = Database::getInstance()->getConnection();
        $checkLike = $pdo->prepare($sqlQuery);
        $checkLike->execute([
            'userId' => $userId,
            'imageID' => $imageID,
        ]);
        return ($checkLike->rowCount() > 0);
    }

    public static function countLikes(int $imageID): int
    {
        $sqlQuery = 'SELECT COUNT(*) as total FROM likes WHERE image_id = :imageID';
        $pdo = Database::getInstance()->getConnection();
        $countLikes = $pdo->prepare($sqlQuery);
        $countLikes->execute(['imageID' => $imageID]);
        $row = $countLikes->fetch(PDO::FETCH_ASSOC);
        return (int) $row['total'];
    }

}