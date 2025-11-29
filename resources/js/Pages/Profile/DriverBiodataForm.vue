<script setup>
import { ref, computed, onBeforeUnmount } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    driver: Object,
    companies: Array,
});

const isEditing = ref(false);

// Photo previews
const fotoKtpPreview = ref(props.driver?.foto_ktp || null);
const fotoSimPreview = ref(props.driver?.foto_sim || null);
const fotoDiriPreview = ref(props.driver?.foto_diri || null);
const fotoKtpFile = ref(null);
const fotoSimFile = ref(null);
const fotoDiriFile = ref(null);
let objectUrls = [];

const form = useForm({
    company_id: props.driver?.company_id || null,
    name: props.driver?.name || '',
    alamat: props.driver?.alamat || '',
    no_hp: props.driver?.no_hp || '',
    no_ktp: props.driver?.no_ktp || '',
    no_sim: props.driver?.no_sim || '',
    foto_ktp: null,
    foto_sim: null,
    foto_diri: null,
    email: props.driver?.user?.email || '',
    username: props.driver?.user?.username || '',
    password: '',
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

const startEdit = () => {
    form.company_id = props.driver?.company_id || null;
    form.name = props.driver?.name || '';
    form.alamat = props.driver?.alamat || '';
    form.no_hp = props.driver?.no_hp || '';
    form.no_ktp = props.driver?.no_ktp || '';
    form.no_sim = props.driver?.no_sim || '';
    form.email = props.driver?.user?.email || '';
    form.username = props.driver?.user?.username || '';
    form.password = '';
    form.foto_ktp = null;
    form.foto_sim = null;
    form.foto_diri = null;
    fotoKtpPreview.value = props.driver?.foto_ktp || null;
    fotoSimPreview.value = props.driver?.foto_sim || null;
    fotoDiriPreview.value = props.driver?.foto_diri || null;
    fotoKtpFile.value = null;
    fotoSimFile.value = null;
    fotoDiriFile.value = null;
    isEditing.value = true;
};

const cancelEdit = () => {
    isEditing.value = false;
    form.reset();
    fotoKtpPreview.value = props.driver?.foto_ktp || null;
    fotoSimPreview.value = props.driver?.foto_sim || null;
    fotoDiriPreview.value = props.driver?.foto_diri || null;
    fotoKtpFile.value = null;
    fotoSimFile.value = null;
    fotoDiriFile.value = null;
    objectUrls.forEach(url => URL.revokeObjectURL(url));
    objectUrls = [];
};

const submitForm = () => {
    const formData = new FormData();
    formData.append('company_id', form.company_id);
    formData.append('name', form.name);
    formData.append('alamat', form.alamat || '');
    formData.append('no_hp', form.no_hp);
    formData.append('no_ktp', form.no_ktp);
    formData.append('no_sim', form.no_sim || '');
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

    form.transform(() => formData).post(route('profile.driver.update'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Biodata driver berhasil diperbarui.',
                timer: 1500,
                showConfirmButton: false,
            });
            isEditing.value = false;
            router.reload({ only: ['driver'] });
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Biodata driver tidak dapat diperbarui. Periksa kembali data yang Anda masukkan.',
            });
        },
    });
};

onBeforeUnmount(() => {
    objectUrls.forEach(url => URL.revokeObjectURL(url));
});
</script>

