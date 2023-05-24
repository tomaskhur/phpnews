<?php

class ArticleRepository
{

    public function __construct(Database $db)
    {
        $this->dbConn = $db;
    }


    public function getArticles()
    {
        $sql = 'SELECT article.*, author.name AS authorName, author.id AS authorId, article.id AS articleId, author.surname AS authorSurname, GROUP_CONCAT(category.name) AS categoryName, GROUP_CONCAT(category.id) AS categoryId FROM article
                INNER JOIN author ON article.author_id = author.id
                INNER JOIN article_category ON article.id = article_category.article_id
                INNER JOIN category ON article_category.category_id = category.id
                WHERE article.visible = 1
                GROUP BY article.id
                ORDER BY article.created_at DESC LIMIT 5';

        $data = $this->dbConn->selectAll($sql);
        return $data;
    }


    public function getAllArticles()
    {
        $sql = 'SELECT article.*, author.name AS authorName, author.surname AS authorSurname, GROUP_CONCAT(category.name) AS categoryName, GROUP_CONCAT(category.id) AS categoryId FROM article
                INNER JOIN author ON article.author_id = author.id
                INNER JOIN article_category ON article.id = article_category.article_id
                INNER JOIN category ON article_category.category_id = category.id
                WHERE article.visible = 1
                GROUP BY article.id
                ORDER BY article.created_at DESC';

        $data = $this->dbConn->selectAll($sql);
        return $data;
    }

    public function getAllArticlesForAdministration()
    {
        $sql = 'SELECT article.*, author.name AS authorName, author.surname AS authorSurname, GROUP_CONCAT(category.name) AS categoryName, GROUP_CONCAT(category.id) AS categoryId FROM article
                INNER JOIN author ON article.author_id = author.id
                INNER JOIN article_category ON article.id = article_category.article_id
                INNER JOIN category ON article_category.category_id = category.id
                GROUP BY article.id
                ORDER BY article.title ASC
                ';

        $data = $this->dbConn->selectAll($sql);
        return $data;
    }


    public function getArticle($id)
    {
        $sql = 'SELECT article.*, author.id AS authorId, author.name AS authorName, author.surname AS authorSurname, GROUP_CONCAT(category.name) AS categoryName, GROUP_CONCAT(category.id) AS categoryId FROM article
                INNER JOIN author ON article.author_id = author.id
                INNER JOIN article_category ON article.id = article_category.article_id
                INNER JOIN category ON article_category.category_id = category.id
                WHERE article.id = :id';

        $data = [
            ':id' => $id
        ];

        $article = $this->dbConn->selectOne($sql, $data);

        return $article;
    }

    public function updateArticle($articleId, $title, $perex, $text ,$visible, $image, $authorId)
    {
        $sql = 'UPDATE article SET title = :title, perex = :perex, text = :text ,visible = :visible, file_name = :file_name, author_id = :authorId WHERE id = :id';

        $data = [
            ':id' => $articleId,
            ':title' => $title,
            ':perex' => $perex,
            ':text' => $text,
            ':visible' => $visible,
            ':file_name' => $image,
            ':authorId' => $authorId
        ];

        $this->dbConn->update($sql, $data);
    }

    public function addArticle($title, $perex, $text, $visible, $image, $authorId)
    {
        $sql = 'INSERT INTO article (title, perex, text, author_id, visible, file_name, created_at) VALUES (:title, :perex, :text ,:authorId, :visible, :file_name, now())';

        $data = [
            ':title' => $title,
            ':perex' => $perex,
            ':text' => $text,
            ':visible' => $visible,
            ':file_name' => $image,
            ':authorId' => $authorId
        ];

        $newArticleId = $this->dbConn->insert($sql, $data);

        return $newArticleId;
    }

    public function deleteArticle($id)
    {
        $sql = 'DELETE FROM article WHERE id = :id';

        $data = [
            ':id' => $id
        ];

        $this->dbConn->delete($sql, $data);
    }

    public function deleteAllCategoriesFromArticle($id)
    {
        $sql = 'DELETE FROM article_category WHERE article_id = :id';

        $data = [
            ':id' => $id
        ];

        $this->dbConn->delete($sql, $data);
    }






}