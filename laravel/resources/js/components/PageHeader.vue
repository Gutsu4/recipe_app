<script setup lang="ts">
import { useRouter } from 'vue-router'

interface Props {
  title: string
  showBackButton?: boolean
  backUrl?: string
}

const props = withDefaults(defineProps<Props>(), {
  showBackButton: false,
  backUrl: '/'
})

const router = useRouter()

const goBack = () => {
  if (props.backUrl) {
    router.push(props.backUrl)
  } else {
    router.back()
  }
}
</script>

<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-4 flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <button
          v-if="showBackButton"
          @click="goBack"
          class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
          aria-label="戻る"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
      </div>
      <div class="flex items-center space-x-3">
        <slot name="actions" />
      </div>
    </div>
  </div>
</template>