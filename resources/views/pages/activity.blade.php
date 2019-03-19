@extends('master')

@push('style')
<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
<link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
@endpush

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

@section('content')
<section class="content-header">
    <h1>
        Rincian
        <small>Kegiatan</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-file"></i> Rincian Kegiatan</a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
      @if(Auth::check() && Auth::user()->type_id === Config::get('user.lecture_id'))
      <div class="col-md-4">
      <div class="box box-primary">
          <div class="box-header with-border">
              <h3 class="box-title">Catatan</h3>
          </div>
          <div class="box-body">
          <table class="table table-bordered">
                <tr>
                  <th>Catatan</th>
                  <th>Mulai</th>
                  <th style="width: 40px">Aksi</th>
                </tr>
                @foreach($lectureNotes as $note)
                <tr>
                  <td>{{$note->name}}</td>
                  <td>
                    {{$note->startTimeRemaining}}
                  </td>
                  <td class="text-center">
                  @if($note->created_by === Auth::user()->id)
                  <form action="/note/{{$note->id}}" method="post">
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
        <div class="box box-primary">
          <div class="box-header with-border">
              <h3 class="box-title">Notes</h3>
          </div>
          <form role="form" action="{{route('createNote')}}" method="POST" enctype="multipart/form-data">
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
                    <label>Dari Tanggal:</label>

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right {{ $errors->has('dateFrom') ? ' is-invalid' : '' }}" name="dateFrom" id="dateFrom" value="{{old('date')}}" autocomplete="off">
                        <input type="text" class="form-control timepicker {{ $errors->has('timeFrom') ? ' is-invalid' : '' }}" name="timeFrom" id="timeFrom" value="{{old('time')}}" autocomplete="off">
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
                        <input type="text" class="form-control timepicker {{ $errors->has('timeTo') ? ' is-invalid' : '' }}" name="timeTo" id="timeTo" value="{{old('time')}}" autocomplete="off">
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
      @endif
      <!-- /.col -->
      <div class="col-md-8">
          <div class="box box-primary">
              <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
              </div>
              <!-- /.box-body -->
          </div>
      <!-- /. box -->
      </div>
      <!-- /.col -->
  </div>
<!-- /.row -->
</section>
@endsection

@push("script")
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<script>
  $(function () {
    $('.select2').select2()
    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })
      })
    }

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({
      eventClick: function(calEvent, jsEvent, view) {
        $("#modal-default").modal("show")
      },
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week : 'week',
        day  : 'day'
      },
      //Random default events
      events    : <?php echo $result ?>,
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)

        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove()
        }

      }
    })

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      init_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })
  })
</script>
@endpush