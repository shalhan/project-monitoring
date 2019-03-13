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
                    <label>Tanggal:</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right {{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" id="schedule" value="{{old('date')}}">
                    </div>
                    @if ($errors->has('date'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('date') }}</strong>
                        </span>
                    @endif
                <!-- /.input group -->
                </div>
                <div class="form-group">
                    <label>Waktu:</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                        </div>
                        <input type="text" name="time" class="form-control timepicker {{ $errors->has('date') ? ' is-invalid' : '' }}" value="{{old('time')}}">
                    </div>
                    @if ($errors->has('time'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('time') }}</strong>
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
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script>
  $(function () {
    $('.select2').select2()

    $('#schedule').daterangepicker()
    // $('#schedule').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })

    //Timepicker
    $('.timepicker').timepicker({
        showInputs: false
    })
  })
</script>
@endpush