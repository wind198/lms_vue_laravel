<script setup lang="ts">
const { t } = useI18n();

const { name } = useRoute();

console.log("Current route: " + name?.toString());

const open = ref<string[]>([]);
const settingLinks = {
  value: "settings",
  text: t("entities.settings"),
  icon: "mdi-cog", // Settings icon
  links: [
    {
      to: "/settings/generations",
      text: t("entities.generation"),
      value: "generation",
      icon: "mdi-timeline-clock", // Generation icon suggestion
    },
    {
      to: "/settings/students",
      text: t("entities.student"),
      value: "students",
      icon: "mdi-account", // Student icon
    },
    {
      to: "/settings/teachers",
      text: t("entities.teacher"),
      value: "teachers",
      icon: "mdi-teach", // Teacher icon
    },
  ],
};

const studyLinks = {
  value: "study",
  text: t("entities.study"),
  icon: "mdi-book-open-page-variant", // Study icon
  links: [
    {
      to: "/study/majors",
      text: t("entities.major"),
      icon: "mdi-book-multiple", // Major icon
      value: "majors",
    },
    {
      to: "/study/courses",
      text: t("entities.course"),
      icon: "mdi-book", // Course icon
      value: "courses",
    },
    {
      to: "/study/classes",
      text: t("entities.class"),
      icon: "mdi-chair-school", // Class icon
      value: "classes",
    },
  ],
};
</script>
<template>
  <v-layout class="rounded rounded-md">
    <v-navigation-drawer :width="255" class="app-left-navigation-drawer">
      <div
        class="logo-container w-100 bg-primary d-flex justify-center align-center"
      >
        <span class="logo text-white">Logo</span>
      </div>
      <v-list v-model:opened="open">
        <template :key="index" v-for="(i, index) in [settingLinks, studyLinks]">
          <v-list-group :value="i.value">
            <template v-slot:activator="{ props }">
              <v-list-item
                v-bind="props"
                :prepend-icon="i.icon"
                :title="i.text"
              >
              </v-list-item>
            </template>
            <v-list-item
              v-for="l in i.links"
              :prepend-icon="l.icon"
              :title="l.text"
              :to="l.to"
            ></v-list-item
          ></v-list-group>
        </template>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      class="appbar"
      :elevation="1"
      title="Learning management system"
    ></v-app-bar>

    <v-main
      class="d-flex align-center justify-center"
      style="min-height: 300px"
    >
      <slot> Main Content </slot>
    </v-main>
  </v-layout>
</template>
<style scoped>
.logo-container {
  height: 64px;
}
.app-left-navigation-drawer {
  width: 240px;
}
</style>
