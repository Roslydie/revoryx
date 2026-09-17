<template>
    <main class="content-wrapper">
        <div class="sidebar-wrapper" id="sidebar">
    <!-- Brand Logo / Identity -->
    <a href="index.html" class="sidebar-brand">
     
      <span>Revoryx & Partners / Admin</span>
    </a>

    <!-- Navigation Menu -->
    <div class="flex-grow-1 overflow-y-auto">
      <!-- Group: Menu -->
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Menu</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="index.html" class="sidebar-menu-link active" id="menu-overview" title="Overview">
              <i class="bi bi-grid-fill"></i>
              <span>Dashboard</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- Group: Components -->
      <div class="sidebar-menu-section">
       
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
           <RouterLink :to="{ name: 'admin.tag' }" class="sidebar-menu-link" id="menu-tags" title="Tags">
              <i class="bi bi-tag"></i>
              <span>Tags</span>
           </RouterLink>
          </li>
          <li class="sidebar-menu-item">
            <RouterLink :to="{ name: 'admin.category' }" class="sidebar-menu-link" id="menu-category" title="Category">
              <i class="bi bi-folder2-open"></i>
              <span>Category</span>
            </RouterLink>
          </li>
          <li class="sidebar-menu-item">
            <RouterLink :to="{ name: 'admin.blog' }" class="sidebar-menu-link" id="menu-blogs" title="Blogs">
              <i class="bi bi-journal-text"></i>
              <span>Blogs</span>
            </RouterLink>
          </li>
          <li class="sidebar-menu-item">
            <RouterLink :to="{ name: 'admin.project' }" class="sidebar-menu-link" id="menu-projects" title="Projects">
              <i class="bi bi-folder2-open"></i>
              <span>Projects</span>
            </RouterLink>
          </li>
          <li class="sidebar-menu-item">
            <RouterLink :to="{ name: 'admin.testimonial' }" class="sidebar-menu-link" id="menu-testimonials" title="Testimonials">
              <i class="bi bi-chat-square-quote"></i>
              <span>Testimonials</span>
            </RouterLink>
          </li>
          <li class="sidebar-menu-item">
            <RouterLink :to="{ name: 'admin.contact' }" class="sidebar-menu-link" id="menu-contacts" title="Contacts">
              <i class="bi bi-envelope"></i>
              <span>Contacts</span>
            </RouterLink>
          </li>
          <li class="sidebar-menu-item">
            <RouterLink :to="{ name: 'admin.newsletter' }" class="sidebar-menu-link" id="menu-newsletter" title="Newsletter">
              <i class="bi bi-envelope"></i>
              <span>Newsletter</span>
            </RouterLink>
          </li>
        </ul>
      </div>

      <!-- Group: Users -->
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Users Management</div>
        <ul class="sidebar-menu-list">
        
           <li class="sidebar-menu-item">
            <RouterLink :to="{ name: 'admin.role' }" class="sidebar-menu-link" id="menu-roles" title="Roles">
              <i class="bi bi-people"></i>
              <span>Roles</span>
            </RouterLink>
          </li>

          <li class="sidebar-menu-item">
            <RouterLink :to="{ name: 'admin.users' }" class="sidebar-menu-link" id="menu-users" title="Users">
              <i class="bi bi-people"></i>
              <span>Users</span>
            </RouterLink>
          </li>
         
         
        </ul>
      </div>
    </div>

    <!-- Sidebar Profile Card (Dynamic Footer) -->
    <div class="sidebar-profile">
      <i class="bi bi-person-circle sidebar-profile-icon" aria-hidden="true"></i>
      <div class="sidebar-profile-info">
        <div class="sidebar-profile-name">{{ currentUser?.full_name || 'User' }}</div>
        <div class="sidebar-profile-email">{{ currentUser?.email || 'No email' }}</div>
      </div>
    </div>
  </div>
</main>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { getData } from '../../plugins/axios.js'

const currentUser = ref(null)

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
</script>

<style scoped>
.sidebar-profile-icon {
  flex: 0 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: var(--brand-forest-medium);
  color: #ffffff;
  font-size: 1.4rem;
}
</style>