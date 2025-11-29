<template>
    <Head title="Upload Screenshot Hasil Quiz" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div class="flex flex-col gap-6 p-6 overflow-auto rounded-2xl shadow bg-white dark:bg-white/[0.03]">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
                    Upload Screenshot Hasil Quiz
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Upload screenshot hasil quiz yang telah Anda selesaikan
                </p>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="$page.props.flash?.success" class="p-4 bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300 rounded-xl">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="p-4 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300 rounded-xl">
                {{ $page.props.flash.error }}
            </div>
            <div v-if="$page.props.flash?.info" class="p-4 bg-blue-50 border border-blue-200 text-blue-700 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-300 rounded-xl">
                {{ $page.props.flash.info }}
            </div>

            <!-- Transfer Info -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Informasi Pergantian PT
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Perusahaan Lama</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ transfer?.old_company?.name || '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Perusahaan Baru</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ transfer?.new_company?.name || '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <form @submit.prevent="submitForm" class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Screenshot Hasil Quiz
                </h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Upload Screenshot Hasil Quiz <span class="text-red-500">*</span>
                    </label>
                    <div v-if="quizPreview" class="mb-3">
                        <img
                            :src="quizPreview"
                            alt="Preview Screenshot Quiz"
                            class="w-full max-w-md h-64 object-contain border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
                        />
                    </div>
                    <input
                        type="file"
                        accept=".jpg,.jpeg,.png"
                        @change="handleQuizUpload"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors dark:file:bg-blue-900/20 dark:file:text-blue-300"
                        required
                    />
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Format: JPG, JPEG, PNG | Maks. 5MB
                    </p>
                    <p v-if="form.errors.screenshot_quiz" class="mt-1.5 text-sm text-red-600">
                        {{ form.errors.screenshot_quiz }}
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <Link
                        :href="route('driver.company-transfer.index')"
                        class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg v-if="form.processing" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ form.processing ? "Mengupload..." : "Upload & Selesai" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import { ref } from "vue";
import Swal from "sweetalert2";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    transfer: Object,
});

const breadcrumbs = [
    { label: "Dashboard", href: route("dashboard") },
    { label: "Pergantian PT", href: route("driver.company-transfer.index") },
    { label: "Upload Screenshot", href: null },
];

const form = useForm({
    screenshot_quiz: null,
});

const quizPreview = ref(null);
const quizFile = ref(null);

const handleQuizUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        quizFile.value = file;
        form.screenshot_quiz = file;
        
        const reader = new FileReader();
        reader.onload = (e) => {
            quizPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submitForm = () => {
    const formData = new FormData();
    formData.append("screenshot_quiz", form.screenshot_quiz);

    form.transform(() => formData).post(route('driver.company-transfer.upload-screenshot.store', { transfer: props.transfer.id }), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Screenshot hasil quiz berhasil diupload. Pengajuan pergantian PT telah dikirim dan menunggu persetujuan administrator.",
                timer: 3000,
                showConfirmButton: false,
            }).then(() => {
                window.location.href = route('driver.company-transfer.index');
            });
        },
        onError: () => {
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "Upload screenshot gagal. Periksa kembali file yang Anda upload.",
            });
        },
    });
};
</script>


