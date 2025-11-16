<template>
    <Head :title="`Edit ${driver.name}`" />

    <div class="flex flex-col gap-3 px-3 h-full">
        <div class="flex justify-between items-center h-10">
            <Breadcrumb :items="breadcrumbs" />
        </div>

        <div
            class="flex flex-col gap-6 p-6 overflow-auto rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"
        >
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Edit Driver: {{ driver.name }}
            </h1>

            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Informasi Akun -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Informasi Akun
                    </h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="space-y-1 text-sm">
                            <label
                                for="email"
                                class="text-gray-900 dark:text-white"
                            >
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                placeholder="Masukkan email"
                                required
                            />
                            <div
                                v-if="form.errors.email"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <div class="space-y-1 text-sm">
                            <label
                                for="username"
                                class="text-gray-900 dark:text-white"
                            >
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="username"
                                v-model="form.username"
                                type="text"
                                class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                placeholder="Masukkan username"
                                required
                            />
                            <div
                                v-if="form.errors.username"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.username }}
                            </div>
                        </div>

                        <div class="space-y-1 text-sm">
                            <label
                                for="password"
                                class="text-gray-900 dark:text-white"
                            >
                                Password (Kosongkan jika tidak ingin mengubah)
                            </label>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                placeholder="Masukkan password baru (min. 8 karakter)"
                            />
                            <div
                                v-if="form.errors.password"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.password }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Driver -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Informasi Driver
                    </h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="space-y-1 text-sm">
                            <label
                                for="company_id"
                                class="text-gray-900 dark:text-white"
                            >
                                Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="company_id"
                                v-model="form.company_id"
                                class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required
                            >
                                <option value="">Pilih Perusahaan</option>
                                <option
                                    v-for="company in props.companies"
                                    :key="company.id"
                                    :value="company.id"
                                >
                                    {{ company.name }}
                                </option>
                            </select>
                            <div
                                v-if="form.errors.company_id"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.company_id }}
                            </div>
                        </div>

                        <div class="space-y-1 text-sm">
                            <label
                                for="name"
                                class="text-gray-900 dark:text-white"
                            >
                                Nama Driver <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                placeholder="Masukkan nama driver"
                                required
                            />
                            <div
                                v-if="form.errors.name"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1 text-sm">
                        <label
                            for="alamat"
                            class="text-gray-900 dark:text-white"
                        >
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="alamat"
                            v-model="form.alamat"
                            rows="3"
                            class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                            placeholder="Masukkan alamat driver"
                            required
                        ></textarea>
                        <div
                            v-if="form.errors.alamat"
                            class="text-sm text-red-500"
                        >
                            {{ form.errors.alamat }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="space-y-1 text-sm">
                            <label
                                for="no_hp"
                                class="text-gray-900 dark:text-white"
                            >
                                Nomor HP <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="no_hp"
                                v-model="form.no_hp"
                                type="text"
                                class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                placeholder="08xxxxxxxxxx"
                                required
                            />
                            <div
                                v-if="form.errors.no_hp"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.no_hp }}
                            </div>
                        </div>

                        <div class="space-y-1 text-sm">
                            <label
                                for="no_ktp"
                                class="text-gray-900 dark:text-white"
                            >
                                Nomor KTP <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="no_ktp"
                                v-model="form.no_ktp"
                                type="text"
                                class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                placeholder="Masukkan nomor KTP"
                                required
                            />
                            <div
                                v-if="form.errors.no_ktp"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.no_ktp }}
                            </div>
                        </div>

                        <div class="space-y-1 text-sm">
                            <label
                                for="no_sim"
                                class="text-gray-900 dark:text-white"
                            >
                                Nomor SIM
                            </label>
                            <input
                                id="no_sim"
                                v-model="form.no_sim"
                                type="text"
                                class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                placeholder="Masukkan nomor SIM"
                            />
                            <div
                                v-if="form.errors.no_sim"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.no_sim }}
                            </div>
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
                            <option value="pending">Menunggu Verifikasi</option>
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

                <!-- Upload Foto -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Upload Foto
                    </h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="space-y-1 text-sm">
                            <label
                                for="foto_ktp"
                                class="text-gray-900 dark:text-white"
                            >
                                Foto KTP
                            </label>
                            <div class="flex flex-col gap-2">
                                <div v-if="fotoKtpPreview" class="flex-shrink-0">
                                    <img
                                        :src="fotoKtpPreview"
                                        alt="Foto KTP Preview"
                                        class="w-full h-32 object-contain border border-gray-200 rounded-lg dark:border-gray-600"
                                    />
                                </div>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleFotoKtpUpload"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                />
                                <p class="text-xs text-gray-500">
                                    Format: JPG, PNG, GIF, WEBP. Maks. 2MB
                                </p>
                            </div>
                            <div
                                v-if="form.errors.foto_ktp"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.foto_ktp }}
                            </div>
                        </div>

                        <div class="space-y-1 text-sm">
                            <label
                                for="foto_sim"
                                class="text-gray-900 dark:text-white"
                            >
                                Foto SIM
                            </label>
                            <div class="flex flex-col gap-2">
                                <div v-if="fotoSimPreview" class="flex-shrink-0">
                                    <img
                                        :src="fotoSimPreview"
                                        alt="Foto SIM Preview"
                                        class="w-full h-32 object-contain border border-gray-200 rounded-lg dark:border-gray-600"
                                    />
                                </div>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleFotoSimUpload"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                />
                                <p class="text-xs text-gray-500">
                                    Format: JPG, PNG, GIF, WEBP. Maks. 2MB
                                </p>
                            </div>
                            <div
                                v-if="form.errors.foto_sim"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.foto_sim }}
                            </div>
                        </div>

                        <div class="space-y-1 text-sm">
                            <label
                                for="foto_diri"
                                class="text-gray-900 dark:text-white"
                            >
                                Foto Diri
                            </label>
                            <div class="flex flex-col gap-2">
                                <div v-if="fotoDiriPreview" class="flex-shrink-0">
                                    <img
                                        :src="fotoDiriPreview"
                                        alt="Foto Diri Preview"
                                        class="w-full h-32 object-contain border border-gray-200 rounded-lg dark:border-gray-600"
                                    />
                                </div>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleFotoDiriUpload"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                />
                                <p class="text-xs text-gray-500">
                                    Format: JPG, PNG, GIF, WEBP. Maks. 2MB
                                </p>
                            </div>
                            <div
                                v-if="form.errors.foto_diri"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.foto_diri }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end pt-4 border-t border-gray-200 dark:border-gray-600">
                    <Link
                        :href="route('drivers.index')"
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
    driver: Object,
    companies: Array,
});

