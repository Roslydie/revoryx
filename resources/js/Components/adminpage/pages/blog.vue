<template>
    <main class="content-wrapper">
        <div class="page-header">
            <div>
                <h1 class="page-title">Blogs</h1>
                <p class="page-subtitle">Create, organize and publish your editorial content.</p>
            </div>
            <button class="btn-date-picker" type="button" @click="openCreateForm">
                <i class="bi bi-plus-lg"></i>
                <span>New blog</span>
            </button>
        </div>

        <div v-if="feedback.message" class="alert" :class="`alert-${feedback.type}`" role="alert">
            {{ feedback.message }}
        </div>

        <div v-if="showForm" class="card blog-form-card">
            <div class="card-header">
                <h2 class="card-title">{{ editingBlog ? 'Edit blog' : 'Create blog' }}</h2>
                <button class="card-more-btn" type="button" aria-label="Close form" @click="closeForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form @submit.prevent="saveBlog">
                <div class="row g-3">
                    <div class="col-lg-8">
                        <label for="blog-title" class="form-label">Title</label>
                        <input
                            id="blog-title"
                            v-model.trim="form.title"
                            type="text"
                            class="form-control"
                            :class="{ 'is-invalid': errors.title }"
                            maxlength="255"
                            required
                        >
                        <div v-if="errors.title" class="invalid-feedback">{{ errors.title }}</div>
                    </div>
                    <div class="col-lg-4">
                        <label for="blog-image" class="form-label">Cover image {{ editingBlog ? '(optional)' : '' }}</label>
                        <input
                            id="blog-image"
                            type="file"
                            class="form-control"
                            :class="{ 'is-invalid': errors.image }"
                            accept="image/jpeg,image/png,image/jpg,image/gif"
                            :required="!editingBlog"
                            @change="handleImageChange"
                        >
                        <div v-if="errors.image" class="invalid-feedback">{{ errors.image }}</div>
                    </div>
                    <div class="col-12">
                        <label for="blog-description" class="form-label">Description</label>
                        <textarea
                            id="blog-description"
                            v-model.trim="form.description"
                            class="form-control"
                            :class="{ 'is-invalid': errors.description }"
                            rows="3"
                            required
                        ></textarea>
                        <div v-if="errors.description" class="invalid-feedback">{{ errors.description }}</div>
                    </div>
                    <div class="col-md-6">
                        <label for="blog-tags" class="form-label">Tags</label>
                        <select id="blog-tags" v-model="form.tags" class="form-select" multiple size="5">
                            <option v-for="tag in tags" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
                        </select>
                        <small class="text-muted">Hold Ctrl or Cmd to select several tags.</small>
                    </div>
                    <div class="col-md-6 d-flex align-items-start">
                        <div v-if="imagePreview" class="blog-image-preview">
                            <img :src="imagePreview" alt="Blog cover preview">
                        </div>
                        <div v-else class="empty-image-preview">
                            <i class="bi bi-image"></i>
                            <span>No image selected</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Content</label>
                        <QuillEditor
                            v-model:content="form.content"
                            content-type="html"
                            theme="snow"
                            toolbar="full"
                            class="blog-editor"
                        />
                        <div v-if="errors.content" class="text-danger small mt-1">{{ errors.content }}</div>
                    </div>
                </div>
                <div class="blog-form-actions d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light" @click="closeForm">Cancel</button>
                    <button type="submit" class="btn btn-dark" :disabled="saving">
                        <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        {{ editingBlog ? 'Update blog' : 'Save blog' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">All blogs</h2>
                    <span class="text-muted small">{{ blogs.length }} blog{{ blogs.length === 1 ? '' : 's' }} total</span>
                </div>
                <button class="card-more-btn" type="button" aria-label="Refresh blogs" :disabled="loading" @click="loadBlogs">
                    <i class="bi bi-arrow-clockwise" :class="{ 'spin': loading }"></i>
                </button>
            </div>

            <div v-if="loading && !blogs.length" class="text-center text-muted py-5">
                <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                Loading blogs...
            </div>
            <Datatable
                v-else
                :data="blogs"
                :columns="columns"
                :items-per-page="10"
                :DeleteAllFunction="deleteSelected"
            >
                <template #title="{ row }">
                    <div class="blog-title-cell">
                        <img v-if="row.image" :src="imageUrl(row.image)" :alt="row.title" class="blog-thumb">
                        <span>{{ row.title }}</span>
                    </div>
                </template>
                <template #status="{ row }">
                    <span class="badge" :class="row.status === 'published' ? 'text-bg-success' : 'text-bg-secondary'">
                        {{ row.status === 'published' ? 'Published' : 'Draft' }}
                    </span>
                </template>
                <template #actions="{ row }">
                    <div class="d-flex justify-content-end gap-1 flex-wrap">
                        <button class="btn btn-sm btn-light" type="button" aria-label="Edit blog" @click="openEditForm(row)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" type="button" aria-label="Delete blog" @click="removeBlog(row)">
                            <i class="bi bi-trash3"></i>
                        </button>
                        <button class="btn btn-sm" :class="row.status === 'published' ? 'btn-outline-warning' : 'btn-outline-success'" type="button" :aria-label="row.status === 'published' ? 'Unpublish blog' : 'Publish blog'" @click="togglePublish(row)">
                            <i :class="row.status === 'published' ? 'bi bi-eye-slash' : 'bi bi-send-check'"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" type="button" aria-label="Preview blog" @click="openPreview(row)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </template>
            </Datatable>
        </div>

        <div v-if="previewBlog" class="preview-backdrop" role="presentation" @click.self="closePreview">
            <article class="preview-modal" role="dialog" aria-modal="true" aria-labelledby="preview-title">
                <div class="preview-modal-header">
                    <div>
                        <span class="text-muted small text-uppercase">Blog preview</span>
                        <h2 id="preview-title">{{ previewBlog.title }}</h2>
                    </div>
                    <button class="card-more-btn" type="button" aria-label="Close preview" @click="closePreview">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <img v-if="previewBlog.image" :src="imageUrl(previewBlog.image)" :alt="previewBlog.title" class="preview-cover">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge" :class="previewBlog.status === 'published' ? 'text-bg-success' : 'text-bg-secondary'">
                        {{ previewBlog.status === 'published' ? 'Published' : 'Draft' }}
                    </span>
                    <span v-for="tag in previewBlog.tags || []" :key="tag.id" class="badge text-bg-light">#{{ tag.name }}</span>
                </div>
                <p class="preview-description">{{ previewBlog.description }}</p>
                <div class="preview-content" v-html="previewBlog.content"></div>
            </article>
        </div>
    </main>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import Swal from 'sweetalert2'
import Datatable from '../datatable/Datatable.vue'
import { deleteData, getData, patchData, postData } from '../../plugins/axios.js'

const blogs = ref([])
const tags = ref([])
const loading = ref(false)
const saving = ref(false)
const showForm = ref(false)
const editingBlog = ref(null)
const previewBlog = ref(null)
const imagePreview = ref('')
const selectedImage = ref(null)
const errors = reactive({})
const feedback = reactive({ message: '', type: 'success' })
const form = reactive({ title: '', description: '', content: '', tags: [] })

const columns = [
    { title: 'Title', data: 'title', slot: 'title' },
    { title: 'Status', data: 'status', slot: 'status' },
    { title: 'Author', data: 'user.full_name' },
    { title: 'Actions', data: 'actions', slot: 'actions' }
]

const setFeedback = (message, type = 'success') => {
    feedback.message = message
    feedback.type = type
}

const imageUrl = (path) => path?.startsWith('http') ? path : `/storage/${path}`

const loadBlogs = async () => {
    loading.value = true
    try {
        const response = await getData('/blogs')
        blogs.value = response.data ?? []
    } catch (error) {
        setFeedback('Unable to load blogs.', 'danger')
    } finally {
        loading.value = false
    }
}

const loadTags = async () => {
    try {
        const response = await getData('/tags')
        tags.value = response.data ?? []
    } catch (error) {
        setFeedback('Unable to load tags.', 'danger')
    }
}

const clearErrors = () => {
    Object.keys(errors).forEach((key) => delete errors[key])
}

const resetForm = () => {
    form.title = ''
    form.description = ''
    form.content = ''
    form.tags = []
    selectedImage.value = null
    imagePreview.value = ''
    editingBlog.value = null
    clearErrors()
}

const openCreateForm = () => {
    resetForm()
    showForm.value = true
}

const openEditForm = (blog) => {
    editingBlog.value = blog
    form.title = blog.title ?? ''
    form.description = blog.description ?? ''
    form.content = blog.content ?? ''
    form.tags = (blog.tags ?? []).map((tag) => tag.id)
    selectedImage.value = null
    imagePreview.value = blog.image ? imageUrl(blog.image) : ''
    clearErrors()
    showForm.value = true
}

const closeForm = () => {
    showForm.value = false
    resetForm()
}

const handleImageChange = (event) => {
    const file = event.target.files?.[0]
    selectedImage.value = file ?? null
    imagePreview.value = file ? URL.createObjectURL(file) : ''
}

const currentUserId = () => {
    try {
        return JSON.parse(localStorage.getItem('user') || '{}').id
    } catch (error) {
        return null
    }
}

const saveBlog = async () => {
    clearErrors()
    const userId = currentUserId()
    if (!editingBlog.value && !userId) {
        setFeedback('Please sign in before creating a blog.', 'danger')
        return
    }

    saving.value = true
    const payload = new FormData()
    payload.append('title', form.title)
    payload.append('description', form.description)
    payload.append('content', form.content)
    if (!editingBlog.value) payload.append('user_id', userId)
    if (selectedImage.value) payload.append('image', selectedImage.value)
    form.tags.forEach((tagId) => payload.append('tags[]', tagId))
    if (editingBlog.value) payload.append('_method', 'PUT')

    try {
        const url = editingBlog.value ? `/blogs/${editingBlog.value.id}` : '/blogs'
        await postData(url, payload)
        setFeedback(editingBlog.value ? 'Blog updated successfully.' : 'Blog created as draft.')
        closeForm()
        await loadBlogs()
    } catch (error) {
        const validationErrors = error.response?.data?.errors ?? {}
        Object.keys(validationErrors).forEach((key) => { errors[key] = validationErrors[key][0] })
        setFeedback(error.response?.data?.message ?? 'Unable to save the blog.', 'danger')
    } finally {
        saving.value = false
    }
}

const removeBlog = async (blog) => {
    const result = await Swal.fire({
        title: 'Delete this blog?',
        text: `The blog "${blog.title}" will be permanently deleted.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete blog',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        confirmButtonColor: '#051C12',
        cancelButtonColor: '#64748b',
        focusCancel: true
    })
    if (!result.isConfirmed) return

    try {
        await deleteData(`/blogs/${blog.id}`)
        setFeedback('Blog deleted successfully.')
        await loadBlogs()
    } catch (error) {
        setFeedback('Unable to delete the blog.', 'danger')
    }
}

const togglePublish = async (blog) => {
    const isPublished = blog.status === 'published'
    const result = await Swal.fire({
        title: isPublished ? 'Move this blog to draft?' : 'Publish this blog?',
        text: isPublished ? 'It will no longer be visible as published.' : 'This blog will become visible to readers.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: isPublished ? 'Unpublish' : 'Publish',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        confirmButtonColor: '#051C12',
        cancelButtonColor: '#64748b',
        focusCancel: true
    })
    if (!result.isConfirmed) return

    try {
        await patchData(`/blogs/${blog.id}/${isPublished ? 'unpublish' : 'publish'}`)
        setFeedback(isPublished ? 'Blog moved to draft.' : 'Blog published successfully.')
        await loadBlogs()
    } catch (error) {
        setFeedback(error.response?.data?.message ?? 'Unable to update the blog status.', 'danger')
    }
}

const openPreview = (blog) => {
    previewBlog.value = blog
}

const closePreview = () => {
    previewBlog.value = null
}

const deleteSelected = () => undefined

onMounted(() => {
    loadBlogs()
    loadTags()
})
</script>

<style scoped>
.blog-form-card {
    max-width: 1100px;
}

.blog-editor {
    height: 320px;
    overflow: hidden;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.blog-editor :deep(.ql-toolbar) {
    flex: 0 0 auto;
    border: 0;
    border-bottom: 1px solid #dee2e6;
}

.blog-editor :deep(.ql-container) {
    height: calc(100% - 42px);
    overflow-y: auto;
    border: 0;
    font-family: inherit;
}

.blog-editor :deep(.ql-editor) {
    min-height: 220px;
}

.blog-form-actions {
    position: relative;
    z-index: 2;
    clear: both;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
    background-color: #fff;
}

.blog-image-preview,
.empty-image-preview {
    width: 100%;
    height: 150px;
    border: 1px dashed #cbd5e1;
    border-radius: 0.5rem;
    overflow: hidden;
}

.blog-image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.empty-image-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: #94a3b8;
}

.blog-title-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 220px;
    font-weight: 600;
}

.blog-thumb {
    width: 42px;
    height: 42px;
    border-radius: 0.4rem;
    object-fit: cover;
    background: #f1f5f9;
}

.preview-backdrop {
    position: fixed;
    inset: 0;
    z-index: 1050;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(5, 28, 18, 0.65);
}

.preview-modal {
    width: min(850px, 100%);
    max-height: 92vh;
    overflow-y: auto;
    padding: 1.75rem;
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.2);
}

.preview-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}

.preview-modal h2 {
    margin: 0.25rem 0 0;
    font-size: 1.5rem;
}

.preview-cover {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 0.6rem;
    margin-bottom: 1.25rem;
}

.preview-description {
    color: #64748b;
    font-size: 1rem;
}

.preview-content :deep(img) {
    max-width: 100%;
}

.spin {
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>