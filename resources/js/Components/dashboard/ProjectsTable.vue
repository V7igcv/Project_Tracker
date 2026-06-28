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

                <TableRow>

                    <TableCell class="font-medium">
                        Project Tracker
                    </TableCell>

                    <TableCell>

                        <div class="space-y-2">

                            <Progress 
                                :model-value="65" 
                                :indicator-class="getProgressColor(65)"
                            />

                            <p 
                                class="text-xs font-semibold"
                                :class="getPercentageColor(65)"
                            >
                                65%
                            </p>

                        </div>

                    </TableCell>

                    <TableCell>
                        <Badge :variant="getPriorityVariant('High')">
                            {{ getPriorityLabel('High') }}
                        </Badge>
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

                            <Progress 
                                :model-value="35" 
                                :indicator-class="getProgressColor(35)"
                            />

                            <p 
                                class="text-xs font-semibold"
                                :class="getPercentageColor(35)"
                            >
                                35%
                            </p>

                        </div>

                    </TableCell>

                    <TableCell>
                        <Badge :variant="getPriorityVariant('Medium')">
                            {{ getPriorityLabel('Medium') }}
                        </Badge>
                    </TableCell>

                    <TableCell>
                        Yesterday
                    </TableCell>

                </TableRow>

            </TableBody>

        </Table>

    </TableContainer>
</template>