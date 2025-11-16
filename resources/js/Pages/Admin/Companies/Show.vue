<template>
    <Head :title="`Detail ${company.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
            <div class="flex gap-2">
                <button
                    v-if="can('companies.edit')"
                    @click="editCompany"
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
                <!-- Logo Section -->
                <div class="flex-shrink-0">
                    <div
                        v-if="company.logo"
                        class="w-32 h-32 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700"
                    >
                        <img
                            :src="company.logo"
                            :alt="company.name"
                            class="object-contain w-full h-full"
                        />
                    </div>
                    <div
                        v-else
                        class="flex items-center justify-center w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg dark:border-gray-600"
                    >
                        <span class="text-gray-400 text-sm">No Logo</span>
                    </div>
                </div>

                <!-- Company Info -->
                <div class="flex-1 space-y-4">
                    <div>
                        <h1
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ company.name }}
                        </h1>
                        <div class="mt-2">
                            <span
                                :class="[
                                    'px-3 py-1 rounded-full text-xs font-medium',
                                    company.status === 'active'
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                        : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                ]"
                            >
                                {{
                                    company.status === "active"
                                        ? "Aktif"
                                        : "Tidak Aktif"
                                }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label
                                class="text-sm font-medium text-gray-500 dark:text-gray-400"
                            >
                                Alamat
                            </label>
                            <p
                                class="mt-1 text-gray-900 dark:text-white"
                            >
                                {{ company.alamat || '-' }}
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
                                {{ formatDate(company.created_at) }}
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
                                {{ formatDate(company.updated_at) }}
                            </p>
                        </div>
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
    company: Object,
});

const { can } = useAuth();

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Perusahaan", url: route("companies.index") },
    { label: props.company.name },
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

const editCompany = () => {
    router.visit(route("companies.edit", props.company.id));
};
</script>

