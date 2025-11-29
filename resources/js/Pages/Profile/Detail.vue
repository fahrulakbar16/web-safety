<script setup>
import { ref, onBeforeUnmount, computed, watch, onMounted, nextTick } from "vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { useColors } from "@/Composables/useColors";
import Modal from "@/Components/common/Modal.vue";

// Props
const props = defineProps({
    user: { type: Object, default: null },
    driver: { type: Object, default: null },
    companies: { type: Array, default: () => [] },
    isAssessmentPassed: { type: Boolean, default: false },
});

const page = usePage();
const { colors, withOpacity } = useColors();
const user = computed(() => props.user ?? page.props.auth?.user ?? {});

// State
const isEditing = ref(false);
const isEditingDriver = ref(false);
const showBiodataModal = ref(false);

// Profile form (name, email, photo)
const photoPreview = ref(user.value?.profile_photo_url || null);
let objectUrl = null;

const profileForm = useForm({
    name: user.value?.name || "",
    email: user.value?.email || "",
    photo: null,
});

function onPhotoChange(e) {
    const file = e.target.files?.[0];
    profileForm.photo = file || null;
    if (objectUrl) URL.revokeObjectURL(objectUrl);
    if (file) {
        objectUrl = URL.createObjectURL(file);
        photoPreview.value = objectUrl;
    } else {
        photoPreview.value = user?.profile_photo_url || null;
    }
}

onBeforeUnmount(() => {
    if (objectUrl) URL.revokeObjectURL(objectUrl);
});

// Password form (old, new, confirm)
const passwordForm = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

// Driver Biodata form & previews
const fotoKtpPreview = ref(props.driver?.foto_ktp || null);
const fotoSimPreview = ref(props.driver?.foto_sim || null);
const fotoDiriPreview = ref(props.driver?.foto_diri || null);
const fotoKtpFile = ref(null);
const fotoSimFile = ref(null);
const fotoDiriFile = ref(null);

const driverForm = useForm({
    company_id: props.driver?.company_id || null,
    name: props.driver?.name || "",
    alamat: props.driver?.alamat || "",
    no_hp: props.driver?.no_hp || "",
    no_ktp: props.driver?.no_ktp || "",
    no_sim: props.driver?.no_sim || "",
    foto_ktp: null,
    foto_sim: null,
    foto_diri: null,
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

const startEditDriver = () => {
    // Prevent editing if assessment is already passed
    if (props.isAssessmentPassed) {
        Swal.fire({
            icon: 'warning',
            title: 'Tidak Dapat Mengubah Biodata',
            text: 'Biodata tidak dapat diubah karena Anda telah lulus assessment. Silakan hubungi administrator jika ada perubahan data yang diperlukan.',
            confirmButtonText: 'Mengerti',
        });
        return;
    }

    driverForm.company_id = props.driver?.company_id || null;
    driverForm.name = props.driver?.name || "";
    driverForm.alamat = props.driver?.alamat || "";
    driverForm.no_hp = props.driver?.no_hp || "";
    driverForm.no_ktp = props.driver?.no_ktp || "";
    driverForm.no_sim = props.driver?.no_sim || "";
    driverForm.foto_ktp = null;
    driverForm.foto_sim = null;
    driverForm.foto_diri = null;
    fotoKtpPreview.value = props.driver?.foto_ktp || null;
    fotoSimPreview.value = props.driver?.foto_sim || null;
    fotoDiriPreview.value = props.driver?.foto_diri || null;
    fotoKtpFile.value = null;
    fotoSimFile.value = null;
    fotoDiriFile.value = null;
    isEditingDriver.value = true;
};

const cancelEditDriver = () => {
    isEditingDriver.value = false;
    driverForm.reset();
    fotoKtpPreview.value = props.driver?.foto_ktp || null;
    fotoSimPreview.value = props.driver?.foto_sim || null;
    fotoDiriPreview.value = props.driver?.foto_diri || null;
    fotoKtpFile.value = null;
    fotoSimFile.value = null;
    fotoDiriFile.value = null;
};

const submitDriverForm = () => {
    const formData = new FormData();
    formData.append("company_id", driverForm.company_id);
    formData.append("name", driverForm.name);
    formData.append("alamat", driverForm.alamat || "");
    formData.append("no_hp", driverForm.no_hp);
    formData.append("no_ktp", driverForm.no_ktp);
    formData.append("no_sim", driverForm.no_sim || "");
    // Tidak mengirim email, username, password - hanya update biodata driver
    if (fotoKtpFile.value) {
        formData.append("foto_ktp", fotoKtpFile.value);
    }
    if (fotoSimFile.value) {
        formData.append("foto_sim", fotoSimFile.value);
    }
    if (fotoDiriFile.value) {
        formData.append("foto_diri", fotoDiriFile.value);
    }
    formData.append("_method", "PUT");
    driverForm.transform(() => formData).post(route("profile.driver.update"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Biodata driver berhasil diperbarui.",
                timer: 1500,
                showConfirmButton: false,
            });
            isEditingDriver.value = false;
            showBiodataModal.value = false;
            router.reload({ only: ["driver"] });
        },
        onError: () => {
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "Biodata driver tidak dapat diperbarui. Periksa kembali data yang Anda masukkan.",
            });
        },
    });
};

