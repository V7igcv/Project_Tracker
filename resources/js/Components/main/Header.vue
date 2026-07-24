<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import {
    Search,
    Bell,
    UserCircle2,
} from 'lucide-vue-next';

const props = defineProps({
    projects: {
        type: Array,
        default: () => [],
    },
});

const search = ref('');
const showDropdown = ref(false);
const container = ref(null);

const filteredProjects = computed(() => {

    if (!search.value.trim()) {
        return [];
    }

    return props.projects.filter(project =>
        project.project_name
            .toLowerCase()
            .includes(search.value.toLowerCase())
    );

});

const goToProject = (project) => {

    search.value = '';

    showDropdown.value = false;

    router.visit(route('projects.phases', project.id));

};

const handleClickOutside = (event) => {

    if (
        container.value &&
        !container.value.contains(event.target)
    ) {
        showDropdown.value = false;
    }

};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <header
        class="flex h-[72px] items-center justify-between border-b border-gray-200 bg-white px-6"
    >
        <!-- Search -->
        <div
            ref="container"
            class="relative w-full max-w-md"
        >
            <div class="relative">
                <Search
                    class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                />

                <input
                    v-model="search"
                    type="text"
                    placeholder="Search projects..."
                    class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm focus:border-[#A76A00] focus:outline-none focus:ring-2 focus:ring-[#A76A00]/20"
                    @focus="showDropdown = true"
                />
                <div
                    v-if="showDropdown && filteredProjects.length"
                    class="absolute z-50 mt-2 max-h-64 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg"
                >

                    <button
                        v-for="project in filteredProjects"
                        :key="project.id"
                        @click="goToProject(project)"
                        class="flex w-full items-center px-4 py-3 text-left transition hover:bg-gray-100"
                    >

                        <Search
                            class="mr-3 h-4 w-4 text-[#A76A00]"
                        />

                        <span>
                            {{ project.project_name }}
                        </span>

                    </button>

                </div>
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