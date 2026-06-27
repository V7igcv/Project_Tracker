<script setup>
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card';

import { Badge } from '@/Components/ui/badge';
import { Progress } from '@/Components/ui/progress';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';

import {
    MoreVertical,
    Pencil,
    Trash2,
} from 'lucide-vue-next';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },

    description: {
        type: String,
        default: '',
    },

    priority: {
        type: String,
        default: 'Medium',
    },

    progress: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits([
    'edit',
    'delete',
]);

const priorityBadge = () => {
    switch (props.priority) {
        case 'High':
            return 'destructive';

        case 'Low':
            return 'secondary';

        default:
            return 'default';
    }
};

const progressStatus = () => {
    if (props.progress === 0)
        return 'Not Yet Started';

    if (props.progress === 100)
        return 'Completed';

    return 'In Progress';
};
</script>

<template>
    <Card
        class="transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
    >
        <CardHeader class="pb-4">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <CardTitle
                        class="text-xl font-semibold"
                    >
                        {{ title }}
                    </CardTitle>

                    <p
                        class="mt-2 line-clamp-3 text-sm text-gray-500"
                    >
                        {{ description }}
                    </p>

                </div>

                <DropdownMenu>

                    <DropdownMenuTrigger as-child>

                        <button
                            class="rounded-md p-2 transition hover:bg-gray-100"
                        >
                            <MoreVertical class="h-5 w-5" />
                        </button>

                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="end">

                        <DropdownMenuItem
                            @click="emit('edit')"
                        >
                            <Pencil class="mr-2 h-4 w-4" />

                            Edit
                        </DropdownMenuItem>

                        <DropdownMenuItem
                            class="text-red-600"
                            @click="emit('delete')"
                        >
                            <Trash2 class="mr-2 h-4 w-4" />

                            Delete
                        </DropdownMenuItem>

                    </DropdownMenuContent>

                </DropdownMenu>

            </div>

        </CardHeader>

        <CardContent>

            <div class="mb-5">

                <Badge
                    :variant="priorityBadge()"
                >
                    {{ priority }}
                </Badge>

            </div>

            <div class="space-y-2">

                <Progress
                    :model-value="progress"
                />

                <div
                    class="flex items-center justify-between text-sm"
                >

                    <span class="font-medium">
                        {{ progress }}%
                    </span>

                    <span class="text-gray-500">
                        {{ progressStatus() }}
                    </span>

                </div>

            </div>

        </CardContent>

    </Card>
</template>