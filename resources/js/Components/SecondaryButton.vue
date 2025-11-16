<script setup>
import { computed } from "vue";
import { useColors } from "@/Composables/useColors";

defineProps({
    type: {
        type: String,
        default: 'button',
    },
});

const { colors } = useColors();

const focusRingStyle = computed(() => ({
    '--tw-ring-color': colors.value.primary,
}));
</script>

<template>
    <button 
        :type="type" 
        :style="focusRingStyle"
        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
