<script setup lang="ts">
import {onMounted, ref} from "vue"
import {useRouter} from "vue-router"
import {useRecipeRepository} from "@/composables/repository/recipeRepository.ts"

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

const router = useRouter()
const recipeRepository = useRecipeRepository()
const recipes = ref<Recipe[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const orderBy = ref('id')
const order = ref<'asc' | 'desc'>('desc')

const fetchRecipes = async () => {
    loading.value = true
    try {
        const response = await recipeRepository.list(orderBy.value, order.value)
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
}

const handleSort = (column: string) => {
    if (orderBy.value === column) {
        order.value = order.value === 'asc' ? 'desc' : 'asc'
    } else {
        orderBy.value = column
        order.value = 'asc'
    }
    fetchRecipes()
}

const getSortIcon = (column: string) => {
    if (orderBy.value !== column) {
        return `<svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
        </svg>`
    }
    if (order.value === 'asc') {
        return `<svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
        </svg>`
    }
    return `<svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
    </svg>`
}

const getSortClass = (column: string) => {
    const baseClass = 'px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer transition-colors select-none'
    if (orderBy.value === column) {
        return `${baseClass} bg-blue-50 text-blue-700 hover:bg-blue-100`
    }
    return `${baseClass} text-gray-500 hover:bg-gray-100 hover:text-gray-700`
}

const getSortLabel = () => {
    const columnLabels: Record<string, string> = {
        id: 'ID',
        name: 'レシピ名',
        calories: 'カロリー',
        cookingTime: '調理時間'
    }
    const columnLabel = columnLabels[orderBy.value] || orderBy.value
    const orderLabel = order.value === 'asc' ? '昇順' : '降順'
    return `${columnLabel} (${orderLabel})`
}

onMounted(() => {
    fetchRecipes()
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

const handleDetail = (recipeId: number) => {
    router.push(`/detail/${recipeId}`)
}

const handleDelete = async (recipeId: number) => {
    if (confirm('本当に削除しますか？')) {
        try {
            await recipeRepository.delete(recipeId)
            // レシピリストから削除されたアイテムを取り除く
            recipes.value = recipes.value.filter(recipe => recipe.id !== recipeId)
            alert('レシピが削除されました')
        } catch (error) {
            console.error('削除に失敗しました:', error)
            alert('削除に失敗しました')
        }
    }
}
</script>

<template>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">レシピ一覧</h2>
                <div class="text-sm text-gray-600 flex items-center">
                    <span class="mr-2">並び順:</span>
                    <div class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                        {{ getSortLabel() }}
                    </div>
                </div>
            </div>
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
                    <th :class="getSortClass('id')" @click="handleSort('id')">
                        <div class="flex items-center justify-between group">
                            <span>ID</span>
                            <span class="ml-2" v-html="getSortIcon('id')"></span>
                        </div>
                    </th>
                    <th :class="getSortClass('name')" @click="handleSort('name')">
                        <div class="flex items-center justify-between group">
                            <span>レシピ名</span>
                            <span class="ml-2" v-html="getSortIcon('name')"></span>
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        カテゴリ
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        人数分
                    </th>
                    <th :class="getSortClass('cookingTime')" @click="handleSort('cookingTime')">
                        <div class="flex items-center justify-between group">
                            <span>調理時間</span>
                            <span class="ml-2" v-html="getSortIcon('cookingTime')"></span>
                        </div>
                    </th>
                    <th :class="getSortClass('calories')" @click="handleSort('calories')">
                        <div class="flex items-center justify-between group">
                            <span>カロリー</span>
                            <span class="ml-2" v-html="getSortIcon('calories')"></span>
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        操作
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="recipe in recipes" :key="recipe.id" class="hover:bg-gray-50 cursor-pointer" @click="handleDetail(recipe.id)">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                        {{ recipe.id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ recipe.name }}</div>
                        <div class="text-sm text-gray-500 truncate max-w-xs" :title="recipe.description">
                            {{ recipe.description }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
              <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button
                            @click.stop="handleDelete(recipe.id)"
                            class="text-red-600 hover:text-red-900 transition-colors"
                        >
                            削除
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
