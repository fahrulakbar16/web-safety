<template>
    <Head title="Dashboard" />

    <div class="p-6 space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Pusat Terpadu untuk Manajemen dan Kustomisasi
            </p>
        </div>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Users -->
            <div
                class="bg-white rounded-2xl shadow p-6 flex items-center gap-4"
            >
                <div class="p-3 rounded-xl" style="background-color: #1b84ff1a">
                    <Users class="w-6 h-6 text-[#1B84FF]" />
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ metrics.total_users }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Pengguna</p>
                </div>
            </div>

            <!-- Active Users -->
            <div
                class="bg-white rounded-2xl shadow p-6 flex items-center gap-4"
            >
                <div class="p-3 rounded-xl" style="background-color: #10b9811a">
                    <UserCheck class="w-6 h-6 text-[#10B981]" />
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ metrics.active_users }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pengguna Aktif</p>
                </div>
            </div>

            <!-- Total Roles -->
            <div
                class="bg-white rounded-2xl shadow p-6 flex items-center gap-4"
            >
                <div class="p-3 rounded-xl" style="background-color: #f59e0b1a">
                    <Shield class="w-6 h-6 text-[#F59E0B]" />
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ metrics.total_roles }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Jabatan</p>
                </div>
            </div>

            <!-- Users with Roles -->
            <div
                class="bg-white rounded-2xl shadow p-6 flex items-center gap-4"
            >
                <div class="p-3 rounded-xl" style="background-color: #8b5cf61a">
                    <UserCog class="w-6 h-6 text-[#8B5CF6]" />
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ metrics.users_with_roles }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pengguna dengan Jabatan</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Users, UserCheck, Shield, UserCog } from "lucide-vue-next";

defineOptions({
    layout: AppLayout,
});

// Props from backend
const props = defineProps({
    metrics: {
        type: Object,
        default: () => ({
            total_users: 0,
            active_users: 0,
            total_roles: 0,
            users_with_roles: 0,
        }),
    },
});
</script>
