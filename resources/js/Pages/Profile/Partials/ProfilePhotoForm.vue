<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { UserCircle2 } from 'lucide-vue-next';

const props = defineProps({
    user: Object,
});

const fileInput = ref(null);
const preview = ref(null);

const form = useForm({
    profile_photo: null,
});

const selectPhoto = () => {
    fileInput.value.click();
};

const updatePreview = (event) => {
    const file = event.target.files[0];

    if (!file) {
        return;
    }

    form.profile_photo = file;

    const reader = new FileReader();

    reader.onload = (e) => {
        preview.value = e.target.result;
    };

    reader.readAsDataURL(file);
};

const submit = () => {
    form.post(route('profile.photo'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            preview.value = null;
            form.reset('profile_photo');
        },
    });
};
</script>

<template>

<section>

    <header>

        <h2 class="text-lg font-semibold text-gray-900">
            Profile Picture
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Upload a profile picture that will appear throughout the application.
        </p>

    </header>

    <div class="mt-6 flex items-center gap-6">

        <div>

            <img
                v-if="preview || user.profile_photo_url"
                :src="preview || user.profile_photo_url"
                class="h-28 w-28 rounded-full border object-cover"
            >

            <UserCircle2
                v-else
                class="h-28 w-28 text-[#A76A00]"
            />

        </div>

        <div>

            <input
                ref="fileInput"
                type="file"
                class="hidden"
                accept="image/*"
                @change="updatePreview"
            >

            <button
                type="button"
                class="rounded-md bg-[#A76A00] px-4 py-2 text-white transition hover:bg-[#8A5A00]"
                @click="selectPhoto"
            >
                Choose Photo
            </button>

            <button
                v-if="form.profile_photo"
                type="button"
                class="ml-3 rounded-md border border-[#A76A00] px-4 py-2 text-[#A76A00] transition hover:bg-[#A76A00] hover:text-white"
                @click="submit"
            >
                Save Photo
            </button>

            <p
                v-if="form.errors.profile_photo"
                class="mt-2 text-sm text-red-600"
            >
                {{ form.errors.profile_photo }}
            </p>

        </div>

    </div>

</section>

</template>