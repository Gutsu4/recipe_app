<script setup lang="ts">
import { onMounted, ref } from "vue"
import { useRecipeRepository } from "@/composables/repository/recipeRepository.ts"

interface Recipe {
  id: number
  name: string
  description: string
  servings: number
  cooking_time: number
  calories: number
  protein: number
  fat: number
  carbs: number
  categories?: { id: number, name: string }[]
  ingredients?: { id: number, name: string, amount: string }[]
  steps?: { id: number, order: number, description: string }[]
}

const recipeRepository = useRecipeRepository()
const recipes = ref<Recipe[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const response = await recipeRepository.list()
    if (response.ok) {
      recipes.value = response.data as Recipe[]
    } else {
      error.value = 'レシピの取得に失敗しました'
    }
  } catch (err) {
    error.value = 'レシピの取得に失敗しました'
  } finally {
    loading.value = false
  }
})

const formatCookingTime = (minutes: number): string => {
  if (minutes >= 60) {
    const hours = Math.floor(minutes / 60)
    const remainingMinutes = minutes % 60
    return remainingMinutes > 0 ? `${hours}時間${remainingMinutes}分` : `${hours}時間`
  }
  return `${minutes}分`
}

const getCategoryNames = (recipe: Recipe): string => {
  if (!recipe.categories || recipe.categories.length === 0) {
    return '未分類'
  }
  return recipe.categories.map(cat => cat.name).join(', ')
}

const handleEdit = (recipeId: number) => {
  console.log('編集:', recipeId)
}

const handleDelete = async (recipeId: number) => {
  if (confirm('本当に削除しますか？')) {
    console.log('削除:', recipeId)
  }
}
</script>

<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b">
      <h2 class="text-xl font-bold text-gray-800">レシピ一覧</h2>
    </div>

    <div v-if="loading" class="p-6 text-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
      <p class="mt-2 text-gray-600">読み込み中...</p>
    </div>

    <div v-else-if="error" class="p-6 text-center">
      <p class="text-red-600">{{ error }}</p>
    </div>

    <div v-else-if="recipes.length === 0" class="p-6 text-center">
      <p class="text-gray-600">レシピが登録されていません</p>
    </div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              レシピ名
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              カテゴリ
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              人数分
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              調理時間
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              カロリー
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              栄養素
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              操作
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="recipe in recipes" :key="recipe.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">{{ recipe.name }}</div>
              <div class="text-sm text-gray-500 truncate max-w-xs" :title="recipe.description">
                {{ recipe.description }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ getCategoryNames(recipe) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ recipe.servings }}人分
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ formatCookingTime(recipe.cooking_time) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ recipe.calories }}kcal
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-xs text-gray-600">
                <div>P: {{ recipe.protein }}g</div>
                <div>F: {{ recipe.fat }}g</div>
                <div>C: {{ recipe.carbs }}g</div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-2">
                <button
                  @click="handleEdit(recipe.id)"
                  class="text-blue-600 hover:text-blue-900 transition-colors"
                >
                  編集
                </button>
                <button
                  @click="handleDelete(recipe.id)"
                  class="text-red-600 hover:text-red-900 transition-colors"
                >
                  削除
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
