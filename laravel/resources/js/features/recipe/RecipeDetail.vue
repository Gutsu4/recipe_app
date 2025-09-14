<script setup>
import {ref, onMounted} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import {useRecipeRepository} from '@/composables/repository/recipeRepository.js'
import PageHeader from '@/components/PageHeader.vue'
import BaseButton from '@/components/BaseButton.vue'

const route = useRoute()
const router = useRouter()
const recipeRepository = useRecipeRepository(router)

const recipe = ref({})
const isEditing = ref(false)
const editForm = ref({})

const ingredients = ref([])
const steps = ref([])
const selectedCategories = ref([])
const availableCategories = ref([
    '主食', '主菜', '副菜', '汁物', 'デザート', 'サラダ', '前菜', '飲み物'
])

const startEdit = () => {
    isEditing.value = true
    editForm.value = {
        name: recipe.value.name || '',
        description: recipe.value.description || '',
        servings: recipe.value.servings || 1,
        cooking_time: recipe.value.cooking_time || 0,
        calories: recipe.value.calories || 0,
        protein: recipe.value.protein || 0,
        fat: recipe.value.fat || 0,
        carbs: recipe.value.carbs || 0,
        fiber: recipe.value.fiber || 0,
        sodium: recipe.value.sodium || 0,
        sugar: recipe.value.sugar || 0
    }
    ingredients.value = recipe.value.ingredients?.map(ing => ({
        name: ing.name,
        amount: ing.amount
    })) || []
    steps.value = recipe.value.steps?.map(step => ({
        instruction: step.instruction
    })) || []
    selectedCategories.value = recipe.value.categories?.map(cat => cat.name) || []
}

const cancelEdit = () => {
    isEditing.value = false
    editForm.value = {}
    ingredients.value = []
    steps.value = []
    selectedCategories.value = []
}

const saveEdit = async () => {
    try {
        const updateData = {
            ...editForm.value,
            ingredients: ingredients.value,
            steps: steps.value,
            categories: selectedCategories.value
        }

        const response = await recipeRepository.update(route.params.id, updateData)
        recipe.value = response.data
        isEditing.value = false
        alert('レシピが更新されました！')
    } catch (error) {
        alert('レシピの更新に失敗しました')
    }
}

const fetchRecipe = async () => {
    try {
        const response = await recipeRepository.show(route.params.id)
        recipe.value = response.data
    } catch (error) {
        alert('レシピの取得に失敗しました')
    }
}

// Ingredient management
const addIngredient = () => {
    ingredients.value.push({name: '', amount: ''})
}

const removeIngredient = (index) => {
    ingredients.value.splice(index, 1)
}

// Step management
const addStep = () => {
    steps.value.push({instruction: ''})
}

const removeStep = (index) => {
    steps.value.splice(index, 1)
}


onMounted(() => {
    fetchRecipe()
})
</script>

