<template>
  <main class="admin-dashboard content-wrapper">
    <header class="dashboard-heading">
      <div>
        <p class="dashboard-eyebrow">REVORYX & PARTNERS / ADMIN</p>
        <h1>Good morning, {{ userName }}</h1>
        <p class="dashboard-subtitle">A clear view of your content, audience and daily operations.</p>
      </div>
      <button class="refresh-button" type="button" :disabled="loading" @click="loadDashboard">
        <i class="bi bi-arrow-clockwise" :class="{ 'spin-once': loading }"></i>
        <span>{{ loading ? 'Refreshing' : 'Refresh data' }}</span>
      </button>
    </header>

    <div v-if="errorMessage" class="dashboard-alert"><i class="bi bi-exclamation-circle"></i>{{ errorMessage }}</div>

    <section class="metric-grid" aria-label="Overview statistics">
      <article v-for="metric in metrics" :key="metric.label" class="metric-card">
        <div class="metric-icon" :class="`metric-icon--${metric.tone}`"><i :class="metric.icon"></i></div>
        <div class="metric-copy"><span>{{ metric.label }}</span><strong>{{ metric.value }}</strong><small>{{ metric.detail }}</small></div>
        <router-link :to="metric.to" class="metric-link" :aria-label="`Open ${metric.label}`"><i class="bi bi-arrow-up-right"></i></router-link>
      </article>
    </section>

    <section class="dashboard-grid dashboard-grid--primary">
      <article class="dashboard-panel welcome-panel">
        <div class="panel-kicker"><span class="status-dot"></span>Workspace overview</div>
        <h2>Keep the digital experience moving.</h2>
        <p>Review your latest content, respond to contacts and keep your subscriber community engaged from one place.</p>
        <div class="quick-actions">
          <router-link to="/admin/blog" class="quick-action"><i class="bi bi-journal-plus"></i><span>Write a blog</span><i class="bi bi-arrow-right"></i></router-link>
          <router-link to="/admin/project" class="quick-action"><i class="bi bi-folder-plus"></i><span>Add a project</span><i class="bi bi-arrow-right"></i></router-link>
          <router-link to="/admin/newsletter" class="quick-action"><i class="bi bi-send-plus"></i><span>New newsletter</span><i class="bi bi-arrow-right"></i></router-link>
        </div>
      </article>
      <article class="dashboard-panel pulse-panel">
        <div class="panel-header"><div><span class="panel-label">Content pulse</span><h2>Publishing health</h2></div><i class="bi bi-activity panel-header-icon"></i></div>
        <div class="pulse-score"><strong>{{ publishingRate }}%</strong><span>published blogs</span></div>
        <div class="pulse-track"><span :style="{ width: `${publishingRate}%` }"></span></div>
        <div class="pulse-rows"><div><span>Published</span><strong>{{ publishedBlogs }}</strong></div><div><span>Drafts</span><strong>{{ draftBlogs }}</strong></div><div><span>Projects</span><strong>{{ projects.length }}</strong></div></div>
      </article>
    </section>

    <section class="dashboard-grid dashboard-grid--content">
      <article class="dashboard-panel activity-panel">
        <div class="panel-header"><div><span class="panel-label">Latest work</span><h2>Recent content</h2></div><router-link to="/admin/blog" class="panel-link">View all <i class="bi bi-arrow-up-right"></i></router-link></div>
        <div v-if="!recentContent.length" class="empty-state">No content available yet.</div>
        <div v-else class="activity-list"><router-link v-for="item in recentContent" :key="item.key" :to="item.to" class="activity-item"><div class="activity-avatar" :class="`activity-avatar--${item.tone}`"><i :class="item.icon"></i></div><div class="activity-copy"><strong>{{ item.title }}</strong><span>{{ item.type }} · {{ formatDate(item.date) }}</span></div><i class="bi bi-chevron-right activity-arrow"></i></router-link></div>
      </article>

      <article class="dashboard-panel contacts-panel">
        <div class="panel-header"><div><span class="panel-label">Inbox</span><h2>Recent contacts</h2></div><router-link to="/admin/contact" class="panel-link">View all <i class="bi bi-arrow-up-right"></i></router-link></div>
        <div v-if="!contacts.length" class="empty-state">No contacts available yet.</div>
        <div v-else class="contact-list"><router-link v-for="contact in contacts.slice(0, 4)" :key="contact.id" to="/admin/contact" class="contact-item"><div class="contact-avatar">{{ initials(contact) }}</div><div class="activity-copy"><strong>{{ contactName(contact) }}</strong><span>{{ contact.email || 'No email' }}</span></div><span class="contact-date">{{ formatDate(contact.created_at) }}</span></router-link></div>
      </article>
    </section>

    <section class="dashboard-panel audience-panel">
      <div class="panel-header"><div><span class="panel-label">Audience</span><h2>Newsletter community</h2></div><router-link to="/admin/newsletter" class="panel-link">Manage newsletter <i class="bi bi-arrow-up-right"></i></router-link></div>
      <div class="audience-content"><div class="audience-total"><strong>{{ subscribers.length }}</strong><span>subscribers</span></div><div class="language-breakdown"><div><span class="language-badge">FR</span><div><strong>{{ frenchSubscribers }}</strong><small>French audience</small></div></div><div><span class="language-badge language-badge--blue">EN</span><div><strong>{{ englishSubscribers }}</strong><small>English audience</small></div></div></div><div class="audience-note"><i class="bi bi-lightning-charge"></i><span>{{ messages.length }} message{{ messages.length === 1 ? '' : 's' }} in history</span></div></div>
    </section>
  </main>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { getData } from '../../plugins/axios.js'

