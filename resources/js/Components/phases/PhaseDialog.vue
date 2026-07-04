<script setup>
import { reactive, computed, watch } from 'vue';

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

import { Textarea } from '@/Components/ui/textarea';

const props = defineProps({
    open: Boolean,

    mode: {
        type: String,
        default: 'create',
    },

    phase: {
        type: Object,
        default: () => ({
            phaseName: '',
            description: '',
        }),
    },
});

const emit = defineEmits([
    'update:open',
    'save',
]);

const form = reactive({
    phaseName: '',
    description: '',
});

watch(
    () => props.phase,
    (value) => {

        form.phaseName = value?.phaseName ?? '';
        form.description = value?.description ?? '';

    },
    {
        immediate: true,
    }
);

const dialogTitle = computed(() =>
    props.mode === 'create'
        ? 'Add New Phase'
        : 'Edit Phase'
);

const dialogDescription = computed(() =>
    props.mode === 'create'
        ? 'Create a new phase for this project.'
        : 'Update the phase information.'
);

const submitText = computed(() =>
    props.mode === 'create'
        ? 'Create Phase'
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
    @update:open="emit('update:open',$event)"
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

            <div>

                <InputLabel
                    value="Phase Name"
                    class="mb-2"
                />

                <TextInput
                    v-model="form.phaseName"
                    class="w-full"
                    placeholder="Enter phase name"
                />

                <InputError />

            </div>

            <div>

                <InputLabel
                    value="Description"
                    class="mb-2"
                />

                <Textarea
                    rows="5"
                    v-model="form.description"
                    placeholder="Enter phase description..."
                />

            </div>

        </div>

        <DialogFooter class="mt-6">

            <Button
                variant="outline"
                @click="emit('update:open',false)"
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