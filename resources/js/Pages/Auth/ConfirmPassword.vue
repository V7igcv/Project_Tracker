<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

import {
    FolderKanban,
    Lock,
    ArrowRight,
} from 'lucide-vue-next';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Confirm Password" />

    <div class="min-h-screen flex bg-white">

        <!-- LEFT PANEL -->
        <div
            class="hidden lg:flex lg:w-1/2 bg-[#3D3D3D] text-white flex-col items-center justify-center px-12"
        >
            <div class="max-w-md text-center">
                <div
                    class="mx-auto mb-8 flex h-16 w-16 items-center justify-center rounded-xl bg-white/10"
                >
                    <FolderKanban class="h-8 w-8" />
                </div>

                <h1 class="text-5xl font-bold leading-tight">
                    Security Check
                </h1>

                <p class="mt-6 text-lg text-[#FFEEDE]">
                    To continue, please confirm your password and verify your
                    identity.
                </p>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div
            class="flex w-full lg:w-1/2 items-center justify-center px-6 py-12"
        >
            <div class="w-full max-w-md">

                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-2 text-[#A76A00]">
                        <FolderKanban class="h-5 w-5" />
                        <span class="font-semibold">
                            ELEVATE
                        </span>
                    </div>

                    <h2 class="mt-4 text-3xl font-bold text-gray-900">
                        Confirm Password
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        This is a secure area of the application.
                    </p>
                </div>

                <div class="mb-6 rounded-lg border border-gray-200 p-4 text-sm text-gray-600">
                    Please confirm your password before continuing.
                </div>

                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Password -->
                    <div>
                        <InputLabel
                            for="password"
                            value="Password"
                            class="mb-2 uppercase tracking-wide text-xs"
                        />

                        <div class="relative">
                            <Lock
                                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            />

                            <TextInput
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autofocus
                                autocomplete="current-password"
                                class="block w-full pl-10"
                            />
                        </div>

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#A76A00] px-4 py-3 font-medium text-white transition hover:bg-[#8A5A00] disabled:opacity-50"
                    >
                        Confirm Password

                        <ArrowRight class="h-4 w-4" />
                    </button>

                </form>

            </div>
        </div>

    </div>
</template>