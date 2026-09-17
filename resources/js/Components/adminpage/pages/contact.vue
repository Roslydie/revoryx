<template>
  <main class="content-wrapper">
    <div v-if="toasts.length" class="contact-alerts">
      <div v-for="toast in toasts" :key="toast.id" class="alert" :class="`alert-${toast.type}`" role="alert">
        {{ toast.message }}
        <button type="button" class="card-more-btn" aria-label="Close notification" @click="removeToast(toast.id)">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
    </div>

    <div v-if="confirmState.show" class="modal d-block contact-modal-backdrop">
      <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-3">
          <div class="modal-header border-0 px-4 py-3">
            <h5 class="modal-title fw-semibold">Confirmation</h5>
          </div>
          <div class="modal-body px-4 py-2">
            <p class="mb-0">{{ confirmState.message }}</p>
          </div>
          <div class="modal-footer border-0 px-4 py-3">
            <button type="button" class="btn btn-light border" @click="cancelConfirm">Annuler</button>
            <button type="button" class="btn btn-danger" @click="confirmAction">Supprimer</button>
          </div>
        </div>
      </div>
    </div>

    <div class="page-header">
      <div>
        <h1 class="page-title">Messages de contact</h1>
        <p class="page-subtitle">Consultez et gérez les messages envoyés par les visiteurs.</p>
      </div>
      <button class="card-more-btn" type="button" aria-label="Refresh contacts" :disabled="loading" @click="fetchContacts">
        <i class="bi bi-arrow-clockwise" :class="{ spin: loading }"></i>
      </button>
    </div>

    <div class="card">
      <div class="card-header">
        <div>
          <h2 class="card-title">Tous les messages</h2>
          <span class="text-muted small">{{ contacts.length }} message{{ contacts.length === 1 ? '' : 's' }} au total</span>
        </div>
        <span class="badge text-bg-light">{{ unreadCount }} nouveau{{ unreadCount === 1 ? '' : 'x' }}</span>
      </div>
      <div v-if="loading && !contacts.length" class="text-center text-muted py-5">
        <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
        Chargement des messages...
      </div>
      <Datatable v-else :data="contacts" :columns="tableColumns" :items-per-page="10" :DeleteAllFunction="deleteSelected">
        <template #name="{ row }">
          <div class="contact-name-cell"><span class="contact-avatar"><i class="bi bi-person"></i></span><strong>{{ row.full_name }}</strong></div>
        </template>
        <template #status="{ row }">
          <span class="badge" :class="statusClass(row.status)">{{ statusLabel(row.status) }}</span>
        </template>
        <template #actions="{ row }">
          <div class="d-flex justify-content-end gap-1 flex-wrap">
            <button class="btn btn-sm btn-light" type="button" aria-label="Voir le message" @click="viewContact(row)"><i class="bi bi-eye"></i></button>
            <button class="btn btn-sm btn-outline-danger" type="button" aria-label="Supprimer le message" @click="deleteContact(row.id)"><i class="bi bi-trash3"></i></button>
          </div>
        </template>
      </Datatable>
    </div>

    <div v-if="showDetailModal" class="modal d-block contact-modal-backdrop">
      <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">
          <div class="modal-header border-0 px-4 py-3">
            <h5 class="modal-title fw-semibold">Détails du message</h5>
            <button type="button" class="btn-close" @click="showDetailModal = false"></button>
          </div>
          <div class="modal-body px-4 py-3" v-if="selectedContact">
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label text-muted small">Nom</label>
                <p class="fw-semibold">{{ selectedContact.full_name }}</p>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small">Email</label>
                <p class="fw-semibold">{{ selectedContact.email }}</p>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label text-muted small">Téléphone</label>
                <p class="fw-semibold">{{ selectedContact.phone }}</p>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small">Sujet</label>
                <p class="fw-semibold">{{ selectedContact.subject }}</p>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label text-muted small">Statut</label>
                <span class="badge" :class="statusClass(selectedContact.status)">{{ statusLabel(selectedContact.status) }}</span>
              </div>
              <div class="col-md-6">
                <label class="form-label text-muted small">Date</label>
                <p class="fw-semibold">{{ formatDate(selectedContact.created_at) }}</p>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted small">Message</label>
              <p style="white-space: pre-wrap; background: #f8fafc; padding: 1rem; border-radius: 0.5rem;">{{ selectedContact.message }}</p>
            </div>
          </div>
          <div class="modal-footer border-0 px-4 py-3">
            <button type="button" class="btn btn-light border" @click="showDetailModal = false">Fermer</button>
            <button type="button" class="btn btn-success" @click="updateStatus('read')" v-if="selectedContact?.status !== 'read'">Marquer comme lu</button>
            <button type="button" class="btn btn-secondary" @click="updateStatus('closed')" v-if="selectedContact?.status !== 'closed'">Fermer le ticket</button>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import Datatable from '../datatable/Datatable.vue'
