<script setup lang="ts">
const props = defineProps<{
    size?: 'sm' | 'lg'
}>()

const sizeMap: Record<'sm' | 'lg', number> = {
    sm: 24,
    lg: 48,
}

const size = props.size ? sizeMap[props.size] : sizeMap.sm
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        :width="size"
        :height="size"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="lucide lucide-plus bg-primary-500 text-white rounded-full"
    >
        <path d="M5 12h14"/>
        <path d="M12 5v14"/>
    </svg>
</template>
