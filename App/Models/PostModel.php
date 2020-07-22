<?php
    class PostModel extends Database
    {
        public function getAllPosts($user_id)
        {
            return $this->fetchAllRecords("
                SELECT * FROM posts
                WHERE user_id = $user_id
            ");
        }
    }
