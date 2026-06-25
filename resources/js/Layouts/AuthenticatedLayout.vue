<script setup>
import Sidebar from '@/Components/main/Sidebar.vue';
import Header from '@/Components/main/Header.vue';
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">

        <!-- Sidebar -->
        <Sidebar />

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden">

            <!-- New Header -->
            <Header />

            <!-- Optional Page Title Slot -->
            <div
                v-if="$slots.header"
                class="border-b bg-white px-5 py-3"
            >
                <slot name="header" />
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-5">
                <slot />
            </main>

        </div>

    </div>
</template>