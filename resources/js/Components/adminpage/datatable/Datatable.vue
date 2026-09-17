<template>
    <div class="border rounded-3 overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-bottom bg-light">
            <div class="input-group input-group-sm" style="max-width: 280px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="ti ti-search text-muted"></i>
                </span>
                <input
                    v-model="searchTerm"
                    type="search"
                    class="form-control border-start-0"
                    placeholder="Search..."
                >
            </div>
            <button
                v-if="selectedCount"
                type="button"
                class="btn btn-sm btn-outline-danger"
                @click="emitDeleteAll"
            >
                Delete ({{ selectedCount }})
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th v-for="column in columns" :key="column.title || column.data" scope="col">
                            {{ column.title || column.data }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in paginatedRows" :key="getRowKey(row)">
                        <td v-for="column in columns" :key="`${getRowKey(row)}-${column.title || column.data}`">
                            <slot v-if="column.slot" :name="column.slot" :row="row"></slot>
                            <span v-else-if="column.render" v-html="renderCell(column, row)"></span>
                            <span v-else>{{ getValue(row, column.data) }}</span>
                        </td>
                    </tr>
                    <tr v-if="!paginatedRows.length">
                        <td :colspan="columns.length" class="text-center text-muted py-4">
                            No data available
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="totalPages > 1" class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-top">
            <small class="text-muted">Page {{ currentPage }} of {{ totalPages }}</small>
            <div class="btn-group btn-group-sm" role="group" aria-label="Table pagination">
                <button type="button" class="btn btn-outline-secondary" :disabled="currentPage === 1" @click="currentPage--">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button
                    v-for="page in totalPages"
                    :key="page"
                    type="button"
                    class="btn"
                    :class="page === currentPage ? 'btn-dark' : 'btn-outline-secondary'"
                    @click="currentPage = page"
                >
                    {{ page }}
                </button>
                <button type="button" class="btn btn-outline-secondary" :disabled="currentPage === totalPages" @click="currentPage++">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'

const props = defineProps({
    data: {
        type: Array,
        default: () => []
    },
    columns: {
        type: Array,
        default: () => []
    },
    DeleteAllFunction: {
        type: Function,
        required: true
    },
    itemsPerPage: {
        type: Number,
        default: 10
    }
})

const searchTerm = ref('')
const selectedCount = ref(0)
const currentPage = ref(1)

const filteredRows = computed(() => {
    const term = searchTerm.value.toLowerCase().trim()

    if (!term) return props.data

    return props.data.filter((row) => {
        return props.columns.some((column) => {
            const value = getValue(row, column.data)
            return String(value ?? '').toLowerCase().includes(term)
        })
    })
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredRows.value.length / props.itemsPerPage)))

const paginatedRows = computed(() => {
    const start = (currentPage.value - 1) * props.itemsPerPage
    return filteredRows.value.slice(start, start + props.itemsPerPage)
})

const getValue = (row, key) => {
    if (!key) return row
    if (typeof key === 'function') return key(row)
    if (typeof key === 'string' && key.includes('.')) {
        return key.split('.').reduce((acc, part) => acc?.[part], row)
    }
    return row?.[key]
}

const getRowKey = (row) => row?.id ?? JSON.stringify(row)

const renderCell = (column, row) => {
    if (!column.render) return ''
    const value = getValue(row, column.data)
    return column.render(value, null, row)
}

const updateSelectionCount = () => {
    selectedCount.value = document.querySelectorAll('.row-checkbox:checked').length
}

const handleSelectionChange = (event) => {
    if (event.target?.classList?.contains('row-checkbox')) {
        updateSelectionCount()
    }
}

const emitDeleteAll = () => {
    props.DeleteAllFunction()
}

watch(searchTerm, () => {
    currentPage.value = 1
})

watch(totalPages, (pageCount) => {
    if (currentPage.value > pageCount) currentPage.value = pageCount
})

onMounted(() => {
    updateSelectionCount()
    document.addEventListener('change', handleSelectionChange)
    document.addEventListener('click', handleSelectionChange)
})

onUnmounted(() => {
    document.removeEventListener('change', handleSelectionChange)
    document.removeEventListener('click', handleSelectionChange)
})
</script>

<style scoped>
.table thead th {
    background-color: #f8fafc;
    color: #64748b;
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 1px solid #e2e8f0;
}

.table td,
.table th {
    border-color: #e2e8f0;
}

.table-hover tbody tr:hover {
    background-color: #f8fafc;
}
</style>