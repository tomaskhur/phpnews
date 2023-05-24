<?php

class CategoryRepository
{
    public function __construct(Database $db)
    {
        $this->dbConn = $db;
    }

    public function getCategories()
    {
        $sql = 'SELECT * FROM category';
        $data = $this->dbConn->selectAll($sql);
        return $data;
    }

    public function getCategory($id)
    {
        $sql = 'SELECT category.name FROM category 
                WHERE id = :id';

        $data = [
            ':id' => $id
        ];
        $category = $this->dbConn->selectOne($sql, $data);
        return $category;
    }

    public function getArticleCategoryForCheckbox($id)
    {
        $sql = 'SELECT category_id FROM article_category
                WHERE article_category.article_id = :article_id';

        $data = [
            ':article_id' => $id
        ];

        $category = $this->dbConn->selectAll($sql, $data);
        return $category;

    }

    public function getCategoriesArticles($id)
    {
        $sql = 'SELECT *, 
                category.id AS categoryId,
                category.name AS categoryName,
                article.id AS articleId,
                article.title AS articleTitle,
                article.perex AS articlePerex,
                article.text AS articleText,
                article.created_at AS articleDate
                FROM article_category
                INNER JOIN category ON article_category.category_id = category.id
                INNER JOIN article ON article_category.article_id = article.id
                WHERE category.id = :id
                ORDER BY article.created_at DESC';

        $data = [
            ':id' => $id
        ];

        $articles = $this->dbConn->selectAll($sql, $data);
        return $articles;
    }

    public function addCategoryToArticle($articleId, $categoryId)
    {
        $sql = 'INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)';

        $data = [
            ':article_id' => $articleId,
            ':category_id' => $categoryId
        ];

        $this->dbConn->insert($sql, $data);
    }

    public function updateCategory($categoryId, $name)
    {
        $sql = 'UPDATE category SET name = :name WHERE id = :id';

        $data = [
            ':id' => $categoryId,
            ':name' => $name
        ];

        $this->dbConn->update($sql, $data);
    }

    public function addCategory($name)
    {
        $sql = 'INSERT INTO category (name) VALUES (:name)';

        $data = [
            ':name' => $name
        ];

        $this->dbConn->insert($sql, $data);
    }

    public function deleteCategory($id)
    {
        $sql = 'DELETE FROM category WHERE id = :id';

        $data = [
            ':id' => $id
        ];

        $this->dbConn->delete($sql, $data);
    }

    public function cannotDeleteCategory($id)
    {
        $sql = 'SELECT COUNT(article_category.article_id) AS categoryCount FROM article_category
                WHERE article_category.category_id = :id';

        $data = [
            ':id' => $id
        ];

        $category = $this->dbConn->selectOne($sql, $data);
        return $category;
    }
}