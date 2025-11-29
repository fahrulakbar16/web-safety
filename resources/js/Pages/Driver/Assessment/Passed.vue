<template>
    <Head title="Selamat! Anda Telah Lulus" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div class="flex flex-col gap-6 p-6 overflow-auto rounded-2xl shadow bg-white dark:bg-white/[0.03]">
            <!-- Success Card -->
            <div class="flex flex-col items-center justify-center py-12 px-6">
                <!-- Success Icon -->
                <div class="flex items-center justify-center w-24 h-24 bg-green-100 dark:bg-green-900/20 rounded-full mb-6">
                    <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 text-center">
                    Selamat! Anda Telah Lulus
                </h1>

                <!-- Event Info -->
                <div v-if="event" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 mb-6 w-full max-w-2xl">
                    <div class="flex items-start gap-4">
                        <div v-if="event.image" class="flex-shrink-0">
                            <img
                                :src="event.image"
                                :alt="event.name"
                                class="w-20 h-20 object-cover rounded-lg"
                            />
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                {{ event.name }}
                            </h2>
                            <p v-if="event.description" class="text-sm text-gray-600 dark:text-gray-400">
                                {{ event.description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Information Card -->
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6 w-full max-w-2xl">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                                Informasi Pengambilan Kartu E-Pass
                            </h3>
                            <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                                <p>
                                    Anda telah berhasil menyelesaikan assessment dan dinyatakan <strong class="text-green-600 dark:text-green-400">LULUS</strong>.
                                </p>
                                <p>
                                    Silakan datang ke <strong class="text-blue-600 dark:text-blue-400">TPK Palaran</strong> untuk mengambil kartu E-Pass Anda.
                                </p>
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mt-4 border border-gray-200 dark:border-gray-700">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Lokasi Pengambilan
                                    </h4>
                                    <p class="text-gray-700 dark:text-gray-300">
                                        <strong>TPK Palaran</strong>
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs mt-1">
                                        Pastikan membawa dokumen identitas yang diperlukan saat mengambil kartu E-Pass.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-6 w-full max-w-2xl">
                    <Link
                        :href="route('profile.show')"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Kembali ke Profile
                    </Link>
                    <button
                        @click="window.print()"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Halaman
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    event: {
        type: Object,
        default: null,
    },
});

const breadcrumbs = [
    { label: "Dashboard", href: route("dashboard") },
    { label: "Assessment Driver", href: route("driver.assessment.index") },
    { label: "Lulus", href: null },
];
</script>

<style scoped>
@media print {
    .flex.justify-between,
    button,
    .bg-yellow-50 {
        display: none !important;
    }
}
</style>

