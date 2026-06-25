<script setup>
import { Link } from '@inertiajs/vue3';
import {
    Search,
    Bell,
    UserCircle2,
} from 'lucide-vue-next';
</script>

<template>
    <header
        class="flex h-[72px] items-center justify-between border-b border-gray-200 bg-white px-6"
    >
        <!-- Search -->
        <div class="w-full max-w-md">
            <div class="relative">
                <Search
                    class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                />

                <input
                    type="text"
                    placeholder="Search projects..."
                    class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm focus:border-[#A76A00] focus:outline-none focus:ring-2 focus:ring-[#A76A00]/20"
                />
            </div>
        </div>

        <!-- Right Section -->
        <div class="ml-6 flex items-center gap-4">

            <!-- Notifications -->
            <button
                class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-[#A76A00]"
            >
                <Bell class="h-5 w-5" />
            </button>

            <!-- Divider -->
            <div class="h-8 w-px bg-gray-300"></div>

            <!-- Profile -->
            <Link
                :href="route('profile.edit')"
                class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-gray-100"
            >
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">
                        Juan Dela Cruz
                    </p>

                    <p class="text-xs text-gray-500">
                        juan.delacruz@email.com
                    </p>
                </div>

                <UserCircle2
                    class="h-10 w-10 text-[#A76A00]"
                />
            </Link>

        </div>
    </header>
</template>