<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const features = [
    {
        icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        title: 'Pendaftaran Online',
        description: 'Daftar menjadi driver dengan mudah melalui platform online. Proses cepat dan tidak perlu antri.',
    },
    {
        icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        title: 'Pilih Perusahaan',
        description: 'Pilih perusahaan yang sesuai dengan preferensi Anda. Tersedia berbagai perusahaan terpercaya.',
    },
    {
        icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
        title: 'Upload Dokumen',
        description: 'Upload dokumen KTP, SIM, dan foto diri dengan mudah. Sistem akan memverifikasi dokumen Anda.',
    },
    {
        icon: 'M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z',
        title: 'Verifikasi Cepat',
        description: 'Proses verifikasi yang cepat dan transparan. Pantau status pendaftaran Anda secara real-time.',
    },
    {
        icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
        title: 'Data Terlindungi',
        description: 'Keamanan data Anda adalah prioritas kami. Semua informasi dienkripsi dan terlindungi dengan baik.',
    },
    {
        icon: 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
        title: 'Akses Mobile',
        description: 'Akses platform kapan saja dan di mana saja melalui perangkat mobile Anda. Fleksibel dan praktis.',
    },
];

const benefits = [
    {
        icon: 'M13 10V3L4 14h7v7l9-11h-7z',
        title: 'Proses Cepat',
        description: 'Pendaftaran selesai dalam hitungan menit tanpa ribet',
    },
    {
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        title: 'Terpercaya',
        description: 'Platform resmi dengan perusahaan-perusahaan terverifikasi',
    },
    {
        icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        title: 'Gratis',
        description: 'Tidak ada biaya pendaftaran, 100% gratis untuk driver',
    },
];

const stats = [
    { value: '1000+', label: 'Driver Terdaftar' },
    { value: '50+', label: 'Perusahaan Mitra' },
    { value: '24/7', label: 'Layanan Online' },
    { value: '99%', label: 'Tingkat Kepuasan' },
];

const steps = [
    {
        number: '01',
        title: 'Daftar Akun',
        description: 'Buat akun dengan email dan password Anda',
    },
    {
        number: '02',
        title: 'Pilih Perusahaan',
        description: 'Pilih perusahaan yang ingin Anda daftarkan',
    },
    {
        number: '03',
        title: 'Lengkapi Data',
        description: 'Isi data diri dan upload dokumen yang diperlukan',
    },
    {
        number: '04',
        title: 'Tunggu Verifikasi',
        description: 'Tim akan memverifikasi data Anda dalam 1-3 hari kerja',
    },
];

const isScrolled = ref(false);

