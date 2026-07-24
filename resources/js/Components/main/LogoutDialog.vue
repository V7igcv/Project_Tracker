<script setup>
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/Components/ui/alert-dialog';

const props = defineProps({
    open: Boolean,
});

const emit = defineEmits([
    'update:open',
    'confirm',
]);
</script>

<template>

<AlertDialog
    :open="open"
    @update:open="emit('update:open', $event)"
>

    <AlertDialogContent>

        <AlertDialogHeader>

            <AlertDialogTitle>
                Log Out
            </AlertDialogTitle>

            <AlertDialogDescription>
                Are you sure you want to log out?
            </AlertDialogDescription>

        </AlertDialogHeader>

        <AlertDialogFooter>

            <AlertDialogCancel>
                Cancel
            </AlertDialogCancel>

            <AlertDialogAction
                class="bg-red-600 hover:bg-red-700"
                @click="emit('confirm')"
            >
                Log Out
            </AlertDialogAction>

        </AlertDialogFooter>

    </AlertDialogContent>

</AlertDialog>

</template>