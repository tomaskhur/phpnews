<?php

class authorRepository
{
    public function __construct(Database $db)
    {
        $this->dbConn = $db;
    }

    public function getAuthors()
    {
        $sql = 'SELECT * FROM author';
        $data = $this->dbConn->selectAll($sql);
        return $data;
    }

    public function getAuthorArticles($id)
    {
            $sql = 'SELECT article.*, author.name AS authorName, author.surname AS authorSurname, GROUP_CONCAT(category.name) AS categoryName, GROUP_CONCAT(category.id) AS categoryId, author.id AS authorId FROM article
                INNER JOIN author ON article.author_id = author.id
                INNER JOIN article_category ON article_category.article_id = article.id
                INNER JOIN category ON article_category.category_id = category.id
                WHERE author.id = :id
                GROUP BY article.id
                ORDER BY article.created_at DESC';


        $data = [
            ':id' => $id
        ];

        $articles = $this->dbConn->selectAll($sql, $data);

        return $articles;
    }

    public function getAuthorArticlesForCategory($id)
    {
        $sql = 'SELECT article.*, author.name AS authorName, author.surname AS authorSurname, GROUP_CONCAT(category.name) AS categoryName, GROUP_CONCAT(category.id) AS categoryId, author.id AS authorId FROM article
                INNER JOIN author ON article.author_id = author.id
                INNER JOIN article_category ON article_category.article_id = article.id
                INNER JOIN category ON article_category.category_id = category.id
                WHERE article.id = :id';


        $data = [
            ':id' => $id
        ];

        $articles = $this->dbConn->selectAll($sql, $data);

        return $articles;
    }

    public function getAuthor($id)
    {
        $sql = 'SELECT * FROM author WHERE id = :id';

        $data = [
            ':id' => $id
        ];

        $author = $this->dbConn->selectOne($sql, $data);

        return $author;
    }

    public function updateAuthor($authorId ,$name, $surname, $email, $visible)
    {
        $sql = 'UPDATE author SET name = :name, surname = :surname, email = :email, visible = :visible WHERE id = :id';

        $data = [
            ':id' => $authorId,
            ':name' => $name,
            ':surname' => $surname,
            ':email' => $email,
            'visible' => $visible
        ];

        $this->dbConn->update($sql, $data);
    }

    public function addAuthor($name, $surname, $email, $password, $visible)
    {
        $sql = 'INSERT INTO author (name, surname, email, password, visible) VALUES (:name, :surname, :email, :password, :visible)';

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            ':name' => $name,
            ':surname' => $surname,
            ':email' => $email,
            ':password' => $passwordHash,
            'visible' => $visible
        ];

        $this->dbConn->insert($sql, $data);
    }

    public function deleteAuthor($id)
    {
        $sql = 'DELETE FROM author WHERE id = :id';

        $data = [
            ':id' => $id
        ];

        $this->dbConn->delete($sql, $data);
    }

    public function cannotDeleteAuthor($id)
    {
        $sql = 'SELECT COUNT(article.id) AS authorCount FROM article
                WHERE article.author_id = :id';

        $data = [
            ':id' => $id
        ];

        $article = $this->dbConn->selectOne($sql, $data);

        return $article;
    }


    //   ******* LOGIN *******

    public function getUserCredentials($email)
    {
        $sql = 'SELECT * FROM author WHERE email = :email';

        $data = [
            ':email' => $email
        ];

        $user = $this->dbConn->selectOne($sql, $data);

        return $user;
    }

    public function changePassword($authorId, $password)
    {
        $sql = 'UPDATE author SET password = :password WHERE id = :id';

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            ':id' => $authorId,
            ':password' => $passwordHash,
        ];

        $this->dbConn->update($sql, $data);
    }
}