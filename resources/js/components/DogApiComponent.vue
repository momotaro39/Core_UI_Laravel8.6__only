<template>
    <div class="style1">
        <h1>{{ title }}</h1>
        <p>Axiosを利用したuserAPIで名称を10個取得</p>
        <p v-for="message in messages">{{ message }} </p>
    </div>
</template>

<script>
export default {
    data() {
        return {
          dogTypes:['akita', 'beagle', 'pekinese', 'pug'],
          dogUrl:'',
          dogImages:'',
        };
    },
    created() {
        axios
            //取得先
            .get("https://dog.ceo/api/breeds/image/random", {
                params: {
                    message: "1", //10名取得
                },
            })
            .then((response) => (this.messages = response.data.message))
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
