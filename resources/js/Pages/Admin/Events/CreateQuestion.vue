<template>
    <Head :title="`Tambah Soal - ${exam.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div
            class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"
        >
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Tambah Soal Baru
            </h1>

            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="space-y-1 text-sm">
                    <label
                        for="urutan"
                        class="text-gray-900 dark:text-white"
                    >
                        Urutan <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="urutan"
                        v-model="form.urutan"
                        type="number"
                        min="1"
                        class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                        placeholder="Masukkan urutan"
                    />
                    <div
                        v-if="form.errors.urutan"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.urutan }}
                    </div>
                </div>

                <div class="space-y-1 text-sm">
                    <label
                        for="soal"
                        class="text-gray-900 dark:text-white"
                    >
                        Soal <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="soal"
                        v-model="form.soal"
                        rows="4"
                        class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                        placeholder="Masukkan pertanyaan"
                        required
                    ></textarea>
                    <div
                        v-if="form.errors.soal"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.soal }}
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <label class="text-sm font-medium text-gray-900 dark:text-white">
                            Opsi Jawaban <span class="text-red-500">*</span>
                        </label>
                        <button
                            type="button"
                            @click="addOption"
                            class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400"
                        >
                            + Tambah Opsi
                        </button>
                    </div>

                    <div
                        v-for="(option, index) in form.options"
                        :key="index"
                        class="p-4 border border-gray-200 rounded-lg dark:border-gray-600 space-y-3"
                    >
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Opsi {{ index + 1 }}
                            </span>
                            <button
                                v-if="form.options.length > 2"
                                type="button"
                                @click="removeOption(index)"
                                class="text-sm text-red-600 hover:text-red-700"
                            >
                                Hapus
                            </button>
                        </div>

                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-2">
                                <label class="text-xs text-gray-600 dark:text-gray-400">
                                    Label (A, B, C, dll)
                                </label>
                                <input
                                    v-model="option.opsi"
                                    type="text"
                                    maxlength="10"
                                    class="w-full text-sm rounded-lg border-gray-400 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="A"
                                    required
                                />
                            </div>
                            <div class="col-span-8">
                                <label class="text-xs text-gray-600 dark:text-gray-400">
                                    Jawaban
                                </label>
                                <input
                                    v-model="option.jawaban"
                                    type="text"
                                    class="w-full text-sm rounded-lg border-gray-400 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Masukkan jawaban"
                                    required
                                />
                            </div>
                            <div class="col-span-2 flex items-end">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input
                                        v-model="option.is_correct"
                                        type="checkbox"
                                        class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                                    />
                                    <span class="text-xs text-gray-600 dark:text-gray-400">
                                        Benar
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="form.errors.options"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.options }}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end pt-4 border-t border-gray-200 dark:border-gray-600">
                    <Link
                        :href="route('events.exams.show', [event.id, exam.id])"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 text-white rounded btn-primary disabled:opacity-50"
                    >
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import { useForm, Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    event: Object,
    exam: Object,
});

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Event", url: route("events.index") },
    { label: props.event.name, url: route("events.show", props.event.id) },
    {
        label: `Ujian: ${props.exam.name}`,
        url: route("events.exams.show", [props.event.id, props.exam.id]),
    },
    { label: "Tambah Soal" },
];

const form = useForm({
    urutan: null,
    soal: "",
    options: [
        { opsi: "A", jawaban: "", is_correct: false },
        { opsi: "B", jawaban: "", is_correct: false },
    ],
});

const addOption = () => {
    const labels = ["A", "B", "C", "D", "E", "F"];
    const nextLabel = labels[form.options.length] || String.fromCharCode(65 + form.options.length);
    form.options.push({
        opsi: nextLabel,
        jawaban: "",
        is_correct: false,
    });
};

const removeOption = (index) => {
    if (form.options.length > 2) {
        form.options.splice(index, 1);
    }
};

const submitForm = () => {
    // Ensure urutan is integer if provided
    if (form.urutan) {
        form.urutan = parseInt(form.urutan);
    }

    form.post(route("events.exams.questions.store", [props.event.id, props.exam.id]), {
        onSuccess: () => {
            // Redirect handled by controller
        },
    });
};
</script>