const submitting = ref(false);

function startEdit() {
    profileForm.name = user.value?.name || "";
    profileForm.email = user.value?.email || "";
    profileForm.photo = null;
    photoPreview.value = user.value?.profile_photo_url || null;
    passwordForm.current_password = "";
    passwordForm.password = "";
    passwordForm.password_confirmation = "";
    isEditing.value = true;
}

function cancelEdit() {
    isEditing.value = false;
    profileForm.reset("photo");
    passwordForm.reset();
    if (objectUrl) URL.revokeObjectURL(objectUrl);
    objectUrl = null;
    photoPreview.value = user.value?.profile_photo_url || null;
}

async function submitAll() {
    submitting.value = true;
    let hasError = false;
    try {
        await profileForm
            .transform((data) => ({ ...data, _method: "PUT" }))
            .post(route("user-profile-information.update"), {
                preserveScroll: true,
                forceFormData: true,
                onError: () => {
                    hasError = true;
                },
            });
        if (
            passwordForm.current_password ||
            passwordForm.password ||
            passwordForm.password_confirmation
        ) {
            await passwordForm.put(route("user-password.update"), {
                preserveScroll: true,
                onError: () => {
                    hasError = true;
                },
            });
        }
        if (profileForm.hasErrors || passwordForm.hasErrors) {
            hasError = true;
        }
        if (!hasError) {
            profileForm.reset("photo");
            passwordForm.reset();
            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }
            await router.reload({ only: ["auth"] });
            photoPreview.value = user.value?.profile_photo_url || null;
            isEditing.value = false;
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Profil berhasil diperbarui.",
                timer: 1500,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "Profil tidak dapat diperbarui. Periksa kembali data yang Anda masukkan.",
            });
        }
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "Kesalahan",
            text: "Terjadi kesalahan pada server. Silakan coba lagi nanti.",
        });
    } finally {
        submitting.value = false;
    }
}

// Function to check and show modal
const checkAndShowModal = () => {
    const flash = page.props.flash;
    // Don't show modal if assessment is already passed
    if (flash?.show_biodata_modal && props.driver && !props.isAssessmentPassed) {
        showBiodataModal.value = true;
    }
};

// Watch for flash message to show modal
watch(
    () => page.props.flash?.show_biodata_modal,
    (showModal) => {
        // Don't show modal if assessment is already passed
        if (showModal && props.driver && !props.isAssessmentPassed) {
            nextTick(() => {
                showBiodataModal.value = true;
            });
        }
    },
    { immediate: true }
);

// Also check on mount
onMounted(() => {
    nextTick(() => {
        checkAndShowModal();
    });
});

const closeBiodataModal = () => {
    showBiodataModal.value = false;
    // Clear flash message
    router.reload({ only: [] });
};

