<table class="table table-bordered" id="role-list-table">
    <thead>
    <tr>
        <th scope="col">Roles</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th scope="col">Roles</th>
        <th scope="col">Action</th>
    </tr>
    </tfoot>
    <tbody id="role-permission-list">
        @foreach($roles as $role)
            <tr>
                <td>
                    {{ $role->name }}
                </td>
                <td>
                    <button class="btn btn-outline-success" href="#" data-toggle="modal"
                            data-target="#changePermission-{{ $role->id }}">
                        <i class="fas fa-solid fa-unlock"></i>
                        Change Permission
                    </button>

                    <div class="modal fade" id="changePermission-{{ $role->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="permission-{{ $role->id }}"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form class="container" method="post" action="{{ route('role-permission.sync-permission',['locale' => app()->getLocale()]) }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="permission-{{ $role->id }}">
                                            Change permission
                                            <strong>
                                                {{ $role->name }}
                                            </strong>
                                            role
                                        </h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @csrf
                                        <input type="hidden" name="role_id" value="{{ $role->id }}" readonly>
                                        <div class="mb-3 col-md-12">
                                            @php
                                                $guardPermissions = $permissions
                                                                        ->where('guard_name',$role->guard_name)
                                                                        ->get();
                                                $getPermission = array();
                                                foreach($role->permissions as $rolePermission) {
                                                    $getPermission[] = $rolePermission->id;
                                                }
                                            @endphp
                                            @foreach($guardPermissions as $permission)
                                                <div class="row">
                                                    <div class="custom-control col-md-6 custom-checkbox">
                                                        <input type="checkbox" name="permission_id[]"
                                                               @if(in_array($permission->id,$getPermission))
                                                                   checked
                                                               @endif
                                                               value="{{ $permission->id }}" class="custom-control-input"
                                                               id="{{ $permission->id }}">
                                                        <label class="custom-control-label" for="{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                             @endforeach
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">
                                            Cancel
                                        </button>
                                        <button id="submit" type="submit" class="btn btn-primary">
                                            Sync
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
