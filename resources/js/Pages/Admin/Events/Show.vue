<template>
    <Head :title="`Detail ${event.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
            <div class="flex gap-2">
                <button
                    v-if="can('events.edit')"
                    @click="editEvent"
                    type="button"
                    class="flex gap-2 items-center px-3 py-2 text-yellow-600 bg-yellow-50 rounded hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400"
                >
                    <EditIcon />
                    <span class="hidden text-sm md:block">Edit Event</span>
                </button>
            </div>
        </div>

        <div
            class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"
        >
            <!-- Event Detail -->
            <div class="flex flex-col gap-4 md:flex-row md:items-start">
                <!-- Image Section -->
                <div class="flex-shrink-0">
                    <div
                        v-if="event.image"
                        class="w-32 h-32 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700"
                    >
                        <img
                            :src="event.image"
                            :alt="event.name"
                            class="object-contain w-full h-full"
                        />
                    </div>
                    <div
                        v-else
                        class="flex items-center justify-center w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg dark:border-gray-600"
                    >
                        <span class="text-gray-400 text-sm">No Image</span>
                    </div>
                </div>

                <!-- Event Info -->
                <div class="flex-1 space-y-4">
                    <div>
                        <h1
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ event.name }}
                        </h1>
                        <div class="mt-2">
                            <span
                                :class="[
                                    'px-3 py-1 rounded-full text-xs font-medium',
                                    event.status === 'active'
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                        : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                ]"
                            >
                                {{
                                    event.status === "active"
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
                                Deskripsi
                            </label>
                            <p
                                class="mt-1 text-gray-900 dark:text-white"
                            >
                                {{ event.description || '-' }}
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
                                {{ formatDate(event.created_at) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Materi Section -->
            <div class="space-y-4 border-t border-gray-200 dark:border-gray-600 pt-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Materi
                    </h2>
                    <Link
                        v-if="can('events.edit')"
                        :href="route('events.materis.create', event.id)"
                        class="flex gap-2 items-center px-3 py-2 text-white rounded btn-primary"
                    >
                        <PlusSquareIcon />
                        <span class="text-sm">Tambah Materi</span>
                    </Link>
                </div>

                <div v-if="event.event_materis && event.event_materis.length > 0" class="space-y-2">
                    <div
                        v-for="materi in sortedMateris"
                        :key="materi.id"
                        class="flex justify-between items-center p-3 border border-gray-200 rounded-lg dark:border-gray-600"
                    >
                        <div class="flex-1">
                            <div class="flex gap-2 items-center">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Urutan: {{ materi.urutan }}
                                </span>
                                <span
                                    :class="[
                                        'px-2 py-0.5 rounded text-xs font-medium',
                                        materi.type === 'text'
                                            ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300'
                                            : 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300',
                                    ]"
                                >
                                    {{ materi.type === 'text' ? 'Text' : 'Video' }}
                                </span>
                            </div>
                            <h3 class="font-medium text-gray-900 dark:text-white mt-1">
                                {{ materi.name }}
                            </h3>
                            <a
                                v-if="materi.file"
                                :href="materi.file"
                                target="_blank"
                                class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 mt-1 inline-block"
                            >
                                Lihat File
                            </a>
                        </div>
                        <div v-if="can('events.edit')" class="flex gap-2">
                            <Link
                                :href="route('events.materis.edit', [event.id, materi.id])"
                                class="text-yellow-500 hover:text-yellow-600"
                                title="Edit"
                            >
                                <EditIcon />
                            </Link>
                            <button
                                @click="openConfirmMateriModal(materi)"
                                class="text-red-500 hover:text-red-600"
                                title="Hapus"
                            >
                                <TrashIcon />
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
                    Belum ada materi
                </div>
            </div>

            <!-- Exam Section -->
            <div class="space-y-4 border-t border-gray-200 dark:border-gray-600 pt-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Ujian
                    </h2>
                    <Link
                        v-if="can('events.edit')"
                        :href="route('events.exams.create', event.id)"
                        class="flex gap-2 items-center px-3 py-2 text-white rounded btn-primary"
                    >
                        <PlusSquareIcon />
                        <span class="text-sm">Tambah Ujian</span>
                    </Link>
                </div>

                <div v-if="event.exams && event.exams.length > 0" class="space-y-2">
                    <div
                        v-for="exam in event.exams"
                        :key="exam.id"
                        class="flex justify-between items-center p-3 border border-gray-200 rounded-lg dark:border-gray-600"
                    >
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 dark:text-white">
                                {{ exam.name }}
                            </h3>
                            <p v-if="exam.description" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ exam.description }}
                            </p>
                            <div class="flex gap-4 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>Durasi: {{ exam.durasi }} menit</span>
                                <span>Jumlah Soal: {{ exam.jumlah_soal }}</span>
                                <span>Minimal Score: {{ exam.minimal_score }}%</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Link
                                :href="route('events.exams.show', [event.id, exam.id])"
                                class="text-blue-500 hover:text-blue-600"
                                title="Detail"
                            >
                                <ShowIcon />
                            </Link>
                            <Link
                                v-if="can('events.edit')"
                                :href="route('events.exams.edit', [event.id, exam.id])"
                                class="text-yellow-500 hover:text-yellow-600"
                                title="Edit"
                            >
                                <EditIcon />
                            </Link>
                            <button
                                v-if="can('events.edit')"
                                @click="openConfirmExamModal(exam)"
                                class="text-red-500 hover:text-red-600"
                                title="Hapus"
                            >
                                <TrashIcon />
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
                    Belum ada ujian
                </div>
            </div>
        </div>


        <!-- Confirm Modals -->
        <ConfirmModal
            :show="isConfirmMateriModalOpen"
            :question="`Yakin ingin menghapus materi`"
            :selected="`${selectedMateri?.name}`"
            title="Hapus Materi"
            confirmText="Ya, Hapus!"
            maxWidth="md"
            @close="closeConfirmMateriModal"
            @confirm="destroyMateri"
        />

        <ConfirmModal
            :show="isConfirmExamModalOpen"
            :question="`Yakin ingin menghapus ujian`"
            :selected="`${selectedExam?.name}`"
            title="Hapus Ujian"
            confirmText="Ya, Hapus!"
            maxWidth="md"
            @close="closeConfirmExamModal"
            @confirm="destroyExam"
        />
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import EditIcon from "@/Components/icons/EditIcon.vue";
import PlusSquareIcon from "@/Components/icons/PlusSquareIcon.vue";
import TrashIcon from "@/Components/icons/TrashIcon.vue";
import ShowIcon from "@/Components/icons/ShowIcon.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import ConfirmModal from "@/Components/common/ConfirmModal.vue";
import { useAuth } from "@/Composables/useAuth";
import { computed, ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    event: Object,
});

