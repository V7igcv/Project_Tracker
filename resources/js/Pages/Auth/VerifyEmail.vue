<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import {
    FolderKanban,
    Mail,
    ArrowRight,
} from 'lucide-vue-next';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <Head title="Email Verification" />

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
                    Verify Your Email
                </h1>

                <p class="mt-6 text-lg text-[#FFEEDE]">
                    Confirm your email address to activate your account and gain
                    full access to your projects and workspace.
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
                        Verify Email
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Please verify your email before continuing.
                    </p>
                </div>

                <!-- Info Card -->
                <div
                    class="mb-6 rounded-lg border border-gray-200 p-4 text-sm text-gray-600"
                >
                    <div class="flex items-start gap-3">
                        <Mail
                            class="mt-0.5 h-5 w-5 shrink-0 text-[#A76A00]"
                        />

                        <div>
                            Thanks for signing up! Before getting started,
                            please verify your email address by clicking the
                            link we just emailed to you. If you didn't receive
                            the email, we'll gladly send another.
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div
                    v-if="verificationLinkSent"
                    class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-600"
                >
                    A new verification link has been sent to the email address
                    you provided during registration.
                </div>

                <form @submit.prevent="submit">

                    <div class="space-y-3">

                        <!-- Resend Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#A76A00] px-4 py-3 font-medium text-white transition hover:bg-[#8A5A00] disabled:opacity-50"
                        >
                            Resend Verification Email

                            <ArrowRight class="h-4 w-4" />
                        </button>

                        <!-- Logout Button -->
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                        >
                            Log Out
                        </Link>

                    </div>

                </form>

            </div>
        </div>

    </div>
</template>