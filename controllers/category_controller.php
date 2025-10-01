<?php
require_once __DIR__ . '/../classes/category_class.php';

/**
 * Category Controller - Business logic for categories
 */
class CategoryController {
    private $category;

    public function __construct() {
        $this->category = new Category();
    }

    /**
     * Get all categories
     */
    public function getAllCategories() {
        return $this->category->getAllCategories();
    }

    /**
     * Get category by ID
     */
    public function getCategory($categoryId) {
        if (empty($categoryId) || !is_numeric($categoryId)) {
            return array(
                'status' => 'error',
                'message' => 'Invalid category ID.'
            );
        }

        $result = $this->category->getCategoryById(intval($categoryId));

        if ($result) {
            return array(
                'status' => 'success',
                'category' => $result
            );
        } else {
            return array(
                'status' => 'error',
                'message' => 'Category not found.'
            );
        }
    }

    /**
     * Add new category
     */
    public function addCategory($categoryName) {
        // Validate input
        $categoryName = trim($categoryName);

        if (empty($categoryName)) {
            return array(
                'status' => 'error',
                'message' => 'Category name is required.'
            );
        }

        if (strlen($categoryName) > 100) {
            return array(
                'status' => 'error',
                'message' => 'Category name is too long (maximum 100 characters).'
            );
        }

        if (strlen($categoryName) < 2) {
            return array(
                'status' => 'error',
                'message' => 'Category name is too short (minimum 2 characters).'
            );
        }

        // Add category
        return $this->category->addCategory($categoryName);
    }

    /**
     * Update category
     */
    public function updateCategory($categoryId, $categoryName) {
        // Validate category ID
        if (empty($categoryId) || !is_numeric($categoryId)) {
            return array(
                'status' => 'error',
                'message' => 'Invalid category ID.'
            );
        }

        // Validate category name
        $categoryName = trim($categoryName);

        if (empty($categoryName)) {
            return array(
                'status' => 'error',
                'message' => 'Category name is required.'
            );
        }

        if (strlen($categoryName) > 100) {
            return array(
                'status' => 'error',
                'message' => 'Category name is too long (maximum 100 characters).'
            );
        }

        if (strlen($categoryName) < 2) {
            return array(
                'status' => 'error',
                'message' => 'Category name is too short (minimum 2 characters).'
            );
        }

        // Update category
        return $this->category->updateCategory(intval($categoryId), $categoryName);
    }

    /**
     * Delete category
     */
    public function deleteCategory($categoryId) {
        if (empty($categoryId) || !is_numeric($categoryId)) {
            return array(
                'status' => 'error',
                'message' => 'Invalid category ID.'
            );
        }

        return $this->category->deleteCategory(intval($categoryId));
    }
}
?>
