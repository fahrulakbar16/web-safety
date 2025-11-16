<template>
    <Head :title="`Edit ${event.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div
            class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"
        >
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Edit Event: {{ event.name }}
            </h1>

            <form @submit.prevent="submitForm" class="space-y-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="space-y-1 text-sm">
                        <label
                            for="name"
                            class="text-gray-900 dark:text-white"
                        >
                            Nama Event <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                            placeholder="Masukkan nama event"
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
                            for="status"
                            class="text-gray-900 dark:text-white"
                        >
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="status"
                            v-model="form.status"
                            class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                        <div
                            v-if="form.errors.status"
                            class="text-sm text-red-500"
                        >
                            {{ form.errors.status }}
                        </div>
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
                        placeholder="Masukkan deskripsi event"
                    ></textarea>
                    <div
                        v-if="form.errors.description"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.description }}
                    </div>
                </div>

                <div class="space-y-1 text-sm">
                    <label
                        for="image"
                        class="text-gray-900 dark:text-white"
                    >
                        Gambar
                    </label>
                    <div class="flex flex-col gap-2">
                        <div v-if="imagePreview" class="flex-shrink-0">
                            <img
                                :src="imagePreview"
                                alt="Gambar Preview"
                                class="w-full h-48 object-contain border border-gray-200 rounded-lg dark:border-gray-600"
                            />
                        </div>
                        <input
                            type="file"
                            accept="image/*"
                            @change="handleImageUpload"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                        <p class="text-xs text-gray-500">
                            Format: JPG, PNG, GIF, WEBP. Maks. 2MB
                        </p>
                    </div>
                    <div
                        v-if="form.errors.image"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.image }}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end pt-4 border-t border-gray-200 dark:border-gray-600">
                    <Link
                        :href="route('events.index')"
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

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    event: Object,
});

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Event", url: route("events.index") },
    { label: `Edit ${props.event.name}` },
];

const imagePreview = ref(null);
const imageFile = ref(null);

const form = useForm({
    name: props.event.name || "",
    description: props.event.description || "",
    image: null,
    status: props.event.status || "active",
});

onMounted(() => {
    if (props.event.image) {
        imagePreview.value = props.event.image;
    }
});

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        imageFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submitForm = () => {
    form.image = imageFile.value;
    
    if (!imageFile.value) {
        delete form.image;
    }

    form.put(route("events.update", props.event.id), {
        forceFormData: true,
        onSuccess: () => {
            // Redirect handled by controller
        },
    });
};
</script>

