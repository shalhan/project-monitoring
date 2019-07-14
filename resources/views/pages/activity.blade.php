@extends('master')

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
      <div class="col-md-4">
          <div class="box box-primary">
              <div class="box-body no-padding">
                @if(Auth::user()->type_id != 1)
                <div class="flex">
                    <div class="strip strip--green"></div> <span class="text-center"> Catatan Pribadi</span>
                </div>
                <div class="flex">
                  <div class="strip strip--orange"></div> <span class="text-center"> Jadwal Kegiatan Anda</span>
                </div>
                <div class="flex">
                    <div class="strip strip--black"></div> <span class="text-center"> Jadwal Kegiatan Lainnya</span>
                </div>
                @else
                <div class="flex">
                    <div class="strip strip--black"></div> <span class="text-center"> Jadwal Kegiatan Dosen</span>
                </div>
                @endif
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
      lang: 'id',
      eventClick: function(calEvent, jsEvent, view) {
        console.log(calEvent)
        var startDate = calEvent.start.format('DD/MM/YYYY HH:mm A')
        var endDate = calEvent.end.format('DD/MM/YYYY HH:mm A')
        var location = calEvent.location
        var events = calEvent.source.rawEventDefs
        var listActivityEl = document.getElementById("listActivity")
        listActivityEl.innerHTML = ''
        setTimeout(()=>{
          for(let index in events) {
            if (events[index].id === calEvent.id) {
              var locationAttr = document.createElement("p") 
              var startDateAttr = document.createElement("p")
              var endDateAttr = document.createElement("p") 
              var title = document.createElement("p") 
              var list = document.createElement("li")
              locationAttr.innerHTML = "Lokasi : " + location
              list.innerHTML = events[index].title
              startDateAttr.innerHTML = 'Mulai : ' + startDate
              endDateAttr.innerHTML = 'Berakhir : ' + endDate
              title.innerHTML = 'Kegiatan :'
              listActivityEl.appendChild(locationAttr)
              listActivityEl.appendChild(startDateAttr)
              listActivityEl.appendChild(endDateAttr)
              listActivityEl.appendChild(title)
              listActivityEl.appendChild(list)
            }
          }
        },500)
        $("#calendarModal").modal("show")
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