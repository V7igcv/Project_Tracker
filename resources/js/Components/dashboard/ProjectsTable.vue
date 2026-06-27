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
</script>

<template>
    <TableContainer title="All Projects">

        <Table>

            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead class="w-[320px]">Progress</TableHead>
                    <TableHead>Priority</TableHead>
                    <TableHead>Last Updated</TableHead>
                </TableRow>
            </TableHeader>

            <TableBody>

                <TableRow>

                    <TableCell class="font-medium">
                        Project Tracker
                    </TableCell>

                    <TableCell>

                        <div class="space-y-2">

                            <Progress :model-value="65" />

                            <p class="text-xs text-gray-500">
                                65%
                            </p>

                        </div>

                    </TableCell>

                    <TableCell>
                            High
                    </TableCell>

                    <TableCell>
                        2 hours ago
                    </TableCell>

                </TableRow>

                <TableRow>

                    <TableCell class="font-medium">
                        Capstone System
                    </TableCell>

                    <TableCell>

                        <div class="space-y-2">

                            <Progress :model-value="35" />

                            <p class="text-xs text-gray-500">
                                35%
                            </p>

                        </div>

                    </TableCell>

                    <TableCell>
                            Medium
                    </TableCell>

                    <TableCell>
                        Yesterday
                    </TableCell>

                </TableRow>

            </TableBody>

        </Table>

    </TableContainer>
</template>