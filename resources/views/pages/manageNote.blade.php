@extends('master')
@push('style')
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
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
            <form role="form" action="{{route('createNote', [ 'id' => -99 ] )}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="form-group">
                    <label for="name">Nama Notes</label>
                    <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" placeholder="Masukkan Nama Notes" value="{{old('name')}}" autocomplete="off">
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="name">Lokasi</label>
                    <input type="text" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" id="place" name="location" placeholder="Masukkan Lokasi" value="{{old('location')}}">
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
            </div>
            <div class="box-footer">
            <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
            </form>

        </div>
    </div>
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Catatan</h3>
            </div>
            <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    <th>Catatan</th>
                    <th>Lokasi</th>
                    <th>Mulai</th>
                    <th style="width: 40px">Aksi</th>
                </tr>
                @foreach($lectureNotes as $note)
                <tr class="{{$note->isEnd ? 'end' : ''}}">
                    <td>{{$note->name}}</td>
                    <td>{{$note->location ? $note->location : "-"}}</td>
                    <td>
                    {{$note->startTime}}
                    </td>
                    <td class="text-center">
                    @if($note->created_by === Auth::user()->id)
                        <a href="{{route('updateNoteView', ['id' => $note->id])}}"><i class="fa fa-pencil"></i></a>

                        <form action="{{route('deleteNote', ['id'=>$note->id])}}" method="post">
                            @method('DELETE')
                            @csrf
                            <label for="deleteNote{{$note->id}}"><i class="fa fa-trash text-red"></i></label>
                            <button class="btn btn-warning hidden" type="submit" id="deleteNote{{$note->id}}">Delete</button>
                        </form>
                    @endif
                    </td>
                </tr>
                @endforeach
                </table>
            </div>

        </div>
    </div>
    </div>
</section>

@push('script')
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
  $(function () {
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
@endsection