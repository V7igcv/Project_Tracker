<script setup>
import TableContainer from '@/Components/common/TableContainer.vue';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';

import { Progress } from '@/Components/ui/progress';
import { Badge } from '@/Components/ui/badge';
import {
    getPriorityVariant,
    getPriorityLabel,
    getProgressColor,
    getPercentageColor,
} from '@/lib/projectUtils';

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <TableContainer title="All Projects">

        <Table>

            <TableHeader>
                <TableRow>
                    <TableHead class="font-extrabold">NAME</TableHead>
                    <TableHead class="w-[320px] font-extrabold">PROGRESS</TableHead>
                    <TableHead class="font-extrabold">PRIORITY</TableHead>
                    <TableHead class="font-extrabold">LAST UPDATED</TableHead>
                </TableRow>
            </TableHeader>

            <TableBody>

                <TableRow v-if="props.projects.length === 0">
                    <TableCell colspan="4" class="py-8 text-center text-gray-500">
                        No active projects.
                    </TableCell>
                </TableRow>

                <TableRow
                    v-else
                    v-for="project in props.projects"
                    :key="project.id"
                >

                    <TableCell class="font-medium">
                        {{ project.project_name }}
                    </TableCell>

                    <TableCell>

                        <div class="space-y-2">

                            <Progress 
                                :model-value="project.progress_percentage" 
                                :indicator-class="getProgressColor(project.progress_percentage)"
                            />

                            <p 
                                class="text-xs font-semibold"
                                :class="getPercentageColor(project.progress_percentage)"
                            >
                                {{ project.progress_percentage }}%
                            </p>

                        </div>

                    </TableCell>

                    <TableCell>
                        <Badge :variant="getPriorityVariant(project.priority)">
                            {{ getPriorityLabel(project.priority) }}
                        </Badge>
                    </TableCell>

                    <TableCell>
                        {{ project.last_updated_human }}
                    </TableCell>

                </TableRow>

            </TableBody>

        </Table>

    </TableContainer>
</template>