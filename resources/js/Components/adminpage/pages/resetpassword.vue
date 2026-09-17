<template>
  <div class="container-xxl auth-page">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <div class="card rounded-lg shadow-sm">
          <div class="card-body p-4">
            <div class="app-brand text-center mb-3">
                <img :src="'/assets/images/logo_norma.png'" alt="Revoryx & Partners" style="height:54px;" />
            </div>
            <h4 class="card-title text-center mb-2">Réinitialiser le mot de passe</h4>
            <p class="text-muted text-center mb-4">Entrez votre nouvelle combinaison pour accéder à votre compte.</p>

            <form @submit.prevent="resetPassword" class="mb-0">
              <input type="hidden" v-model="token" />

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" v-model="email" class="form-control" :class="errors.email ? 'is-invalid' : ''" placeholder="votre@adresse.com" required />
                <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
              </div>

              <div class="mb-3">
                <label class="form-label">Nouveau mot de passe</label>
                <div class="input-group">
                  <input :type="showPwd ? 'text' : 'password'" v-model="password" class="form-control" :class="errors.password ? 'is-invalid' : ''" placeholder="••••••••" required />
                  <button type="button" class="btn btn-outline-secondary" @click="showPwd = !showPwd">{{ showPwd ? 'Cacher' : 'Afficher' }}</button>
                  <div v-if="errors.password" class="invalid-feedback d-block">{{ errors.password }}</div>
                </div>
                <small class="text-muted">Min. 8 caractères.</small>
              </div>

              <div class="mb-3">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" v-model="password_confirmation" class="form-control" :class="errors.password_confirmation ? 'is-invalid' : ''" placeholder="Confirmez le mot de passe" required />
                <div v-if="errors.password_confirmation" class="invalid-feedback">{{ errors.password_confirmation }}</div>
              </div>

              <button class="btn btn-primary w-100" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                <span v-if="loading">Mise à jour...</span>
                <span v-else>Réinitialiser</span>
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
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { postData } from "../../plugins/axios.js";

const route = useRoute();
const router = useRouter();

const token = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");
const loading = ref(false);
const message = ref("");
const error = ref("");
const errors = ref({});
const showPwd = ref(false);

onMounted(() => {
  token.value = route.query.token || "";
  email.value = route.query.email || "";
});

const resetPassword = async () => {
  loading.value = true;
  message.value = "";
  error.value = "";
  errors.value = {};

  try {
    const res = await postData("/reset-password", {
      token: token.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });
    message.value = res.message || "Mot de passe mis à jour.";
    setTimeout(() => router.push({ name: 'admin.login' }), 1800);
  } catch (err) {
    if (err.response) {
      if (err.response.status === 422) {
        // Validation errors
        const respErrors = err.response.data.errors || {};
        for (const key in respErrors) {
          errors.value[key] = respErrors[key][0];
        }
      } else {
        error.value = err.response.data.message || 'Erreur lors de la réinitialisation';
      }
    } else {
      error.value = 'Erreur réseau.';
    }
  } finally {
    loading.value = false;
  }
};
</script>