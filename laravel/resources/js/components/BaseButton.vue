<script setup lang="ts">
interface Props {
  variant?: 'primary' | 'secondary' | 'success' | 'danger' | 'warning' | 'ghost'
  size?: 'sm' | 'md' | 'lg'
  disabled?: boolean
  loading?: boolean
  type?: 'button' | 'submit' | 'reset'
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  size: 'md',
  disabled: false,
  loading: false,
  type: 'button'
})

const emit = defineEmits<{
  click: [event: MouseEvent]
}>()

const baseClasses = 'font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center gap-2'

const variantClasses = {
  primary: 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
  secondary: 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
  success: 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
  danger: 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
  warning: 'bg-yellow-600 hover:bg-yellow-700 text-white focus:ring-yellow-500',
  ghost: 'bg-transparent hover:bg-gray-100 text-gray-700 border border-gray-300 focus:ring-gray-500'
}

const sizeClasses = {
  sm: 'px-3 py-1.5 text-sm',
  md: 'px-4 py-2 text-sm',
  lg: 'px-6 py-3 text-base'
}

const disabledClasses = 'opacity-50 cursor-not-allowed'

const buttonClasses = [
  baseClasses,
  variantClasses[props.variant],
  sizeClasses[props.size],
  (props.disabled || props.loading) ? disabledClasses : ''
].join(' ')
</script>

<template>
  <button
    :type="type"
    :class="buttonClasses"
    :disabled="disabled || loading"
    @click="emit('click', $event)"
  >
    <svg
      v-if="loading"
      class="w-4 h-4 animate-spin"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      />
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      />
    </svg>
    <slot />
  </button>
</template>