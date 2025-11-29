<template>
    <Head title="Registrasi Driver" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-block mb-4">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Driver Registration
                    </h1>
                </Link>
                <p class="text-gray-600 dark:text-gray-300">
                    Lengkapi data diri Anda untuk mendaftar sebagai driver
                </p>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="$page.props.flash?.success" class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ $page.props.flash.error }}
            </div>

            <!-- Registration Form -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8">
                <form @submit.prevent="submitForm" class="space-y-8">
                    <!-- Informasi Akun -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                Informasi Akun
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="nama@email.com"
                                    required
                                />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="username"
                                    v-model="form.username"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="username"
                                    required
                                />
                                <p v-if="form.errors.username" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.username }}
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Minimal 8 karakter"
                                    required
                                />
                                <p v-if="form.errors.password" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.password }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Driver -->
                    <div class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                Informasi Driver
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="relative">
                                <label for="company_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Perusahaan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        id="company_search"
                                        v-model="companySearch"
                                        type="text"
                                        @input="filterCompanies"
                                        @focus="showCompanyDropdown = true"
                                        @blur="handleCompanyBlur"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Cari atau tambah perusahaan baru"
                                        required
                                    />
                                    <div
                                        v-if="showCompanyDropdown && (filteredCompanies.length > 0 || showCreateOption)"
                                        class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-auto"
                                    >
                                        <div
                                            v-for="company in filteredCompanies"
                                            :key="company.id"
                                            @mousedown.prevent="selectCompany(company)"
                                            class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-gray-700 cursor-pointer"
                                        >
                                            {{ company.name }}
                                        </div>
                                        <div
                                            v-if="showCreateOption"
                                            @mousedown.prevent="createNewCompany"
                                            class="px-4 py-2 hover:bg-green-50 dark:hover:bg-gray-700 cursor-pointer"
                                            :class="filteredCompanies.length > 0 ? 'border-t border-gray-200 dark:border-gray-600' : ''"
                                        >
                                            <div class="flex items-center gap-2 text-green-600 dark:text-green-400">

                                                <span>"{{ companySearch.trim() }}"</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input
                                    v-model="form.company_id"
                                    type="hidden"
                                    required
                                />
                                <p v-if="form.errors.company_id" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.company_id }}
                                </p>
                                <p v-if="selectedCompanyName" class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Dipilih: <strong>{{ selectedCompanyName }}</strong>
                                </p>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Nama lengkap"
                                    required
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.name }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="alamat"
                                v-model="form.alamat"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Alamat lengkap"
                                required
                            ></textarea>
                            <p v-if="form.errors.alamat" class="mt-1 text-sm text-red-500">
                                {{ form.errors.alamat }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nomor HP <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="no_hp"
                                    v-model="form.no_hp"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="08xxxxxxxxxx"
                                    required
                                />
                                <p v-if="form.errors.no_hp" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.no_hp }}
                                </p>
                            </div>

                            <div>
                                <label for="no_ktp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nomor KTP <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="no_ktp"
                                    v-model="form.no_ktp"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="16 digit nomor KTP"
                                    required
                                />
                                <p v-if="form.errors.no_ktp" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.no_ktp }}
                                </p>
                            </div>

                            <div>
                                <label for="no_sim" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nomor SIM
                                </label>
                                <input
                                    id="no_sim"
                                    v-model="form.no_sim"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Nomor SIM (opsional)"
                                />
                                <p v-if="form.errors.no_sim" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.no_sim }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Dokumen -->
                    <div class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                Upload Dokumen
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Foto KTP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Foto KTP
                                </label>
                                <div class="space-y-2">
                                    <div v-if="fotoKtpPreview" class="mb-2">
                                        <img
                                            :src="fotoKtpPreview"
                                            alt="Foto KTP Preview"
                                            class="w-full h-32 object-cover border border-gray-300 rounded-lg"
                                        />
                                    </div>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="handleFotoKtpUpload"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300"
                                    />
                                    <p class="text-xs text-gray-500">Maks. 2MB (JPG, PNG, GIF, WEBP)</p>
                                </div>
                                <p v-if="form.errors.foto_ktp" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.foto_ktp }}
                                </p>
                            </div>

                            <!-- Foto SIM -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Foto SIM
                                </label>
                                <div class="space-y-2">
                                    <div v-if="fotoSimPreview" class="mb-2">
                                        <img
                                            :src="fotoSimPreview"
                                            alt="Foto SIM Preview"
                                            class="w-full h-32 object-cover border border-gray-300 rounded-lg"
                                        />
                                    </div>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="handleFotoSimUpload"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300"
                                    />
                                    <p class="text-xs text-gray-500">Maks. 2MB (JPG, PNG, GIF, WEBP)</p>
                                </div>
                                <p v-if="form.errors.foto_sim" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.foto_sim }}
                                </p>
                            </div>

                            <!-- Foto Diri -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Foto Diri
                                </label>
                                <div class="space-y-2">
                                    <div v-if="fotoDiriPreview" class="mb-2">
                                        <img
                                            :src="fotoDiriPreview"
                                            alt="Foto Diri Preview"
                                            class="w-full h-32 object-cover border border-gray-300 rounded-lg"
                                        />
                                    </div>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="handleFotoDiriUpload"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300"
                                    />
                                    <p class="text-xs text-gray-500">Maks. 2MB (JPG, PNG, GIF, WEBP)</p>
                                </div>
                                <p v-if="form.errors.foto_diri" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.foto_diri }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                        <Link
                            href="/"
                            class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors"
                        >
                            Kembali ke Beranda
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-8 py-3 text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed font-semibold"
                        >
                            <span v-if="form.processing">Mendaftar...</span>
                            <span v-else>Daftar Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>Catatan:</strong> Setelah mendaftar, data Anda akan diverifikasi oleh tim. Anda akan mendapat notifikasi setelah verifikasi selesai.
                    Silakan login setelah akun Anda aktif.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    companies: Array,
});