const loading = ref(false)
const errorMessage = ref('')
const currentUser = ref(null)
const users = ref([])
const blogs = ref([])
const projects = ref([])
const contacts = ref([])
const subscribers = ref([])
const messages = ref([])

const userName = computed(() => currentUser.value?.full_name?.split(' ')[0] || 'there')
const publishedBlogs = computed(() => blogs.value.filter(blog => blog.status === 'published').length)
const draftBlogs = computed(() => Math.max(blogs.value.length - publishedBlogs.value, 0))
const publishingRate = computed(() => blogs.value.length ? Math.round((publishedBlogs.value / blogs.value.length) * 100) : 0)
const frenchSubscribers = computed(() => subscribers.value.filter(item => item.language !== 'en').length)
const englishSubscribers = computed(() => subscribers.value.filter(item => item.language === 'en').length)
const metrics = computed(() => [
  { label: 'Team members', value: users.value.length, detail: 'Active workspace users', icon: 'bi bi-people', tone: 'green', to: '/admin/users' },
  { label: 'Projects', value: projects.value.length, detail: 'In your portfolio', icon: 'bi bi-folder2-open', tone: 'blue', to: '/admin/project' },
  { label: 'Blog articles', value: blogs.value.length, detail: `${publishedBlogs.value} published`, icon: 'bi bi-journal-text', tone: 'lime', to: '/admin/blog' },
  { label: 'Subscribers', value: subscribers.value.length, detail: 'Newsletter audience', icon: 'bi bi-envelope-paper', tone: 'coral', to: '/admin/newsletter' },
])
const recentContent = computed(() => [...blogs.value.map(item => ({ key: `blog-${item.id}`, title: item.title, type: 'Blog article', date: item.created_at, icon: 'bi bi-journal-text', tone: 'green', to: '/admin/blog' })), ...projects.value.map(item => ({ key: `project-${item.id}`, title: item.title, type: 'Project', date: item.created_at, icon: 'bi bi-folder2-open', tone: 'blue', to: '/admin/project' }))].sort((a, b) => new Date(b.date || 0) - new Date(a.date || 0)).slice(0, 5))

const contactName = contact => contact.full_name || [contact.prenom, contact.nom].filter(Boolean).join(' ') || 'Unknown contact'
const initials = contact => contactName(contact).split(' ').filter(Boolean).slice(0, 2).map(part => part[0]).join('').toUpperCase()
const formatDate = date => date ? new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric' }).format(new Date(date)) : 'Recently'

const loadDashboard = async () => {
  loading.value = true
  errorMessage.value = ''
  const requests = await Promise.allSettled([getData('/me'), getData('/users'), getData('/blogs'), getData('/projects'), getData('/contacts'), getData('/news'), getData('/newsletter/messages')])
  const [me, userData, blogData, projectData, contactData, subscriberData, messageData] = requests
  currentUser.value = me.status === 'fulfilled' ? me.value.user : JSON.parse(localStorage.getItem('user') || 'null')
  users.value = userData.status === 'fulfilled' ? (userData.value.users || []) : []
  blogs.value = blogData.status === 'fulfilled' ? (blogData.value.data || []) : []
  projects.value = projectData.status === 'fulfilled' ? (projectData.value.data || []) : []
  contacts.value = contactData.status === 'fulfilled' ? (contactData.value.data || []) : []
  subscribers.value = subscriberData.status === 'fulfilled' ? (subscriberData.value.data || []) : []
  messages.value = messageData.status === 'fulfilled' ? (messageData.value.data || []) : []
  if (requests.every(request => request.status === 'rejected')) errorMessage.value = 'Dashboard data could not be loaded.'
  loading.value = false
}

