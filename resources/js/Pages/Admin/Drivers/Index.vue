<template>
    <Head title="Daftar Driver" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
            <Link
                v-if="can('drivers.create')"
                :href="route('drivers.create')"
                class="flex gap-2 items-center px-3 py-2 text-white rounded btn-primary"
            >
                <PlusSquareIcon />
                <span class="hidden text-sm md:block">Tambah Driver</span>
            </Link>
        </div>

        <div
            class="h-[90%] grid-cols-12 gap-4 md:gap-6 overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"
        >
            <div
                class="flex flex-col gap-2 px-8 py-1 sm:flex-row sm:items-center sm:justify-between"
            >
                <div
                    class="font-bold text-gray-700 md:text-xl dark:text-gray-300"
                >
                    Daftar Driver
                </div>

                <div class="flex gap-3 items-center">
                    <select
                        v-model="companyFilter"
                        class="px-3 h-10 text-sm text-gray-800 bg-transparent rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 focus:border-blue-300 focus:outline-hidden focus:ring-2 focus:ring-blue-500/20"
                    >
                        <option value="">Semua Perusahaan</option>
                        <option
                            v-for="company in props.companies"
                            :key="company.id"
                            :value="company.id"
                        >
                            {{ company.name }}
                        </option>
                    </select>
                    <div class="relative py-2">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2">
                            <SearchIcon class="text-gray-400" />
                        </div>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari driver"
                            class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-400 dark:focus:border-blue-800 xl:w-[200px]"
                        />
                    </div>
                </div>
            </div>

            <!-- Status Filter Tabs -->
            <div class="px-8 border-b border-gray-200 dark:border-gray-600">
                <nav class="flex gap-4 -mb-px">
                    <button
                        @click="statusFilter = 'all'"
                        :class="[
                            'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
                            statusFilter === 'all'
                                ? 'border-primary text-primary'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        ]"
                    >
                        Semua
                        <span
                            v-if="statusFilter === 'all'"
                            class="px-2 py-0.5 ml-2 text-xs text-primary bg-primary/10 rounded-full"
                        >
                            {{ getStatusCount('all') }}
                        </span>
                    </button>
                    <button
                        @click="statusFilter = 'pending'"
                        :class="[
                            'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
                            statusFilter === 'pending'
                                ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        ]"
                    >
                        Menunggu Verifikasi
                        <span
                            v-if="statusFilter === 'pending'"
                            class="px-2 py-0.5 ml-2 text-xs text-yellow-600 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-300"
                        >
                            {{ getStatusCount('pending') }}
                        </span>
                    </button>
                    <button
                        @click="statusFilter = 'active'"
                        :class="[
                            'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
                            statusFilter === 'active'
                                ? 'border-green-500 text-green-600 dark:text-green-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        ]"
                    >
                        Aktif
                        <span
                            v-if="statusFilter === 'active'"
                            class="px-2 py-0.5 ml-2 text-xs text-green-600 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300"
                        >
                            {{ getStatusCount('active') }}
                        </span>
                    </button>
                    <button
                        @click="statusFilter = 'inactive'"
                        :class="[
                            'px-4 py-2 text-sm font-medium border-b-2 transition-colors',
                            statusFilter === 'inactive'
                                ? 'border-red-500 text-red-600 dark:text-red-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                        ]"
                    >
                        Tidak Aktif
                        <span
                            v-if="statusFilter === 'inactive'"
                            class="px-2 py-0.5 ml-2 text-xs text-red-600 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300"
                        >
                            {{ getStatusCount('inactive') }}
                        </span>
                    </button>
                </nav>
            </div>

            <div class="overflow-auto" data-simplebar>
                <table class="min-w-full text-sm">
                    <thead>
                        <tr>
                            <th
                                class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div class="flex justify-center items-center">
                                    <p
                                        class="flex flex-col items-center font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        No.
                                    </p>
                                </div>
                            </th>
                            <th
                                @click="changeSort('name')"
                                class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div
                                    class="flex gap-2 justify-center items-center px-3"
                                >
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        Nama Driver
                                    </p>
                                    <div class="flex flex-col items-center">
                                        <UpIcon
                                            :class="[
                                                '-mb-1',
                                                sortBy === 'name' &&
                                                sortDirection === 'asc'
                                                    ? 'text-gray-900 dark:text-gray-200'
                                                    : 'text-gray-400 dark:text-gray-500',
                                            ]"
                                        />
                                        <DownIcon
                                            :class="[
                                                '-mt-1',
                                                sortBy === 'name' &&
                                                sortDirection === 'desc'
                                                    ? 'text-gray-900 dark:text-gray-200'
                                                    : 'text-gray-400 dark:text-gray-500',
                                            ]"
                                        />
                                    </div>
                                </div>
                            </th>
                            <th
                                class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div class="flex justify-center items-center">
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        User & Perusahaan
                                    </p>
                                </div>
                            </th>
                            <th
                                class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div class="flex justify-center items-center">
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        Kontak
                                    </p>
                                </div>
                            </th>
                            <th
                                class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div class="flex justify-center items-center">
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        Status
                                    </p>
                                </div>
                            </th>
                            <th
                                v-if="can('drivers.edit') || can('drivers.delete')"
                                class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"
                            >
                                <div class="flex justify-center items-center">
                                    <p
                                        class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"
                                    >
                                        Aksi
                                    </p>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="drivers.data && drivers.data.length > 0">
                            <tr
                                v-for="(driver, index) in drivers.data"
                                :key="driver.id"
                                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"
                            >
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex justify-center items-center whitespace-nowrap"
                                    >
                                        <p
                                            class="px-3 text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                (drivers.current_page - 1) *
                                                    drivers.per_page +
                                                index +
                                                1
                                            }}.
                                        </p>
                                    </div>
                                </td>
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex gap-3 items-center whitespace-nowrap ps-5"
                                    >
                                        <div
                                            class="flex flex-col leading-tight"
                                        >
                                            <p
                                                class="font-medium text-gray-800 dark:text-white/90"
                                            >
                                                {{ driver.name }}
                                            </p>
                                            <span
                                                class="text-gray-500 dark:text-gray-400 text-xs"
                                            >
                                                KTP: {{ driver.no_ktp }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex flex-col justify-center items-center px-3"
                                    >
                                        <p
                                            class="text-gray-500 dark:text-gray-400"
                                        >
                                            {{ driver.user?.name || '-' }}
                                        </p>
                                        <p
                                            class="text-gray-400 dark:text-gray-500 text-xs"
                                        >
                                            {{ driver.company?.name || '-' }}
                                        </p>
                                    </div>
                                </td>
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex flex-col justify-center items-center px-3"
                                    >
                                        <p
                                            class="text-gray-500 dark:text-gray-400"
                                        >
                                            {{ driver.no_hp }}
                                        </p>
                                        <p
                                            v-if="driver.no_sim"
                                            class="text-gray-400 dark:text-gray-500 text-xs"
                                        >
                                            SIM: {{ driver.no_sim }}
                                        </p>
                                    </div>
                                </td>
                                <td
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex justify-center items-center px-3 whitespace-nowrap"
                                    >
                                        <span
                                            :class="[
                                                'px-3 py-1 rounded-full text-xs font-medium',
                                                driver.status === 'active'
                                                    ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                                    : driver.status === 'pending'
                                                    ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300'
                                                    : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                            ]"
                                        >
                                            {{
                                                driver.status === "active"
                                                    ? "Aktif"
                                                    : driver.status === "pending"
                                                    ? "Menunggu Verifikasi"
                                                    : "Tidak Aktif"
                                            }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    v-if="
                                        can('drivers.edit') ||
                                        can('drivers.delete')
                                    "
                                    class="py-2.5 border border-gray-200 dark:border-gray-600"
                                >
                                    <div
                                        class="flex gap-3 justify-center px-4 whitespace-nowrap sm:px-0"
                                    >
                                        <Link
                                            v-if="can('drivers.edit')"
                                            :href="route('drivers.edit', driver.id)"
                                            class="text-yellow-500 hover:text-yellow-600"
                                            title="Edit"
                                        >
                                            <EditIcon />
                                        </Link>
                                        <button
                                            v-if="can('drivers.delete')"
                                            @click.stop="openConfirmModal(driver)"
                                            class="text-red-500 hover:text-red-600"
                                            title="Hapus"
                                        >
                                            <TrashIcon />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr v-else>
                            <td
                                :colspan="
                                    can('drivers.edit') || can('drivers.delete')
                                        ? 6
                                        : 5
                                "
                                class="py-6 font-medium text-center text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-600"
                            >
                                Tidak ada driver ditemukan
                            </td>
                        </tr>
                    </tbody>
                </table>

                <ConfirmModal
                    :show="isConfirmModalOpen"
                    :question="`Yakin ingin menghapus`"
                    :selected="`${selectedItem?.name}`"
                    title="Hapus Driver"
                    confirmText="Ya, Hapus!"
                    maxWidth="md"
                    @close="closeConfirmModal"
                    @confirm="destroyData"
                />
            </div>

            <Pagination
                v-if="drivers.data && drivers.data.length > 0"
                :pagination="drivers"
            />
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PlusSquareIcon from "@/Components/icons/PlusSquareIcon.vue";
import SearchIcon from "@/Components/icons/SearchIcon.vue";
import EditIcon from "@/Components/icons/EditIcon.vue";
import UpIcon from "@/Components/icons/UpIcon.vue";
import DownIcon from "@/Components/icons/DownIcon.vue";
import TrashIcon from "@/Components/icons/TrashIcon.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import ConfirmModal from "@/Components/common/ConfirmModal.vue";
import Pagination from "@/Components/common/Pagination.vue";
import { useAuth } from "@/Composables/useAuth";
import { ref, watch } from "vue";
import { router, Head, Link } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    drivers: Object,
    companies: Array,
    search: String,
    status: String,
    company_id: String,
    statusCounts: Object,
    sortBy: String,
    sortDirection: String,
});

