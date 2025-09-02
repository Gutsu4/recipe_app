<script setup lang="ts">
import FeatureCard from "@/components/FeatureCard.vue";

const features = [
  {
    title: "学習しやすい構成",
    description: "Vue と Laravel の分離された構成で理解しやすい！",
  },
  {
    title: "Docker 開発環境",
    description: "make init ですぐに開発スタート！",
  },
  {
    title: "Tailwind CSS 対応",
    description: "直感的に使えるユーティリティファーストな CSS。",
  },
];
</script>

<template>
  <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-3">
    <FeatureCard
      v-for="(feature, index) in features"
      :key="index"
      :title="feature.title"
      :description="feature.description"
    />
  </div>
</template>