<template>
    <div>
        <div v-if="!isEditing" class="recipe-detail">
            <!-- Header -->
            <PageHeader
                :title="recipe.name || 'レシピ詳細'"
                :show-back-button="true"
                back-url="/"
            >
                <template #actions>
                    <BaseButton
                        variant="primary"
                        @click="startEdit"
                    >
                        編集
                    </BaseButton>
                </template>
            </PageHeader>

            <!-- Content Container -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div v-if="recipe.description" class="text-gray-700 mb-6 text-lg">
                    {{ recipe.description }}
                </div>


                <!-- Info Cards Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg text-center">
                        <div class="text-sm font-medium text-blue-800">人数</div>
                        <div class="text-xl font-bold text-blue-900">{{ recipe.servings }}人分</div>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg text-center">
                        <div class="text-sm font-medium text-green-800">調理時間</div>
                        <div class="text-xl font-bold text-green-900">{{ recipe.cooking_time }}分</div>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div v-if="recipe.categories?.length" class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">分類</h2>
                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="category in recipe.categories"
                        :key="category.id"
                        class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm"
                    >
                        {{ category.name }}
                    </span>
                </div>
            </div>

            <!-- Ingredients -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">材料</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="space-y-2">
                        <li
                            v-for="ingredient in recipe.ingredients"
                            :key="ingredient.id"
                            class="flex justify-between"
                        >
                            <span class="font-medium">{{ ingredient.name }}</span>
                            <span class="text-gray-600">{{ ingredient.amount }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Steps -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">調理手順</h2>
                <div class="space-y-4">
                    <div
                        v-for="(step, index) in recipe.steps"
                        :key="step.id"
                        class="flex gap-4"
                    >
                        <div
                            class="bg-blue-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold flex-shrink-0">
                            {{ index + 1 }}
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg flex-1">
                            {{ step.instruction }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nutrition Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">栄養情報</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-3 rounded text-center">
                        <div class="font-medium text-gray-700">カロリー</div>
                        <div class="text-lg">{{ recipe.calories }}kcal</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded text-center">
                        <div class="font-medium text-gray-700">タンパク質</div>
                        <div class="text-lg">{{ recipe.protein }}g</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded text-center">
                        <div class="font-medium text-gray-700">脂質</div>
                        <div class="text-lg">{{ recipe.fat }}g</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded text-center">
                        <div class="font-medium text-gray-700">炭水化物</div>
                        <div class="text-lg">{{ recipe.carbs }}g</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded text-center">
                        <div class="font-medium text-gray-700">食物繊維</div>
                        <div class="text-lg">{{ recipe.fiber }}g</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded text-center">
                        <div class="font-medium text-gray-700">ナトリウム</div>
                        <div class="text-lg">{{ recipe.sodium }}mg</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded text-center">
                        <div class="font-medium text-gray-700">糖質</div>
                        <div class="text-lg">{{ recipe.sugar }}g</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Mode -->
        <div v-else class="recipe-edit">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">レシピ編集</h1>
                <div class="space-x-2">
                    <button
                        @click="cancelEdit"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600"
                    >
                        キャンセル
                    </button>
                    <button
                        @click="saveEdit"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600"
                    >
                        保存
                    </button>
                </div>
            </div>

            <!-- Edit Form (reuse structure from RecipeCreateForm) -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">基本情報</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">レシピ名 *</label>
                        <input
                            v-model="editForm.name"
                            type="text"
                            placeholder="レシピ名を入力"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">説明</label>
                        <textarea
                            v-model="editForm.description"
                            placeholder="レシピの説明を入力"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">人数</label>
                        <input
                            v-model="editForm.servings"
                            type="number"
                            min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">調理時間（分）</label>
                        <input
                            v-model="editForm.cooking_time"
                            type="number"
                            min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                </div>
            </div>

            <!-- Ingredients Edit -->
            <div class="mb-8">
                <label class="block text-lg font-semibold text-gray-700 mb-4">材料 *</label>
                <div class="space-y-2">
                    <div
                        v-for="(item, index) in ingredients"
                        :key="index"
                        class="flex gap-2 items-center"
                    >
                        <input
                            v-model="item.name"
                            type="text"
                            placeholder="材料名 (例: トマト)"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                        <input
                            v-model="item.amount"
                            type="text"
                            placeholder="使用量 (例: 2個、大さじ1、少々)"
                            class="w-48 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                        <button
                            type="button"
                            @click="removeIngredient(index)"
                            class="px-2 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                        >
                            ×
                        </button>
                    </div>
                </div>
                <button
                    type="button"
                    @click="addIngredient"
                    class="mt-3 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm"
                >
                    + 材料を追加
                </button>
            </div>

            <!-- Steps Edit -->
            <div class="mb-8">
                <label class="block text-lg font-semibold text-gray-700 mb-4">調理手順 *</label>
                <div class="space-y-3">
                    <div
                        v-for="(step, index) in steps"
                        :key="index"
                        class="flex gap-3 items-start"
                    >
                        <span class="bg-blue-100 text-blue-800 px-3 py-2 rounded-full text-sm font-medium mt-1">{{
                                index + 1
                            }}</span>
                        <textarea
                            v-model="step.instruction"
                            :placeholder="`手順${index + 1}の説明を入力`"
                            rows="2"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        ></textarea>
                        <button
                            type="button"
                            @click="removeStep(index)"
                            class="px-2 py-2 bg-red-500 text-white rounded hover:bg-red-600 mt-1"
                        >
                            ×
                        </button>
                    </div>
                </div>
                <button
                    type="button"
                    @click="addStep"
                    class="mt-3 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm"
                >
                    + 手順を追加
                </button>
            </div>

            <!-- Nutrition Edit -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">栄養情報</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">カロリー (kcal)</label>
                        <input
                            v-model="editForm.calories"
                            type="number"
                            min="0"
                            step="0.1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">タンパク質 (g)</label>
                        <input
                            v-model="editForm.protein"
                            type="number"
                            min="0"
                            step="0.1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">脂質 (g)</label>
                        <input
                            v-model="editForm.fat"
                            type="number"
                            min="0"
                            step="0.1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">炭水化物 (g)</label>
                        <input
                            v-model="editForm.carbs"
                            type="number"
                            min="0"
                            step="0.1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">食物繊維 (g)</label>
                        <input
                            v-model="editForm.fiber"
                            type="number"
                            min="0"
                            step="0.1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ナトリウム (mg)</label>
                        <input
                            v-model="editForm.sodium"
                            type="number"
                            min="0"
                            step="0.1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">糖質 (g)</label>
                        <input
                            v-model="editForm.sugar"
                            type="number"
                            min="0"
                            step="0.1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                </div>
            </div>

            <!-- Categories Edit -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">分類</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label
                        v-for="category in availableCategories"
                        :key="category"
                        class="flex items-center space-x-2 p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer"
                    >
                        <input
                            type="checkbox"
                            :value="category"
                            v-model="selectedCategories"
                            class="text-blue-500 focus:ring-blue-500"
                        >
                        <span class="text-sm font-medium text-gray-700">{{ category }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>
