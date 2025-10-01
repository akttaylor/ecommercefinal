document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
});

// Load all categories
async function loadCategories() {
    try {
        const res = await fetch('../actions/get_categories_action.php');
        const data = await res.json();

        const container = document.getElementById('categories-container');

        if (data.status === 'success' && data.categories && data.categories.length > 0) {
            let tableHTML = `
                <table class="categories-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.categories.forEach(category => {
                tableHTML += `
                    <tr>
                        <td>${category.cat_id}</td>
                        <td>${escapeHtml(category.cat_name)}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-warning btn-sm" onclick="showEditCategoryModal(${category.cat_id}, '${escapeHtml(category.cat_name)}')">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteCategory(${category.cat_id}, '${escapeHtml(category.cat_name)}')">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tableHTML += `
                    </tbody>
                </table>
            `;

            container.innerHTML = tableHTML;
        } else if (data.status === 'success') {
            container.innerHTML = `
                <div class="empty-state">
                    <h3>No Categories Found</h3>
                    <p>Get started by adding your first category!</p>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="empty-state">
                    <h3>Error Loading Categories</h3>
                    <p>${data.message || 'Unable to load categories'}</p>
                </div>
            `;
        }
    } catch (err) {
        console.error('Error loading categories:', err);
        document.getElementById('categories-container').innerHTML = `
            <div class="empty-state">
                <h3>Error</h3>
                <p>Network error. Please try again.</p>
            </div>
        `;
    }
}

// Show add category modal
function showAddCategoryModal() {
    Swal.fire({
        title: 'Add New Category',
        html: `
            <input type="text" id="category-name" class="swal2-input" placeholder="Category Name" maxlength="100">
        `,
        confirmButtonText: 'Add Category',
        confirmButtonColor: '#2f5233',
        showCancelButton: true,
        cancelButtonColor: '#6c757d',
        preConfirm: () => {
            const categoryName = document.getElementById('category-name').value.trim();
            if (!categoryName) {
                Swal.showValidationMessage('Category name is required');
                return false;
            }
            if (categoryName.length < 2) {
                Swal.showValidationMessage('Category name must be at least 2 characters');
                return false;
            }
            return categoryName;
        }
    }).then(async (result) => {
        if (result.isConfirmed) {
            await addCategory(result.value);
        }
    });
}

// Add category
async function addCategory(categoryName) {
    try {
        const res = await fetch('../actions/add_category_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ category_name: categoryName })
        });

        const data = await res.json();

        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Category added successfully',
                confirmButtonColor: '#2f5233'
            });
            loadCategories();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to add category',
                confirmButtonColor: '#2f5233'
            });
        }
    } catch (err) {
        console.error('Error adding category:', err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Network error. Please try again.',
            confirmButtonColor: '#2f5233'
        });
    }
}

// Show edit category modal
function showEditCategoryModal(categoryId, currentName) {
    Swal.fire({
        title: 'Edit Category',
        html: `
            <input type="text" id="category-name" class="swal2-input" placeholder="Category Name" value="${escapeHtml(currentName)}" maxlength="100">
        `,
        confirmButtonText: 'Update Category',
        confirmButtonColor: '#2f5233',
        showCancelButton: true,
        cancelButtonColor: '#6c757d',
        preConfirm: () => {
            const categoryName = document.getElementById('category-name').value.trim();
            if (!categoryName) {
                Swal.showValidationMessage('Category name is required');
                return false;
            }
            if (categoryName.length < 2) {
                Swal.showValidationMessage('Category name must be at least 2 characters');
                return false;
            }
            return categoryName;
        }
    }).then(async (result) => {
        if (result.isConfirmed) {
            await editCategory(categoryId, result.value);
        }
    });
}

// Edit category
async function editCategory(categoryId, categoryName) {
    try {
        const res = await fetch('../actions/edit_category_action.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                category_id: categoryId,
                category_name: categoryName
            })
        });

        const data = await res.json();

        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Category updated successfully',
                confirmButtonColor: '#2f5233'
            });
            loadCategories();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to update category',
                confirmButtonColor: '#2f5233'
            });
        }
    } catch (err) {
        console.error('Error updating category:', err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Network error. Please try again.',
            confirmButtonColor: '#2f5233'
        });
    }
}

// Delete category
function deleteCategory(categoryId, categoryName) {
    Swal.fire({
        title: 'Delete Category?',
        text: `Are you sure you want to delete "${categoryName}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const res = await fetch('../actions/delete_category_action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ category_id: categoryId })
                });

                const data = await res.json();

                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message || 'Category deleted successfully',
                        confirmButtonColor: '#2f5233'
                    });
                    loadCategories();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete category',
                        confirmButtonColor: '#2f5233'
                    });
                }
            } catch (err) {
                console.error('Error deleting category:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Network error. Please try again.',
                    confirmButtonColor: '#2f5233'
                });
            }
        }
    });
}

// Helper function to escape HTML
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
