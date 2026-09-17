<template>
    <main class="content-wrapper">
        <div class="page-header">
            <div>
                <h1 class="page-title">Tags</h1>
                <p class="page-subtitle">Organize your content with reusable tags.</p>
            </div>
            <button class="btn-date-picker" type="button" @click="openCreateForm">
                <i class="bi bi-plus-lg"></i>
                <span>New tag</span>
            </button>
        </div>

        <div v-if="feedback.message" class="alert" :class="`alert-${feedback.type}`" role="alert">
            {{ feedback.message }}
        </div>

        <div v-if="showForm" class="card tag-form-card">
            <div class="card-header">
                <h2 class="card-title">{{ editingTag ? 'Edit tag' : 'Create tag' }}</h2>
                <button class="card-more-btn" type="button" aria-label="Close form" @click="closeForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form @submit.prevent="saveTag">
                <div class="mb-3">
                    <label for="tag-name" class="form-label">Name</label>
                    <input
                        id="tag-name"
                        v-model.trim="form.name"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': formError }"
                        placeholder="e.g. Technology"
                        maxlength="255"
                        required
                    >
                    <div v-if="formError" class="invalid-feedback">{{ formError }}</div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light" @click="closeForm">Cancel</button>
                    <button type="submit" class="btn btn-dark" :disabled="saving">
                        <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        {{ editingTag ? 'Update tag' : 'Create tag' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">All tags</h2>
                    <span class="text-muted small">{{ tags.length }} tag{{ tags.length === 1 ? '' : 's' }} total</span>
                </div>
                <button class="card-more-btn" type="button" aria-label="Refresh tags" :disabled="loading" @click="loadTags">
                    <i class="bi bi-arrow-clockwise" :class="{ 'spin': loading }"></i>
                </button>
            </div>

            <div v-if="loading && !tags.length" class="text-center text-muted py-5">
                <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                Loading tags...
            </div>
            <Datatable
                v-else
                :data="tags"
                :columns="columns"
                :items-per-page="10"
                :DeleteAllFunction="deleteSelected"
            >
                <template #actions="{ row }">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn btn-sm btn-light" type="button" aria-label="Edit tag" @click="openEditForm(row)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" type="button" aria-label="Delete tag" @click="removeTag(row)">
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

const tags = ref([])
const loading = ref(false)
const saving = ref(false)
const showForm = ref(false)
const editingTag = ref(null)
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

const loadTags = async () => {
    loading.value = true
    try {
        const response = await getData('/tags')
        tags.value = response.data ?? []
    } catch (error) {
        setFeedback('Unable to load tags.', 'danger')
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    form.name = ''
    formError.value = ''
    editingTag.value = null
}

const openCreateForm = () => {
    resetForm()
    showForm.value = true
}

const openEditForm = (tag) => {
    editingTag.value = tag
    form.name = tag.name
    formError.value = ''
    showForm.value = true
}

const closeForm = () => {
    showForm.value = false
    resetForm()
}

const saveTag = async () => {
    if (!form.name) {
        formError.value = 'The tag name is required.'
        return
    }

    saving.value = true
    formError.value = ''
    try {
        if (editingTag.value) {
            await putData(`/tags/${editingTag.value.id}`, { name: form.name })
            setFeedback('Tag updated successfully.')
        } else {
            await postData('/tags', { name: form.name })
            setFeedback('Tag created successfully.')
        }
        closeForm()
        await loadTags()
    } catch (error) {
        formError.value = error.response?.data?.message ?? 'Unable to save the tag.'
    } finally {
        saving.value = false
    }
}

const removeTag = async (tag) => {
    const result = await Swal.fire({
        title: 'Delete this tag?',
        text: `The tag "${tag.name}" will be permanently deleted.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete tag',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        confirmButtonColor: '#051C12',
        cancelButtonColor: '#64748b',
        focusCancel: true
    })

    if (!result.isConfirmed) return

    try {
        await deleteData(`/tags/${tag.id}`)
        setFeedback('Tag deleted successfully.')
        await loadTags()
    } catch (error) {
        setFeedback('Unable to delete the tag.', 'danger')
    }
}

const deleteSelected = () => undefined

onMounted(loadTags)
</script>

<style scoped>
.tag-form-card {
    max-width: 680px;
}

.tag-form-card .form-control {
    max-width: 520px;
}

.spin {
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>