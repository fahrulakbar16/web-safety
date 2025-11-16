<template>
    <Head :title="`Edit Materi - ${event.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div
            class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"
        >
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Edit Materi: {{ materi.name }}
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
                        required
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
                        for="name"
                        class="text-gray-900 dark:text-white"
                    >
                        Nama Materi <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                        placeholder="Masukkan nama materi"
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
                        for="type"
                        class="text-gray-900 dark:text-white"
                    >
                        Tipe <span class="text-red-500">*</span>
                    </label>
                <select
                    id="type"
                    v-model="form.type"
                    @change="handleTypeChange"
                    class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required
                >
                    <option value="text">Text</option>
                    <option value="video">Video</option>
                </select>
                    <div
                        v-if="form.errors.type"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.type }}
                    </div>
                </div>

                <!-- Text Editor (when type is text) -->
                <div v-if="form.type === 'text'" class="space-y-1 text-sm">
                    <label
                        for="description"
                        class="text-gray-900 dark:text-white"
                    >
                        Konten Materi <span class="text-red-500">*</span>
                    </label>
                    <Editor
                        v-model="form.description"
                        editorStyle="min-height: 300px"
                        :pt="{
                            root: { class: 'w-full' },
                            toolbar: { class: 'dark:bg-gray-700 dark:border-gray-600' },
                            content: { class: 'dark:bg-gray-700 dark:text-white' }
                        }"
                    />
                    <div
                        v-if="form.errors.description"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.description }}
                    </div>
                </div>

                <!-- Video Upload (when type is video) -->
                <div v-if="form.type === 'video'" class="space-y-1 text-sm">
                    <label
                        for="file"
                        class="text-gray-900 dark:text-white"
                    >
                        File Video <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-col gap-2">
                        <div v-if="videoPreview || materi.file" class="flex-shrink-0">
                            <video
                                v-if="videoPreview"
                                :src="videoPreview"
                                controls
                                class="w-full h-64 object-contain border border-gray-200 rounded-lg dark:border-gray-600"
                            ></video>
                            <div v-else-if="materi.file" class="p-4 border border-gray-200 rounded-lg dark:border-gray-600">
                                <video
                                    :src="materi.file"
                                    controls
                                    class="w-full h-64 object-contain"
                                ></video>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    File saat ini: <a :href="materi.file" target="_blank" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">Lihat File</a>
                                </p>
                            </div>
                        </div>
                        <input
                            id="file"
                            type="file"
                            accept="video/*"
                            @change="handleFileUpload"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                        <p class="text-xs text-gray-500">
                            Format: MP4, AVI, MOV. Maks. 10MB
                        </p>
                    </div>
                    <div
                        v-if="form.errors.file"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.file }}
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
                        <span v-else>Simpan Perubahan</span>
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
import { ref, onMounted } from "vue";
import Editor from "primevue/editor";
import "quill/dist/quill.snow.css";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    event: Object,
    materi: Object,
});

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Event", url: route("events.index") },
    { label: props.event.name, url: route("events.show", props.event.id) },
    { label: `Edit Materi: ${props.materi.name}` },
];

const filePreview = ref(null);
const videoPreview = ref(null);
const file = ref(null);

const form = useForm({
    urutan: props.materi.urutan || 1,
    name: props.materi.name || "",
    type: props.materi.type || "text",
    description: props.materi.description || "",
    file: null,
});

onMounted(() => {
    if (props.materi.file && props.materi.type === 'video') {
        // Video preview will be shown
    }
});

const handleTypeChange = () => {
    // Reset fields when type changes
    if (form.type === 'text') {
        // Clear file when switching to text
        file.value = null;
        filePreview.value = null;
        videoPreview.value = null;
    } else if (form.type === 'video') {
        // Clear description when switching to video (optional, user might want to keep it)
        // form.description = '';
    }
};

const handleFileUpload = (event) => {
    const fileInput = event.target.files[0];
    if (fileInput) {
        file.value = fileInput;
        filePreview.value = fileInput.name;

        // Create video preview if it's a video file
        if (fileInput.type.startsWith('video/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                videoPreview.value = e.target.result;
            };
            reader.readAsDataURL(fileInput);
        } else {
            videoPreview.value = null;
        }
    }
};

const submitForm = () => {
    // Ensure numeric values are converted to integers
    form.urutan = parseInt(form.urutan) || 1;

    // Only include file if type is video
    if (form.type === 'video') {
        form.file = file.value;
        if (!file.value) {
            delete form.file;
        }
    } else {
        // Remove file if type is text
        delete form.file;
    }

    form.transform((data) => ({
        ...data,
        _method: 'PUT',
    })).post(route("events.materis.update", [props.event.id, props.materi.id]), {
        forceFormData: true,
        onSuccess: () => {
            // Redirect handled by controller
        },
    });
};
</script>

