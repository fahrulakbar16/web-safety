<template>
    <slot></slot>
</template>

<script setup>
import { ref, provide, onMounted, watch, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useColors } from "@/Composables/useColors";

const theme = ref("light");
const isInitialized = ref(false);
const page = usePage();
const { colors, getRgb } = useColors();

const isDarkMode = computed(() => theme.value === "dark");

const toggleTheme = () => {
    theme.value = theme.value === "light" ? "dark" : "light";
};

/**
 * Apply CSS variables for colors
 */
const applyColorVariables = () => {
    const root = document.documentElement;
    
    // Set CSS variables for colors
    root.style.setProperty("--color-primary", colors.value.primary);
    root.style.setProperty("--color-secondary", colors.value.secondary);
    root.style.setProperty("--color-accent", colors.value.accent);
    root.style.setProperty("--color-success", colors.value.success);
    root.style.setProperty("--color-warning", colors.value.warning);
    root.style.setProperty("--color-error", colors.value.error);
    root.style.setProperty("--color-info", colors.value.info);
    
    // Set RGB values for opacity support
    root.style.setProperty("--color-primary-rgb", getRgb("primary"));
    root.style.setProperty("--color-secondary-rgb", getRgb("secondary"));
    root.style.setProperty("--color-accent-rgb", getRgb("accent"));
    root.style.setProperty("--color-success-rgb", getRgb("success"));
    root.style.setProperty("--color-warning-rgb", getRgb("warning"));
    root.style.setProperty("--color-error-rgb", getRgb("error"));
    root.style.setProperty("--color-info-rgb", getRgb("info"));
};

onMounted(() => {
    const savedTheme = localStorage.getItem("theme");
    const initialTheme = savedTheme || "light";

    theme.value = initialTheme;
    isInitialized.value = true;
    
    // Apply color variables on mount
    applyColorVariables();
});

// Watch for theme changes
watch([theme, isInitialized], ([newTheme, initialized]) => {
    if (initialized) {
        localStorage.setItem("theme", newTheme);
        if (newTheme === "dark") {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
    }
});

// Watch for settings changes to update colors
watch(
    () => page.props.settings,
    () => {
        applyColorVariables();
    },
    { deep: true }
);

provide("theme", {
    isDarkMode,
    toggleTheme,
});
</script>

<script>
import { inject } from "vue";

export function useTheme() {
    const theme = inject("theme");
    if (!theme) {
        throw new Error("useTheme must be used within a ThemeProvider");
    }
    return theme;
}
</script>
