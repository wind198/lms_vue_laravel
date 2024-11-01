<script setup lang="ts"></script>
<template>
  <app-wrappers class="login-layout">
    <div class="public-layout h-screen w-100 d-flex">
      <div class="left-side flex-fill bg-surface w-50" />
      <div class="right-side flex-fill d-flex flex-column justify-center w-50">
        <router-view />
      </div>
    </div>
  </app-wrappers>
</template>
<style scoped>
.right-side {
  background-color: rgba(var(--v-theme-primary), 0.75);
}
</style>
