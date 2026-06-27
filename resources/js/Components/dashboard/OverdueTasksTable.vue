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
</script>

<template>

    <TableContainer title="Overdue Tasks">

        <Table>

            <TableHeader>

                <TableRow>

                    <TableHead>Task Name</TableHead>
                    <TableHead>Project</TableHead>
                    <TableHead>Overdue At</TableHead>

                </TableRow>

            </TableHeader>

            <TableBody>

                <TableRow>

                    <TableCell>
                        UI Design
                    </TableCell>

                    <TableCell>
                        Project Tracker
                    </TableCell>

                    <TableCell class="font-medium text-red-600">
                        2 days overdue
                    </TableCell>

                </TableRow>

                <TableRow>

                    <TableCell>
                        Backend API
                    </TableCell>

                    <TableCell>
                        Capstone System
                    </TableCell>

                    <TableCell class="font-medium text-red-600">
                        5 days overdue
                    </TableCell>

                </TableRow>

            </TableBody>

        </Table>

    </TableContainer>

</template>