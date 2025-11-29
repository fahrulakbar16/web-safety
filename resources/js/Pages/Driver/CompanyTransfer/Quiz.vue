<template>
    <Head :title="`Quiz Ulang: ${exam.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div class="flex flex-col gap-6 p-6 overflow-auto rounded-2xl shadow bg-white dark:bg-white/[0.03]">
            <!-- Info Box -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            Quiz Ulang untuk Pergantian PT
                        </h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Silakan ikuti quiz ulang terlebih dahulu. Setelah quiz selesai dan lulus, Anda akan diminta untuk mengupload screenshot hasil quiz.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Exam Info -->
            <div class="space-y-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ exam.name }}
                    </h1>
                    <p v-if="exam.description" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ exam.description }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                    <span>Durasi: {{ exam.durasi }} menit</span>
                    <span>Jumlah Soal: {{ exam.jumlah_soal }}</span>
                    <span>Minimal Score: {{ exam.minimal_score }}%</span>
                </div>

                <!-- Timer -->
                <div v-if="isExamStarted && !isExamFinished" class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-blue-900 dark:text-blue-300">Waktu Tersisa:</span>
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ formatTime(remainingTime) }}</span>
                    </div>
                </div>
            </div>

            <!-- Start Exam Button -->
            <div v-if="!isExamStarted" class="flex justify-center pt-6 border-t border-gray-200 dark:border-gray-700">
                <button
                    @click="startExam"
                    class="px-8 py-3 text-lg font-semibold text-white bg-green-600 rounded-xl hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl"
                >
                    Mulai Quiz
                </button>
            </div>

            <!-- Exam Questions -->
            <div v-if="isExamStarted && !isExamFinished" class="space-y-6 mt-6">
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl mb-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                            Pastikan Anda menjawab semua soal sebelum waktu habis. Setelah quiz selesai, Anda akan diminta untuk mengupload screenshot hasil quiz.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="submitExam" class="space-y-6">
                    <div
                        v-for="(question, index) in examQuestions"
                        :key="question.id"
                        class="p-6 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 shadow-sm"
                    >
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Soal {{ index + 1 }}: {{ question.soal }}
                            </h3>
                        </div>

                        <div class="space-y-3">
                            <label
                                v-for="option in question.exam_question_options"
                                :key="option.id"
                                class="flex items-start gap-3 p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                :class="form.answers[question.id] == option.id ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-500 dark:border-blue-500' : ''"
                            >
                                <input
                                    type="radio"
                                    :name="`question_${question.id}`"
                                    :value="option.id"
                                    v-model="form.answers[question.id]"
                                    class="mt-1 w-4 h-4 text-blue-600 focus:ring-blue-500"
                                />
                                <span class="flex-1 text-gray-900 dark:text-white">{{ option.jawaban }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Terjawab: {{ Object.keys(form.answers).length }} / {{ examQuestions.length }} soal
                        </div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ form.processing ? "Mengirim..." : "Selesai & Submit Jawaban" }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Exam Results -->
            <div v-if="isExamFinished && examResult" class="mt-6 p-6 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800">
                <div class="text-center">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                        :class="examResult.is_passed ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20'"
                    >
                        <svg
                            v-if="examResult.is_passed"
                            class="w-10 h-10 text-green-600 dark:text-green-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg
                            v-else
                            class="w-10 h-10 text-red-600 dark:text-red-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3
                        class="text-2xl font-bold mb-2"
                        :class="examResult.is_passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                    >
                        {{ examResult.is_passed ? "Selamat! Anda Lulus" : "Anda Belum Lulus" }}
                    </h3>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">
                        Score: {{ examResult.score }}%
                    </p>
                    <p v-if="!examResult.is_passed" class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Minimal score yang diperlukan: {{ exam.minimal_score }}%
                    </p>
                    <div v-if="examResult.is_passed" class="mt-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Quiz berhasil diselesaikan! Silakan upload screenshot hasil quiz untuk menyelesaikan pengajuan pergantian PT.
                        </p>
                        <Link
                            :href="route('driver.company-transfer.upload-screenshot', { transfer: transfer_id })"
                            class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Upload Screenshot Hasil Quiz
                        </Link>
                    </div>
                    <div v-else class="mt-6">
                        <Link
                            :href="route('driver.company-transfer.quiz', { transfer: transfer_id, event: event.id, exam: exam.id })"
                            class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors shadow-sm"
                        >
                            Coba Lagi
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import { ref, computed, onBeforeUnmount } from "vue";
import Swal from "sweetalert2";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    event: Object,
    exam: Object,
    attempts: {
        type: Array,
        default: () => [],
    },
    examResult: Object,
    transfer_id: Number,
});

const breadcrumbs = [
    { label: "Dashboard", href: route("dashboard") },
    { label: "Pergantian PT", href: route("driver.company-transfer.index") },
    { label: "Quiz Ulang", href: null },
];

const examQuestions = computed(() => {
    return props.exam?.exam_questions || [];
});

const isExamStarted = ref(false);
const isExamFinished = ref(props.examResult !== null && props.examResult !== undefined);
const examResult = ref(props.examResult);
const remainingTime = ref(0);
const timerInterval = ref(null);

const form = useForm({
    answers: {},
});

const startExam = () => {
    if (confirm('Apakah Anda siap untuk memulai quiz? Waktu akan dimulai setelah Anda klik OK.')) {
        isExamStarted.value = true;
        isExamFinished.value = false;
        remainingTime.value = props.exam.durasi * 60;

        timerInterval.value = setInterval(() => {
            remainingTime.value--;
            if (remainingTime.value <= 0) {
                clearInterval(timerInterval.value);
                submitExam(true);
            }
        }, 1000);

        setTimeout(() => {
            const questionsSection = document.querySelector('.space-y-6.mt-6');
            if (questionsSection) {
                questionsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 200);
    }
};

const formatTime = (seconds) => {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
};

const submitExam = (autoSubmit = false) => {
    if (autoSubmit) {
        Swal.fire({
            icon: 'warning',
            title: 'Waktu Habis',
            text: 'Waktu ujian telah habis. Jawaban Anda akan otomatis disubmit.',
            timer: 2000,
            showConfirmButton: false,
        });
    }

    if (timerInterval.value) {
        clearInterval(timerInterval.value);
        timerInterval.value = null;
    }

        form.post(route('driver.company-transfer.quiz.submit', {
        transfer: props.transfer_id,
        event: props.event.id,
        exam: props.exam.id,
    }), {
        preserveScroll: true,
        onSuccess: (page) => {
            // If passed, will redirect to upload screenshot automatically
            // If not passed, result will be shown on the page
            if (page.props.examResult) {
                isExamFinished.value = true;
                examResult.value = page.props.examResult;
                
                if (!examResult.value?.is_passed) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Belum Lulus',
                        text: `Score Anda: ${examResult.value?.score || 0}%. Minimal score yang diperlukan: ${props.exam.minimal_score}%. Silakan coba lagi.`,
                    });
                }
            }
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat mengirim jawaban. Silakan coba lagi.',
            });
        },
    });
};

onBeforeUnmount(() => {
    if (timerInterval.value) {
        clearInterval(timerInterval.value);
    }
});
</script>

