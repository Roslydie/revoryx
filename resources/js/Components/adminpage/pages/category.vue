<template>
    <main class="content-wrapper">
        <div class="page-header">
            <div>
                <h1 class="page-title">Categories</h1>
                <p class="page-subtitle">Organize your projects with clear and reusable categories.</p>
            </div>
            <button class="btn-date-picker" type="button" @click="openCreateForm">
                <i class="bi bi-plus-lg"></i>
                <span>New category</span>
            </button>
        </div>

        <div v-if="feedback.message" class="alert" :class="`alert-${feedback.type}`" role="alert">
            {{ feedback.message }}
        </div>

        <div v-if="showForm" class="card category-form-card">
            <div class="card-header">
                <h2 class="card-title">{{ editingCategory ? 'Edit category' : 'Create category' }}</h2>
                <button class="card-more-btn" type="button" aria-label="Close form" @click="closeForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form @submit.prevent="saveCategory">
                <div class="mb-3">
                    <label for="category-name" class="form-label">Name</label>
                    <input
                        id="category-name"
                        v-model.trim="form.name"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': formError }"
                        placeholder="e.g. Web design"
                        maxlength="255"
                        required
                    >
                    <div v-if="formError" class="invalid-feedback">{{ formError }}</div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light" @click="closeForm">Cancel</button>
                    <button type="submit" class="btn btn-dark" :disabled="saving">
                        <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        {{ editingCategory ? 'Update category' : 'Create category' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">All categories</h2>
                    <span class="text-muted small">{{ categories.length }} categor{{ categories.length === 1 ? 'y' : 'ies' }} total</span>
                </div>
                <button class="card-more-btn" type="button" aria-label="Refresh categories" :disabled="loading" @click="loadCategories">
                    <i class="bi bi-arrow-clockwise" :class="{ 'spin': loading }"></i>
                </button>
            </div>

            <div v-if="loading && !categories.length" class="text-center text-muted py-5">
                <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                Loading categories...
            </div>
            <Datatable
                v-else
                :data="categories"
                :columns="columns"
                :items-per-page="10"
                :DeleteAllFunction="deleteSelected"
            >
                <template #actions="{ row }">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn btn-sm btn-light" type="button" aria-label="Edit category" @click="openEditForm(row)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" type="button" aria-label="Delete category" @click="removeCategory(row)">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </template>
            </Datatable>
        </div>
    </main>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import Swal from 'sweetalert2'
import Datatable from '../datatable/Datatable.vue'
import { deleteData, getData, postData, putData } from '../../plugins/axios.js'

const categories = ref([])
const loading = ref(false)
const saving = ref(false)
const showForm = ref(false)
const editingCategory = ref(null)
const formError = ref('')
const feedback = reactive({ message: '', type: 'success' })
const form = reactive({ name: '' })

const columns = [
    { title: 'Name', data: 'name' },
    { title: 'Actions', data: 'actions', slot: 'actions' }
]

const setFeedback = (message, type = 'success') => {
    feedback.message = message
    feedback.type = type
}

const loadCategories = async () => {
    loading.value = true
    try {
        const response = await getData('/categories')
        categories.value = response.data ?? []
    } catch (error) {
        setFeedback('Unable to load categories.', 'danger')
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    form.name = ''
    formError.value = ''
    editingCategory.value = null
}

const openCreateForm = () => {
    resetForm()
    showForm.value = true
}

const openEditForm = (category) => {
    editingCategory.value = category
    form.name = category.name
    formError.value = ''
    showForm.value = true
}

const closeForm = () => {
    showForm.value = false
    resetForm()
}

const saveCategory = async () => {
    if (!form.name) {
        formError.value = 'The category name is required.'
        return
    }

    saving.value = true
    formError.value = ''
    try {
        if (editingCategory.value) {
            await putData(`/categories/${editingCategory.value.id}`, { name: form.name })
            setFeedback('Category updated successfully.')
        } else {
            await postData('/categories', { name: form.name })
            setFeedback('Category created successfully.')
        }
        closeForm()
        await loadCategories()
    } catch (error) {
        formError.value = error.response?.data?.message ?? 'Unable to save the category.'
    } finally {
        saving.value = false
    }
}

const removeCategory = async (category) => {
    const result = await Swal.fire({
        title: 'Delete this category?',
        text: `The category "${category.name}" will be permanently deleted.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete category',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        confirmButtonColor: '#051C12',
        cancelButtonColor: '#64748b',
        focusCancel: true
    })

    if (!result.isConfirmed) return

    try {
        await deleteData(`/categories/${category.id}`)
        setFeedback('Category deleted successfully.')
        await loadCategories()
    } catch (error) {
        setFeedback('Unable to delete the category.', 'danger')
    }
}

const deleteSelected = () => undefined

onMounted(loadCategories)
</script>

<style scoped>
.category-form-card {
    max-width: 680px;
}

.category-form-card .form-control {
    max-width: 520px;
}

.spin {
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>