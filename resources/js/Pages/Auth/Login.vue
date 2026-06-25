<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import {
    Mail,
    Lock,
    FolderKanban,
    ArrowRight,
} from 'lucide-vue-next';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />

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
                    Elevate Your Workflow
                </h1>

                <p class="mt-6 text-lg text-[#FFEEDE]">
                    Streamline project milestones, track real-time progress,
                    and empower your team with data-driven insights.
                </p>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div
            class="flex w-full lg:w-1/2 items-center justify-center px-6 py-12"
        >
            <div class="w-full max-w-md">

                <!-- Logo / Title -->
                <div class="mb-8">
                    <div class="flex items-center gap-2 text-[#A76A00]">
                        <FolderKanban class="h-5 w-5" />
                        <span class="font-semibold">
                            Project Tracker
                        </span>
                    </div>

                    <h2 class="mt-4 text-3xl font-bold text-gray-900">
                        Welcome Back
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Enter your credentials to access your dashboard.
                    </p>
                </div>

                <div
                    v-if="status"
                    class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-600"
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

                    <!-- Password -->
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <InputLabel
                                for="password"
                                value="Password"
                                class="uppercase tracking-wide text-xs"
                            />

                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-xs text-[#A76A00] hover:text-[#8A5A00]"
                            >
                                Forgot password?
                            </Link>
                        </div>

                        <div class="relative">
                            <Lock
                                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            />

                            <TextInput
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                class="block w-full pl-10"
                            />
                        </div>

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <!-- Remember Me -->
                    <div>
                        <label class="flex items-center">
                            <Checkbox
                                name="remember"
                                v-model:checked="form.remember"
                            />

                            <span class="ms-2 text-sm text-gray-600">
                                Remember me
                            </span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#A76A00] px-4 py-3 font-medium text-white transition hover:bg-[#8A5A00] disabled:opacity-50"
                    >
                        Login

                        <ArrowRight class="h-4 w-4" />
                    </button>

                    <!-- Register -->
                    <p class="pt-4 text-center text-sm text-gray-600">
                        Don't have an account?

                        <Link
                            :href="route('register')"
                            class="font-medium text-[#A76A00] hover:text-[#8A5A00]"
                        >
                            Sign up
                        </Link>
                    </p>

                </form>
            </div>
        </div>
    </div>
</template>