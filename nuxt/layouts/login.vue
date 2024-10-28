<script setup lang="ts"></script>
<template>
  <div class="public-layout h-screen w-100 d-flex">
    <div class="left-side flex-fill bg-surface w-50"></div>
    <div class="right-side flex-fill d-flex flex-column justify-center w-50">
      <slot></slot>
    </div>
  </div>
</template>
<style scoped>
.right-side{
  background-color: rgba(var(--v-theme-primary), 0.75);
}</style>
