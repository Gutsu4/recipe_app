<script setup lang="ts">
import {useRouter} from "vue-router";
import RecipeList from "@/features/recipe/RecipeList.vue";
import Layout from "@/components/Layout.vue";
import PlusSvg from "@/components/svgs/PlusSvg.vue";

const router = useRouter();

function goToCreate() {
    router.push("/create");
}
</script>

<template>
    <Layout>
        <RecipeList/>

        <div class="flex justify-end mt-4">
            <button class="p-2 rounded-full bg-primary-500 text-white" @click="goToCreate">
                <PlusSvg size="lg"/>
            </button>
        </div>
    </Layout>
</template>
