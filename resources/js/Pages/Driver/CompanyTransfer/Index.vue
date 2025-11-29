<template>
    <Head title="Pergantian PT" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div class="flex flex-col gap-6 p-6 overflow-auto rounded-2xl shadow bg-white dark:bg-white/[0.03]">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
                    Pergantian PT
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Ajukan pergantian perusahaan dengan melengkapi persyaratan yang diperlukan
                </p>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="$page.props.flash?.success" class="p-4 bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300 rounded-xl">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="p-4 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300 rounded-xl">
                {{ $page.props.flash.error }}
            </div>

            <!-- Current Company Info -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Perusahaan Saat Ini
                </h3>
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Nama Perusahaan</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ driver?.company?.name || '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Requirements Info -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Persyaratan Pergantian PT
                </h3>
                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 rounded-full font-semibold text-xs">
                            1
                        </span>
                        <div>
                            <p class="font-medium">Surat Pengunduran Diri</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                Upload surat pengunduran diri dari perusahaan lama (Format: PDF, JPG, JPEG, PNG, maks. 5MB)
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 rounded-full font-semibold text-xs">
                            2
                        </span>
                        <div>
                            <p class="font-medium">Mengikuti Quiz Ulang</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                Setelah mengisi form, Anda akan diarahkan untuk mengikuti quiz ulang. Setelah quiz selesai, upload screenshot hasil quiz.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- New Company Selection -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Pilih Perusahaan Baru
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Perusahaan Baru <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.new_company_id"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required
                        >
                            <option value="">Pilih Perusahaan Baru</option>
                            <option
                                v-for="company in companies"
                                :key="company.id"
                                :value="company.id"
                            >
                                {{ company.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.new_company_id" class="mt-1.5 text-sm text-red-600">
                            {{ form.errors.new_company_id }}
                        </p>
                    </div>
                </div>

                <!-- Surat Pengunduran Diri -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Surat Pengunduran Diri
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Upload Surat Pengunduran Diri <span class="text-red-500">*</span>
                        </label>
                        <div v-if="suratPreview" class="mb-3">
                            <img
                                v-if="isImageFile(suratPreview)"
                                :src="suratPreview"
                                alt="Preview Surat Pengunduran Diri"
                                class="w-full max-w-md h-64 object-contain border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"
                            />
                            <div v-else class="p-4 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">File PDF</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Surat pengunduran diri</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input
                            type="file"
                            accept=".pdf,.jpg,.jpeg,.png"
                            @change="handleSuratUpload"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors dark:file:bg-blue-900/20 dark:file:text-blue-300"
                            required
                        />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Format: PDF, JPG, JPEG, PNG | Maks. 5MB
                        </p>
                        <p v-if="form.errors.surat_pengunduran_diri" class="mt-1.5 text-sm text-red-600">
                            {{ form.errors.surat_pengunduran_diri }}
                        </p>
                    </div>
                </div>


                <!-- Notes (Optional) -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Catatan (Opsional)
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan Tambahan
                        </label>
                        <textarea
                            v-model="form.notes"
                            rows="4"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="Tambahkan catatan jika diperlukan..."
                            maxlength="1000"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Maks. 1000 karakter
                        </p>
                        <p v-if="form.errors.notes" class="mt-1.5 text-sm text-red-600">
                            {{ form.errors.notes }}
                        </p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
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
                        {{ form.processing ? "Mengirim..." : "Kirim Pengajuan" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Components/common/Breadcrumb.vue";
import { ref } from "vue";
import Swal from "sweetalert2";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    driver: Object,
    companies: Array,
});

const breadcrumbs = [
    { label: "Dashboard", href: route("dashboard") },
    { label: "Pergantian PT", href: null },
];

const form = useForm({
    new_company_id: null,
    surat_pengunduran_diri: null,
    notes: "",
});

const suratPreview = ref(null);
const suratFile = ref(null);

const isImageFile = (file) => {
    if (typeof file === 'string') {
        return file.startsWith('data:image/');
    }
    return file && file.type && file.type.startsWith('image/');
};

const handleSuratUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        suratFile.value = file;
        form.surat_pengunduran_diri = file;

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                suratPreview.value = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            suratPreview.value = 'pdf';
        }
    }
};

const submitForm = () => {
    const formData = new FormData();
    formData.append("new_company_id", form.new_company_id);
    formData.append("surat_pengunduran_diri", form.surat_pengunduran_diri);
    if (form.notes) {
        formData.append("notes", form.notes);
    }

    form.transform(() => formData).post(route("driver.company-transfer.store"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            // Form will redirect to quiz page automatically
            // No need to show success message here as user will be redirected
        },
        onError: () => {
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "Pengajuan pergantian PT gagal dikirim. Periksa kembali data yang Anda masukkan.",
            });
        },
    });
};
</script>

