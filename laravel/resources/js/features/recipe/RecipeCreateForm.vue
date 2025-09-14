<script setup>
import {ref} from 'vue'
import {useRecipeRepository} from "@/composables/repository/recipeRepository.js";
import {useRouter} from "vue-router";
import PageHeader from '@/components/PageHeader.vue'
import BaseButton from '@/components/BaseButton.vue'


const router = useRouter();
const recipeRepository = useRecipeRepository(router);

const form = ref({
    name: '',
    description: '',
    servings: 1,
    cooking_time: 0,
    calories: 0,
    protein: 0,
    fat: 0,
    carbs: 0,
    fiber: 0,
    sodium: 0,
    sugar: 0
})

const ingredients = ref([
    {name: '', amount: ''}
])

const steps = ref([
    {instruction: ''}
])

const addIngredient = () => {
    ingredients.value.push({name: '', amount: ''})
}

const removeIngredient = (index) => {
    ingredients.value.splice(index, 1)
}

const addStep = () => {
    steps.value.push({instruction: ''})
}

const removeStep = (index) => {
    steps.value.splice(index, 1)
}

// Categories
const selectedCategories = ref([])
const availableCategories = ref([
    '主食',
    '主菜',
    '副菜',
    '汁物',
    'デザート',
    'サラダ',
    '前菜',
    '飲み物'
])

const handleSubmit = () => {
    const recipeData = {
        ...form.value,
        ingredients: ingredients.value,
        steps: steps.value,
        categories: selectedCategories.value
    }
    console.log(recipeData);
    recipeRepository.store(recipeData)
        .then(response => {
            alert('レシピが作成されました！')
            router.push('/detail' + '/' + response.data.id)
        })
        .catch(error => {
            alert('レシピの作成中にエラーが発生しました。')
        })
}
</script>

<template>
    <div>
        <PageHeader
            title="レシピ作成"
            :show-back-button="true"
            back-url="/"
        />

        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-6 pb-3 border-b border-gray-200">基本情報</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">レシピ名 *</label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="レシピ名を入力"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">説明</label>
                    <textarea
                        v-model="form.description"
                        placeholder="レシピの説明を入力"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">人数</label>
                    <input
                        v-model="form.servings"
                        type="number"
                        min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">調理時間（分）</label>
                    <input
                        v-model="form.cooking_time"
                        type="number"
                        min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>
        </div>

        <!-- Ingredients Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-6 pb-3 border-b border-gray-200">材料 *</h2>

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
            <BaseButton variant="success" size="sm" @click="addIngredient" class="mt-3">
                + 材料を追加
            </BaseButton>
        </div>

        <!-- Steps Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-6 pb-3 border-b border-gray-200">調理手順 *</h2>
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
            <BaseButton variant="success" size="sm" @click="addStep" class="mt-3">
                + 手順を追加
            </BaseButton>
        </div>

        <!-- Nutrition Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-6 pb-3 border-b border-gray-200">栄養情報</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">カロリー (kcal)</label>
                    <input
                        v-model="form.calories"
                        type="number"
                        min="0"
                        step="0.1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">タンパク質 (g)</label>
                    <input
                        v-model="form.protein"
                        type="number"
                        min="0"
                        step="0.1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">脂質 (g)</label>
                    <input
                        v-model="form.fat"
                        type="number"
                        min="0"
                        step="0.1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">炭水化物 (g)</label>
                    <input
                        v-model="form.carbs"
                        type="number"
                        min="0"
                        step="0.1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">食物繊維 (g)</label>
                    <input
                        v-model="form.fiber"
                        type="number"
                        min="0"
                        step="0.1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ナトリウム (mg)</label>
                    <input
                        v-model="form.sodium"
                        type="number"
                        min="0"
                        step="0.1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">糖質 (g)</label>
                    <input
                        v-model="form.sugar"
                        type="number"
                        min="0"
                        step="0.1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-6 pb-3 border-b border-gray-200">分類</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <label
                    v-for="category in availableCategories"
                    :key="category"
                    class="flex items-center space-x-2 p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer transition-colors"
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

        <!-- Submit Button -->
        <div class="text-center">
            <BaseButton
                variant="primary"
                size="lg"
                @click="handleSubmit"
            >
                レシピを作成
            </BaseButton>
        </div>
    </div>
</template>
