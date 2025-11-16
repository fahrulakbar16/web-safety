<script setup>
import { computed } from "vue";
import { useColors } from "@/Composables/useColors";

defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});

const { colors, withOpacity } = useColors();

const buttonStyle = computed(() => ({
    backgroundColor: colors.value.primary,
    borderColor: colors.value.primary,
}));

const hoverStyle = computed(() => ({
    backgroundColor: colors.value.primary,
    filter: 'brightness(0.9)',
}));

const focusRingStyle = computed(() => ({
    '--tw-ring-color': colors.value.primary,
}));
</script>

<template>
    <button
        :type="type"
        :style="buttonStyle"
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"
        @mouseenter="$event.target.style.filter = 'brightness(0.9)'"
        @mouseleave="$event.target.style.filter = ''"
        @focus="$event.target.style.boxShadow = `0 0 0 2px ${colors.primary}40`"
        @blur="$event.target.style.boxShadow = ''"
    >
        <slot />
    </button>
</template>
