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

    <TableContainer title="Upcoming Deadlines">

        <Table>

            <TableHeader>

                <TableRow>

                    <TableHead class="font-extrabold">TASK NAME</TableHead>
                    <TableHead class="font-extrabold">PROJECT</TableHead>
                    <TableHead class="font-extrabold">DUE</TableHead>

                </TableRow>

            </TableHeader>

            <TableBody>

                <TableRow>

                    <TableCell>
                        Testing
                    </TableCell>

                    <TableCell>
                        Project Tracker
                    </TableCell>

                    <TableCell class="font-medium">
                        Tomorrow
                    </TableCell>

                </TableRow>

                <TableRow>

                    <TableCell>
                        Documentation
                    </TableCell>

                    <TableCell>
                        Capstone System
                    </TableCell>

                    <TableCell class="font-medium">
                        3 days
                    </TableCell>

                </TableRow>

            </TableBody>

        </Table>

    </TableContainer>

</template>