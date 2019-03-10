@extends('guest')

@section('content')
<section class="container content">
<div class="row">
<div class="col-md-6 col-md-offset-3">
    <!-- general form elements -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Masuk</h3>
        </div>
    <!-- /.box-header -->
    <!-- form start -->
        <form role="form" action="/rincian-kegiatan">
            <div class="box-body">
                <div class="form-group">
                    <label for="name">Username</label>
                    <input type="text" class="form-control" id="name" placeholder="Masukkan Username">
                </div>
                <div class="form-group">
                    <label for="place">Password</label>
                    <input type="password" class="form-control" id="place" placeholder="Masukkan Password">
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary float-right">Masuk</button>
            </div>
        </form>
    </div>
</div>
</div>
</section>
@endsection

@push("script")

@endpush