<script setup>
import { computed, reactive, watch } from 'vue';

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/Components/ui/dialog';

import { Button } from '@/Components/ui/button';

import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    open: {
        type: Boolean,
        required: true,
    },

    mode: {
        type: String,
        default: 'create',
    },

    task: {
        type: Object,
        default: () => ({
            taskName: '',
            startDate: '',
            endDate: '',
        }),
    },
});

const emit = defineEmits([
    'update:open',
    'save',
]);

const form = reactive({
    taskName: '',
    startDate: '',
    endDate: '',
});

watch(
    () => props.task,
    (value) => {
        form.taskName = value?.taskName ?? '';
        form.startDate = value?.startDate ?? '';
        form.endDate = value?.endDate ?? '';
    },
    {
        immediate: true,
    }
);

const dialogTitle = computed(() =>
    props.mode === 'create'
        ? 'Add New Task'
        : 'Edit Task'
);

const dialogDescription = computed(() =>
    props.mode === 'create'
        ? 'Create a task inside this phase.'
        : 'Update this task.'
);

const submitText = computed(() =>
    props.mode === 'create'
        ? 'Create Task'
        : 'Save Changes'
);

const submit = () => {
    emit('save', {
        ...form,
    });
};
</script>

<template>

<Dialog
    :open="open"
    @update:open="emit('update:open', $event)"
>

    <DialogContent class="sm:max-w-lg">

        <DialogHeader>

            <DialogTitle>
                {{ dialogTitle }}
            </DialogTitle>

            <DialogDescription>
                {{ dialogDescription }}
            </DialogDescription>

        </DialogHeader>

        <div class="space-y-6">

            <!-- Task Name -->

            <div>

                <InputLabel
                    value="Task Name"
                    class="mb-2"
                />

                <TextInput
                    v-model="form.taskName"
                    class="w-full"
                    placeholder="Enter task name"
                />

                <InputError />

            </div>

            <!-- Start Date -->

            <div>

                <InputLabel
                    value="Start Date"
                    class="mb-2"
                />

                <TextInput
                    v-model="form.startDate"
                    type="date"
                    class="w-full"
                />

            </div>

            <!-- End Date -->

            <div>

                <InputLabel
                    value="End Date"
                    class="mb-2"
                />

                <TextInput
                    v-model="form.endDate"
                    type="date"
                    class="w-full"
                />

            </div>

        </div>

        <DialogFooter class="mt-6">

            <Button
                variant="outline"
                @click="emit('update:open', false)"
            >
                Cancel
            </Button>

            <Button
                class="bg-[#A76A00] hover:bg-[#8A5A00]"
                @click="submit"
            >
                {{ submitText }}
            </Button>

        </DialogFooter>

    </DialogContent>

</Dialog>

</template>