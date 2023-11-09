<template>
    <Head title="Home"/>

    <GuestLayout>
        <form
            @submit.prevent="form.post(route('subscription.store'), { onSuccess: () => form.reset() })"
            class="flex flex-col gap-4 items-center"
        >
            <TextInput
                v-model="form.email"
                placeholder="Email"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            ></TextInput>
            <InputError :message="form.errors.email" class="mt-2"/>

            <template v-if="!form.isPercentageBased">
                <TextInput
                    v-model="form.price"
                    placeholder="Price subscription"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></TextInput>
                <InputError :message="form.errors.price" class="mt-2"/>
            </template>

            <template v-if="form.isPercentageBased">
                <TextInput
                    v-model="form.percentage"
                    placeholder="Price percentage based subscription"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></TextInput>
                <InputError :message="form.errors.percentage" class="mt-2"/>
            </template>

            <label class="flex items-center">
                <Checkbox name="isPercentageBased" v-model:checked="form.isPercentageBased"/>
                <span class="ml-2 text-sm text-gray-600">Percentage based</span>
            </label>

            <div class="flex flex-col items-center">
                <p>Choose your currency</p>
                <div class="flex justify-center gap-4">
                    <template
                        v-for="currency in currencies"
                        :key="currency"
                    >
                        <div class="flex items-center gap-2 hover:cursor-pointer">
                            <input type="radio" :id="currency" :value="currency" v-model="form.currency"/>
                            <label :for="currency">{{ currency }}</label>
                        </div>
                    </template>
                </div>
                <InputError :message="form.errors.currency" class="mt-2"/>
            </div>

            <template v-if="form.isPercentageBased">
                <div class="flex flex-col items-center">
                    <p>Choose your time interval</p>
                    <div class="flex justify-center gap-4">
                        <template
                            v-for="(interval, value) in intervals"
                            :key="interval"
                        >
                            <div class="flex items-center gap-2 hover:cursor-pointer">
                                <input type="radio" :id="interval" :value="value" v-model="form.interval"/>
                                <label :for="interval">{{ interval }}</label>
                            </div>
                        </template>
                    </div>
                    <InputError :message="form.errors.interval" class="mt-2"/>
                </div>
            </template>

            <PrimaryButton class="mt-4 text-center">Subscribe</PrimaryButton>
        </form>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {useForm, Head} from '@inertiajs/vue3';
import Checkbox from "@/Components/Checkbox.vue";

defineProps(['currencies', 'intervals'])

const form = useForm({
    email: '',
    price: '',
    currency: '',
    isPercentageBased: false,
    percentage: '',
    interval: ''
});
</script>

<style scoped>

</style>
