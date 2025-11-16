<template>
    <Head :title="`Detail ${driver.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
            <div class="flex gap-2">
                <button
                    v-if="can('drivers.edit')"
                    @click="editDriver"
                    type="button"
                    class="flex gap-2 items-center px-3 py-2 text-yellow-600 bg-yellow-50 rounded hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400"
                >
                    <EditIcon />
                    <span class="hidden text-sm md:block">Edit</span>
                </button>
            </div>
        </div>

        <div
            class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"
        >
            <div class="flex flex-col gap-4 md:flex-row md:items-start">
                <!-- Foto Diri -->
                <div class="flex-shrink-0">
                    <div
                        v-if="driver.foto_diri"
                        class="w-32 h-32 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700"
                    >
                        <img
                            :src="driver.foto_diri"
                            :alt="driver.name"
                            class="object-contain w-full h-full"
                        />
                    </div>
                    <div
                        v-else
                        class="flex items-center justify-center w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg dark:border-gray-600"
                    >
                        <span class="text-gray-400 text-sm">No Photo</span>
                    </div>
                </div>

                <!-- Driver Info -->
                <div class="flex-1 space-y-4">
                    <div>
                        <h1
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ driver.name }}
                        </h1>
                        <div class="mt-2">
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
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                User
                            </label>
                            <p
                                class="mt-1 text-gray-900 dark:text-white"
                            >
                                {{ driver.user?.name || '-' }} 
                                <span v-if="driver.user?.email" class="text-gray-500 text-sm">({{ driver.user.email }})</span>
                            </p>
                        </div>

                        <div>
                            <label
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                Perusahaan
                            </label>
                            <p
                                class="mt-1 text-gray-900 dark:text-white"
                            >
                                {{ driver.company?.name || '-' }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                Alamat
                            </label>
                            <p
                                class="mt-1 text-gray-900 dark:text-white"
                            >
                                {{ driver.alamat || '-' }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                Nomor HP
                            </label>
                            <p
                                class="mt-1 text-gray-900 dark:text-white"
                            >
                                {{ driver.no_hp || '-' }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                Nomor KTP
                            </label>
                            <p
                                class="mt-1 text-gray-900 dark:text-white"
                            >
                                {{ driver.no_ktp || '-' }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                Nomor SIM
                            </label>
                            <p
                                class="mt-1 text-gray-900 dark:text-white"
                            >
                                {{ driver.no_sim || '-' }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                Dibuat
                            </label>
                            <p
                                class="mt-1 text-gray-900 dark:text-white"
                            >
                                {{ formatDate(driver.created_at) }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                Diperbarui
                            </label>
                            <p
                                class="mt-1 text-gray-900 dark:text-white"
                            >
                                {{ formatDate(driver.updated_at) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foto KTP dan SIM -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label
                        class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 block"
                    >
                        Foto KTP
                    </label>
                    <div
                        v-if="driver.foto_ktp"
                        class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700"
                    >
                        <img
                            :src="driver.foto_ktp"
                            alt="Foto KTP"
                            class="object-contain w-full h-64"
                        />
                    </div>
                    <div
                        v-else
                        class="flex items-center justify-center h-64 border-2 border-dashed border-gray-300 rounded-lg dark:border-gray-600"
                    >
                        <span class="text-gray-400 text-sm">No Photo</span>
                    </div>
                </div>

                <div>
                    <label
                        class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 block"
                    >
                        Foto SIM
                    </label>
                    <div
                        v-if="driver.foto_sim"
                        class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700"
                    >
                        <img
                            :src="driver.foto_sim"
                            alt="Foto SIM"
                            class="object-contain w-full h-64"
                        />
                    </div>
                    <div
                        v-else
                        class="flex items-center justify-center h-64 border-2 border-dashed border-gray-300 rounded-lg dark:border-gray-600"
                    >
                        <span class="text-gray-400 text-sm">No Photo</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import EditIcon from "@/Components/icons/EditIcon.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import { useAuth } from "@/Composables/useAuth";
import { Head, router } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    driver: Object,
});

const { can } = useAuth();

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Driver", url: route("drivers.index") },
    { label: props.driver.name },
];

const formatDate = (date) => {
    if (!date) return "-";
    return new Intl.DateTimeFormat("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    }).format(new Date(date));
};

const editDriver = () => {
    router.visit(route("drivers.edit", props.driver.id));
};
</script>

