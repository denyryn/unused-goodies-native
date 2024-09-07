<?php 
    function getCurrentUserImagePath($user_id) {
        global $pdo;
        $sql = 'SELECT profile_photo
                FROM users
                WHERE user_id = :user_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    
        // Fetch the result as an associative array
        $result = $stmt->fetch();
    
        // Return the profile_photo path or an empty string if not found
        return isset($result['profile_photo']) ? $result['profile_photo'] : '';
    }
?>
