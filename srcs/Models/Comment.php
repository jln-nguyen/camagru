<?php

class Comment
{
    public static function countComments(int $imageId): int
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM comments WHERE image_id = :image_id');
        $stmt->bindParam(':image_id', $imageId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
    // public static function getCommentsByImageId(int $imageId): array
    // {
    //     $pdo = Database::getInstance()->getConnection();
    //     $stmt = $pdo->prepare('SELECT * FROM comments WHERE image_id = :image_id ORDER BY created_at DESC');
    //     $stmt->bindParam(':image_id', $imageId, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public static function getCommentsByImageId(int $imageId): array
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare('
            SELECT comments.*, users.username 
            FROM comments 
            JOIN users ON comments.user_id = users.id 
            WHERE comments.image_id = :image_id 
            ORDER BY comments.created_at DESC
        ');
        $stmt->bindParam(':image_id', $imageId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addComment(int $imageId, int $userId, string $commentText): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare('INSERT INTO comments (image_id, user_id, comment_text) VALUES (:image_id, :user_id, :comment_text)');
        $stmt->bindParam(':image_id', $imageId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':comment_text', $commentText, PDO::PARAM_STR);
        return $stmt->execute();
    }
}

?>