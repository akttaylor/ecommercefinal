<?php
require_once __DIR__ . '/../settings/db_class.php';

class Category {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    /**
     * Get all categories
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";
        return $this->db->read($sql);
    }

    /**
     * Get category by ID
     */
    public function getCategoryById($categoryId) {
        $sql = "SELECT * FROM categories WHERE cat_id = ? LIMIT 1";
        $rows = $this->db->read($sql, [$categoryId]);
        return $rows[0] ?? false;
    }

    /**
     * Check if category name already exists
     */
    public function categoryExists($categoryName, $excludeId = null) {
        $sql = "SELECT cat_id FROM categories WHERE cat_name = ?";
        $params = [$categoryName];
        if ($excludeId !== null) { $sql .= " AND cat_id != ?"; $params[] = $excludeId; }
        $rows = $this->db->read($sql, $params);
        return !empty($rows);
    }

    /**
     * Add new category
     */
    public function addCategory($categoryName) {
        if ($this->categoryExists($categoryName)) return ['status'=>'error','message'=>'Category already exists.'];
        list($ok, $id) = $this->db->write("INSERT INTO categories (cat_name) VALUES (?)", [$categoryName]);
        if ($ok) return ['status'=>'success','message'=>'Category added successfully!','category_id'=>$id];
        return ['status'=>'error','message'=>'Failed to add category.'];
    }

    /**
     * Update category
     */
    public function updateCategory($categoryId, $categoryName) {
        if ($this->categoryExists($categoryName, $categoryId)) return ['status'=>'error','message'=>'Category name already exists.'];
        list($ok,$info) = $this->db->write("UPDATE categories SET cat_name = ? WHERE cat_id = ?", [$categoryName, $categoryId]);
        if ($ok) return ['status'=>'success','message'=>'Category updated successfully!'];
        return ['status'=>'error','message'=>'Failed to update category.'];
    }

    /**
     * Delete category
     */
    public function deleteCategory($categoryId) {
        // Check if category has products
        $rows = $this->db->read("SELECT COUNT(*) as count FROM products WHERE product_cat = ?", [$categoryId]);
        $count = $rows[0]['count'] ?? 0;
        if ($count > 0) return ['status'=>'error','message'=>'Cannot delete category. It has ' . $count . ' product(s) associated with it.'];

        list($ok,$info) = $this->db->write("DELETE FROM categories WHERE cat_id = ?", [$categoryId]);
        if ($ok) return ['status'=>'success','message'=>'Category deleted successfully!'];
        return ['status'=>'error','message'=>'Failed to delete category.'];
    }
}
?>
