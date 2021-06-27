<template>
  <div id="app">
    <GitData repoName="UserName" />
  </div>
</template>

<script>
import GitData from "./components/GitHubApiComponent.vue";

export default {
  name: "app",
  components: {
    GitData
  }
};
</script>