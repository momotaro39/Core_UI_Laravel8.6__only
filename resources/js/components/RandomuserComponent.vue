<template>
    <div class="style1">
        <h1>{{ message }}</h1>
        <p>Axiosを利用したuserAPIで名称を10個取得</p>
        <p v-for="user in users">{{ user.name.title }} {{ user.name.last }}</p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: "Axios機能",
            users: [], //userの配列を作る
        };
    },
    created() {
        axios
            //取得先
            .get("https://randomuser.me/api/", {
                params: {
                    results: "10", //10名取得
                },
            })
            .then((response) => (this.users = response.data.results))
            .catch((response) => console.log(response));
    },
};
</script>

<style>
.style1 {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}
</style>