const { can } = useAuth();

const breadcrumbs = [{ label: "Menu Utama" }, { label: "Driver" }];

// Filters
const statusFilter = ref(props.status || "all");
const companyFilter = ref(props.company_id || "");

// Get status count for badge display
const getStatusCount = (status) => {
    if (!props.statusCounts) return 0;
    return props.statusCounts[status] || 0;
};

function fetchDrivers({
    sortBy = props.sortBy,
    sortDirection = props.sortDirection,
} = {}) {
    router.get(
        route("drivers.index"),
        {
            search: search.value,
            status: statusFilter.value,
            company_id: companyFilter.value,
            sortBy,
            sortDirection,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }
    );
}

const search = ref(props.search || "");

let timeout = null;
watch(search, () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        fetchDrivers();
    }, 400);
});

watch([statusFilter, companyFilter], () => {
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.delete('page');
    router.get(
        route("drivers.index"),
        {
            search: search.value,
            status: statusFilter.value,
            company_id: companyFilter.value,
            sortBy: props.sortBy,
            sortDirection: props.sortDirection,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }
    );
});

function changeSort(column) {
    let direction = "asc";
    if (props.sortBy === column && props.sortDirection === "asc") {
        direction = "desc";
    }
    fetchDrivers({ sortBy: column, sortDirection: direction });
}

// destroy
const selectedItem = ref(null);

const isConfirmModalOpen = ref(false);
const openConfirmModal = (item) => {
    if (!can("drivers.delete")) {
        return;
    }
    selectedItem.value = item;
    isConfirmModalOpen.value = true;
};
const closeConfirmModal = () => {
    selectedItem.value = null;
    isConfirmModalOpen.value = false;
};
const destroyData = () => {
    if (!can("drivers.delete")) {
        return;
    }
    router.delete(route("drivers.destroy", selectedItem.value.id), {
        onSuccess: () => {
            closeConfirmModal();
        },
        preserveScroll: true,
    });
};
</script>

