<template>
    <Head :title="`Detail Ujian - ${exam.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div
            class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"
        >
            <!-- Exam Detail -->
            <div class="space-y-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ exam.name }}
                    </h1>
                    <p v-if="exam.description" class="mt-2 text-gray-600 dark:text-gray-400">
                        {{ exam.description }}
                    </p>
                </div>

                <div class="flex gap-4 text-sm text-gray-600 dark:text-gray-400">
                    <span>Durasi: {{ exam.durasi }} menit</span>
                    <span>Jumlah Soal: {{ exam.jumlah_soal }}</span>
                    <span>Minimal Score: {{ exam.minimal_score }}%</span>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="space-y-4 border-t border-gray-200 dark:border-gray-600 pt-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Soal
                    </h2>
                    <Link
                        v-if="can('events.edit')"
                        :href="route('events.exams.questions.create', [event.id, exam.id])"
                        class="flex gap-2 items-center px-3 py-2 text-white rounded btn-primary"
                    >
                        <PlusSquareIcon />
                        <span class="text-sm">Tambah Soal</span>
                    </Link>
                </div>

                <div v-if="exam.exam_questions && exam.exam_questions.length > 0" class="space-y-4">
                    <div
                        v-for="question in exam.exam_questions"
                        :key="question.id"
                        class="p-4 border border-gray-200 rounded-lg dark:border-gray-600"
                    >
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="flex gap-2 items-center mb-2">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                        Soal {{ question.urutan }}
                                    </span>
                                </div>
                                <p class="text-gray-900 dark:text-white font-medium">
                                    {{ question.soal }}
                                </p>
                            </div>
                            <div v-if="can('events.edit')" class="flex gap-2">
                                <Link
                                    :href="route('events.exams.questions.edit', [event.id, exam.id, question.id])"
                                    class="text-yellow-500 hover:text-yellow-600"
                                    title="Edit"
                                >
                                    <EditIcon />
                                </Link>
                                <button
                                    @click="openConfirmQuestionModal(question)"
                                    class="text-red-500 hover:text-red-600"
                                    title="Hapus"
                                >
                                    <TrashIcon />
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2 mt-4">
                            <div
                                v-for="option in question.exam_question_options"
                                :key="option.id"
                                :class="[
                                    'p-2 rounded border',
                                    option.is_correct
                                        ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800'
                                        : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700',
                                ]"
                            >
                                <div class="flex gap-2 items-center">
                                    <span class="font-medium text-sm text-gray-700 dark:text-gray-300">
                                        {{ option.opsi }}.
                                    </span>
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ option.jawaban }}
                                    </span>
                                    <span
                                        v-if="option.is_correct"
                                        class="ml-auto px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded dark:bg-green-900 dark:text-green-300"
                                    >
                                        Benar
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
                    Belum ada soal
                </div>
            </div>
        </div>

        <!-- Confirm Modal -->
        <ConfirmModal
            :show="isConfirmQuestionModalOpen"
            :question="`Yakin ingin menghapus soal`"
            :selected="`Soal ${selectedQuestion?.urutan}`"
            title="Hapus Soal"
            confirmText="Ya, Hapus!"
            maxWidth="md"
            @close="closeConfirmQuestionModal"
            @confirm="destroyQuestion"
        />
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import EditIcon from "@/Components/icons/EditIcon.vue";
import PlusSquareIcon from "@/Components/icons/PlusSquareIcon.vue";
import TrashIcon from "@/Components/icons/TrashIcon.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import ConfirmModal from "@/Components/common/ConfirmModal.vue";
import { useAuth } from "@/Composables/useAuth";
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    event: Object,
    exam: Object,
});

const { can } = useAuth();

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Event", url: route("events.index") },
    { label: props.event.name, url: route("events.show", props.event.id) },
    { label: `Ujian: ${props.exam.name}` },
];

const selectedQuestion = ref(null);
const isConfirmQuestionModalOpen = ref(false);

const openConfirmQuestionModal = (question) => {
    selectedQuestion.value = question;
    isConfirmQuestionModalOpen.value = true;
};

const closeConfirmQuestionModal = () => {
    selectedQuestion.value = null;
    isConfirmQuestionModalOpen.value = false;
};

const destroyQuestion = () => {
    router.delete(
        route("events.exams.questions.destroy", [
            props.event.id,
            props.exam.id,
            selectedQuestion.value.id,
        ]),
        {
            onSuccess: closeConfirmQuestionModal,
            preserveScroll: true,
        }
    );
};
</script>

