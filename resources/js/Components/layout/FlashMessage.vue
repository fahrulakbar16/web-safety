<template>
    <div class="space-y-2">
        <!-- Success Toast -->
        <div
            v-if="successMessage"
            class="fixed end-1 top-20 mx-7 my-1.5 z-50 inline-flex items-center gap-x-3 rounded-lg border bg-white p-2 shadow"
            :style="successStyle"
        >
            <div
                class="flex h-8 w-8 items-center justify-center rounded-lg"
                :style="successBgStyle"
            >
                <svg
                    class="h-5 w-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                    />
                </svg>
            </div>
            <div class="max-w-48 text-sm font-semibold">
                {{ successMessage }}
            </div>
            <button
                @click="successMessage = ''"
                class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-gray-400 hover:bg-gray-200"
            >
                ✕
            </button>
        </div>

        <!-- Error Toast -->
        <div
            v-if="errorMessage"
            class="fixed end-1 top-20 mx-7 my-1.5 z-50 inline-flex items-center gap-x-3 rounded-lg border bg-white p-2 shadow"
            :style="errorStyle"
        >
            <div
                class="flex h-8 w-8 items-center justify-center rounded-lg"
                :style="errorBgStyle"
            >
                <svg
                    class="h-5 w-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"
                    />
                </svg>
            </div>
            <div class="max-w-48 text-sm font-semibold">
                {{ errorMessage }}
            </div>
            <button
                @click="errorMessage = ''"
                class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-gray-400 hover:bg-gray-200"
            >
                ✕
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useColors } from "@/Composables/useColors";

const successMessage = ref("");
const errorMessage = ref("");
const page = usePage();
const { colors, withOpacity } = useColors();

// Computed styles for success alert
const successStyle = computed(() => ({
    borderColor: colors.value.success,
    color: colors.value.success,
}));

const successBgStyle = computed(() => ({
    backgroundColor: withOpacity('success', 0.2),
}));

// Computed styles for error alert
const errorStyle = computed(() => ({
    borderColor: colors.value.error,
    color: colors.value.error,
}));

const errorBgStyle = computed(() => ({
    backgroundColor: withOpacity('error', 0.2),
}));

// Watch flash messages
watch(
    () => page.props.flash.success,
    (message) => {
        if (message) {
            successMessage.value = message;
            setTimeout(() => (successMessage.value = ""), 5000);
        }
    },
    { immediate: true }
);

watch(
    () => page.props.flash.error,
    (message) => {
        if (message) {
            errorMessage.value = message;
            setTimeout(() => (errorMessage.value = ""), 5000);
        }
    },
    { immediate: true }
);

window.flash = {
    success: (msg) => {
        successMessage.value = msg;
        setTimeout(() => (successMessage.value = ""), 5000);
    },
    error: (msg) => {
        errorMessage.value = msg;
        setTimeout(() => (errorMessage.value = ""), 5000);
    },
};
</script>
