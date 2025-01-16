<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    quote: {
        type: Object,
        required: true,
    },
    symbol: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Quote Data" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Quote Data for {{ symbol }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="quote">
                            <p class="text-lg">
                                <span class="font-semibold">Price:</span> {{ quote['05. price'] }}
                            </p>
                            <p class="text-lg">
                                <span class="font-semibold">Change:</span> {{ quote['09. change'] }}
                            </p>
                            <p class="text-lg">
                                <span class="font-semibold">Change Percent:</span> {{ quote['10. change percent'] }}
                            </p>
                        </div>
                        <p v-else class="text-red-500">No quote data found.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