const fotoKtpPreview = ref(null);
const fotoSimPreview = ref(null);
const fotoDiriPreview = ref(null);
const fotoKtpFile = ref(null);
const fotoSimFile = ref(null);
const fotoDiriFile = ref(null);

const companySearch = ref('');
const showCompanyDropdown = ref(false);
const selectedCompanyName = ref('');
const allCompanies = ref(props.companies || []);

const form = useForm({
    company_id: null,
    name: '',
    alamat: '',
    no_hp: '',
    no_ktp: '',
    no_sim: '',
    foto_ktp: null,
    foto_sim: null,
    foto_diri: null,
    status: 'pending',
    email: '',
    username: '',
    password: '',
});

const filteredCompanies = computed(() => {
    if (!companySearch.value) {
        return allCompanies.value;
    }
    const searchLower = companySearch.value.toLowerCase().trim();
    return allCompanies.value.filter(company =>
        company.name.toLowerCase().includes(searchLower)
    );
});

const showCreateOption = computed(() => {
    if (!companySearch.value || !companySearch.value.trim()) {
        return false;
    }
    const searchLower = companySearch.value.toLowerCase().trim();
    // Show create option if no exact match found
    const exactMatch = allCompanies.value.find(company =>
        company.name.toLowerCase() === searchLower
    );
    return !exactMatch;
});

const filterCompanies = () => {
    showCompanyDropdown = true;
};

const selectCompany = (company) => {
    form.company_id = company.id;
    companySearch.value = company.name;
    selectedCompanyName.value = company.name;
    showCompanyDropdown.value = false;
};

const handleCompanyBlur = () => {
    // Delay to allow click event on dropdown items
    setTimeout(() => {
        showCompanyDropdown.value = false;
    }, 200);
};

const createNewCompany = async () => {
    if (!companySearch.value.trim()) {
        return;
    }

    try {
        const response = await axios.post(route('companies.quick-store.public'), {
            name: companySearch.value.trim(),
        });

        if (response.data.success) {
            const newCompany = response.data.company;
            allCompanies.value.push(newCompany);
            selectCompany(newCompany);
        }
    } catch (error) {
        console.error('Error creating company:', error);
        const errorMessage = error.response?.data?.message || error.response?.data?.error || 'Gagal menambahkan perusahaan baru. Silakan coba lagi.';
        alert(errorMessage);
    }
};

// Watch for company_id changes to sync with companySearch
watch(() => form.company_id, (newId) => {
    if (newId) {
        const company = allCompanies.value.find(c => c.id === newId);
        if (company) {
            companySearch.value = company.name;
            selectedCompanyName.value = company.name;
        }
    } else {
        companySearch.value = '';
        selectedCompanyName.value = '';
    }
});

const handleFotoKtpUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        fotoKtpFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            fotoKtpPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleFotoSimUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        fotoSimFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            fotoSimPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleFotoDiriUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        fotoDiriFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            fotoDiriPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submitForm = () => {
    // Validate company_id
    if (!form.company_id) {
        alert('Silakan pilih atau tambahkan perusahaan terlebih dahulu.');
        return;
    }

    const formData = new FormData();
    formData.append('company_id', form.company_id);
    formData.append('name', form.name);
    formData.append('alamat', form.alamat || '');
    formData.append('no_hp', form.no_hp);
    formData.append('no_ktp', form.no_ktp);
    formData.append('no_sim', form.no_sim || '');
    formData.append('status', form.status);
    formData.append('email', form.email);
    formData.append('username', form.username);
    formData.append('password', form.password);

    if (fotoKtpFile.value) {
        formData.append('foto_ktp', fotoKtpFile.value);
    }
    if (fotoSimFile.value) {
        formData.append('foto_sim', fotoSimFile.value);
    }
    if (fotoDiriFile.value) {
        formData.append('foto_diri', fotoDiriFile.value);
    }

    form.post(route('driver.register.store'), {
        forceFormData: true,
        onSuccess: () => {
            // Reset form after successful submission
            form.reset();
            companySearch.value = '';
            selectedCompanyName.value = '';
            showCompanyDropdown.value = false;
            fotoKtpPreview.value = null;
            fotoSimPreview.value = null;
            fotoDiriPreview.value = null;
            fotoKtpFile.value = null;
            fotoSimFile.value = null;
            fotoDiriFile.value = null;
        },
    });
};
</script>

