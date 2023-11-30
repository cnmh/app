<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table table-striped" id="users-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            {{--
                            <a href="{{ route('users.show', [$user->id]) }}"
                               class='btn btn-default btn-sm'>
                                <i class="far fa-eye"></i>
                            </a> 
                            --}}
                            @can('update-User')

                            <a href="{{ route('manage.role.permission', [$user->id]) }}" class='btn btn-default btn-sm d-flex'>
                                <i class="far fa-edit mr-2 mt-1"></i>
                                Parametre
                            </a>
                            @endcan
                            {{-- 
                            <a href="{{ route('users.edit', [$user->id]) }}" class='btn btn-default btn-sm'>
                                <i class="far fa-edit"></i>
                            </a>
                            --}}
                            @can('destroy-User')

                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            @endcan
                            

                            
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $users])
        </div>
        <div class="float-left">
            @can('export-UserController')
                                <button type="button" class="btn btn-default swalDefaultQuestion">
                                    <i class="fas fa-download"></i> Exporter
                                </button>
                                @endcan
                                @can('import-UserController')
                                <button type="button" class="btn btn-default swalDefaultQuestion">
                                    <i class="fas fa-file-import"></i> Importer
                                </button>
                                @endcan
        </div>
    </div>
</div>
