<template>
    <Head :title="`Ujian: ${exam.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]">
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

            <!-- Previous Attempts -->
            <div v-if="attempts.length > 0 && !isExamStarted" class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Riwayat Ujian</h3>
                <div class="space-y-2">
                    <div
                        v-for="attempt in attempts"
                        :key="attempt.id"
                        class="flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-xl"
                    >
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">
                                Score: {{ attempt.score }}%
                            </span>
                            <span
                                class="ml-2 px-2 py-1 text-xs rounded"
                                :class="attempt.is_passed ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'"
                            >
                                {{ attempt.is_passed ? 'Lulus' : 'Tidak Lulus' }}
                            </span>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ formatDate(attempt.finished_at) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Start Exam Button -->
            <div v-if="!isExamStarted" class="flex justify-center pt-6 border-t border-gray-200 dark:border-gray-700">
                <button
                    @click="startExam"
                    class="px-8 py-3 text-lg font-semibold text-white rounded-xl transition-colors shadow-lg hover:shadow-xl"
                    style="background-color: #10b981;"
                    onmouseover="this.style.backgroundColor='#059669'"
                    onmouseout="this.style.backgroundColor='#10b981'"
                >
                    Mulai Ujian
                </button>
            </div>

            <!-- Exam Questions -->
            <div v-if="isExamStarted && !isExamFinished" class="space-y-6 mt-6">
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                            Pastikan Anda menjawab semua soal sebelum waktu habis. Jawaban yang sudah dipilih akan tersimpan otomatis.
                        </p>
                    </div>
                </div>

                <div v-if="examQuestions.length === 0" class="p-6 text-center border border-gray-200 dark:border-gray-700 rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada soal yang tersedia untuk ujian ini.</p>
                </div>

                <form v-else @submit.prevent="submitExam" class="space-y-6">
                    <div
                        v-for="(question, index) in examQuestions"
                        :key="question.id"
                        class="p-6 border border-gray-200 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-shadow"
                    >
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Soal {{ index + 1 }}: {{ question.soal }}
                            </h3>
                        </div>
                        <div class="space-y-2">
                            <label
                                v-for="option in question.exam_question_options"
                                :key="option.id"
                                class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-all"
                                :class="{
                                    'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700 shadow-sm': form.answers[question.id] === option.id
                                }"
                            >
                                <input
                                    type="radio"
                                    :name="`question_${question.id}`"
                                    :value="option.id"
                                    v-model="form.answers[question.id]"
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                />
                                <span class="text-gray-900 dark:text-white">
                                    {{ option.opsi }}. {{ option.jawaban }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Terjawab: {{ Object.keys(form.answers).length }} / {{ examQuestions.length }} soal
                        </div>
                        <button
                            type="submit"
                            @click.prevent="confirmSubmit"
                            class="px-6 py-3 text-white rounded-xl transition-colors font-semibold shadow-lg hover:shadow-xl btn-primary"
                        >
                            Selesai & Submit Jawaban
                        </button>
                    </div>
                </form>
            </div>

            <!-- Exam Results -->
            <div v-if="isExamFinished && examResult" class="space-y-4">
                <div
                    class="p-6 rounded-2xl text-center shadow-lg"
                    :class="examResult.is_passed ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800'"
                >
                    <div class="text-4xl font-bold mb-2" :class="examResult.is_passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                        {{ examResult.score }}%
                    </div>
                    <div class="text-xl font-semibold mb-2" :class="examResult.is_passed ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'">
                        {{ examResult.is_passed ? 'Selamat! Anda Lulus' : 'Anda Tidak Lulus' }}
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">
                        Minimal score yang diperlukan: {{ exam.minimal_score }}%
                    </p>
                </div>

                <div class="flex justify-center gap-4">
                    <Link
                        :href="route('driver.assessment.index')"
                        class="px-6 py-3 text-white rounded-xl transition-colors font-semibold shadow-lg hover:shadow-xl btn-primary"
                    >
                        Kembali ke Assessment
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, onMounted, onBeforeUnmount } from "vue";

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
});

// Ensure exam.exam_questions is always an array
const examQuestions = computed(() => {
    return props.exam?.exam_questions || [];
});

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Assessment Driver", url: route("driver.assessment.index") },
    { label: props.exam.name },
];

const isExamStarted = ref(false);
const isExamFinished = ref(props.examResult !== null && props.examResult !== undefined);
const examResult = ref(props.examResult);
const remainingTime = ref(0);
const timerInterval = ref(null);

// Debug: Log exam data
console.log('Exam data:', props.exam);
console.log('Exam questions:', examQuestions.value);
console.log('Is exam started:', isExamStarted.value);
console.log('Is exam finished:', isExamFinished.value);

const form = useForm({
    answers: {},
});

const startExam = () => {
    if (confirm('Apakah Anda siap untuk memulai ujian? Waktu akan dimulai setelah Anda klik OK.')) {
        isExamStarted.value = true;
        isExamFinished.value = false;
        remainingTime.value = props.exam.durasi * 60; // Convert to seconds

        // Start timer
        timerInterval.value = setInterval(() => {
            remainingTime.value--;
            if (remainingTime.value <= 0) {
                clearInterval(timerInterval.value);
                submitExam(true); // Auto submit when time runs out
            }
        }, 1000);

        // Scroll to questions after a short delay to ensure DOM is updated
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
    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
};

const confirmSubmit = () => {
    const answeredCount = Object.keys(form.answers).length;
    const totalQuestions = examQuestions.value.length;

    if (answeredCount < totalQuestions) {
        if (!confirm(`Anda hanya menjawab ${answeredCount} dari ${totalQuestions} soal. Yakin ingin submit?`)) {
            return;
        }
    }

    if (confirm('Yakin ingin menyelesaikan ujian? Jawaban tidak dapat diubah setelah submit.')) {
        submitExam();
    }
};

const submitExam = (autoSubmit = false) => {
    if (timerInterval.value) {
        clearInterval(timerInterval.value);
    }

        form.post(route('driver.assessment.exam.submit', [props.event.id, props.exam.id]), {
        preserveScroll: true,
    });
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

onBeforeUnmount(() => {
    if (timerInterval.value) {
        clearInterval(timerInterval.value);
    }
});
</script>

