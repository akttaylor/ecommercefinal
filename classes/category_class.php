<?php
require_once __DIR__ . '/../db_connection.php';

/**
 * Category Class - Handles all category database operations
 */
class Category extends DatabaseConnection {

    /**
     * Get all categories
     */
    public function getAllCategories() {
        try {
            $conn = $this->getConnection();
            $sql = "SELECT * FROM categories ORDER BY cat_name ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get category by ID
     */
    public function getCategoryById($categoryId) {
        try {
            $conn = $this->getConnection();
            $sql = "SELECT * FROM categories WHERE cat_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting category: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if category name already exists
     */
    public function categoryExists($categoryName, $excludeId = null) {
        try {
            $conn = $this->getConnection();
            $sql = "SELECT cat_id FROM categories WHERE cat_name = :name";

            if ($excludeId !== null) {
                $sql .= " AND cat_id != :exclude_id";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $categoryName);

            if ($excludeId !== null) {
                $stmt->bindParam(':exclude_id', $excludeId, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error checking category: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Add new category
     */
    public function addCategory($categoryName) {
        try {
            $conn = $this->getConnection();

            // Check if category already exists
            if ($this->categoryExists($categoryName)) {
                return array(
                    'status' => 'error',
                    'message' => 'Category already exists.'
                );
            }

            $sql = "INSERT INTO categories (cat_name) VALUES (:name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $categoryName);

            if ($stmt->execute()) {
                return array(
                    'status' => 'success',
                    'message' => 'Category added successfully!',
                    'category_id' => $conn->lastInsertId()
                );
            } else {
                return array(
                    'status' => 'error',
                    'message' => 'Failed to add category.'
                );
            }
        } catch (PDOException $e) {
            error_log("Error adding category: " . $e->getMessage());
            return array(
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            );
        }
    }

    /**
     * Update category
     */
    public function updateCategory($categoryId, $categoryName) {
        try {
            $conn = $this->getConnection();

            // Check if category name exists for other categories
            if ($this->categoryExists($categoryName, $categoryId)) {
                return array(
                    'status' => 'error',
                    'message' => 'Category name already exists.'
                );
            }

            $sql = "UPDATE categories SET cat_name = :name WHERE cat_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $categoryName);
            $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return array(
                    'status' => 'success',
                    'message' => 'Category updated successfully!'
                );
            } else {
                return array(
                    'status' => 'error',
                    'message' => 'Failed to update category.'
                );
            }
        } catch (PDOException $e) {
            error_log("Error updating category: " . $e->getMessage());
            return array(
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            );
        }
    }

    /**
     * Delete category
     */
    public function deleteCategory($categoryId) {
        try {
            $conn = $this->getConnection();

            // Check if category has products
            $checkSql = "SELECT COUNT(*) as count FROM products WHERE product_cat = :id";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
            $checkStmt->execute();
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                return array(
                    'status' => 'error',
                    'message' => 'Cannot delete category. It has ' . $result['count'] . ' product(s) associated with it.'
                );
            }

            $sql = "DELETE FROM categories WHERE cat_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return array(
                    'status' => 'success',
                    'message' => 'Category deleted successfully!'
                );
            } else {
                return array(
                    'status' => 'error',
                    'message' => 'Failed to delete category.'
                );
            }
        } catch (PDOException $e) {
            error_log("Error deleting category: " . $e->getMessage());
            return array(
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            );
        }
    }
}
?>
