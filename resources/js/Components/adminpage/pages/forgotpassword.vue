<template>
  <div class="container-xxl auth-page">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <div class="card rounded-lg shadow-sm">
          <div class="card-body p-4">
            <div class="app-brand text-center mb-3">
                <img :src="'/assets/images/logo_norma.png'" alt="Revoryx & Partners" style="height:54px;" />
            </div>
            <h4 class="card-title text-center mb-2">Mot de passe oublié</h4>
            <p class="text-muted text-center mb-4">Indiquez votre adresse e-mail pour recevoir un lien de réinitialisation.</p>

            <form @submit.prevent="sendResetLink">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                  v-model="email"
                  type="email"
                  class="form-control"
                  :class="errors.email ? 'is-invalid' : ''"
                  placeholder="votre@adresse.com"
                  required
                />
                <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
              </div>

              <button class="btn btn-primary w-100" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                <span v-if="loading">Envoi...</span>
                <span v-else>Envoyer le lien de réinitialisation</span>
              </button>
            </form>

            <div class="mt-3">
              <div v-if="message" class="alert alert-success mb-0">{{ message }}</div>
              <div v-if="error" class="alert alert-danger mb-0">{{ error }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { postData } from '../../plugins/axios.js'
const email = ref('')
const loading = ref(false)
const message = ref('')
const error = ref('')
const errors = ref({})

const sendResetLink = async () => {
  loading.value = true
  message.value = ''
  error.value = ''
  errors.value = {}

  try {
    const response = await postData('/forgot-password', { email: email.value })
    message.value = response.message || "Un lien a été envoyé à votre adresse e-mail."
  } catch (err) {
    if (err.response) {
      if (err.response.status === 422) {
        const respErrors = err.response.data.errors || {}
        for (const k in respErrors) errors.value[k] = respErrors[k][0]
      } else {
        error.value = err.response?.data?.message || "Une erreur est survenue."
      }
    } else {
      error.value = 'Erreur réseau.'
    }
  } finally {
    loading.value = false
  }
}
</script>