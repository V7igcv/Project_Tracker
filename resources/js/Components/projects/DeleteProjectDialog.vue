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
    open: {
        type: Boolean,
        required: true,
    },

    projectName: {
        type: String,
        default: '',
    },
});

const emit = defineEmits([
    'update:open',
    'confirm',
]);

const handleConfirm = () => {
    emit('confirm');
    emit('update:open', false);
};
</script>

<template>

    <AlertDialog
        :open="open"
        @update:open="emit('update:open', $event)"
    >

        <AlertDialogContent>

            <AlertDialogHeader>

                <AlertDialogTitle>
                    Delete Project?
                </AlertDialogTitle>

                <AlertDialogDescription>

                    Are you sure you want to delete

                    <span class="font-semibold text-gray-900">
                        "{{ projectName }}"
                    </span>

                    ?

                    <br><br>

                    All project phases, tasks, and progress data will be permanently deleted.
                    This action cannot be undone.

                </AlertDialogDescription>

            </AlertDialogHeader>

            <AlertDialogFooter>

                <AlertDialogCancel>
                    Cancel
                </AlertDialogCancel>

                <AlertDialogAction
                    variant="destructive"
                    @click="handleConfirm"
                >
                    Delete Project
                </AlertDialogAction>

            </AlertDialogFooter>

        </AlertDialogContent>

    </AlertDialog>

</template>