<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import {
    User,
    Mail,
    Lock,
    FolderKanban,
    ArrowRight,
} from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () =>
            form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

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
                    Start Managing Projects Better
                </h1>

                <p class="mt-6 text-lg text-[#FFEEDE]">
                    Create your account and organize tasks, monitor progress,
                    and collaborate efficiently with your team.
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
                        Create Account
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Fill in your details to create a new account.
                    </p>
                </div>

                <form
                    @submit.prevent="submit"
                    class="space-y-5"
                >

                    <!-- Name -->
                    <div>
                        <InputLabel
                            for="name"
                            value="Full Name"
                            class="mb-2 uppercase tracking-wide text-xs"
                        />

                        <div class="relative">
                            <User
                                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            />

                            <TextInput
                                id="name"
                                type="text"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="John Doe"
                                class="block w-full pl-10"
                            />
                        </div>

                        <InputError
                            class="mt-2"
                            :message="form.errors.name"
                        />
                    </div>

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
                                autocomplete="new-password"
                                class="block w-full pl-10"
                            />
                        </div>

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <InputLabel
                            for="password_confirmation"
                            value="Confirm Password"
                            class="mb-2 uppercase tracking-wide text-xs"
                        />

                        <div class="relative">
                            <Lock
                                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            />

                            <TextInput
                                id="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                class="block w-full pl-10"
                            />
                        </div>

                        <InputError
                            class="mt-2"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <!-- Register Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#A76A00] px-4 py-3 font-medium text-white transition hover:bg-[#8A5A00] disabled:opacity-50"
                    >
                        Create Account

                        <ArrowRight class="h-4 w-4" />
                    </button>

                    <!-- Login Link -->
                    <p class="pt-4 text-center text-sm text-gray-600">
                        Already have an account?

                        <Link
                            :href="route('login')"
                            class="font-medium text-[#A76A00] hover:text-[#8A5A00]"
                        >
                            Log in
                        </Link>
                    </p>

                </form>
            </div>
        </div>
    </div>
</template>