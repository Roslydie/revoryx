<template>
  <router-view/>
</template>

<script setup>
import { onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
let templateScriptLoaded = false;

const loadTemplateScript = () => {
  if (templateScriptLoaded || !window.jQuery || route.name !== 'user.home') return;

  const existingScript = document.querySelector('script[data-user-template]');
  if (existingScript) {
    templateScriptLoaded = true;
    return;
  }

  if (route.name === 'user.home') {
        const script = document.createElement('script');
        script.src = '/assets/js/theme.js';
        script.defer = true;
        script.addEventListener('load', () => {
          window.dispatchEvent(new CustomEvent('user-template-ready'));
        }, { once: true });
        script.dataset.userTemplate = 'true';
        document.body.appendChild(script);
    templateScriptLoaded = true;
    }
};

onMounted(loadTemplateScript);
watch(() => route.name, loadTemplateScript);

</script>

<style>

</style>