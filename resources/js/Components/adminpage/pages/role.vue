<template>
    <main class="content-wrapper">
        <div class="page-header">
            <div>
                <h1 class="page-title">Roles</h1>
                <p class="page-subtitle">Define the access levels available for your users.</p>
            </div>
            <button class="btn-date-picker" type="button" @click="openCreateForm">
                <i class="bi bi-plus-lg"></i>
                <span>New role</span>
            </button>
        </div>

        <div v-if="feedback.message" class="alert" :class="`alert-${feedback.type}`" role="alert">
            {{ feedback.message }}
        </div>

        <div v-if="showForm" class="card role-form-card">
            <div class="card-header">
                <h2 class="card-title">{{ editingRole ? 'Edit role' : 'Create role' }}</h2>
                <button class="card-more-btn" type="button" aria-label="Close form" @click="closeForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form @submit.prevent="saveRole">
                <div class="mb-3">
                    <label for="role-name" class="form-label">Name</label>
                    <input
                        id="role-name"
                        v-model.trim="form.name"
                        type="text"
                        class="form-control"
                        :class="{ 'is-invalid': errors.name }"
                        maxlength="255"
                        placeholder="e.g. editor"
                        required
                    >
                    <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light" @click="closeForm">Cancel</button>
                    <button type="submit" class="btn btn-dark" :disabled="saving">
                        <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        {{ editingRole ? 'Update role' : 'Create role' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">All roles</h2>
                    <span class="text-muted small">{{ roles.length }} role{{ roles.length === 1 ? '' : 's' }} total</span>
                </div>
                <button class="card-more-btn" type="button" aria-label="Refresh roles" :disabled="loading" @click="loadRoles">
                    <i class="bi bi-arrow-clockwise" :class="{ 'spin': loading }"></i>
                </button>
            </div>

            <div v-if="loading && !roles.length" class="text-center text-muted py-5">
                <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                Loading roles...
            </div>
            <Datatable v-else :data="roles" :columns="columns" :items-per-page="10" :DeleteAllFunction="deleteSelected">
                <template #name="{ row }">
                    <span class="role-name"><i class="bi bi-shield-check"></i>{{ row.name }}</span>
                </template>
                <template #actions="{ row }">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn btn-sm btn-light" type="button" aria-label="Edit role" @click="openEditForm(row)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" type="button" aria-label="Delete role" @click="removeRole(row)">
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

const roles = ref([])
const loading = ref(false)
const saving = ref(false)
const showForm = ref(false)
const editingRole = ref(null)
const errors = reactive({})
const feedback = reactive({ message: '', type: 'success' })
const form = reactive({ name: '' })

const columns = [
    { title: 'Role', data: 'name', slot: 'name' },
    { title: 'Actions', data: 'actions', slot: 'actions' }
]

const setFeedback = (message, type = 'success') => {
    feedback.message = message
    feedback.type = type
}

const loadRoles = async () => {
    loading.value = true
    try {
        const response = await getData('/roles')
        roles.value = response.roles ?? []
    } catch (error) {
        setFeedback('Unable to load roles.', 'danger')
    } finally {
        loading.value = false
    }
}

const clearErrors = () => {
    Object.keys(errors).forEach((key) => delete errors[key])
}

const resetForm = () => {
    form.name = ''
    editingRole.value = null
    clearErrors()
}

const openCreateForm = () => {
    resetForm()
    showForm.value = true
}

const openEditForm = (role) => {
    editingRole.value = role
    form.name = role.name
    clearErrors()
    showForm.value = true
}

const closeForm = () => {
    showForm.value = false
    resetForm()
}

const saveRole = async () => {
    clearErrors()
    saving.value = true
    const isEditing = Boolean(editingRole.value)
    try {
        if (isEditing) {
            await putData(`/roles/${editingRole.value.id}`, { name: form.name })
        } else {
            await postData('/roles', { name: form.name })
        }
        closeForm()
        await loadRoles()
        setFeedback(isEditing ? 'Role updated successfully.' : 'Role created successfully.')
    } catch (error) {
        const validationErrors = error.response?.data?.errors ?? {}
        Object.keys(validationErrors).forEach((key) => { errors[key] = validationErrors[key][0] })
        setFeedback(error.response?.data?.message ?? 'Unable to save the role.', 'danger')
    } finally {
        saving.value = false
    }
}

const removeRole = async (role) => {
    const result = await Swal.fire({
        title: 'Delete this role?',
        text: `The role "${role.name}" will be permanently deleted.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete role',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        confirmButtonColor: '#051C12',
        cancelButtonColor: '#64748b',
        focusCancel: true
    })
    if (!result.isConfirmed) return

    try {
        await deleteData(`/roles/${role.id}`)
        setFeedback('Role deleted successfully.')
        await loadRoles()
    } catch (error) {
        setFeedback(error.response?.data?.message ?? 'Unable to delete the role.', 'danger')
    }
}

const deleteSelected = () => undefined

onMounted(loadRoles)
</script>

<style scoped>
.role-form-card {
    max-width: 680px;
}

.role-form-card .form-control {
    max-width: 520px;
}

.role-name {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    font-weight: 600;
    text-transform: capitalize;
}

.role-name i {
    color: #17452f;
}

.spin {
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>