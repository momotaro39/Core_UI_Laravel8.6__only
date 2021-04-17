<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">ExampleApi Component</div>

          <div class="card-body">
            exampleapi componentを現在出力中
            <ul>
              <li v-for="user in users">名前 {{ user.name }} メールアドレス {{ user.email }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        data(){
            return {
                users: []
            }
        },
        mounted() {
          axios.get('/userapi').then(response => this.users = response.data)
        }
    }
</script>