onMounted(loadDashboard)
</script>

<style scoped>
.admin-dashboard { min-height: 100vh; padding: 2rem 2.5rem 3rem; background: #f4f6f5; color: #0b130f; }
.dashboard-heading { display:flex; align-items:flex-end; justify-content:space-between; gap:24px; margin-bottom:28px; }
.dashboard-eyebrow,.panel-label { margin:0 0 7px; color:#0c5adb; font-size:11px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
.dashboard-heading h1 { margin:0; color:#123b6d; font-size:clamp(1.8rem,3vw,2.55rem); letter-spacing:-.04em; }
.dashboard-subtitle { margin:8px 0 0; color:#718198; }
.refresh-button { display:flex; align-items:center; gap:9px; border:1px solid #dce5ed; border-radius:8px; padding:11px 16px; background:#fff; color:#123b6d; font-weight:700; box-shadow:0 5px 18px rgba(18,59,109,.06); }
.refresh-button:disabled { opacity:.6; }.spin-once { animation: spin 1s linear infinite; } @keyframes spin { to { transform:rotate(360deg); } }
.dashboard-alert { margin-bottom:20px; padding:13px 16px; border:1px solid #f4c6c1; border-radius:8px; color:#8c2f28; background:#fff1ef; }.dashboard-alert i { margin-right:8px; }
.metric-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:20px; }.metric-card { position:relative; display:flex; align-items:center; gap:14px; min-height:132px; padding:20px; border:1px solid #e4ebef; border-radius:12px; background:#fff; box-shadow:0 8px 24px rgba(18,59,109,.05); }.metric-icon { display:grid; flex:0 0 46px; place-items:center; width:46px; height:46px; border-radius:10px; font-size:20px; }.metric-icon--green { color:#15724d; background:#def5e8; }.metric-icon--blue { color:#0c5adb; background:#e3edff; }.metric-icon--lime { color:#657d08; background:#eff7c9; }.metric-icon--coral { color:#b45445; background:#fff0eb; }.metric-copy { display:flex; flex-direction:column; min-width:0; }.metric-copy span { color:#718198; font-size:12px; font-weight:700; }.metric-copy strong { margin:3px 0; color:#123b6d; font-size:27px; line-height:1.1; }.metric-copy small { overflow:hidden; color:#9aa8b6; font-size:11px; text-overflow:ellipsis; white-space:nowrap; }.metric-link { position:absolute; top:16px; right:16px; color:#a8b5c2; }.metric-link:hover { color:#0c5adb; }
.dashboard-grid { display:grid; gap:20px; margin-bottom:20px; }.dashboard-grid--primary { grid-template-columns:1.35fr .65fr; }.dashboard-grid--content { grid-template-columns:1.25fr .75fr; }.dashboard-panel { border:1px solid #e4ebef; border-radius:12px; background:#fff; box-shadow:0 8px 24px rgba(18,59,109,.05); }.welcome-panel { padding:28px; color:#fff; background:linear-gradient(135deg,#123b6d,#0c5adb); }.panel-kicker { color:#b9f2d5; font-size:12px; font-weight:700; }.status-dot { display:inline-block; width:7px; height:7px; margin-right:7px; border-radius:50%; background:#65d5a0; }.welcome-panel h2 { max-width:430px; margin:22px 0 11px; color:#fff; font-size:27px; line-height:1.2; }.welcome-panel p { max-width:470px; margin:0 0 24px; color:#d9e8f7; line-height:1.7; }.quick-actions { display:flex; flex-wrap:wrap; gap:9px; }.quick-action { display:flex; align-items:center; gap:9px; padding:10px 12px; border:1px solid rgba(255,255,255,.22); border-radius:7px; color:#fff; background:rgba(255,255,255,.1); font-size:12px; font-weight:700; }.quick-action i:last-child { margin-left:auto; }.quick-action:hover { color:#123b6d; background:#fff; }
.pulse-panel { padding:24px; }.panel-header { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }.panel-header h2 { margin:0; color:#123b6d; font-size:20px; }.panel-header-icon { color:#65d5a0; font-size:24px; }.pulse-score { display:flex; align-items:baseline; gap:8px; margin:28px 0 12px; }.pulse-score strong { color:#123b6d; font-size:42px; }.pulse-score span { color:#718198; font-size:12px; }.pulse-track { height:8px; overflow:hidden; border-radius:10px; background:#eaf0f3; }.pulse-track span { display:block; height:100%; border-radius:inherit; background:#65d5a0; }.pulse-rows { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-top:24px; }.pulse-rows div { display:flex; flex-direction:column; gap:4px; }.pulse-rows span { color:#9aa8b6; font-size:11px; }.pulse-rows strong { color:#123b6d; font-size:18px; }
.activity-panel,.contacts-panel,.audience-panel { padding:24px; }.panel-link { color:#0c5adb; font-size:12px; font-weight:700; white-space:nowrap; }.activity-list,.contact-list { margin-top:18px; }.activity-item,.contact-item { display:flex; align-items:center; gap:12px; padding:13px 0; border-bottom:1px solid #edf1f3; }.activity-item:last-child,.contact-item:last-child { border-bottom:0; }.activity-avatar,.contact-avatar { display:grid; flex:0 0 38px; place-items:center; width:38px; height:38px; border-radius:9px; font-size:16px; }.activity-avatar--green { color:#15724d; background:#def5e8; }.activity-avatar--blue { color:#0c5adb; background:#e3edff; }.activity-copy { display:flex; flex:1; min-width:0; flex-direction:column; gap:4px; }.activity-copy strong { overflow:hidden; color:#26384d; font-size:13px; text-overflow:ellipsis; white-space:nowrap; }.activity-copy span { overflow:hidden; color:#9aa8b6; font-size:11px; text-overflow:ellipsis; white-space:nowrap; }.activity-arrow { color:#bdc8d1; font-size:12px; }.contact-avatar { color:#123b6d; background:#e8f0fb; font-size:12px; font-weight:800; }.contact-date { color:#9aa8b6; font-size:11px; white-space:nowrap; }.empty-state { padding:32px 0 12px; color:#9aa8b6; font-size:13px; text-align:center; }
.audience-panel { margin-bottom:20px; }.audience-content { display:flex; align-items:center; gap:45px; margin-top:24px; }.audience-total { display:flex; flex-direction:column; min-width:145px; padding-right:35px; border-right:1px solid #e5ebef; }.audience-total strong { color:#123b6d; font-size:38px; line-height:1; }.audience-total span { margin-top:8px; color:#718198; font-size:12px; }.language-breakdown { display:flex; flex:1; gap:38px; }.language-breakdown > div { display:flex; align-items:center; gap:11px; }.language-badge { display:grid; place-items:center; width:35px; height:35px; border-radius:50%; color:#15724d; background:#def5e8; font-size:10px; font-weight:800; }.language-badge--blue { color:#0c5adb; background:#e3edff; }.language-breakdown div div { display:flex; flex-direction:column; gap:3px; }.language-breakdown strong { color:#123b6d; font-size:18px; }.language-breakdown small { color:#9aa8b6; font-size:11px; }.audience-note { display:flex; align-items:center; gap:8px; color:#718198; font-size:12px; }.audience-note i { color:#d99d20; }
@media (max-width:1100px) { .metric-grid { grid-template-columns:repeat(2,1fr); }.dashboard-grid--primary,.dashboard-grid--content { grid-template-columns:1fr; } }
@media (max-width:640px) { .admin-dashboard { padding:1.25rem 1rem 2rem; }.dashboard-heading { align-items:flex-start; flex-direction:column; }.refresh-button { width:100%; justify-content:center; }.metric-grid { gap:10px; }.metric-card { min-height:112px; padding:14px; }.metric-copy strong { font-size:22px; }.metric-copy small { display:none; }.welcome-panel,.pulse-panel,.activity-panel,.contacts-panel,.audience-panel { padding:20px; }.audience-content { align-items:flex-start; flex-direction:column; gap:22px; }.audience-total { width:100%; padding:0 0 18px; border-right:0; border-bottom:1px solid #e5ebef; }.language-breakdown { width:100%; justify-content:space-between; gap:12px; }.audience-note { width:100%; } }
</style>
