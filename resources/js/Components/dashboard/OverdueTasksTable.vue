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

const props = defineProps({
    tasks: {
        type: Array,
        required: true,
    },
});
</script>

<template>

    <TableContainer title="Overdue Tasks">

        <Table>

            <TableHeader>

                <TableRow>

                    <TableHead class="font-extrabold">TASK NAME</TableHead>
                    <TableHead class="font-extrabold">PROJECT</TableHead>
                    <TableHead class="font-extrabold">OVERDUE AT</TableHead>

                </TableRow>

            </TableHeader>

            <TableBody>

                <TableRow v-if="props.tasks.length === 0">
                    <TableCell colspan="3" class="py-8 text-center text-gray-500">
                        No overdue tasks. Great job!
                    </TableCell>
                </TableRow>

                <TableRow
                    v-else
                    v-for="task in props.tasks"
                    :key="task.id"
                >

                    <TableCell>
                        {{ task.task_name }}
                    </TableCell>

                    <TableCell>
                        {{ task.phase?.project?.project_name }}
                    </TableCell>

                    <TableCell class="font-medium text-red-600">
                        {{ task.overdue_human }}
                    </TableCell>

                </TableRow>

            </TableBody>

        </Table>

    </TableContainer>

</template>