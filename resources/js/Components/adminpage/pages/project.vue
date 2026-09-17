<template>
    <main class="content-wrapper">
        <div class="page-header">
            <div>
                <h1 class="page-title">Projects</h1>
                <p class="page-subtitle">Create, organize and publish your portfolio projects.</p>
            </div>
            <button class="btn-date-picker" type="button" @click="openCreateForm">
                <i class="bi bi-plus-lg"></i>
                <span>New project</span>
            </button>
        </div>

        <div v-if="feedback.message" class="alert" :class="`alert-${feedback.type}`" role="alert">
            {{ feedback.message }}
        </div>

        <div v-if="showForm" class="card project-form-card">
            <div class="card-header">
                <h2 class="card-title">{{ editingProject ? 'Edit project' : 'Create project' }}</h2>
                <button class="card-more-btn" type="button" aria-label="Close form" @click="closeForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form @submit.prevent="saveProject">
                <div class="row g-3">
                    <div class="col-lg-8">
                        <label for="project-title" class="form-label">Title</label>
                        <input id="project-title" v-model.trim="form.title" type="text" class="form-control" :class="{ 'is-invalid': errors.title }" maxlength="255" required>
                        <div v-if="errors.title" class="invalid-feedback">{{ errors.title }}</div>
                    </div>
                    <div class="col-lg-4">
                        <label for="project-category" class="form-label">Category</label>
                        <select id="project-category" v-model="form.category_id" class="form-select" :class="{ 'is-invalid': errors.category_id }" required>
                            <option value="" disabled>Select a category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                        </select>
                        <div v-if="errors.category_id" class="invalid-feedback">{{ errors.category_id }}</div>
                    </div>
                    <div class="col-md-4">
                        <label for="project-client" class="form-label">Client</label>
                        <input id="project-client" v-model.trim="form.client" type="text" class="form-control" :class="{ 'is-invalid': errors.client }" required>
                        <div v-if="errors.client" class="invalid-feedback">{{ errors.client }}</div>
                    </div>
                    <div class="col-md-4">
                        <label for="project-location" class="form-label">Location</label>
                        <input id="project-location" v-model.trim="form.location" type="text" class="form-control" :class="{ 'is-invalid': errors.location }" required>
                        <div v-if="errors.location" class="invalid-feedback">{{ errors.location }}</div>
                    </div>
                    <div class="col-md-4">
                        <label for="project-years" class="form-label">Year</label>
                        <input id="project-years" v-model.trim="form.years" type="text" class="form-control" :class="{ 'is-invalid': errors.years }" maxlength="4" required>
                        <div v-if="errors.years" class="invalid-feedback">{{ errors.years }}</div>
                    </div>
                    <div class="col-12">
                        <label for="project-images" class="form-label">Project images</label>
                        <input id="project-images" type="file" class="form-control" :class="{ 'is-invalid': errors.images }" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple :required="!editingProject" @change="handleImagesChange">
                        <div v-if="errors.images" class="invalid-feedback">{{ errors.images }}</div>
                        <small class="text-muted">You can select several images. Existing images remain unless removed below.</small>
                    </div>
                    <div v-if="imagePreviews.length" class="col-12">
                        <div class="project-image-grid">
                            <div v-for="(image, index) in imagePreviews" :key="image.key" class="project-image-item">
                                <img :src="image.url" alt="Project preview">
                                <button v-if="editingProject && image.existing" type="button" class="project-image-remove" aria-label="Remove image" @click="removeExistingImage(index)">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="project-description" class="form-label">Description</label>
                        <textarea id="project-description" v-model.trim="form.description" class="form-control" :class="{ 'is-invalid': errors.description }" rows="3" required></textarea>
                        <div v-if="errors.description" class="invalid-feedback">{{ errors.description }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Content</label>
                        <QuillEditor v-model:content="form.content" content-type="html" theme="snow" toolbar="full" class="project-editor" />
                        <div v-if="errors.content" class="text-danger small mt-1">{{ errors.content }}</div>
                    </div>
                </div>
                <div class="project-form-actions d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light" @click="closeForm">Cancel</button>
                    <button type="submit" class="btn btn-dark" :disabled="saving">
                        <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        {{ editingProject ? 'Update project' : 'Save project' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">All projects</h2>
                    <span class="text-muted small">{{ projects.length }} project{{ projects.length === 1 ? '' : 's' }} total</span>
                </div>
                <button class="card-more-btn" type="button" aria-label="Refresh projects" :disabled="loading" @click="loadProjects">
                    <i class="bi bi-arrow-clockwise" :class="{ 'spin': loading }"></i>
                </button>
            </div>
            <div v-if="loading && !projects.length" class="text-center text-muted py-5">
                <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                Loading projects...
            </div>
            <Datatable v-else :data="projects" :columns="columns" :items-per-page="10" :DeleteAllFunction="deleteSelected">
                <template #title="{ row }">
                    <div class="project-title-cell">
                        <img v-if="row.images?.[0]" :src="imageUrl(row.images[0])" :alt="row.title" class="project-thumb">
                        <span>{{ row.title }}</span>
                    </div>
                </template>
                <template #status="{ row }">
                    <span class="badge" :class="row.status === 'published' ? 'text-bg-success' : 'text-bg-secondary'">{{ row.status === 'published' ? 'Published' : 'Draft' }}</span>
                </template>
                <template #actions="{ row }">
                    <div class="d-flex justify-content-end gap-1 flex-wrap">
                        <button class="btn btn-sm btn-light" type="button" aria-label="Edit project" @click="openEditForm(row)"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger" type="button" aria-label="Delete project" @click="removeProject(row)"><i class="bi bi-trash3"></i></button>
                        <button class="btn btn-sm" :class="row.status === 'published' ? 'btn-outline-warning' : 'btn-outline-success'" type="button" :aria-label="row.status === 'published' ? 'Unpublish project' : 'Publish project'" @click="togglePublish(row)"><i :class="row.status === 'published' ? 'bi bi-eye-slash' : 'bi bi-send-check'"></i></button>
                        <button class="btn btn-sm btn-outline-secondary" type="button" aria-label="Preview project" @click="openPreview(row)"><i class="bi bi-eye"></i></button>
                    </div>
                </template>
            </Datatable>
        </div>

        <div v-if="previewProject" class="preview-backdrop" role="presentation" @click.self="closePreview">
            <article class="preview-modal" role="dialog" aria-modal="true" aria-labelledby="project-preview-title">
                <div class="preview-modal-header">
                    <div>
                        <span class="text-muted small text-uppercase">Project preview</span>
                        <h2 id="project-preview-title">{{ previewProject.title }}</h2>
                    </div>
                    <button class="card-more-btn" type="button" aria-label="Close preview" @click="closePreview"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge" :class="previewProject.status === 'published' ? 'text-bg-success' : 'text-bg-secondary'">{{ previewProject.status === 'published' ? 'Published' : 'Draft' }}</span>
                    <span class="badge text-bg-light">{{ previewProject.category?.name }}</span>
                    <span class="badge text-bg-light">{{ previewProject.years }}</span>
                </div>
                <div v-if="previewProject.images?.length" class="preview-gallery">
                    <img v-for="image in previewProject.images" :key="image" :src="imageUrl(image)" :alt="previewProject.title" class="preview-gallery-image">
                </div>
                <div class="project-meta mb-3"><span><i class="bi bi-person"></i>{{ previewProject.client }}</span><span><i class="bi bi-geo-alt"></i>{{ previewProject.location }}</span></div>
                <p class="preview-description">{{ previewProject.description }}</p>
                <div class="preview-content" v-html="previewProject.content"></div>
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

const projects = ref([])
const categories = ref([])
const loading = ref(false)
const saving = ref(false)
const showForm = ref(false)
const editingProject = ref(null)
const previewProject = ref(null)
const selectedImages = ref([])
const imagePreviews = ref([])
const errors = reactive({})
const feedback = reactive({ message: '', type: 'success' })
const form = reactive({ category_id: '', title: '', client: '', location: '', years: '', description: '', content: '', existing_images: [] })

const columns = [
    { title: 'Title', data: 'title', slot: 'title' },
    { title: 'Category', data: 'category.name' },
    { title: 'Client', data: 'client' },
    { title: 'Status', data: 'status', slot: 'status' },
    { title: 'Actions', data: 'actions', slot: 'actions' }
]

const setFeedback = (message, type = 'success') => { feedback.message = message; feedback.type = type }
const imageUrl = (path) => path?.startsWith('http') ? path : `/storage/${path}`

const loadProjects = async () => {
    loading.value = true
    try { projects.value = (await getData('/projects')).data ?? [] } catch (error) { setFeedback('Unable to load projects.', 'danger') } finally { loading.value = false }
}

const loadCategories = async () => {
    try { categories.value = (await getData('/categories')).data ?? [] } catch (error) { setFeedback('Unable to load categories.', 'danger') }
}

const clearErrors = () => Object.keys(errors).forEach((key) => delete errors[key])
const resetForm = () => {
    Object.assign(form, { category_id: '', title: '', client: '', location: '', years: '', description: '', content: '', existing_images: [] })
    editingProject.value = null; selectedImages.value = []; imagePreviews.value = []; clearErrors()
}
const openCreateForm = () => { resetForm(); showForm.value = true }

const openEditForm = (project) => {
    editingProject.value = project
    Object.assign(form, { category_id: project.category_id, title: project.title ?? '', client: project.client ?? '', location: project.location ?? '', years: project.years ?? '', description: project.description ?? '', content: project.content ?? '', existing_images: [...(project.images ?? [])] })
    selectedImages.value = []; imagePreviews.value = (project.images ?? []).map((image) => ({ key: image, url: imageUrl(image), existing: true })); clearErrors(); showForm.value = true
}
const closeForm = () => { showForm.value = false; resetForm() }

const handleImagesChange = (event) => {
    selectedImages.value = Array.from(event.target.files ?? [])
    const existing = imagePreviews.value.filter((image) => image.existing)
    imagePreviews.value = [...existing, ...selectedImages.value.map((file) => ({ key: `${file.name}-${file.lastModified}`, url: URL.createObjectURL(file), existing: false }))]
}
const removeExistingImage = (index) => {
    const image = imagePreviews.value[index]
    form.existing_images = form.existing_images.filter((path) => path !== image.url.replace('/storage/', ''))
    imagePreviews.value.splice(index, 1)
}

const currentUserId = () => { try { return JSON.parse(localStorage.getItem('user') || '{}').id } catch (error) { return null } }

const saveProject = async () => {
    clearErrors(); const userId = currentUserId()
    if (!editingProject.value && !userId) { setFeedback('Please sign in before creating a project.', 'danger'); return }
    saving.value = true
    const payload = new FormData()
    Object.entries({ category_id: form.category_id, title: form.title, client: form.client, location: form.location, years: form.years, description: form.description, content: form.content }).forEach(([key, value]) => payload.append(key, value))
    if (!editingProject.value) payload.append('user_id', userId)
    form.existing_images.forEach((image) => payload.append('existing_images[]', image))
    selectedImages.value.forEach((image) => payload.append('images[]', image))
    if (editingProject.value) payload.append('_method', 'PUT')
    try {
        await postData(editingProject.value ? `/projects/${editingProject.value.id}` : '/projects', payload)
        const isEditing = Boolean(editingProject.value); closeForm(); await loadProjects(); setFeedback(isEditing ? 'Project updated successfully.' : 'Project created as draft.')
    } catch (error) {
        const validationErrors = error.response?.data?.errors ?? {}; Object.keys(validationErrors).forEach((key) => { errors[key] = validationErrors[key][0] }); setFeedback(error.response?.data?.message ?? 'Unable to save the project.', 'danger')
    } finally { saving.value = false }
}

const confirmAction = (title, text, confirmText) => Swal.fire({ title, text, icon: 'question', showCancelButton: true, confirmButtonText: confirmText, cancelButtonText: 'Cancel', reverseButtons: true, confirmButtonColor: '#051C12', cancelButtonColor: '#64748b', focusCancel: true })
const removeProject = async (project) => {
    if (!(await confirmAction('Delete this project?', `The project "${project.title}" will be permanently deleted.`, 'Delete project')).isConfirmed) return
    try { await deleteData(`/projects/${project.id}`); setFeedback('Project deleted successfully.'); await loadProjects() } catch (error) { setFeedback('Unable to delete the project.', 'danger') }
}
const togglePublish = async (project) => {
    const published = project.status === 'published'
    if (!(await confirmAction(published ? 'Move this project to draft?' : 'Publish this project?', published ? 'It will no longer be published.' : 'This project will become visible to readers.', published ? 'Unpublish' : 'Publish')).isConfirmed) return
    try { await patchData(`/projects/${project.id}/${published ? 'unpublish' : 'publish'}`); setFeedback(published ? 'Project moved to draft.' : 'Project published successfully.'); await loadProjects() } catch (error) { setFeedback(error.response?.data?.message ?? 'Unable to update the project status.', 'danger') }
}
const openPreview = (project) => { previewProject.value = project }
const closePreview = () => { previewProject.value = null }
const deleteSelected = () => undefined

onMounted(() => { loadProjects(); loadCategories() })
</script>

<style scoped>
.project-form-card { max-width: 1100px; }
.project-editor { height: 320px; overflow: hidden; border: 1px solid #dee2e6; border-radius: 0.375rem; }
.project-editor :deep(.ql-toolbar) { border: 0; border-bottom: 1px solid #dee2e6; }
.project-editor :deep(.ql-container) { height: calc(100% - 42px); overflow-y: auto; border: 0; font-family: inherit; }
.project-editor :deep(.ql-editor) { min-height: 220px; }
.project-form-actions { position: relative; z-index: 2; clear: both; padding-top: 1rem; border-top: 1px solid #e2e8f0; background-color: #fff; }
.project-image-grid, .preview-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 0.75rem; }
.project-image-item { position: relative; height: 120px; overflow: hidden; border-radius: 0.5rem; background: #f1f5f9; }
.project-image-item img, .preview-gallery-image { width: 100%; height: 100%; object-fit: cover; }
.project-image-remove { position: absolute; top: 0.35rem; right: 0.35rem; border: 0; border-radius: 50%; background: rgba(5, 28, 18, 0.8); color: white; }
.project-title-cell { display: flex; align-items: center; gap: 0.75rem; min-width: 220px; font-weight: 600; }
.project-thumb { width: 42px; height: 42px; border-radius: 0.4rem; object-fit: cover; background: #f1f5f9; }
.preview-backdrop { position: fixed; inset: 0; z-index: 1050; display: flex; align-items: center; justify-content: center; padding: 1rem; background: rgba(5, 28, 18, 0.65); }
.preview-modal { width: min(900px, 100%); max-height: 92vh; overflow-y: auto; padding: 1.75rem; background: #fff; border-radius: 1rem; box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.2); }
.preview-modal-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }
.preview-modal h2 { margin: 0.25rem 0 0; font-size: 1.5rem; }
.preview-gallery { grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); margin-bottom: 1.25rem; }
.preview-gallery-image { height: 180px; border-radius: 0.6rem; }
.project-meta { display: flex; flex-wrap: wrap; gap: 1.25rem; color: #64748b; }
.project-meta span { display: inline-flex; align-items: center; gap: 0.4rem; }
.preview-description { color: #64748b; font-size: 1rem; }
.preview-content :deep(img) { max-width: 100%; }
.spin { animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>