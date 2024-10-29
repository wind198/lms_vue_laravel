<script setup lang="ts">
const { t } = useI18n();
const welcomeMsg = t("messages.info.welcome");
const loginInstructionMsg = t("messages.info.pleaseLogin");
</script>
<template>
  <v-card>
    <v-card-title>{{ welcomeMsg }} </v-card-title>
    <v-card-subtitle>
      {{ loginInstructionMsg }}
    </v-card-subtitle>
    <v-card-text> 
        <v-text-field
        :rules=""
        ></v-text-field>
    </v-card-text>
  </v-card>
  <v-form ref="loginFormRef"> </v-form>
</template>
<style scoped></style>