<template>
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <!-- Header + tombol -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                {{ isEditing ? 'Ubah Biodata Driver' : 'Biodata Driver' }}
            </h3>
            <div class="flex items-center gap-2">
                <button
                    v-if="!isEditing"
                    type="button"
                    @click="startEdit"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-md btn-primary"
                >
                    Ubah Biodata
                </button>
            </div>
        </div>

        <!-- ================== MODE VIEW ================== -->
        <div v-if="!isEditing" class="p-6">
            <dl class="grid grid-cols-1 gap-y-5 gap-x-12 sm:grid-cols-2">
                <div>
                    <dt class="text-sm text-gray-500">Nama Lengkap</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">
                        {{ driver?.name || '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Perusahaan</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">
                        {{ driver?.company?.name || '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Alamat</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">
                        {{ driver?.alamat || '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Nomor HP</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">
                        {{ driver?.no_hp || '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Nomor KTP</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">
                        {{ driver?.no_ktp || '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Nomor SIM</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">
                        {{ driver?.no_sim || '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Email</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">
                        {{ driver?.user?.email || '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Username</dt>
                    <dd class="mt-1 text-base font-semibold text-gray-900">
                        {{ driver?.user?.username || '-' }}
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm text-gray-500 mb-2">Dokumen</dt>
                    <dd class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div v-if="driver?.foto_ktp">
                            <p class="text-xs text-gray-500 mb-1">Foto KTP</p>
                            <img :src="driver.foto_ktp" alt="Foto KTP" class="w-full h-32 object-cover border border-gray-300 rounded-lg" />
                        </div>
                        <div v-if="driver?.foto_sim">
                            <p class="text-xs text-gray-500 mb-1">Foto SIM</p>
                            <img :src="driver.foto_sim" alt="Foto SIM" class="w-full h-32 object-cover border border-gray-300 rounded-lg" />
                        </div>
                        <div v-if="driver?.foto_diri">
                            <p class="text-xs text-gray-500 mb-1">Foto Diri</p>
                            <img :src="driver.foto_diri" alt="Foto Diri" class="w-full h-32 object-cover border border-gray-300 rounded-lg" />
                        </div>
                    </dd>
                </div>
            </dl>
        </div>

        <!-- ================== MODE EDIT (FORM) ================== -->
        <form v-else @submit.prevent="submitForm" class="p-6">
            <div class="grid grid-cols-1 gap-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Perusahaan <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.company_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="">Pilih Perusahaan</option>
                            <option v-for="company in companies" :key="company.id" :value="company.id">
                                {{ company.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.company_id" class="mt-1 text-sm text-red-500">
                            {{ form.errors.company_id }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">
                            {{ form.errors.name }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        v-model="form.alamat"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    ></textarea>
                    <p v-if="form.errors.alamat" class="mt-1 text-sm text-red-500">
                        {{ form.errors.alamat }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.no_hp"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                        <p v-if="form.errors.no_hp" class="mt-1 text-sm text-red-500">
                            {{ form.errors.no_hp }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor KTP <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.no_ktp"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                        <p v-if="form.errors.no_ktp" class="mt-1 text-sm text-red-500">
                            {{ form.errors.no_ktp }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor SIM
                        </label>
                        <input
                            v-model="form.no_sim"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        />
                        <p v-if="form.errors.no_sim" class="mt-1 text-sm text-red-500">
                            {{ form.errors.no_sim }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.username"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                        <p v-if="form.errors.username" class="mt-1 text-sm text-red-500">
                            {{ form.errors.username }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru (kosongkan jika tidak ingin mengubah)
                    </label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                    <p v-if="form.errors.password" class="mt-1 text-sm text-red-500">
                        {{ form.errors.password }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto KTP
                        </label>
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
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                        <p class="text-xs text-gray-500 mt-1">Maks. 2MB (JPG, PNG, GIF, WEBP)</p>
                        <p v-if="form.errors.foto_ktp" class="mt-1 text-sm text-red-500">
                            {{ form.errors.foto_ktp }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto SIM
                        </label>
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
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                        <p class="text-xs text-gray-500 mt-1">Maks. 2MB (JPG, PNG, GIF, WEBP)</p>
                        <p v-if="form.errors.foto_sim" class="mt-1 text-sm text-red-500">
                            {{ form.errors.foto_sim }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Diri
                        </label>
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
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                        <p class="text-xs text-gray-500 mt-1">Maks. 2MB (JPG, PNG, GIF, WEBP)</p>
                        <p v-if="form.errors.foto_diri" class="mt-1 text-sm text-red-500">
                            {{ form.errors.foto_diri }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white rounded-md btn-primary disabled:opacity-50"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </button>
                    <button
                        type="button"
                        @click="cancelEdit"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border rounded-md hover:bg-gray-50"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

