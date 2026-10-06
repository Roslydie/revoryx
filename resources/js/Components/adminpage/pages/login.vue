<template>
    <div class="login-wrapper">
        <div class="login-bg-shape login-bg-shape-1"></div>
        <div class="login-bg-shape login-bg-shape-2"></div>

        <div class="login-card">
            <a href="/admin/login" class="login-brand text-decoration-none">
                
                <span>Revoryx &amp; Partners</span>
            </a>

            <p class="login-subtitle">Please sign in to access your dashboard</p>

            <div v-if="errorMessage" class="alert alert-danger py-2" role="alert">
                {{ errorMessage }}
            </div>
            <div v-if="route.query.unauthorized === 'true' && !errorMessage" class="alert alert-warning py-2" role="alert">
                Your session has expired. Please sign in again.
            </div>

            <form @submit.prevent="submitLogin" novalidate>
                <div class="login-form-group">
                    <label for="email" class="login-form-label">Email Address</label>
                    <div class="login-input-group">
                        <i class="bi bi-envelope input-icon"></i>
                        <input v-model.trim="form.email" type="email" id="email" class="login-input" placeholder="name@company.com" required autocomplete="email">
                    </div>
                </div>

                <div class="login-form-group">
                    <label for="password" class="login-form-label">Password</label>
                    <div class="login-input-group">
                        <i class="bi bi-shield-lock input-icon"></i>
                        <input v-model="form.password" :type="showPassword ? 'text' : 'password'" id="password" class="login-input login-input-password" placeholder="••••••••" required autocomplete="current-password">
                        <button type="button" class="password-toggle-btn" :aria-label="showPassword ? 'Hide password' : 'Show password'" @click="showPassword = !showPassword">
                            <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="login-options">
                    <label class="custom-control-label">
                        <input v-model="rememberMe" type="checkbox" class="custom-checkbox-input">
                        <span>Remember Me</span>
                    </label>
                    <router-link :to="{ name: 'admin.forgot-password' }" class="forgot-password-link">
                        Forgot password?
                    </router-link>
                </div>

                <button type="submit" class="btn-login" :disabled="submitting">
                    <span v-if="submitting" class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                    <span>{{ submitting ? 'Signing in...' : 'Sign In to Dashboard' }}</span>
                    <i v-if="!submitting" class="bi bi-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { postData } from '../../plugins/axios.js'

const router = useRouter()
const route = useRoute()
const form = reactive({ email: '', password: '' })
const submitting = ref(false)
const showPassword = ref(false)
const rememberMe = ref(false)
const errorMessage = ref('')



const submitLogin = async () => {
    errorMessage.value = ''
    submitting.value = true
    try {
        const response = await postData('/login', form)
        localStorage.setItem('token', response.token)
        localStorage.setItem('user', JSON.stringify(response.user))
        localStorage.setItem('token_expires_at', String(Date.now() + 10 * 60 * 1000))
        await router.push({ name: route.query.redirect === 'user' ? 'user.home' : 'admin.home' })
    } catch (error) {
        errorMessage.value = error.response?.data?.message ?? 'Unable to sign in. Please check your credentials.'
    } finally {
        submitting.value = false
    }
}
</script>

<style scoped>
.login-wrapper {
    width: 100vw;
    min-height: 100vh;
}
</style>