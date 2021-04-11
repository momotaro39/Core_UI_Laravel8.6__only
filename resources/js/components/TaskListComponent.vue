<template>
    <div class="container">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">タイトル</th>
                    <th scope="col">内容</th>
                    <th scope="col">Person In Charge</th>
                    <th scope="col">詳細</th>
                    <th scope="col">編集</th>
                    <th scope="col">削除</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Title1</td>
                    <td>Content1</td>
                    <td>Ichiro</td>
                    <td>
                        <button class="btn btn-primary">詳細</button>
                    </td>
                    <td>
                        <button class="btn btn-success">編集</button>
                    </td>
                    <td>
                        <button class="btn btn-danger">削除</button>
                    </td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Title2</td>
                    <td>Content2</td>
                    <td>Jiro</td>
                    <td>
                        <button class="btn btn-primary">詳細</button>
                    </td>
                    <td>
                        <button class="btn btn-success">編集</button>
                    </td>
                    <td><button class="btn btn-danger">削除</button></td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>Title3</td>
                    <td>Content3</td>
                    <td>Saburo</td>
                    <td>
                        <button class="btn btn-primary">詳細</button>
                    </td>
                    <td>
                        <button class="btn btn-success">編集</button>
                    </td>
                    <td>
                        <button class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {};
</script>