onMounted(() => {
    const handleScroll = () => {
        isScrolled.value = window.scrollY > 50;
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <Head title="Pendaftaran Driver - Platform Terpercaya" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Navigation -->
        <nav
            :class="[
                'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
                isScrolled ? 'bg-white/80 dark:bg-slate-900/80 backdrop-blur-md shadow-lg' : 'bg-transparent'
            ]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                Driver Registration
                            </h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Link
                            v-if="!$page.props.auth?.user"
                            :href="route('login')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                        >
                            Login Driver
                        </Link>
                        <Link
                            v-if="!$page.props.auth?.user"
                            :href="route('driver.register')"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl"
                        >
                            Registrasi Driver
                        </Link>
                        <Link
                            v-if="$page.props.auth?.user"
                            :href="route('dashboard')"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl"
                        >
                            Dashboard Driver
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-32 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
            </div>

            <div class="relative max-w-7xl mx-auto">
                <div class="text-center">
                    <div class="inline-block px-4 py-2 mb-6 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                            🚗 Platform Pendaftaran Driver Terpercaya
                        </span>
                    </div>
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 dark:text-white mb-6">
                        Daftar Sebagai
                        <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Driver
                        </span>
                        <br />
                        Sekarang Juga
                    </h1>
                    <p class="mt-6 text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                        Bergabunglah dengan ribuan driver yang telah mempercayai platform kami untuk menghubungkan Anda dengan perusahaan-perusahaan terbaik
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                            <Link
                            v-if="!$page.props.auth?.user"
                            :href="route('driver.register')"
                            class="px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1 flex items-center justify-center gap-2"
                            >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Registrasi Driver - Gratis
                            </Link>
                            <Link
                            v-if="canLogin && !$page.props.auth?.user"
                            :href="route('login')"
                            class="px-8 py-4 text-lg font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-800 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Login Driver
                            </Link>
                        <Link
                            v-if="$page.props.auth?.user"
                            :href="route('dashboard')"
                            class="px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Masuk ke Dashboard Driver
                        </Link>
                    </div>
                    <p class="mt-6 text-sm text-gray-500 dark:text-gray-400">
                        ✨ Proses pendaftaran hanya membutuhkan 5 menit
                    </p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div
                        v-for="(stat, index) in stats"
                        :key="index"
                        class="text-center p-6 bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1"
                    >
                        <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            {{ stat.value }}
                        </div>
                        <div class="mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400 font-medium">
                            {{ stat.label }}
                        </div>
                    </div>
                </div>
                            </div>
        </section>

        <!-- Benefits Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white/50 dark:bg-slate-800/50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        Mengapa Pilih Kami?
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Platform terpercaya yang memudahkan proses pendaftaran driver ke perusahaan
                    </p>
                                    </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        v-for="(benefit, index) in benefits"
                        :key="index"
                        class="group p-8 bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-slate-700"
                    >
                        <div class="w-14 h-14 mb-6 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="benefit.icon" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                            {{ benefit.title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ benefit.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        Cara Pendaftaran
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Proses pendaftaran yang mudah dan cepat dalam 4 langkah sederhana
                                </p>
                            </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div
                        v-for="(step, index) in steps"
                        :key="index"
                        class="relative group"
                    >
                        <div class="p-8 bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-slate-700">
                            <div class="absolute -top-4 -left-4 w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ step.number }}
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 mt-4">
                                {{ step.title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                {{ step.description }}
                            </p>
                        </div>
                        <div v-if="index < steps.length - 1" class="hidden lg:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-white/50 dark:bg-slate-800/50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                        Fitur Platform
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Platform lengkap dengan berbagai fitur untuk memudahkan proses pendaftaran Anda
                                </p>
                            </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        v-for="(feature, index) in features"
                        :key="index"
                        class="group p-8 bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-slate-700"
                    >
                        <div class="w-14 h-14 mb-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                                </svg>
                            </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                            {{ feature.title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ feature.description }}
                                </p>
                            </div>
                        </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-12 md:p-16 shadow-2xl">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                        Siap Memulai Karir Sebagai Driver?
                    </h2>
                    <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                        Daftar sekarang dan bergabunglah dengan ribuan driver yang telah mempercayai platform kami. Proses cepat, mudah, dan 100% gratis!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            v-if="!$page.props.auth?.user"
                            :href="route('driver.register')"
                            class="px-8 py-4 text-lg font-semibold text-blue-600 bg-white rounded-xl hover:bg-gray-50 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Registrasi Driver - Gratis
                        </Link>
                        <Link
                            v-if="canLogin && !$page.props.auth?.user"
                            :href="route('login')"
                            class="px-8 py-4 text-lg font-semibold text-white bg-blue-700 rounded-xl hover:bg-blue-800 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border-2 border-white/20 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Login Driver
                        </Link>
                        <Link
                            v-if="$page.props.auth?.user"
                            :href="route('dashboard')"
                            class="px-8 py-4 text-lg font-semibold text-blue-600 bg-white rounded-xl hover:bg-gray-50 transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard Driver
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 px-4 sm:px-6 lg:px-8 border-t border-gray-200 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <p class="text-gray-600 dark:text-gray-400">
                        © {{ new Date().getFullYear() }} Driver Registration Platform. All rights reserved.
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-500">
                        Powered by Laravel {{ laravelVersion }} (PHP {{ phpVersion }})
                    </p>
            </div>
        </div>
        </footer>
    </div>
</template>

<style scoped>
@keyframes blob {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
