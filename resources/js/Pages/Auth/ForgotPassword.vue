<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import {
    FolderKanban,
    Mail,
    ArrowRight,
} from 'lucide-vue-next';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />

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

                <h1 class="text-5xl font-bold leading-tight text-[#FFEEDE]">
                    Recover Your Account
                </h1>

                <p class="mt-6 text-lg text-[#FFEEDE]">
                    We'll help you regain access to your account securely by
                    sending a password reset link to your email.
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
                            Project Tracker
                        </span>
                    </div>

                    <h2 class="mt-4 text-3xl font-bold text-gray-900">
                        Forgot Password
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Enter your email and we'll send you a reset link.
                    </p>
                </div>

                <!-- Description -->
                <div class="mb-6 rounded-lg border border-gray-200 p-4 text-sm text-gray-600">
                    Forgot your password? No problem. Just let us know your
                    email address and we'll email you a password reset link
                    that will allow you to choose a new one.
                </div>

                <!-- Success Message -->
                <div
                    v-if="status"
                    class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-600"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Email -->
                    <div>
                        <InputLabel
                            for="email"
                            value="Email Address"
                            class="mb-2 uppercase tracking-wide text-xs"
                        />

                        <div class="relative">
                            <Mail
                                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            />

                            <TextInput
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="name@company.com"
                                class="block w-full pl-10"
                            />
                        </div>

                        <InputError
                            class="mt-2"
                            :message="form.errors.email"
                        />
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#A76A00] px-4 py-3 font-medium text-white transition hover:bg-[#8A5A00] disabled:opacity-50"
                    >
                        Send Reset Link

                        <ArrowRight class="h-4 w-4" />
                    </button>

                    <!-- Back to Login -->
                    <p class="pt-4 text-center text-sm text-gray-600">
                        Remember your password?

                        <Link
                            :href="route('login')"
                            class="font-medium text-[#A76A00] hover:text-[#8A5A00]"
                        >
                            Back to Login
                        </Link>
                    </p>

                </form>

            </div>
        </div>

    </div>
</template>