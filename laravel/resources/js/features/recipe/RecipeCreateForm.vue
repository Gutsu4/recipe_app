<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">材料 *</label>

        <div class="space-y-2">
            <div class="flex gap-2 items-center">
                <!-- 材料名 -->
                <input
                    type="text"
                    placeholder="材料名 (例: トマト)"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                >

                <!-- 数量 -->
                <input
                    type="text"
                    placeholder="数量 (例: 2)"
                    class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                >

                <!-- 単位 -->
                <select
                    class="w-28 px-2 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">単位</option>
                    <option>個</option>
                    <option>g</option>
                    <option>ml</option>
                    <option>大さじ</option>
                    <option>小さじ</option>
                    <option>少々</option>
                </select>

                <!-- 削除ボタン -->
                <button
                    type="button"
                    class="px-2 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                >
                    ×
                </button>
            </div>
        </div>

        <!-- 追加ボタン -->
        <button
            type="button"
            class="mt-3 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm"
        >
            + 材料を追加
        </button>
    </div>
</template>
