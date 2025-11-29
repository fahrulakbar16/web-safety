<template>
    <Head :title="`Materi: ${eventMateri.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]">
            <!-- Blocking Message -->
            <div
                v-if="!canAccess && blockingMateri"
                class="p-4 bg-yellow-50 border border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800 rounded-xl"
            >
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-1">
                            Materi Terkunci
                        </h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">
                            Anda harus menyelesaikan materi sebelumnya terlebih dahulu: <strong>{{ blockingMateri.name }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Material Content -->
            <div v-if="canAccess">
                <!-- Material Header -->
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
                        {{ eventMateri.name }}
                    </h1>
                    <p v-if="eventMateri.description" class="text-sm text-gray-500 dark:text-gray-400">
                        {{ eventMateri.description }}
                    </p>
                    <div class="flex items-center gap-2 mt-4">
                        <span
                            class="px-3 py-1 text-sm rounded"
                            :class="{
                                'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300': eventMateri.type === 'video',
                                'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300': eventMateri.type === 'text',
                            }"
                        >
                            {{ eventMateri.type === 'video' ? 'Video' : 'Teks' }}
                        </span>
                        <span
                            v-if="isCompleted"
                            class="px-3 py-1 text-sm bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded"
                        >
                            ✓ Selesai
                        </span>
                    </div>
                </div>

                <!-- Material Content -->
                <div class="mb-6">
                    <div v-if="eventMateri.type === 'video' && eventMateri.file" class="mb-4">
                        <video
                            :src="eventMateri.file"
                            controls
                            class="w-full rounded-lg"
                            @ended="handleVideoEnded"
                        >
                            Browser Anda tidak mendukung video.
                        </video>
                    </div>
                    <div v-else-if="eventMateri.type === 'text' && eventMateri.file" class="mb-4">
                        <iframe
                            :src="eventMateri.file"
                            class="w-full h-96 rounded-lg border border-gray-200 dark:border-gray-700"
                        ></iframe>
                    </div>
                    <div v-else class="mb-4 p-8 text-center border border-gray-200 dark:border-gray-700 rounded-lg">
                        <p class="text-gray-500 dark:text-gray-400">
                            Tidak ada konten yang tersedia
                        </p>
                    </div>
                </div>

                <!-- Complete Button -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <Link
                        :href="route('driver.assessment.index')"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors"
                    >
                        Kembali
                    </Link>
                    <form @submit.prevent="completeMateri" v-if="!isCompleted">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-semibold"
                            style="background-color: #10b981;"
                            onmouseover="this.style.backgroundColor='#059669'"
                            onmouseout="this.style.backgroundColor='#10b981'"
                        >
                            <span v-if="form.processing">Menyelesaikan...</span>
                            <span v-else>✓ Tandai Sebagai Selesai</span>
                        </button>
                    </form>
                    <div v-else class="px-6 py-2 text-green-600 bg-green-50 dark:bg-green-900/20 rounded-xl font-semibold border border-green-200 dark:border-green-800">
                        Materi Telah Diselesaikan
                    </div>
                </div>
            </div>

            <!-- Back Button if blocked -->
            <div v-else class="flex justify-start pt-6 border-t border-gray-200 dark:border-gray-700">
                <Link
                    :href="route('driver.assessment.index')"
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors"
                >
                    Kembali ke Assessment
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    eventMateri: Object,
    canAccess: Boolean,
    blockingMateri: Object,
    isCompleted: Boolean,
});

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Assessment Driver", url: route("driver.assessment.index") },
    { label: props.eventMateri.name },
];

const form = useForm({});

const completeMateri = () => {
    form.post(route('driver.assessment.materi.complete', props.eventMateri.id), {
        onSuccess: () => {
            // Redirect will be handled by controller
        },
    });
};

const handleVideoEnded = () => {
    // Optional: Auto-mark as completed when video ends
    // You can uncomment this if you want auto-complete on video end
    // if (!props.isCompleted) {
    //     completeMateri();
    // }
};
</script>

