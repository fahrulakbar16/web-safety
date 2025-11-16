<template>
    <Link href="/" class="lg:hidden flex items-center gap-2">
        <img
            :src="logoUrl"
            alt="Logo"
            width="45"
            height="45"
        />
        <div class="flex flex-col leading-tight">
            <div class="text-blue-600 font-bold tracking-wider">
                {{ siteName }}
            </div>
            <div class="text-sky-500 italic text-sm">{{ siteDescription }}</div>
        </div>
    </Link>
</template>

<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

// Website settings
const logoUrl = computed(() => page.props.settings?.logo_main_url || '/images/logo/henskristal.png');
const siteName = computed(() => page.props.settings?.site_name || 'Henskristal');
const siteDescription = computed(() => page.props.settings?.site_description || 'Ice Solution');
</script>
