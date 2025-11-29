<template>
    <FullScreenLayout>
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-gray-50/30 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 py-12" :style="{ '--primary-color': primaryColor, '--secondary-color': secondaryColor }">
            <!-- Background decorative elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 right-0 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl" :style="{ backgroundColor: withOpacity('primary', 0.2) }"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full mix-blend-multiply filter blur-3xl" :style="{ backgroundColor: withOpacity('secondary', 0.2) }"></div>
            </div>

            <div class="relative w-full max-w-md">
                <!-- Logo/Brand Section -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-2xl shadow-lg" :style="{ background: `linear-gradient(to bottom right, ${primaryColor}, ${secondaryColor})` }">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Selamat Datang
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Masuk ke akun Anda untuk melanjutkan
                    </p>
                </div>

                <!-- Login Form Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8">
                    <form @submit.prevent="form.post('/login')" class="space-y-6">
                        <!-- Username/Email Field -->
                        <div>
                            <label for="username" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Username / Email
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    id="username"
                                    v-model="form.user"
                                    placeholder="Masukkan username / email Anda"
                                    class="block w-full pl-10 pr-4 py-3 text-sm text-gray-900 placeholder-gray-400 bg-gray-50 border rounded-xl transition-all duration-200 dark:bg-gray-700/50 dark:text-white dark:placeholder-gray-400 focus:ring-2 focus:outline-none"
                                    :class="form.errors.user ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 dark:border-gray-600'"
                                    :style="form.errors.user ? {} : { '--tw-ring-color': primaryColor, '--focus-border-color': primaryColor }"
                                    @focus="(e) => { if (!form.errors.user) e.target.style.borderColor = primaryColor; e.target.style.boxShadow = `0 0 0 2px ${withOpacity('primary', 0.2)}`; }"
                                    @blur="(e) => { if (!form.errors.user) { e.target.style.borderColor = ''; e.target.style.boxShadow = ''; } }"
                                />
                            </div>
                            <p v-if="form.errors.user" class="mt-1 text-xs text-red-600 dark:text-red-400">{{ form.errors.user }}</p>
                                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Kata Sandi
                                    <span class="text-red-500 ml-1">*</span>
                                            </label>
                                <a href="/forgot-password" class="text-xs font-medium transition-colors" :style="{ color: primaryColor }" :class="{ 'hover:opacity-80': true }">
                                    Lupa Kata Sandi?
                                </a>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    id="password"
                                    placeholder="Masukkan kata sandi"
                                    class="block w-full pl-10 pr-10 py-3 text-sm text-gray-900 placeholder-gray-400 bg-gray-50 border rounded-xl transition-all duration-200 dark:bg-gray-700/50 dark:text-white dark:placeholder-gray-400 focus:ring-2 focus:outline-none"
                                    :class="form.errors.password ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 dark:border-gray-600'"
                                    :style="form.errors.password ? {} : { '--tw-ring-color': primaryColor, '--focus-border-color': primaryColor }"
                                    @focus="(e) => { if (!form.errors.password) e.target.style.borderColor = primaryColor; e.target.style.boxShadow = `0 0 0 2px ${withOpacity('primary', 0.2)}`; }"
                                    @blur="(e) => { if (!form.errors.password) { e.target.style.borderColor = ''; e.target.style.boxShadow = ''; } }"
                                />
                                <button
                                    type="button"
                                    @click="togglePasswordVisibility"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                >
                                    <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="mt-1 text-xs text-red-600 dark:text-red-400">{{ form.errors.password }}</p>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="remember"
                                v-model="form.remember"
                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                :style="{ 'accent-color': primaryColor }"
                            />
                            <label for="remember" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                Ingat Saya
                            </label>
                    </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex items-center justify-center w-full px-4 py-3 text-sm font-semibold text-white rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transform transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            :style="{
                                background: `linear-gradient(to right, ${primaryColor}, ${secondaryColor})`,
                                '--tw-ring-color': primaryColor
                            }"
                            @mouseenter="(e) => { if (!form.processing) { e.target.style.background = `linear-gradient(to right, ${primaryDark}, ${secondaryDark})`; } }"
                            @mouseleave="(e) => { if (!form.processing) { e.target.style.background = `linear-gradient(to right, ${primaryColor}, ${secondaryColor})`; } }"
                        >
                            <span v-if="!form.processing">Masuk Sekarang</span>
                            <span v-else class="flex items-center">
                                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                            <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Footer Text -->
                <p class="mt-6 text-xs text-center text-gray-500 dark:text-gray-400">
                    © {{ new Date().getFullYear() }} All rights reserved.
                </p>
            </div>
        </div>
    </FullScreenLayout>
</template>

<script setup>
import FullScreenLayout from "@/Components/layout/FullScreenLayout.vue";
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useColors } from "@/Composables/useColors";

const props = defineProps({
    googleLoginUrl: String,
});

const form = useForm({
    user: "",
    password: "",
    remember: false,
});

const showPassword = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

// Get colors from settings
const { colors, withOpacity, getRgb } = useColors();

// Computed styles for dynamic colors
const primaryColor = computed(() => colors.value.primary);
const secondaryColor = computed(() => colors.value.secondary);

// Helper function to darken color
const darkenColor = (hex, percent = 10) => {
    const num = parseInt(hex.replace("#", ""), 16);
    const amt = Math.round(2.55 * percent);
    const R = Math.max(0, Math.min(255, (num >> 16) - amt));
    const G = Math.max(0, Math.min(255, ((num >> 8) & 0x00FF) - amt));
    const B = Math.max(0, Math.min(255, (num & 0x0000FF) - amt));
    return `#${(0x1000000 + R * 0x10000 + G * 0x100 + B).toString(16).slice(1).toUpperCase().padStart(6, '0')}`;
};

const primaryDark = computed(() => darkenColor(primaryColor.value, 10));
const secondaryDark = computed(() => darkenColor(secondaryColor.value, 10));
</script>
