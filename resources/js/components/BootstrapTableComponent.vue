
<template>
    <div>
        <div id="toolbar">
            <a href="create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> 新規登録</a>
        </div>
        <bootstrap-table :data="myData" :options="myOptions" :columns="myColumns" />
    </div>
</template>

<script>
    import BootstrapTable from 'bootstrap-table/dist/bootstrap-table-vue.min.js'
    export default {
        props: ['models'],
        components: {
            'bootstrap-table': BootstrapTable
        },
        data () {
            return {
                mySelections: [],
                myData: [],
                myOptions: {
                    search: true,
                    pagination: true,
                    paginationPreText: 'Previous',
                    paginationNextText: 'Next',
                    pageSize: 5,
                    pageList: [5, 10, 25, 50, 100, 200, "All"],
                    showColumns: true,
                    showPrint: true,
                    showExport: true,
                    exportDataType: "all",
                    filterControl: true,
                    toolbar: '#toolbar',
                    clickToSelect: true,
                    idField: 'id',
                    selectItemName: 'id',
                },
                myColumns: [
                    { field: 'id', title: 'ID', sortable: true },
                    { field: 'name', title: '名前', sortable: true, filterControl: 'input' },
                    { field: 'admin_role_id', title: '管理番号', sortable: true, filterControl: 'input' },
                    { field: 'user_role_id', title: '顧客判別番号', sortable: true, filterControl: 'select' },
                    { field: 'band_id', title: 'バンド番号', sortable: true, filterControl: 'select' },
                    {
                        field: 'action',
                        title: 'Actions',
                        align: 'center',
                        width: '140px',
                        clickToSelect: false,
                        formatter: function (e, value, row){
                            return '<button class="btn btn-sm btn-info mr-1 show"><i class="fas fa-eye"></i></button><button class="btn btn-sm btn-warning mr-1 edit"><i class="fas fa-edit"></i></button><button class="btn btn-sm btn-danger mr-1 destroy"><i class="fas fa-trash"></i></button>'
                        },
                        events: {
                            'click .show': function (e, value, row){
                                alert("詳細ページへ移動");
                                //return window.location.assign('/emp/'+row.id)
                            },
                            'click .edit': function (e, value, row){
                                alert("編集ページへ移動");
                                //return window.location.assign('/emp/'+row.id+'/edit')
                            },
                            'click .destroy': function (e, value, row){
                                alert("アイテム削除へ移動");
                                //axios.delete('/emp/'+row.id, {
                                //    id: row.id
                                //})
                                //return window.location.replace('/emp')
                            },
                        }
                    }
                ]
            }
        },
        created () {
            this.myData = JSON.parse(this.models)
        },
        methods: {
        }
    }
</script>