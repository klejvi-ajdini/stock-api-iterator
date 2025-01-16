<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    stocks: {
        type: Array,
        required: true,
    },
    symbol: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Stock Data" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Stock Data for {{ symbol }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <ul v-if="stocks.length > 0" class="space-y-4">
                            <li
                                v-for="(stock, index) in stocks"
                                :key="index"
                                class="p-4 border rounded-lg shadow-sm dark:border-gray-700"
                            >
                                <p class="text-lg">
                                    <span class="font-semibold">Date:</span> {{ stock.date }}
                                </p>
                                <p>
                                    <span class="font-semibold">Open:</span> {{ stock.open }}
                                </p>
                                <p>
                                    <span class="font-semibold">High:</span> {{ stock.high }}
                                </p>
                                <p>
                                    <span class="font-semibold">Low:</span> {{ stock.low }}
                                </p>
                                <p>
                                    <span class="font-semibold">Close:</span> {{ stock.close }}
                                </p>
                            </li>
                        </ul>
                        <p v-else class="text-red-500">No stock data found.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