const breadcrumbs = [
    { label: "Menu Utama" },
    { label: "Driver", url: route("drivers.index") },
    { label: `Edit ${props.driver.name}` },
];

const fotoKtpPreview = ref(null);
const fotoSimPreview = ref(null);
const fotoDiriPreview = ref(null);
const fotoKtpFile = ref(null);
const fotoSimFile = ref(null);
const fotoDiriFile = ref(null);

// Initialize previews with existing images
onMounted(() => {
    if (props.driver.foto_ktp) {
        fotoKtpPreview.value = props.driver.foto_ktp;
    }
    if (props.driver.foto_sim) {
        fotoSimPreview.value = props.driver.foto_sim;
    }
    if (props.driver.foto_diri) {
        fotoDiriPreview.value = props.driver.foto_diri;
    }
});

const form = useForm({
    company_id: props.driver.company_id || "",
    name: props.driver.name || "",
    alamat: props.driver.alamat || "",
    no_hp: props.driver.no_hp || "",
    no_ktp: props.driver.no_ktp || "",
    no_sim: props.driver.no_sim || "",
    foto_ktp: null,
    foto_sim: null,
    foto_diri: null,
    status: props.driver.status || "pending",
    // User fields
    email: props.driver.user?.email || "",
    username: props.driver.user?.username || "",
    password: "",
});

// Handle file uploads
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
    if (form.password) {
        formData.append('password', form.password);
    }

    if (fotoKtpFile.value) {
        formData.append('foto_ktp', fotoKtpFile.value);
    }
    if (fotoSimFile.value) {
        formData.append('foto_sim', fotoSimFile.value);
    }
    if (fotoDiriFile.value) {
        formData.append('foto_diri', fotoDiriFile.value);
    }

    formData.append('_method', 'PUT');
    form.transform(() => formData).post(route("drivers.update", props.driver.id), {
        forceFormData: true,
        onSuccess: () => {
            // Redirect handled by controller
        },
    });
};
</script>

