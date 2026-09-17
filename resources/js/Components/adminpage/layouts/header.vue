<template>
    <main class="content-wrapper">
        <header class="navbar-custom">
      <div class="navbar-left">
        <!-- Desktop sidebar toggle (visible on large screens only) -->
        <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3"
          id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
          <i class="bi bi-chevron-bar-left"></i>
        </button>
        <!-- Mobile sidebar toggle -->
        <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
          <i class="bi bi-list"></i>
        </button>

        <!-- Quick Actions Dropdown -->
       
      </div>

      <!-- Mid navbar: search pill -->
      <div class="navbar-search-wrapper">
        <input type="text" class="navbar-search-input" placeholder="Search anything in Spark..." id="main-search">
        <button class="navbar-search-btn" aria-label="Search">
          <i class="bi bi-search"></i>
        </button>
      </div>

      <!-- Right actions -->
      <div class="navbar-actions">
        <!-- Fullscreen Toggle -->
        <button class="navbar-action-btn me-1" aria-label="Toggle Fullscreen" id="btn-fullscreen">
          <i class="bi bi-arrows-fullscreen"></i>
        </button>
       

        <!-- Profile Dropdown -->
        <div class="dropdown ms-2">
          <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false" id="profile-dropdown">
            <i class="bi bi-person-circle navbar-profile-icon" aria-hidden="true"></i>
            <span class="navbar-profile-name d-none d-md-inline">{{ userRole }}</span>
            <i class="bi bi-chevron-down navbar-profile-caret"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profile-dropdown">
            <li class="dropdown-header">Welcome !</li>
            <li><RouterLink class="dropdown-item" :to="{ name: 'admin.profile' }"><i class="bi bi-person"></i> My Account</RouterLink></li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li><button class="dropdown-item text-danger" type="button" @click="logout"><i class="bi bi-box-arrow-right"></i>
              Logout</button></li>
          </ul>
        </div>
      </div>
    </header>
        </main>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { getData, postData } from '../../plugins/axios.js'

const router = useRouter()
const currentUser = ref(null)
const userRole = computed(() => currentUser.value?.role?.name || 'User')

try {
  currentUser.value = JSON.parse(localStorage.getItem('user') || 'null')
} catch (error) {
  currentUser.value = null
}

onMounted(async () => {
  try {
    const response = await getData('/me')
    currentUser.value = response.user
    localStorage.setItem('user', JSON.stringify(response.user))
  } catch (error) {
    // The Axios interceptor handles an expired session.
  }
})

const logout = async () => {
  try {
    await postData('/logout')
  } catch (error) {
    // Clear local authentication even if the token has already expired.
  } finally {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    localStorage.removeItem('token_expires_at')
    await router.push({ name: 'admin.login' })
  }
}
</script>

<style scoped>
.navbar-profile-icon {
  color: var(--brand-forest-medium);
  font-size: 1.45rem;
}
</style>