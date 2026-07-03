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

import {
    getPriorityVariant,
    getPriorityLabel,
    getProgressColor,
    getPercentageColor,
    getProgressStatus,
} from '@/lib/projectUtils';

import { computed } from 'vue';

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
    'click',    
    'edit',
    'delete',
]);

const priorityVariant = computed(() =>
    getPriorityVariant(props.priority)
);

const priorityLabel = computed(() =>
    getPriorityLabel(props.priority)
);

const progressColor = computed(() =>
    getProgressColor(props.progress)
);

const percentageColor = computed(() =>
    getPercentageColor(props.progress)
);

const progressStatus = computed(() =>
    getProgressStatus(props.progress)
);
</script>

<template>
    <Card
        class="cursor-pointer transition-all duration-200 hover:-translate-y-1 hover:shadow-lg"
        @click="emit('click')"
    >
        <CardHeader class="pb-3">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <CardTitle class="text-xl font-semibold">
                        {{ title }}
                    </CardTitle>
                    <p class="mt-2 line-clamp-3 text-sm text-gray-500">
                        {{ description }}
                    </p>
                </div>

                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button 
                            class="rounded-md p-2 transition hover:bg-gray-100" 
                            @click.stop
                        >
                            <MoreVertical class="h-5 w-5" />
                        </button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="end">
                        <DropdownMenuItem @click.stop="emit('edit')">
                            <Pencil class="mr-2 h-4 w-4" />
                            Edit
                        </DropdownMenuItem>

                        <DropdownMenuItem class="text-red-600" @click.stop="emit('delete')">
                            <Trash2 class="mr-2 h-4 w-4" />
                            Delete
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </CardHeader>

        <!-- Divider -->
        <div class="mx-6 border-t border-grey/10"></div>

        <CardContent>
            <div class="mb-5">
                <Badge :variant="priorityVariant">
                    {{ priorityLabel }}
                </Badge>
            </div>

            <div class="space-y-2">
                <!-- Pass the dynamic color class to Progress -->
                <Progress 
                    :model-value="progress" 
                    :indicator-class="progressColor"
                />

                <div class="flex items-center justify-between text-sm">
                    <!-- Dynamic color for percentage -->
                    <span class="font-medium" :class="percentageColor">
                        {{ progress }}%
                    </span>

                    <span class="text-gray-500">
                        {{ progressStatus }}
                    </span>
                </div>
            </div>
        </CardContent>
    </Card>
</template>