const goToBiodataForm = () => {
    showBiodataModal.value = false;
    // Scroll to biodata section and start edit mode
    if (props.driver) {
        startEditDriver();
        // Scroll to biodata form
        nextTick(() => {
            const biodataSection = document.querySelector('[data-biodata-section]');
            if (biodataSection) {
                biodataSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }
    router.reload({ only: [] });
};

const getFieldLabel = (field) => {
    const labels = {
        'company_id': 'Perusahaan',
        'name': 'Nama Lengkap',
        'alamat': 'Alamat',
        'no_hp': 'Nomor HP',
        'no_ktp': 'Nomor KTP',
        'no_sim': 'Nomor SIM',
        'foto_ktp': 'Foto KTP',
        'foto_sim': 'Foto SIM',
        'foto_diri': 'Foto Diri',
        'email': 'Email',
        'username': 'Username',
        'driver_data': 'Data Driver',
    };
    return labels[field] || field;
};
</script>

<template>
  <div class="space-y-6">
    <!-- Profile Section -->
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
        <div class="flex items-center gap-3">
          <div class="flex items-center justify-center w-10 h-10 bg-blue-600 rounded-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">
            {{ isEditing ? "Ubah Data Profile" : "Informasi Profile" }}
          </h3>
        </div>
        <div class="flex items-center gap-2">
          <button
            v-if="!isEditing"
            type="button"
            @click="startEdit"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Ubah Profile
          </button>
        </div>
      </div>

      <!-- ================== MODE VIEW ================== -->
      <div
        v-if="!isEditing"
        class="p-6"
      >
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- Kiri: avatar -->
          <div class="flex-shrink-0 flex flex-col items-center lg:items-start gap-4">
            <div
              class="flex items-center justify-center w-40 h-40 lg:w-48 lg:h-48 overflow-hidden rounded-2xl shadow-lg ring-4 ring-blue-100"
              :style="{ backgroundColor: withOpacity('primary', 0.1) }"
            >
              <img
                v-if="user?.profile_photo_url"
                :src="user.profile_photo_url"
                alt="Profile"
                class="object-cover w-full h-full"
              />
              <span
                v-else
                class="text-5xl lg:text-6xl font-bold select-none"
                :style="{ color: colors.primary }"
              >
                {{
                  (user?.name || user?.username || "?")
                    .toString()
                    .charAt(0)
                    .toUpperCase()
                }}
              </span>
            </div>
            <div class="text-center lg:text-left">
              <p class="text-sm font-medium text-gray-700">Foto Profil</p>
              <p class="text-xs text-gray-500 mt-1">Klik Ubah Profile untuk mengubah</p>
            </div>
          </div>

          <!-- Kanan: semua info -->
          <div class="flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nama Lengkap</dt>
                <dd class="text-base font-semibold text-gray-900">
                  {{ user?.name || "-" }}
                </dd>
              </div>
              <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Email</dt>
                <dd class="text-base font-semibold text-gray-900 break-all">
                  {{ user?.email || "-" }}
                </dd>
              </div>
              <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Username</dt>
                <dd class="text-base font-semibold text-gray-900">
                  {{ user?.username || "-" }}
                </dd>
              </div>
              <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Role</dt>
                <dd class="text-base font-semibold text-gray-900">
                  <span class="inline-flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    {{ user?.role || user?.roles?.[0]?.name || "-" }}
                  </span>
                </dd>
              </div>
              <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 md:col-span-2">
                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Status</dt>
                <dd class="mt-1">
                  <span
                    :class="[
                      'inline-flex items-center gap-2 px-3 py-1.5 text-sm font-semibold rounded-full',
                      user?.status === 'active'
                        ? 'bg-green-100 text-green-800'
                        : user?.status === 'pending'
                        ? 'bg-yellow-100 text-yellow-800'
                        : 'bg-gray-100 text-gray-800',
                    ]"
                  >
                    <span
                      :class="[
                        'w-2 h-2 rounded-full',
                        user?.status === 'active'
                          ? 'bg-green-500'
                          : user?.status === 'pending'
                          ? 'bg-yellow-500'
                          : 'bg-gray-500',
                      ]"
                    ></span>
                    {{ user?.status ? user.status.charAt(0).toUpperCase() + user.status.slice(1) : "-" }}
                  </span>
                </dd>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Biodata Driver Section -->
    <div v-if="props.driver && !isEditingDriver" class="overflow-hidden bg-white shadow sm:rounded-lg">
      <div class="flex items-center justify-between px-6 py-5 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
        <div class="flex items-center gap-3">
          <div class="flex items-center justify-center w-10 h-10 bg-green-600 rounded-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">Biodata Driver</h3>
        </div>
        <div v-if="props.isAssessmentPassed" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-yellow-700 bg-yellow-100 rounded-lg border border-yellow-300">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          Tidak Dapat Diubah
        </div>
        <button
          v-else
          type="button"
          @click="startEditDriver"
          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors shadow-sm"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
          Ubah Biodata
        </button>
      </div>
      <!-- Warning message if assessment passed -->
      <div v-if="props.isAssessmentPassed" class="px-6 py-4 bg-yellow-50 border-b border-yellow-200">
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <div>
            <p class="text-sm font-medium text-yellow-800">
              Biodata tidak dapat diubah karena Anda telah lulus assessment.
            </p>
            <p class="text-xs text-yellow-700 mt-1">
              Silakan hubungi administrator jika ada perubahan data yang diperlukan.
            </p>
          </div>
        </div>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              Nama Lengkap
            </dt>
            <dd class="text-base font-semibold text-gray-900 mt-2">
              {{ props.driver?.name || '-' }}
            </dd>
          </div>
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              Perusahaan
            </dt>
            <dd class="text-base font-semibold text-gray-900 mt-2">
              {{ props.driver?.company?.name || '-' }}
            </dd>
          </div>
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 md:col-span-2">
            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Alamat
            </dt>
            <dd class="text-base font-semibold text-gray-900 mt-2">
              {{ props.driver?.alamat || '-' }}
            </dd>
          </div>
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              Nomor HP
            </dt>
            <dd class="text-base font-semibold text-gray-900 mt-2">
              {{ props.driver?.no_hp || '-' }}
            </dd>
          </div>
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
              </svg>
              Nomor KTP
            </dt>
            <dd class="text-base font-semibold text-gray-900 mt-2">
              {{ props.driver?.no_ktp || '-' }}
            </dd>
          </div>
          <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
            <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Nomor SIM
            </dt>
            <dd class="text-base font-semibold text-gray-900 mt-2">
              {{ props.driver?.no_sim || '-' }}
            </dd>
          </div>
        </div>

        <!-- Dokumen Section -->
        <div v-if="props.driver?.foto_ktp || props.driver?.foto_sim || props.driver?.foto_diri" class="border-t border-gray-200 pt-6">
          <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Dokumen
          </h4>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div v-if="props.driver?.foto_ktp" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
              <p class="text-xs font-medium text-gray-600 mb-2">Foto KTP</p>
              <img
                :src="props.driver.foto_ktp"
                alt="Foto KTP"
                class="w-full h-40 object-cover border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                @click="window.open(props.driver.foto_ktp, '_blank')"
              />
            </div>
            <div v-if="props.driver?.foto_sim" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
              <p class="text-xs font-medium text-gray-600 mb-2">Foto SIM</p>
              <img
                :src="props.driver.foto_sim"
                alt="Foto SIM"
                class="w-full h-40 object-cover border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                @click="window.open(props.driver.foto_sim, '_blank')"
              />
            </div>
            <div v-if="props.driver?.foto_diri" class="bg-gray-50 rounded-lg p-4 border border-gray-200">
              <p class="text-xs font-medium text-gray-600 mb-2">Foto Diri</p>
              <img
                :src="props.driver.foto_diri"
                alt="Foto Diri"
                class="w-full h-40 object-cover border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                @click="window.open(props.driver.foto_diri, '_blank')"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================== MODE EDIT (FORM) ================== -->
    <form
      v-if="isEditing"
      @submit.prevent="submitAll"
      class="overflow-hidden bg-white shadow sm:rounded-lg"
    >
      <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-900">Ubah Data Profile</h3>
        <p class="text-sm text-gray-600 mt-1">Perbarui informasi profile Anda</p>
      </div>

      <div class="p-6">
        <div class="flex flex-col lg:flex-row gap-8 mb-6">
          <!-- Avatar + preview -->
          <div class="flex-shrink-0 flex flex-col items-center lg:items-start gap-4">
            <div
              class="flex items-center justify-center w-40 h-40 lg:w-48 lg:h-48 overflow-hidden rounded-2xl shadow-lg ring-4 ring-blue-100"
              :style="{ backgroundColor: withOpacity('primary', 0.1) }"
            >
              <img
                v-if="photoPreview"
                :src="photoPreview"
                alt="Profile"
                class="object-cover w-full h-full"
              />
              <span
                v-else
                class="text-5xl lg:text-6xl font-bold select-none"
                :style="{ color: colors.primary }"
              >
                {{
                  (user?.name || user?.username || "?")
                    .toString()
                    .charAt(0)
                    .toUpperCase()
                }}
              </span>
            </div>
            <div class="text-center lg:text-left">
              <p class="text-sm font-medium text-gray-700">Pratinjau Foto Profil</p>
            </div>
          </div>

          <!-- Form fields -->
          <div class="flex-1 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="profileForm.name"
                  type="text"
                  autocomplete="name"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                  placeholder="Masukkan nama lengkap"
                />
                <span v-if="profileForm.errors.name" class="mt-1.5 block text-sm text-red-600">
                  {{ profileForm.errors.name }}
                </span>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Email <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="profileForm.email"
                  type="email"
                  autocomplete="email"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                  placeholder="nama@email.com"
                />
                <span v-if="profileForm.errors.email" class="mt-1.5 block text-sm text-red-600">
                  {{ profileForm.errors.email }}
                </span>
              </div>
            </div>

            <!-- Password Section -->
            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
              <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Ubah Password (Opsional)
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Password Lama
                  </label>
                  <input
                    v-model="passwordForm.current_password"
                    type="password"
                    autocomplete="current-password"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="Masukkan password lama"
                  />
                  <span v-if="passwordForm.errors.current_password" class="mt-1.5 block text-sm text-red-600">
                    {{ passwordForm.errors.current_password }}
                  </span>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru
                  </label>
                  <input
                    v-model="passwordForm.password"
                    type="password"
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="Masukkan password baru"
                  />
                  <span v-if="passwordForm.errors.password" class="mt-1.5 block text-sm text-red-600">
                    {{ passwordForm.errors.password }}
                  </span>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password
                  </label>
                  <input
                    v-model="passwordForm.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="Konfirmasi password baru"
                  />
                  <span v-if="passwordForm.errors.password_confirmation" class="mt-1.5 block text-sm text-red-600">
                    {{ passwordForm.errors.password_confirmation }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Photo Upload Section -->
            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
              <label class="block text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Ubah Foto Profil
              </label>
              <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <label class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg cursor-pointer hover:bg-blue-700 transition-colors shadow-sm">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                  </svg>
                  Pilih File
                  <input
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="onPhotoChange"
                  />
                </label>
                <span class="text-sm text-gray-600">
                  {{ profileForm.photo ? profileForm.photo.name : "Tidak ada file dipilih" }}
                </span>
              </div>
              <span v-if="profileForm.errors.photo" class="mt-2 block text-sm text-red-600">
                {{ profileForm.errors.photo }}
              </span>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="cancelEdit"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <svg v-if="submitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ submitting ? "Menyimpan..." : "Simpan Perubahan" }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <!-- /MODE EDIT -->

    <!-- ================== BIODATA DRIVER (EDIT MODE) ================== -->
    <form
      v-if="props.driver && isEditingDriver && !props.isAssessmentPassed"
      @submit.prevent="submitDriverForm"
      class="overflow-hidden bg-white shadow sm:rounded-lg"
      data-biodata-section
    >
      <div class="px-6 py-5 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-900">Ubah Biodata Driver</h3>
        <p class="text-sm text-gray-600 mt-1">Perbarui informasi biodata driver Anda</p>
      </div>
      <div class="p-6">
        <div class="space-y-6">
          <!-- Informasi Dasar -->
          <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              Informasi Dasar
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Perusahaan <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="driverForm.company_id"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
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
                <p v-if="driverForm.errors.company_id" class="mt-1.5 text-sm text-red-600">
                  {{ driverForm.errors.company_id }}
                </p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="driverForm.name"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                  placeholder="Masukkan nama lengkap"
                  required
                />
                <p v-if="driverForm.errors.name" class="mt-1.5 text-sm text-red-600">
                  {{ driverForm.errors.name }}
                </p>
              </div>
            </div>

            <div class="mt-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Alamat <span class="text-red-500">*</span>
              </label>
              <textarea
                v-model="driverForm.alamat"
                rows="3"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                placeholder="Masukkan alamat lengkap"
                required
              ></textarea>
              <p v-if="driverForm.errors.alamat" class="mt-1.5 text-sm text-red-600">
                {{ driverForm.errors.alamat }}
              </p>
            </div>
          </div>

          <!-- Kontak & Identitas -->
          <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              Kontak & Identitas
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nomor HP <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="driverForm.no_hp"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                  placeholder="08xxxxxxxxxx"
                  required
                />
                <p v-if="driverForm.errors.no_hp" class="mt-1.5 text-sm text-red-600">
                  {{ driverForm.errors.no_hp }}
                </p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nomor KTP <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="driverForm.no_ktp"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                  placeholder="16 digit nomor KTP"
                  required
                />
                <p v-if="driverForm.errors.no_ktp" class="mt-1.5 text-sm text-red-600">
                  {{ driverForm.errors.no_ktp }}
                </p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nomor SIM
                </label>
                <input
                  v-model="driverForm.no_sim"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                  placeholder="Nomor SIM (opsional)"
                />
                <p v-if="driverForm.errors.no_sim" class="mt-1.5 text-sm text-red-600">
                  {{ driverForm.errors.no_sim }}
                </p>
              </div>
            </div>
          </div>


          <!-- Dokumen -->
          <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Upload Dokumen
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-white rounded-lg p-4 border border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                  Foto KTP
                </label>
                <div v-if="fotoKtpPreview" class="mb-3">
                  <img
                    :src="fotoKtpPreview"
                    alt="Foto KTP Preview"
                    class="w-full h-36 object-cover border border-gray-300 rounded-lg shadow-sm"
                  />
                </div>
                <input
                  type="file"
                  accept="image/*"
                  @change="handleFotoKtpUpload"
                  class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors"
                />
                <p class="text-xs text-gray-500 mt-2">
                  Maks. 2MB (JPG, PNG, GIF, WEBP)
                </p>
                <p v-if="driverForm.errors.foto_ktp" class="mt-1.5 text-sm text-red-600">
                  {{ driverForm.errors.foto_ktp }}
                </p>
              </div>
              <div class="bg-white rounded-lg p-4 border border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                  Foto SIM
                </label>
                <div v-if="fotoSimPreview" class="mb-3">
                  <img
                    :src="fotoSimPreview"
                    alt="Foto SIM Preview"
                    class="w-full h-36 object-cover border border-gray-300 rounded-lg shadow-sm"
                  />
                </div>
                <input
                  type="file"
                  accept="image/*"
                  @change="handleFotoSimUpload"
                  class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors"
                />
                <p class="text-xs text-gray-500 mt-2">
                  Maks. 2MB (JPG, PNG, GIF, WEBP)
                </p>
                <p v-if="driverForm.errors.foto_sim" class="mt-1.5 text-sm text-red-600">
                  {{ driverForm.errors.foto_sim }}
                </p>
              </div>
              <div class="bg-white rounded-lg p-4 border border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                  Foto Diri
                </label>
                <div v-if="fotoDiriPreview" class="mb-3">
                  <img
                    :src="fotoDiriPreview"
                    alt="Foto Diri Preview"
                    class="w-full h-36 object-cover border border-gray-300 rounded-lg shadow-sm"
                  />
                </div>
                <input
                  type="file"
                  accept="image/*"
                  @change="handleFotoDiriUpload"
                  class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors"
                />
                <p class="text-xs text-gray-500 mt-2">
                  Maks. 2MB (JPG, PNG, GIF, WEBP)
                </p>
                <p v-if="driverForm.errors.foto_diri" class="mt-1.5 text-sm text-red-600">
                  {{ driverForm.errors.foto_diri }}
                </p>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="cancelEditDriver"
              class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="driverForm.processing"
              class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="driverForm.processing" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              {{ driverForm.processing ? "Menyimpan..." : "Simpan Perubahan" }}
            </button>
          </div>
        </div>
      </div>
    </form>
    <!-- /BIODATA DRIVER EDIT -->

    <!-- Modal for Biodata Notification -->
    <Modal
      :show="showBiodataModal"
      :max-width="'md'"
      :closeable="true"
      title="Biodata Belum Lengkap"
      close-text="Tutup"
      confirm-text="Lengkapi Biodata"
      @close="closeBiodataModal"
      @confirm="goToBiodataForm"
    >
      <div class="space-y-4">
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 dark:bg-yellow-900/20 rounded-full">
              <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
              Perhatian
            </h4>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
              Biodata driver Anda belum lengkap. Silakan lengkapi biodata terlebih dahulu sebelum mengakses fitur assessment.
            </p>
            <div v-if="page.props.flash?.missing_fields && page.props.flash.missing_fields.length > 0" class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
              <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Field yang masih kosong:</p>
              <ul class="list-disc list-inside space-y-1">
                <li v-for="field in page.props.flash.missing_fields" :key="field" class="text-xs text-gray-600 dark:text-gray-400">
                  {{ getFieldLabel(field) }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </Modal>
  </div>
</template>
