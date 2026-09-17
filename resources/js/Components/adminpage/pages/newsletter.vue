<template>
  <main class="newsletter-admin container-xxl flex-grow-1 container-p-y">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h4 class="mb-1">Newsletter</h4>
        <small class="text-muted">Manage subscribers and bilingual messages.</small>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-success" @click="showSubscriberForm = true"><i class="bi bi-person-plus me-1"></i>Add subscriber</button>
        <button class="btn btn-primary" @click="openMessageForm"><i class="bi bi-envelope-plus me-1"></i>New message</button>
      </div>
    </div>

    <div v-if="feedback.message" class="alert" :class="feedback.type === 'success' ? 'alert-success' : 'alert-danger'">{{ feedback.message }}</div>

    <div v-if="showSubscriberForm" class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Add subscriber</h5><button class="btn-close" @click="showSubscriberForm = false"></button></div>
      <form class="card-body row g-3" @submit.prevent="addSubscriber">
        <div class="col-md-7"><label class="form-label">Email</label><input v-model.trim="subscriberForm.email" type="email" class="form-control" required></div>
        <div class="col-md-3"><label class="form-label">Language</label><select v-model="subscriberForm.language" class="form-select"><option value="fr">Français</option><option value="en">English</option></select></div>
        <div class="col-md-2 d-flex align-items-end"><button class="btn btn-dark w-100" :disabled="loading">Save</button></div>
      </form>
    </div>

    <div v-if="showMessageForm" class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">New bilingual message</h5><button class="btn-close" @click="showMessageForm = false"></button></div>
      <form class="card-body" @submit.prevent="saveMessage">
        <div class="d-flex gap-2 mb-3"><button type="button" class="btn btn-sm" :class="activeLanguage === 'fr' ? 'btn-primary' : 'btn-outline-primary'" @click="activeLanguage = 'fr'">Français</button><button type="button" class="btn btn-sm" :class="activeLanguage === 'en' ? 'btn-primary' : 'btn-outline-primary'" @click="activeLanguage = 'en'">English</button></div>
        <div class="mb-3"><label class="form-label">Subject ({{ activeLanguage.toUpperCase() }})</label><input v-model.trim="messageForm.subject[activeLanguage]" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Message ({{ activeLanguage.toUpperCase() }})</label><QuillEditor v-model:content="messageForm.content[activeLanguage]" content-type="html" theme="snow" toolbar="full" class="newsletter-editor" /></div>
        <div class="d-flex justify-content-end gap-2"><button type="button" class="btn btn-light" @click="showMessageForm = false">Cancel</button><button class="btn btn-dark" :disabled="loading">Save message</button></div>
      </form>
    </div>

    <section class="card mb-4"><div class="card-header"><h5 class="mb-0">Subscribers</h5></div><div class="card-body"><DataTable :data="subscribers" :columns="subscriberColumns" /></div></section>
    <section class="card"><div class="card-header"><h5 class="mb-0">Message history</h5></div><div class="card-body"><DataTable :data="messages" :columns="messageColumns" /></div></section>
  </main>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import Swal from 'sweetalert2'
import DataTable from '../datatable/Datatable.vue'
import { deleteData, getData, postData, putData } from '../../plugins/axios.js'

const subscribers = ref([])
const messages = ref([])
const loading = ref(false)
const showSubscriberForm = ref(false)
const showMessageForm = ref(false)
const activeLanguage = ref('fr')
const subscriberForm = reactive({ email: '', language: 'fr' })
const messageForm = reactive({ subject: { fr: '', en: '' }, content: { fr: '', en: '' } })
const feedback = reactive({ message: '', type: 'success' })

const notify = (message, type = 'success') => { feedback.message = message; feedback.type = type; window.setTimeout(() => { feedback.message = '' }, 4500) }
const loadSubscribers = async () => { const response = await getData('/news'); subscribers.value = response.data || [] }
const loadMessages = async () => { const response = await getData('/newsletter/messages'); messages.value = response.data || [] }
const loadAll = async () => { await Promise.all([loadSubscribers(), loadMessages()]) }

const addSubscriber = async () => {
  loading.value = true
  try {
    await postData('/addnews', subscriberForm)
    subscriberForm.email = ''
    showSubscriberForm.value = false
    await loadSubscribers()
    notify('Subscriber added successfully.')
  } catch (error) { notify(error.response?.data?.message || 'Unable to add subscriber.', 'error') } finally { loading.value = false }
}

const openMessageForm = () => {
  messageForm.subject.fr = ''; messageForm.subject.en = ''; messageForm.content.fr = ''; messageForm.content.en = ''
  activeLanguage.value = 'fr'; showMessageForm.value = true
}

const saveMessage = async () => {
  loading.value = true
  try {
    await postData('/newsletter/messages', { subject: messageForm.subject, content: messageForm.content })
    showMessageForm.value = false
    await loadMessages()
    notify('Message saved. You can send it from the history table.')
  } catch (error) { notify(error.response?.data?.message || 'Unable to save message.', 'error') } finally { loading.value = false }
}

const removeSubscriber = async (id) => {
  const result = await Swal.fire({ title: 'Delete subscriber?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete' })
  if (!result.isConfirmed) return
  try { await deleteData(`/deletenews/${id}`); await loadSubscribers(); notify('Subscriber deleted.') } catch (error) { notify('Unable to delete subscriber.', 'error') }
}

const sendMessage = async (id) => {
  const result = await Swal.fire({ title: 'Send this message?', text: 'It will be sent according to each subscriber language.', icon: 'question', showCancelButton: true, confirmButtonText: 'Send' })
  if (!result.isConfirmed) return
  loading.value = true
  try { const response = await postData(`/newsletter/messages/${id}/send`); await loadMessages(); notify(response.message) } catch (error) { notify(error.response?.data?.message || 'Unable to send message.', 'error') } finally { loading.value = false }
}

const subscriberColumns = [
  { title: 'Email', data: 'email' },
  { title: 'Language', data: 'language' },
  { title: 'Subscribed', data: 'created_at' },
  { title: 'Actions', data: null, render: (data, type, row) => `<button class="btn btn-sm btn-outline-danger" onclick="window.removeNewsletterSubscriber(${row.id})">Delete</button>` },
]
const messageColumns = [
  { title: 'Subject FR', data: 'subject_fr' },
  { title: 'Subject EN', data: 'subject_en' },
  { title: 'Status', data: 'status' },
  { title: 'Recipients', data: 'recipients_count' },
  { title: 'Sent at', data: 'sent_at' },
  { title: 'Actions', data: null, render: (data, type, row) => `<button class="btn btn-sm btn-primary" ${row.status === 'sent' ? 'disabled' : ''} onclick="window.sendNewsletterMessage(${row.id})">Send</button>` },
]

onMounted(async () => {
  window.removeNewsletterSubscriber = removeSubscriber
  window.sendNewsletterMessage = sendMessage
  try { await loadAll() } catch (error) { notify('Unable to load newsletter data.', 'error') }
})
</script>

<style scoped>
.newsletter-editor { min-height: 260px; border: 1px solid #dee2e6; border-radius: .375rem; }
.newsletter-editor :deep(.ql-toolbar) { border: 0; border-bottom: 1px solid #dee2e6; }
.newsletter-editor :deep(.ql-container) { min-height: 215px; border: 0; }
</style>
