<script setup>
import { ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

import LogoutDialog from '@/Components/main/LogoutDialog.vue';

import {
    Menu,
    LayoutDashboard,
    FolderKanban,
    LogOut,
} from 'lucide-vue-next';

const storageKey = 'sidebar-collapsed';

const collapsed = ref(
    typeof window !== 'undefined'
        ? localStorage.getItem(storageKey) === 'true'
        : false,
);

const logoutDialogOpen = ref(false);

const logout = () => {
    router.post(route('logout'));
};

watch(collapsed, (value) => {
    localStorage.setItem(storageKey, String(value));
});

const toggleSidebar = () => {
    collapsed.value = !collapsed.value;
};
</script>

<template>
    <aside
        :class="collapsed ? 'w-20' : 'w-64'"
        class="sticky top-0 flex h-screen shrink-0 flex-col overflow-hidden bg-[#3D3D3D] text-white transition-all duration-300"
    >
        <!-- Header -->
        <div
            class="flex h-[72px] items-center justify-between border-b border-white/10 px-4"
        >
            <div
                v-if="!collapsed"
                class="flex items-center gap-3"
            >
                <img
                    src="/images/Elevate1.svg"
                    alt="Elevate Logo"
                    class="h-9 w-9"
                >

                <span
                    class="truncate text-3xl font-bold text-[#FFEEDE]"
                >
                    ELEVATE
                </span>
            </div>

            <button
                @click="toggleSidebar"
                class="rounded-lg p-2 transition hover:bg-white/10"
            >
                <Menu class="h-5 w-5" />
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4">

            <!-- Dashboard -->
            <Link
                :href="route('dashboard')"
                :class="[
                    route().current('dashboard')
                        ? 'bg-[#A76A00] text-white'
                        : 'hover:bg-white/10',
                ]"
                class="mb-1 flex items-center px-3 py-3 transition"
            >
                <LayoutDashboard class="h-5 w-5 shrink-0" />

                <span
                    v-if="!collapsed"
                    class="ml-3"
                >
                    Dashboard
                </span>
            </Link>

            <!-- Projects -->
            <Link
                :href="route('projects')"
                :class="[
                    route().current('projects')
                        ? 'bg-[#A76A00] text-white'
                        : 'hover:bg-white/10',
                ]"
                class="flex items-center px-3 py-3 transition"
            >
                <FolderKanban class="h-5 w-5 shrink-0" />

                <span
                    v-if="!collapsed"
                    class="ml-3"
                >
                    Projects
                </span>
            </Link>

        </nav>

        <!-- Divider -->
        <div class="mx-3 border-t border-white/10"></div>

        <!-- Logout -->
        <div class="px-3 py-3">
            <button
                class="flex w-full items-center px-3 py-3 transition hover:bg-red-500/20"
                @click="logoutDialogOpen = true"
            >
                <LogOut class="h-5 w-5 shrink-0" />

                <span
                    v-if="!collapsed"
                    class="ml-3"
                >
                    Log Out
                </span>
            </button>
        </div>
    </aside>

    <LogoutDialog
        :open="logoutDialogOpen"
        @update:open="logoutDialogOpen = $event"
        @confirm="logout"
    />

</template>