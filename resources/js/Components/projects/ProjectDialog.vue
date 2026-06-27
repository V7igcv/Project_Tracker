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

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';

import { Textarea } from '@/Components/ui/textarea';

const props = defineProps({
    open: {
        type: Boolean,
        required: true,
    },

    mode: {
        type: String,
        default: 'create',
    },

    project: {
        type: Object,
        default: () => ({
            name: '',
            priority: 'Medium',
            description: '',
        }),
    },
});

const emit = defineEmits([
    'update:open',
    'save',
]);

const form = reactive({
    name: '',
    priority: 'Medium',
    description: '',
});

watch(
    () => props.project,
    (value) => {
        form.name = value?.name ?? '';
        form.priority = value?.priority ?? 'Medium';
        form.description = value?.description ?? '';
    },
    {
        immediate: true,
    }
);

const dialogTitle = computed(() =>
    props.mode === 'create'
        ? 'Add New Project'
        : 'Edit Project'
);

const dialogDescription = computed(() =>
    props.mode === 'create'
        ? 'Create a new project to begin tracking progress.'
        : 'Update your project information.'
);

const submitText = computed(() =>
    props.mode === 'create'
        ? 'Create Project'
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

                <!-- Project Name -->

                <div>

                    <InputLabel
                        value="Project Name"
                        class="mb-2"
                    />

                    <TextInput
                        v-model="form.name"
                        class="w-full"
                        placeholder="Enter project name"
                    />

                    <InputError />

                </div>

                <!-- Priority -->

                <div>

                    <InputLabel
                        value="Priority"
                        class="mb-2"
                    />

                    <Select
                        v-model="form.priority"
                    >

                        <SelectTrigger class="w-full">

                            <SelectValue />

                        </SelectTrigger>

                        <SelectContent>

                            <SelectItem value="Low">
                                Low
                            </SelectItem>

                            <SelectItem value="Medium">
                                Medium
                            </SelectItem>

                            <SelectItem value="High">
                                High
                            </SelectItem>

                        </SelectContent>

                    </Select>

                </div>

                <!-- Description -->

                <div>

                    <InputLabel
                        value="Description"
                        class="mb-2"
                    />

                    <Textarea
                        v-model="form.description"
                        rows="5"
                        placeholder="Enter project description..."
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