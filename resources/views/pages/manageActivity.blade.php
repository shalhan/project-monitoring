@extends('master')

@push("style")
<!-- daterange picker -->
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css"> -->
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
        @if(session('success'))
            @component('alert')
                @slot('type')
                    success
                @endslot

                {{session('success')}}
            @endcomponent
        @endif
        <form role="form" action="{{route('createActivity')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="form-group">
                    <label for="name">Nama Kegiatan</label>
                    <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" placeholder="Masukkan Nama Kegiatan" value="{{old('name')}}">
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="place">Lokasi / Tempat</label>
                    <input type="text" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" id="place" name="location" placeholder="Masukkan Lokasi Kegiatan" value="{{old('location')}}">
                    @if ($errors->has('location'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('location') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="form-group">
                    <label>Dari Tanggal:</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right {{ $errors->has('dateFrom') ? ' is-invalid' : '' }}" name="dateFrom" id="dateFrom" value="{{old('date')}}" autocomplete="off">
                        <input type="text" class="form-control timepicker {{ $errors->has('timeFrom') || $errors->has('dateFrom') ? ' is-invalid' : '' }}" name="timeFrom" id="timeFrom" value="{{old('time')}}" autocomplete="off">
                    </div>
                    @if ($errors->has('dateFrom'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('dateFrom') }}</strong>
                        </span>
                    @endif
                    @if ($errors->has('timeFrom'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('timeFrom') }}</strong>
                        </span>
                    @endif
                <!-- /.input group -->
                </div>
                <div class="form-group">
                    <label>Sampai Tanggal:</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right {{ $errors->has('dateTo') ? ' is-invalid' : '' }}" name="dateTo" id="dateTo" value="{{old('date')}}" autocomplete="off">
                        <input type="text" class="form-control timepicker {{ $errors->has('timeTo') || $errors->has('dateTo') ? ' is-invalid' : '' }}" name="timeTo" id="timeTo" value="{{old('time')}}" autocomplete="off">
                    </div>
                    @if ($errors->has('dateTo'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('dateTo') }}</strong>
                        </span>
                    @endif
                    @if ($errors->has('timeTo'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('timeTo') }}</strong>
                        </span>
                    @endif
                <!-- /.input group -->
                </div>
                <div class="form-group">
                    <label>Pilih Panitia :</label>
                    <select class="form-control select2 {{ $errors->has('chief_id') ? ' is-invalid' : '' }}" multiple="multiple" name="user_id[]" style="width: 100%;">
                    @foreach($lectures as $lecture)
                    <option value="{{$lecture->id}}" {{old('chief_id') == $lecture->id ? 'selected' : ''}}>{{$lecture->name}}</option>
                    @endforeach
                    </select>
                    @if ($errors->has('chief_id'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('chief_id') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="exampleInputFile">Unduh File :</label>
                    <input type="file" id="exampleInputFile" name="file">
                    <p class="help-block">Only jpeg,jpg,png,pdf,doc,docx (max: 2040kb)</p>
                    @if ($errors->has('file'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('file') }}</strong>
                        </span>
                    @endif
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
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- <script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script> -->
<script>
  $(function () {
    $('.select2').select2()
    // $('#scheduleFrom').daterangepicker()
    // $('#schedule').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = mm + '/' + dd + '/' + yyyy;
    $('#dateFrom').datepicker({
      autoclose: true
    }).val(today).datepicker('update');
    //Timepicker
    $('#timeFrom').timepicker({
        showInputs: false
    })
    $('#dateTo').datepicker({
      autoclose: true
    }).val(today).datepicker('update');
    //Timepicker
    $('#timeTo').timepicker({
        showInputs: false
    })
  })
</script>
@endpush