const { can } = useAuth();

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Event", url: route("events.index") },
    { label: props.event.name },
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

const editEvent = () => {
    router.visit(route("events.edit", props.event.id));
};

// Materi
const selectedMateri = ref(null);

const sortedMateris = computed(() => {
    if (!props.event.event_materis) return [];
    return [...props.event.event_materis].sort((a, b) => a.urutan - b.urutan);
});

const isConfirmMateriModalOpen = ref(false);
const openConfirmMateriModal = (materi) => {
    selectedMateri.value = materi;
    isConfirmMateriModalOpen.value = true;
};
const closeConfirmMateriModal = () => {
    selectedMateri.value = null;
    isConfirmMateriModalOpen.value = false;
};
const destroyMateri = () => {
    router.delete(route("events.materis.destroy", [props.event.id, selectedMateri.value.id]), {
        onSuccess: closeConfirmMateriModal,
        preserveScroll: true,
    });
};

// Exam
const selectedExam = ref(null);

const isConfirmExamModalOpen = ref(false);
const openConfirmExamModal = (exam) => {
    selectedExam.value = exam;
    isConfirmExamModalOpen.value = true;
};
const closeConfirmExamModal = () => {
    selectedExam.value = null;
    isConfirmExamModalOpen.value = false;
};
const destroyExam = () => {
    router.delete(route("events.exams.destroy", [props.event.id, selectedExam.value.id]), {
        onSuccess: closeConfirmExamModal,
        preserveScroll: true,
    });
};
</script>

