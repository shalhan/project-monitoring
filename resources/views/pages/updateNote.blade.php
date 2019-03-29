@extends('master')
@push('style')
<link rel="stylesheet" href="/plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
<style>
  .end {
    color: rgba(0,0,0,0.2) !important;
    background: #f3f3f3 !important;
  }
</style>
@endpush
@section('content')
<section class="content-header">
    <h1>
        Kelola
        <small>Kegiatan</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-plus"></i> Kelola Kegiatan</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Notes</h3>
                </div>
                    <!-- form start -->
                @if(session('success'))
                    @component('alert')
                        @slot('type')
                            success
                        @endslot

                        {{session('success')}}
                    @endcomponent
                @endif
                <form role="form" action="{{route('updateNote', ['id'=>$note->id])}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="name">Nama Notes</label>
                        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" placeholder="Masukkan Nama Notes" value="{{$note->name}}" autocomplete="off">
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name">Lokasi</label>
                        <input type="text" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" id="place" name="location" placeholder="Masukkan Lokasi" value="{{$note->location}}">
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
                            <input type="text" class="form-control pull-right {{ $errors->has('dateFrom') ? ' is-invalid' : '' }}" name="dateFrom" id="dateFrom" value="{{$note->fromDate('m/d/Y')}}" autocomplete="off">
                            <input type="text" class="form-control timepicker {{ $errors->has('timeFrom') || $errors->has('dateFrom') ? ' is-invalid' : '' }}" name="timeFrom" id="timeFrom" value="{{$note->fromTime('H:i A')}}" autocomplete="off">
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
                            <input type="text" class="form-control pull-right {{ $errors->has('dateTo') ? ' is-invalid' : '' }}" name="dateTo" id="dateTo" value="{{$note->toDate('m/d/Y')}}" autocomplete="off">
                            <input type="text" class="form-control timepicker {{ $errors->has('timeTo') || $errors->has('dateTo') ? ' is-invalid' : '' }}" name="timeTo" id="timeTo" value="{{$note->toTime('H:i A')}}" autocomplete="off">
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
                </div>
                <div class="box-footer">
                <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
                </form>

            </div>
        </div>
    </div>
</section>

@push('script')
<script src="/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
  $(function () {
    // $('#scheduleFrom').daterangepicker()
    // $('#schedule').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    $('#dateFrom').datepicker({
      autoclose: true
    })
    //Timepicker
    $('#timeFrom').timepicker({
        showInputs: false
    })
    $('#dateTo').datepicker({
      autoclose: true
    })
    //Timepicker
    $('#timeTo').timepicker({
        showInputs: false
    })
  })
</script>
@endpush
@endsection