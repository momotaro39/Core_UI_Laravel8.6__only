<template>
  <div class="gitdata">
    <h>public repo num : {{this.userDatas.public_repos}}</h>
  </div>
</template>


<script>
import axios from "axios";

export default {
  name: "GitData",
  props: {
    repoName: String
  },
  data() {
    return { userDatas: [] };
  },
  mounted() {
    const request = axios.create({
      baseURL: "https://api.github.com"
    });
    request
      .get(
        `/users/${this.repoName}?access_token=config('app.github_user_name')` // api key はgitにあげない！.envなどで管理
      )
      .then(res => {
        this.userDatas = res.data;
        console.log(res);
      });
  }
};
</script>