import { getData, putData, deleteData } from '../../plugins/axios'

const contacts = ref([])
const selectedContact = ref(null)
const showDetailModal = ref(false)
const loading = ref(false)
const toasts = ref([])
const confirmState = ref({ show: false, message: '', action: null })

const tableColumns = [
  { title: '', data: null, render: (_data, _type, row) => `<input type="checkbox" class="form-check-input row-checkbox" data-id="${row.id}">` },
  { title: 'Nom', data: 'full_name', slot: 'name' },
  { title: 'Email', data: 'email' },
  { title: 'Sujet', data: 'subject' },
  { title: 'Statut', data: 'status', slot: 'status' },
  { title: 'Date', data: 'created_at', render: (data) => formatDate(data) },
  { title: 'Actions', data: 'actions', slot: 'actions' }
]

const unreadCount = computed(() => contacts.value.filter((contact) => contact.status === 'new').length)

const showToast = (message, type = 'info') => {
    const id = Date.now() + Math.random()
    toasts.value.push({ id, message, type })
    setTimeout(() => removeToast(id), 3000)
}

const removeToast = (id) => {
    toasts.value = toasts.value.filter((toast) => toast.id !== id)
}

const openConfirm = (message, action) => {
    confirmState.value = { show: true, message, action }
}

const cancelConfirm = () => {
    confirmState.value = { show: false, message: '', action: null }
}

const confirmAction = async () => {
    const action = confirmState.value.action
    cancelConfirm()
    if (action) {
        await action()
    }
}

const fetchContacts = async () => {
  loading.value = true
    try {
        const response = await getData('/contacts')
        contacts.value = Array.isArray(response) ? response : []
    } catch (error) {
        console.error('Error fetching contacts:', error)
        showToast('Impossible de charger les messages.', 'error')
      } finally {
        loading.value = false
    }
}

const viewContact = (contact) => {
    selectedContact.value = contact
    showDetailModal.value = true
}

const updateStatus = async (status) => {
    if (!selectedContact.value) return
    try {
        await putData(`/contacts/${selectedContact.value.id}`, { status })
        showToast('Statut mis à jour avec succès.', 'success')
        await fetchContacts()
        showDetailModal.value = false
    } catch (error) {
        console.error('Error updating status:', error)
        showToast('Impossible de mettre à jour le statut.', 'error')
    }
}

const deleteContact = async (id) => {
    openConfirm('Êtes-vous sûr de vouloir supprimer ce message ?', async () => {
        try {
            await deleteData(`/contacts/${id}`)
            await fetchContacts()
            showToast('Message supprimé avec succès.', 'success')
        } catch (error) {
            console.error('Error deleting contact:', error)
            showToast('Impossible de supprimer le message.', 'error')
        }
    })
}

const deleteSelected = async () => {
    const selected = document.querySelectorAll('.row-checkbox:checked')
    if (!selected.length) {
        showToast('Veuillez sélectionner des messages à supprimer.', 'info')
        return
    }

    openConfirm(`Supprimer ${selected.length} message(s) ?`, async () => {
        for (const item of selected) {
            const id = item.getAttribute('data-id')
            try {
                await deleteData(`/contacts/${id}`)
            } catch (error) {
                console.error(`Error deleting contact ${id}:`, error)
            }
        }

        await fetchContacts()
        showToast('Messages supprimés avec succès.', 'success')
    })
}

const statusClass = (status) => {
    switch (status) {
        case 'read': return 'bg-success-subtle text-success'
        case 'closed': return 'bg-secondary-subtle text-secondary'
        default: return 'bg-warning-subtle text-warning'
    }
}

const statusLabel = (status) => {
    switch (status) {
        case 'read': return 'Lu'
        case 'closed': return 'Fermé'
        default: return 'Nouveau'
    }
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('fr-FR', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
}

onMounted(() => {
    fetchContacts()
})
</script>

<style scoped>
.contact-alerts {
  position: fixed;
  top: 24px;
  right: 24px;
  z-index: 1080;
  width: min(380px, calc(100vw - 48px));
}

.contact-alerts .alert {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 10px;
}

.contact-alerts .card-more-btn {
  flex: 0 0 auto;
}

.contact-modal-backdrop {
  background: rgba(15, 23, 42, .58);
}

.modal.d-block {
    display: flex !important;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1050;
}

.modal-dialog {
    width: 90%;
  max-width: 720px;
}

.contact-name-cell {
  display: flex;
  align-items: center;
  gap: 10px;
}

.contact-avatar {
  display: inline-grid;
  width: 32px;
  height: 32px;
  place-items: center;
  border-radius: 50%;
  color: #475569;
  background: #eef2f7;
}
</style>