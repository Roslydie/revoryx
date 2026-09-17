<template>
    <main class="content-wrapper">
        <div class="page-header">
            <div>
                <h1 class="page-title">My profile</h1>
                <p class="page-subtitle">Manage your account information and security settings.</p>
            </div>
        </div>

        <div v-if="feedback.message" class="alert" :class="`alert-${feedback.type}`" role="alert">
            {{ feedback.message }}
        </div>

        <div v-if="loading" class="card text-center text-muted py-5">
            <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
            Loading profile...
        </div>

        <div v-else class="row g-4">
            <div class="col-xl-4">
                <div class="card profile-summary-card h-100">
                    <div class="profile-avatar"><i class="bi bi-person"></i></div>
                    <h2 class="profile-name">{{ user.full_name }}</h2>
                    <p class="profile-email">{{ user.email }}</p>
                    <span class="badge profile-role">{{ user.role?.name || 'User' }}</span>
                    <div class="profile-status mt-4">
                        <span class="status-dot" :class="user.is_active ? 'active' : 'inactive'"></span>
                        {{ user.is_active ? 'Active account' : 'Inactive account' }}
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="card-title">Personal information</h2>
                            <span class="text-muted small">Update the details associated with your account.</span>
                        </div>
                    </div>

                    <form @submit.prevent="saveProfile">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="profile-name" class="form-label">Full name</label>
                                <input id="profile-name" v-model.trim="form.full_name" type="text" class="form-control" :class="{ 'is-invalid': errors.full_name }" required>
                                <div v-if="errors.full_name" class="invalid-feedback">{{ errors.full_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label for="profile-email" class="form-label">Email</label>
                                <input id="profile-email" v-model.trim="form.email" type="email" class="form-control" :class="{ 'is-invalid': errors.email }" required>
                                <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
                            </div>
                            <div class="col-md-6">
                                <label for="profile-role" class="form-label">Role</label>
                                <input id="profile-role" :value="user.role?.name || 'User'" type="text" class="form-control" disabled>
                                <small class="text-muted">Your role can only be changed by an administrator.</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <h3 class="section-label">Change password</h3>
                            <p class="text-muted small">Leave these fields empty to keep your current password.</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="profile-password" class="form-label">New password</label>
                                <input id="profile-password" v-model="form.password" type="password" class="form-control" :class="{ 'is-invalid': errors.password }" minlength="8" autocomplete="new-password">
                                <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
                            </div>
                            <div class="col-md-6">
                                <label for="profile-password-confirmation" class="form-label">Confirm password</label>
                                <input id="profile-password-confirmation" v-model="form.password_confirmation" type="password" class="form-control" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-dark" :disabled="saving">
                                <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                                Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { getData, putData } from '../../plugins/axios.js'

const user = ref({})
const loading = ref(true)
const saving = ref(false)
const errors = reactive({})
const feedback = reactive({ message: '', type: 'success' })
const form = reactive({ full_name: '', email: '', password: '', password_confirmation: '' })

const setFeedback = (message, type = 'success') => {
    feedback.message = message
    feedback.type = type
}

const clearErrors = () => {
    Object.keys(errors).forEach((key) => delete errors[key])
}

const loadProfile = async () => {
    try {
        const response = await getData('/me')
        user.value = response.user
        form.full_name = response.user.full_name ?? ''
        form.email = response.user.email ?? ''
        localStorage.setItem('user', JSON.stringify(response.user))
    } catch (error) {
        setFeedback('Unable to load your profile.', 'danger')
    } finally {
        loading.value = false
    }
}

const saveProfile = async () => {
    clearErrors()
    saving.value = true
    const payload = {
        full_name: form.full_name,
        email: form.email,
        role_id: user.value.role_id,
        is_active: user.value.is_active
    }
    if (form.password) {
        payload.password = form.password
        payload.password_confirmation = form.password_confirmation
    }

    try {
        const response = await putData(`/users/${user.value.id}`, payload)
        user.value = response.user
        form.password = ''
        form.password_confirmation = ''
        localStorage.setItem('user', JSON.stringify(response.user))
        setFeedback('Profile updated successfully.')
    } catch (error) {
        const validationErrors = error.response?.data?.errors ?? {}
        Object.keys(validationErrors).forEach((key) => { errors[key] = validationErrors[key][0] })
        setFeedback(error.response?.data?.message ?? 'Unable to update your profile.', 'danger')
    } finally {
        saving.value = false
    }
}

onMounted(loadProfile)
</script>

<style scoped>
.profile-summary-card {
    display: flex;
    align-items: center;
    flex-direction: column;
    text-align: center;
}

.profile-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 92px;
    height: 92px;
    margin-bottom: 1rem;
    border-radius: 50%;
    background: #e8f0eb;
    color: #17452f;
    font-size: 2.5rem;
}

.profile-name {
    margin-bottom: 0.35rem;
    font-size: 1.25rem;
}

.profile-email {
    margin-bottom: 1rem;
    color: #64748b;
}

.profile-role {
    padding: 0.5rem 0.8rem;
    background: #e8f0eb;
    color: #17452f;
    font-weight: 600;
    text-transform: capitalize;
}

.profile-status {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    color: #64748b;
    font-size: 0.875rem;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-dot.active { background: #198754; }
.status-dot.inactive { background: #6c757d; }

.section-label {
    margin-bottom: 0.25rem;
    font-size: 1rem;
}
</style>