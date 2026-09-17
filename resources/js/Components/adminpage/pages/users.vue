<template>
    <main class="content-wrapper">
        <div class="page-header">
            <div>
                <h1 class="page-title">Users</h1>
                <p class="page-subtitle">Manage administrator accounts and access roles.</p>
            </div>
            <button class="btn-date-picker" type="button" @click="openCreateForm">
                <i class="bi bi-plus-lg"></i>
                <span>New user</span>
            </button>
        </div>

        <div v-if="feedback.message" class="alert" :class="`alert-${feedback.type}`" role="alert">
            {{ feedback.message }}
        </div>

        <div v-if="showForm" class="card user-form-card">
            <div class="card-header">
                <h2 class="card-title">{{ editingUser ? 'Edit user' : 'Create user' }}</h2>
                <button class="card-more-btn" type="button" aria-label="Close form" @click="closeForm">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form @submit.prevent="saveUser">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="user-name" class="form-label">Full name</label>
                        <input id="user-name" v-model.trim="form.full_name" type="text" class="form-control" :class="{ 'is-invalid': errors.full_name }" required>
                        <div v-if="errors.full_name" class="invalid-feedback">{{ errors.full_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label for="user-email" class="form-label">Email</label>
                        <input id="user-email" v-model.trim="form.email" type="email" class="form-control" :class="{ 'is-invalid': errors.email }" required>
                        <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
                    </div>
                    <div class="col-md-6">
                        <label for="user-role" class="form-label">Role</label>
                        <select id="user-role" v-model="form.role_id" class="form-select" :class="{ 'is-invalid': errors.role_id }" required>
                            <option value="" disabled>Select a role</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                        </select>
                        <div v-if="errors.role_id" class="invalid-feedback">{{ errors.role_id }}</div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch mt-3">
                            <input id="user-active" v-model="form.is_active" class="form-check-input" type="checkbox" role="switch">
                            <label for="user-active" class="form-check-label">Active account</label>
                        </div>
                    </div>
                    <div v-if="!editingUser" class="col-12">
                        <div class="alert alert-info mb-0">
                                            A secure temporary password will be generated and sent to the new user's email address.
                        </div>
                    </div>
                    <template v-else>
                        <div class="col-md-6">
                            <label for="user-password" class="form-label">New password <span class="text-muted">(optional)</span></label>
                            <input id="user-password" v-model="form.password" type="password" class="form-control" :class="{ 'is-invalid': errors.password }" minlength="8">
                            <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
                        </div>
                        <div class="col-md-6">
                            <label for="user-password-confirmation" class="form-label">Confirm password</label>
                            <input id="user-password-confirmation" v-model="form.password_confirmation" type="password" class="form-control">
                        </div>
                    </template>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light" @click="closeForm">Cancel</button>
                    <button type="submit" class="btn btn-dark" :disabled="saving">
                        <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        {{ editingUser ? 'Update user' : 'Create user' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">All users</h2>
                    <span class="text-muted small">{{ users.length }} user{{ users.length === 1 ? '' : 's' }} total</span>
                </div>
                <button class="card-more-btn" type="button" aria-label="Refresh users" :disabled="loading" @click="loadUsers">
                    <i class="bi bi-arrow-clockwise" :class="{ 'spin': loading }"></i>
                </button>
            </div>

            <div v-if="loading && !users.length" class="text-center text-muted py-5">
                <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                Loading users...
            </div>
            <Datatable v-else :data="users" :columns="columns" :items-per-page="10" :DeleteAllFunction="deleteSelected">
                <template #user="{ row }">
                    <div class="user-name-cell">
                        <span class="user-avatar">{{ initials(row.full_name) }}</span>
                        <span>{{ row.full_name }}</span>
                    </div>
                </template>
                <template #status="{ row }">
                    <span class="badge" :class="row.is_active ? 'text-bg-success' : 'text-bg-secondary'">
                        {{ row.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </template>
                <template #actions="{ row }">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn btn-sm btn-light" type="button" aria-label="Edit user" @click="openEditForm(row)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" type="button" aria-label="Delete user" @click="removeUser(row)">
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

const users = ref([])
const roles = ref([])
const loading = ref(false)
const saving = ref(false)
const showForm = ref(false)
const editingUser = ref(null)
const errors = reactive({})
const feedback = reactive({ message: '', type: 'success' })
const form = reactive({ full_name: '', email: '', role_id: '', is_active: true, password: '', password_confirmation: '' })

const columns = [
    { title: 'User', data: 'full_name', slot: 'user' },
    { title: 'Email', data: 'email' },
    { title: 'Role', data: 'role.name' },
    { title: 'Status', data: 'is_active', slot: 'status' },
    { title: 'Actions', data: 'actions', slot: 'actions' }
]

const setFeedback = (message, type = 'success') => {
    feedback.message = message
    feedback.type = type
}

const loadUsers = async () => {
    loading.value = true
    try {
        const response = await getData('/users')
        users.value = response.users ?? []
    } catch (error) {
        setFeedback('Unable to load users.', 'danger')
    } finally {
        loading.value = false
    }
}

const loadRoles = async () => {
    try {
        const response = await getData('/roles')
        roles.value = response.roles ?? []
    } catch (error) {
        setFeedback('Unable to load roles.', 'danger')
    }
}

const clearErrors = () => {
    Object.keys(errors).forEach((key) => delete errors[key])
}

const resetForm = () => {
    Object.assign(form, { full_name: '', email: '', role_id: '', is_active: true, password: '', password_confirmation: '' })
    editingUser.value = null
    clearErrors()
}

const openCreateForm = () => {
    resetForm()
    showForm.value = true
}

const openEditForm = (user) => {
    editingUser.value = user
    Object.assign(form, { full_name: user.full_name, email: user.email, role_id: user.role_id, is_active: Boolean(user.is_active), password: '', password_confirmation: '' })
    clearErrors()
    showForm.value = true
}

const closeForm = () => {
    showForm.value = false
    resetForm()
}

const saveUser = async () => {
    clearErrors()
    saving.value = true
    const isEditing = Boolean(editingUser.value)
    try {
        const payload = { full_name: form.full_name, email: form.email, role_id: form.role_id, is_active: form.is_active }
        if (editingUser.value && form.password) {
            payload.password = form.password
            payload.password_confirmation = form.password_confirmation
        }
        const response = isEditing
            ? await putData(`/users/${editingUser.value.id}`, payload)
            : await postData('/users', payload)

        closeForm()
        await loadUsers()
        if (!isEditing && response.email_sent === false) {
            setFeedback(`User created, but the welcome email could not be sent: ${response.email_error ?? 'SMTP delivery failed.'}`, 'warning')
        } else {
            setFeedback(isEditing ? 'User updated successfully.' : 'User created and welcome email sent successfully.')
        }
    } catch (error) {
        const validationErrors = error.response?.data?.errors ?? {}
        Object.keys(validationErrors).forEach((key) => { errors[key] = validationErrors[key][0] })
        setFeedback(error.response?.data?.message ?? 'Unable to save the user.', 'danger')
    } finally {
        saving.value = false
    }
}

const removeUser = async (user) => {
    const result = await Swal.fire({
        title: 'Delete this user?',
        text: `The account "${user.full_name}" will be permanently deleted.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete user',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        confirmButtonColor: '#051C12',
        cancelButtonColor: '#64748b',
        focusCancel: true
    })
    if (!result.isConfirmed) return

    try {
        await deleteData(`/users/${user.id}`)
        setFeedback('User deleted successfully.')
        await loadUsers()
    } catch (error) {
        setFeedback(error.response?.data?.message ?? 'Unable to delete the user.', 'danger')
    }
}

const initials = (name) => name?.split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase() || '?'
const deleteSelected = () => undefined

onMounted(() => {
    loadUsers()
    loadRoles()
})
</script>

<style scoped>
.user-form-card {
    max-width: 900px;
}

.user-name-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 180px;
    font-weight: 600;
}

.user-avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #e8f0eb;
    color: #17452f;
    font-size: 0.75rem;
    font-weight: 700;
}

.spin {
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>