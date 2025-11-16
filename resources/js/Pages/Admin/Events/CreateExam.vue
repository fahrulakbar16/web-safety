<template>
    <Head :title="`Tambah Ujian - ${event.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div
            class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"
        >
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Tambah Ujian Baru
            </h1>

            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="space-y-1 text-sm">
                    <label
                        for="name"
                        class="text-gray-900 dark:text-white"
                    >
                        Nama Ujian <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                        placeholder="Masukkan nama ujian"
                        required
                    />
                    <div
                        v-if="form.errors.name"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.name }}
                    </div>
                </div>

                <div class="space-y-1 text-sm">
                    <label
                        for="description"
                        class="text-gray-900 dark:text-white"
                    >
                        Deskripsi
                    </label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="3"
                        class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                        placeholder="Masukkan deskripsi ujian"
                    ></textarea>
                    <div
                        v-if="form.errors.description"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.description }}
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="space-y-1 text-sm">
                        <label
                            for="durasi"
                            class="text-gray-900 dark:text-white"
                        >
                            Durasi (menit) <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="durasi"
                            v-model="form.durasi"
                            type="number"
                            min="1"
                            class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                            placeholder="Durasi"
                            required
                        />
                        <div
                            v-if="form.errors.durasi"
                            class="text-sm text-red-500"
                        >
                            {{ form.errors.durasi }}
                        </div>
                    </div>

                    <div class="space-y-1 text-sm">
                        <label
                            for="jumlah_soal"
                            class="text-gray-900 dark:text-white"
                        >
                            Jumlah Soal <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="jumlah_soal"
                            v-model="form.jumlah_soal"
                            type="number"
                            min="1"
                            class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                            placeholder="Jumlah soal"
                            required
                        />
                        <div
                            v-if="form.errors.jumlah_soal"
                            class="text-sm text-red-500"
                        >
                            {{ form.errors.jumlah_soal }}
                        </div>
                    </div>

                    <div class="space-y-1 text-sm">
                        <label
                            for="minimal_score"
                            class="text-gray-900 dark:text-white"
                        >
                            Minimal Score (%) <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="minimal_score"
                            v-model="form.minimal_score"
                            type="number"
                            min="0"
                            max="100"
                            class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                            placeholder="Minimal score"
                            required
                        />
                        <div
                            v-if="form.errors.minimal_score"
                            class="text-sm text-red-500"
                        >
                            {{ form.errors.minimal_score }}
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end pt-4 border-t border-gray-200 dark:border-gray-600">
                    <Link
                        :href="route('events.show', event.id)"
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

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    event: Object,
});

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Event", url: route("events.index") },
    { label: props.event.name, url: route("events.show", props.event.id) },
    { label: "Tambah Ujian" },
];

const form = useForm({
    name: "",
    description: "",
    durasi: 60,
    jumlah_soal: 10,
    minimal_score: 70,
});

const submitForm = () => {
    // Ensure numeric values are converted to integers
    form.durasi = parseInt(form.durasi) || 60;
    form.jumlah_soal = parseInt(form.jumlah_soal) || 10;
    form.minimal_score = parseInt(form.minimal_score) || 70;

    form.post(route("events.exams.store", props.event.id), {
        onSuccess: () => {
            // Redirect handled by controller
        },
    });
};
</script>

