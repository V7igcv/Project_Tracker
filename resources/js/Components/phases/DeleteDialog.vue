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

    title: {
        type: String,
        default: '',
    },

    type: {
        type: String,
        default: 'phase',
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

                Delete {{ type }}?

            </AlertDialogTitle>

            <AlertDialogDescription>

                Are you sure you want to delete

                <span class="font-semibold text-gray-900">

                    "{{ title }}"

                </span>

                ?

                <br><br>

                <template v-if="type === 'phase'">

                    All tasks and progress inside this phase will also be permanently deleted.
                    This action cannot be undone.

                </template>

                <template v-else>

                    All recorded progress for this task will be permanently deleted.
                    This action cannot be undone.

                </template>

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

                Delete {{ type }}

            </AlertDialogAction>

        </AlertDialogFooter>

    </AlertDialogContent>

</AlertDialog>

</template>