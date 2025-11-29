<template>
    <Head title="Assessment Driver" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
                    Assessment Driver
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Selesaikan materi secara berurutan untuk mengikuti ujian
                </p>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="$page.props.flash?.success" class="p-4 bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300 rounded-xl">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="p-4 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300 rounded-xl">
                {{ $page.props.flash.error }}
            </div>

            <!-- Events List -->
            <div v-if="events.length === 0" class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">
                    Belum ada event yang tersedia
                </p>
            </div>

            <div v-else class="grid grid-cols-1 gap-6">
                <div
                    v-for="event in events"
                    :key="event.id"
                    class="bg-white rounded-2xl shadow p-6 dark:bg-gray-800"
                >
                    <!-- Event Header -->
                    <div class="flex items-start gap-4 mb-6">
                        <div v-if="event.image" class="flex-shrink-0">
                            <img
                                :src="event.image"
                                :alt="event.name"
                                class="w-24 h-24 object-cover rounded-lg"
                            />
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ event.name }}
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                {{ event.description }}
                            </p>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-500 dark:text-gray-400">Progress:</span>
                                    <span class="font-semibold text-blue-600 dark:text-blue-400">
                                        {{ event.completed_materis }} / {{ event.total_materis }} Materi
                                    </span>
                                </div>
                                <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div
                                        class="h-2.5 rounded-full transition-all"
                                        style="background-color: #465fff;"
                                        :style="{ width: `${(event.completed_materis / event.total_materis) * 100}%` }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Materials List -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Materi Pembelajaran
                        </h3>
                        <div class="space-y-3">
                            <div
                                v-for="(materi, index) in event.materis"
                                :key="materi.id"
                                class="flex items-center gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-xl transition-all"
                                :class="{
                                    'bg-gray-50 dark:bg-gray-900/50': !materi.is_completed && !canAccessMateri(event.materis, index),
                                    'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800': materi.is_completed,
                                    'bg-white dark:bg-gray-800 hover:shadow-md': canAccessMateri(event.materis, index) && !materi.is_completed,
                                }"
                            >
                                <div class="flex-shrink-0">
                                    <div
                                        v-if="materi.is_completed"
                                        class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center"
                                    >
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div
                                        v-else-if="canAccessMateri(event.materis, index)"
                                        class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold"
                                    >
                                        {{ materi.urutan }}
                                    </div>
                                    <div
                                        v-else
                                        class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-400 font-bold"
                                    >
                                        {{ materi.urutan }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">
                                        {{ materi.name }}
                                    </h4>
                                    <p v-if="materi.description" class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ materi.description }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span
                                            class="px-2 py-1 text-xs rounded"
                                            :class="{
                                                'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300': materi.type === 'video',
                                                'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300': materi.type === 'text',
                                            }"
                                        >
                                            {{ materi.type === 'video' ? 'Video' : 'Teks' }}
                                        </span>
                                        <span
                                            v-if="materi.is_completed"
                                            class="px-2 py-1 text-xs bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 rounded"
                                        >
                                            Selesai
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <Link
                                        v-if="canAccessMateri(event.materis, index)"
                                        :href="route('driver.assessment.materi.show', materi.id)"
                                        class="px-4 py-2 text-sm font-medium text-white rounded-lg btn-primary transition-colors"
                                    >
                                        {{ materi.is_completed ? 'Lihat Ulang' : 'Buka Materi' }}
                                    </Link>
                                    <button
                                        v-else
                                        disabled
                                        class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 dark:bg-gray-700 rounded-lg cursor-not-allowed"
                                    >
                                        Terkunci
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Exams Section -->
                    <div v-if="event.exams.length > 0">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            Ujian
                        </h3>
                        <div class="space-y-3">
                            <div
                                v-for="exam in event.exams"
                                :key="exam.id"
                                class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-xl transition-all"
                                :class="{
                                    'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800': !event.all_materis_completed,
                                    'bg-white dark:bg-gray-800 hover:shadow-md': event.all_materis_completed,
                                }"
                            >
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">
                                        {{ exam.name }}
                                    </h4>
                                    <p v-if="exam.description" class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ exam.description }}
                                    </p>
                                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span>Durasi: {{ exam.durasi }} menit</span>
                                        <span>Soal: {{ exam.jumlah_soal }}</span>
                                        <span>Min. Score: {{ exam.minimal_score }}%</span>
                                    </div>
                                    <div v-if="exam.attempts.length > 0" class="mt-2">
                                        <div
                                            v-for="attempt in exam.attempts"
                                            :key="attempt.id"
                                            class="text-sm"
                                            :class="attempt.is_passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                                        >
                                            Score: {{ attempt.score }}% - {{ attempt.is_passed ? 'Lulus' : 'Tidak Lulus' }}
                                            ({{ formatDate(attempt.finished_at) }})
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <Link
                                        v-if="event.all_materis_completed"
                                        :href="route('driver.assessment.exam.show', [event.id, exam.id])"
                                        class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                                        style="background-color: #10b981;"
                                        onmouseover="this.style.backgroundColor='#059669'"
                                        onmouseout="this.style.backgroundColor='#10b981'"
                                    >
                                        Mulai Ujian
                                    </Link>
                                    <button
                                        v-else
                                        disabled
                                        class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 dark:bg-gray-700 rounded-lg cursor-not-allowed"
                                    >
                                        Selesaikan Semua Materi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import { Head, Link } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    events: Array,
});

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Assessment Driver" },
];

const canAccessMateri = (materis, currentIndex) => {
    // First materi is always accessible
    if (currentIndex === 0) {
        return true;
    }

    // Check if all previous materis are completed
    for (let i = 0; i < currentIndex; i++) {
        if (!materis[i].is_completed) {
            return false;
        }
    }

    return true;
};

const formatDate = (date) => {
    if (!date) return '-';
    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(date));
};
</script>

