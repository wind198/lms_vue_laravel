<script setup lang="ts">
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const open = ref<string[]>([])
const settingLinks = {
  value: 'settings',
  text: t('nouns.management'),
  icon: 'mdi-cog', // Settings icon
  links: [
    {
      to: '/settings/generations',
      text: t('nouns.generation'),
      value: 'generation',
      icon: 'mdi-account-group', // Generation icon suggestion
    },
    {
      to: '/settings/students',
      text: t('nouns.student'),
      value: 'students',
      icon: 'mdi-account', // Student icon
    },
    {
      to: '/settings/teachers',
      text: t('nouns.teacher'),
      value: 'teachers',
      icon: 'mdi-human-male-board', // Teacher icon
    },
    {
      to: '/settings/rooms',
      text: t('nouns.room'),
      value: 'rooms',
      icon: 'mdi-table-chair', // Teacher icon
    },
  ],
}

const studyLinks = {
  value: 'study',
  text: t('nouns.study'),
  icon: 'mdi-book-open-page-variant', // Study icon
  links: [
    {
      to: '/study/majors',
      text: t('nouns.major'),
      icon: 'mdi-book-multiple', // Major icon
      value: 'majors',
    },
    {
      to: '/study/courses',
      text: t('nouns.course'),
      icon: 'mdi-book', // Course icon
      value: 'courses',
    },
    {
      to: '/study/classes',
      text: t('nouns.class'),
      icon: 'mdi-chair-school', // Class icon
      value: 'classes',
    },
  ],
}
</script>
<template>
  <app-wrapper class="home-layout">
    <v-layout class="rounded rounded-md h-screen" style="overflow-y: auto">
      <v-navigation-drawer class="app-left-navigation-drawer" :width="255">
        <div
          class="logo-container w-100 bg-primary d-flex justify-center align-center"
        >
          <span class="logo text-white">Logo</span>
        </div>
        <v-list v-model:opened="open">
          <template
            v-for="(i, index) in [settingLinks, studyLinks]"
            :key="index"
          >
            <v-list-group :value="i.value">
              <template #activator="{ props }">
                <v-list-item
                  v-bind="props"
                  :prepend-icon="i.icon"
                  :title="i.text"
                />
              </template>
              <v-list-item
                v-for="l in i.links"
                :key="l.value"
                :prepend-icon="l.icon"
                :title="l.text"
                :to="l.to"
            /></v-list-group>
          </template>
        </v-list>
      </v-navigation-drawer>

      <v-app-bar class="appbar" :elevation="1" :title="t('others.appName')" />

      <v-main style="min-height: 300px">
        <router-view />
      </v-main>
    </v-layout>
  </app-wrapper>
</template>
<style scoped>
.logo-container {
  height: 64px;
}
.app-left-navigation-drawer {
  width: 240px;
}
</style>
