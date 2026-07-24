<script setup>
import axios from 'axios';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
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

const page = usePage();

const user = computed(() => page.props.auth.user);

const notifications = ref([]);

const showNotifications = ref(false);

const notificationContainer = ref(null);

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

const loadNotifications = async () => {

    try {

        const response = await axios.get(
            route('notifications.index')
        );

        notifications.value = response.data;

    } catch (error) {

        console.error(
            'Failed to load notifications.',
            error
        );

    }

};

const toggleNotifications = () => {

    showNotifications.value =
        !showNotifications.value;

};

const openNotification = (notification) => {

    showNotifications.value = false;

    router.visit(
        route(
            'projects.phases',
            notification.project_id
        )
    );

};

const handleClickOutside = (event) => {

    if (
        container.value &&
        !container.value.contains(event.target)
    ) {

        showDropdown.value = false;

    }

    if (
        notificationContainer.value &&
        !notificationContainer.value.contains(event.target)
    ) {

        showNotifications.value = false;

    }

};

onMounted(() => {

    loadNotifications();

    document.addEventListener(
        'click',
        handleClickOutside
    );

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
            <div
                ref="notificationContainer"
                class="relative"
            >

                <button
                    class="relative rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-[#A76A00]"
                    @click="toggleNotifications"
                >

                    <Bell class="h-5 w-5" />

                    <span
                        v-if="notifications.length"
                        class="absolute right-1 top-1 h-2.5 w-2.5 rounded-full bg-red-500"
                    ></span>

                </button>

                <div
                    v-if="showNotifications"
                    class="absolute right-0 z-50 mt-2 max-h-[420px] w-96 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-xl"
                >

                    <div
                        class="flex items-center justify-between border-b px-4 py-3"
                    >

                        <h3 class="font-semibold text-gray-900">
                            Notifications
                        </h3>

                        <span
                            class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600"
                        >
                            {{ notifications.length }}
                        </span>

                    </div>

                    <div
                        v-if="notifications.length === 0"
                        class="flex flex-col items-center justify-center p-8 text-center"
                    >

                        <Bell
                            class="mb-2 h-8 w-8 text-gray-300"
                        />

                        <p
                            class="text-sm font-medium text-gray-700"
                        >
                            No notifications
                        </p>

                        <p
                            class="mt-1 text-xs text-gray-500"
                        >
                            You're all caught up.
                        </p>

                    </div>

                    <button
                        v-for="notification in notifications"
                        :key="notification.id"
                        @click="openNotification(notification)"
                        class="flex w-full items-start gap-3 border-b px-4 py-3 text-left transition hover:bg-gray-50"
                    >

                        <div
                            class="mt-1 h-3 w-3 shrink-0 rounded-full"
                            :class="
                                notification.severity === 'danger'
                                    ? 'bg-red-500'
                                    : 'bg-yellow-400'
                            "
                        ></div>

                        <div class="min-w-0 flex-1">

                            <p class="text-sm text-gray-800">
                                {{ notification.message }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                {{ notification.project_name }}
                            </p>

                        </div>

                    </button>

                </div>

            </div>

            <!-- Divider -->
            <div class="h-8 w-px bg-gray-300"></div>

            <!-- Profile -->
            <Link
                :href="route('profile.edit')"
                class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-gray-100"
            >
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">
                        {{ user.name }}
                    </p>

                    <p class="text-xs text-gray-500">
                        {{ user.email }}
                    </p>
                </div>

                <div
                    class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full"
                >
                    <img
                        v-if="user.profile_photo"
                        :src="`/storage/${user.profile_photo}`"
                        alt="Profile Picture"
                        class="h-full w-full object-cover"
                    >

                    <UserCircle2
                        v-else
                        class="h-10 w-10 text-[#A76A00]"
                    />
                </div>
            </Link>

        </div>
    </header>
</template>