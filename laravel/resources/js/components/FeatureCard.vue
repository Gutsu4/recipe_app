<script setup lang="ts">
defineProps<{
  title: string;
  description: string;
}>();
</script>

<template>
  <div class="p-4 bg-white rounded-xl shadow hover:shadow-lg transition duration-300">
    <h3 class="text-xl font-bold text-primary-600">{{ title }}</h3>
    <p class="text-gray-600 mt-2 text-sm">{{ description }}</p>
  </div>
</template>
