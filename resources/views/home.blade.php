@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body">
        <!-- Display Validation Errors -->
        @include('common.errors')

        <!-- New Oauth Form -->
        <form action="{{ url('token') }}" method="GET" class="form-horizontal">
            {!! csrf_field() !!}

            <!-- Task Name -->
            <div class="form-group">
                <label for="user" class="col-sm-3 control-label">User</label>

                <div class="col-sm-6">
                    <input type="text" name="user" id="user" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-3 control-label">Password</label>

                <div class="col-sm-6">
                    <input type="password" name="password" id="password" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="client_id" class="col-sm-3 control-label">User</label>

                <div class="col-sm-6">
                    <input type="text" name="client_id" id="client_id" class="form-control" readonly
                           value="WZGUVKRMCSOBFHRDXPGKQMYQRBJHQZON">
                </div>
            </div>
            <div class="form-group">
                <label for="client_secret" class="col-sm-3 control-label">Client Secret</label>

                <div class="col-sm-6">
                    <input type="text" name="client_secret" id="client_secret" class="form-control" readonly
                           value="96670596656ec13f26598b7049421744">
                </div>
            </div>




            <!-- Add Task Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-plus"></i> Get PM OAuth
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TODO: Current Tasks -->
@endsection