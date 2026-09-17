<template>
    <main class="content-wrapper">
        <div class="page-header">
            <div>
                <h1 class="page-title">Testimonials</h1>
                <p class="page-subtitle">Review customer feedback and publish the voices you want to show on the homepage.</p>
            </div>
            <button class="btn-date-picker" type="button" @click="openCreateForm">
                <i class="bi bi-plus-lg"></i>
                <span>New testimonial</span>
            </button>
        </div>

        <div v-if="feedback.message" class="alert" :class="`alert-${feedback.type}`" role="alert">
            {{ feedback.message }}
        </div>

        <div v-if="showForm" class="card testimonial-form-card">
            <div class="card-header">
                <h2 class="card-title">{{ editingTestimonial ? 'Edit testimonial' : 'Create testimonial' }}</h2>
                <button class="card-more-btn" type="button" aria-label="Close form" @click="closeForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form @submit.prevent="saveTestimonial">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="testimonial-first-name" class="form-label">First name</label>
                        <input id="testimonial-first-name" v-model.trim="form.prenom" type="text" class="form-control" :class="{ 'is-invalid': errors.prenom }" required>
                        <div v-if="errors.prenom" class="invalid-feedback">{{ errors.prenom }}</div>
                    </div>
                    <div class="col-md-6">
                        <label for="testimonial-last-name" class="form-label">Last name</label>
                        <input id="testimonial-last-name" v-model.trim="form.nom" type="text" class="form-control" :class="{ 'is-invalid': errors.nom }" required>
                        <div v-if="errors.nom" class="invalid-feedback">{{ errors.nom }}</div>
                    </div>
                    <div class="col-12">
                        <label for="testimonial-message" class="form-label">Testimonial</label>
                        <textarea id="testimonial-message" v-model.trim="form.message" class="form-control" :class="{ 'is-invalid': errors.message }" rows="5" required></textarea>
                        <div v-if="errors.message" class="invalid-feedback">{{ errors.message }}</div>
                    </div>
                </div>
                <div class="testimonial-form-actions d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light" @click="closeForm">Cancel</button>
                    <button type="submit" class="btn btn-dark" :disabled="saving">
                        <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        {{ editingTestimonial ? 'Update testimonial' : 'Save testimonial' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">All testimonials</h2>
                    <span class="text-muted small">{{ testimonials.length }} testimonial{{ testimonials.length === 1 ? '' : 's' }} total</span>
                </div>
                <button class="card-more-btn" type="button" aria-label="Refresh testimonials" :disabled="loading" @click="loadTestimonials">
                    <i class="bi bi-arrow-clockwise" :class="{ spin: loading }"></i>
                </button>
            </div>

            <div v-if="loading && !testimonials.length" class="text-center text-muted py-5">
                <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                Loading testimonials...
            </div>
            <Datatable
                v-else
                :data="testimonials"
                :columns="columns"
                :items-per-page="10"
                :DeleteAllFunction="deleteSelected"
            >
                <template #name="{ row }">
                    <div class="testimonial-name-cell">
                        <span class="testimonial-avatar"><i class="bi bi-person"></i></span>
                        <strong>{{ row.prenom }} {{ row.nom }}</strong>
                    </div>
                </template>
                <template #message="{ row }">
                    <span class="testimonial-message-cell">{{ row.message }}</span>
                </template>
                <template #status="{ row }">
                    <span class="badge" :class="row.published ? 'text-bg-success' : 'text-bg-secondary'">
                        {{ row.published ? 'Published' : 'Draft' }}
                    </span>
                </template>
                <template #actions="{ row }">
                    <div class="d-flex justify-content-end gap-1 flex-wrap">
                        <button class="btn btn-sm btn-light" type="button" aria-label="Edit testimonial" @click="openEditForm(row)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm" :class="row.published ? 'btn-outline-warning' : 'btn-outline-success'" type="button" :aria-label="row.published ? 'Unpublish testimonial' : 'Publish testimonial'" @click="togglePublish(row)">
                            <i :class="row.published ? 'bi bi-eye-slash' : 'bi bi-send-check'"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" type="button" aria-label="Delete testimonial" @click="removeTestimonial(row)">
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
import { deleteData, getData, patchData, postData, putData } from '../../plugins/axios.js'

const testimonials = ref([])
const loading = ref(false)
const saving = ref(false)
const showForm = ref(false)
const editingTestimonial = ref(null)
const errors = reactive({})
const feedback = reactive({ message: '', type: 'success' })
const form = reactive({ prenom: '', nom: '', message: '' })

const columns = [
    { title: 'Name', data: 'name', slot: 'name' },
    { title: 'Message', data: 'message', slot: 'message' },
    { title: 'Status', data: 'published', slot: 'status' },
    { title: 'Date', data: 'created_at' },
    { title: 'Actions', data: 'actions', slot: 'actions' }
]

const setFeedback = (message, type = 'success') => {
    feedback.message = message
    feedback.type = type
}

const clearErrors = () => Object.keys(errors).forEach((key) => delete errors[key])

const formatDate = (date) => date
    ? new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric', year: 'numeric' }).format(new Date(date))
    : '-'

const loadTestimonials = async () => {
    loading.value = true
    try {
        const response = await getData('/testimonials')
        testimonials.value = Array.isArray(response) ? response : response.data ?? []
    } catch (error) {
        setFeedback('Unable to load testimonials.', 'danger')
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    form.prenom = ''
    form.nom = ''
    form.message = ''
    editingTestimonial.value = null
    clearErrors()
}

const openCreateForm = () => {
    resetForm()
    showForm.value = true
}

const openEditForm = (testimonial) => {
    editingTestimonial.value = testimonial
    form.prenom = testimonial.prenom ?? ''
    form.nom = testimonial.nom ?? ''
    form.message = testimonial.message ?? ''
    clearErrors()
    showForm.value = true
}

const closeForm = () => {
    showForm.value = false
    resetForm()
}

const saveTestimonial = async () => {
    clearErrors()
    saving.value = true
    try {
        if (editingTestimonial.value) {
            await putData(`/testimonials/${editingTestimonial.value.id}`, form)
            setFeedback('Testimonial updated successfully.')
        } else {
            await postData('/testimonials', form)
            setFeedback('Testimonial created as draft.')
        }
        closeForm()
        await loadTestimonials()
    } catch (error) {
        const validationErrors = error.response?.data?.errors ?? {}
        Object.keys(validationErrors).forEach((key) => { errors[key] = validationErrors[key][0] })
        setFeedback(error.response?.data?.message ?? 'Unable to save the testimonial.', 'danger')
    } finally {
        saving.value = false
    }
}

const togglePublish = async (testimonial) => {
    try {
        await patchData(`/testimonials/${testimonial.id}/publish`)
        setFeedback(testimonial.published ? 'Testimonial moved to draft.' : 'Testimonial published successfully.')
        await loadTestimonials()
    } catch (error) {
        setFeedback('Unable to update the testimonial status.', 'danger')
    }
}

const removeTestimonial = async (testimonial) => {
    const result = await Swal.fire({
        title: 'Delete this testimonial?',
        text: `The testimonial from ${testimonial.prenom} ${testimonial.nom} will be permanently deleted.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete testimonial',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        confirmButtonColor: '#051C12',
        cancelButtonColor: '#64748b',
        focusCancel: true
    })
    if (!result.isConfirmed) return

    try {
        await deleteData(`/testimonials/${testimonial.id}`)
        setFeedback('Testimonial deleted successfully.')
        await loadTestimonials()
    } catch (error) {
        setFeedback('Unable to delete the testimonial.', 'danger')
    }
}

const deleteSelected = () => undefined

onMounted(loadTestimonials)
</script>

<style scoped>
.testimonial-form-card { max-width: 900px; }
.testimonial-name-cell { display: flex; align-items: center; gap: .75rem; min-width: 180px; }
.testimonial-avatar { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; color: #fff; background: #0f5132; }
.testimonial-message-cell { display: block; max-width: 420px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.spin { animation: spin .8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>