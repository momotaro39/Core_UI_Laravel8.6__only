@extends('dashboard.base')

@section('content')


<div class="container-fluid">
  <div class="fade-in">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4>役割設定</h4>
          </div>
          <div class="card-body">
            <table class="table table-striped table-bordered datatable">
              <thead>
                <tr>
                  <th>名前</th>
                  <th>作成日</th>
                  <th>更新日</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    {{ $role->name }}
                  </td>
                  <td>
                    {{ $role->created_at }}
                  </td>
                  <td>
                    {{ $role->updated_at }}
                  </td>
                </tr>
              </tbody>
            </table>
            <a class="btn btn-primary" href="{{ route('roles.index') }}">戻る</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

@endsection

@section('javascript')

@endsection