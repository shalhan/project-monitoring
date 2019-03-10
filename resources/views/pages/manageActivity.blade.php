@extends('master')

@push("style")
<!-- daterange picker -->
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">

@endpush

@section('content')
<section class="content-header">
    <h1>
        Manage
        <small>Kegiatan</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-plus"></i> Tambah Kegiatan</a></li>
    </ol>
</section>
<section class="content">
<div class="row">
<div class="col-md-6">
    <!-- general form elements -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Kegiatan</h3>
        </div>
    <!-- /.box-header -->
    <!-- form start -->
        <form role="form">
            <div class="box-body">
                <div class="form-group">
                    <label for="name">Nama Kegiatan</label>
                    <input type="text" class="form-control" id="name" placeholder="Masukkan Nama Kegiatan">
                </div>
                <div class="form-group">
                    <label for="place">Lokasi / Tempat</label>
                    <input type="text" class="form-control" id="place" placeholder="Masukkan Lokasi Kegiatan">
                </div>
                <div class="form-group">
                    <label>Tanggal:</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="schedule">
                    </div>
                <!-- /.input group -->
                </div>
                <div class="form-group">
                    <label>Waktu:</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                        </div>
                        <input type="text" class="form-control timepicker">
                    </div>
                    <!-- /.input group -->
                </div>
                <div class="form-group">
                    <label>Pilih Ketua :</label>
                    <select class="form-control select2" style="width: 100%;">
                    <option selected="selected">Alabama</option>
                    <option>Alaska</option>
                    <option>California</option>
                    <option>Delaware</option>
                    <option>Tennessee</option>
                    <option>Texas</option>
                    <option>Washington</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile">Unduh File :</label>
                    <input type="file" id="exampleInputFile" multiple>

                    <p class="help-block">Example block-level help text here.</p>
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
            <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form>
    </div>
</div>
</div>
</section>
@endsection

@push("script")
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script>
  $(function () {
    $('.select2').select2()

    $('#schedule').daterangepicker()
    //Timepicker
    $('.timepicker').timepicker({
        showInputs: false
    })
  })
</script>
@endpush