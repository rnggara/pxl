@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Meeting Scheduler
                </h3>
            </div>
        </div>
        <div class="card-body">
            <div id="kt_calendar"></div>
        </div>
    </div>
@endsection
@section('custom_script')
<script>
    var KTCalendarBasic = function() {

        return {
            //main function to initiate the module
            init: function() {
                var todayDate = moment().startOf('day');
                var YM = todayDate.format('YYYY-MM');
                var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
                var TODAY = todayDate.format('YYYY-MM-DD');
                var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

                var calendarEl = document.getElementById('kt_calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                    themeSystem: 'bootstrap',
                    displayEventTime : false,
                    isRTL: KTUtil.isRTL(),
                    selectable: true,
                    dateClick : function (info){
                        window.location.href = "meeting-scheduler/"+btoa(info.dateStr)
                    },

                    header: {
                        left: 'prev,today',
                        center: 'title',
                        right: 'next'
                    },

                    height: 800,
                    contentHeight: 780,
                    aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio

                    nowIndicator: true,
                    now: TODAY + 'T08:00:00', // just for demo

                    views: {
                        dayGridMonth: {
                            buttonText: 'month'
                        },
                        timeGridWeek: {
                            buttonText: 'week'
                        },
                        timeGridDay: {
                            buttonText: 'day'
                        }
                    },

                    defaultView: 'dayGridMonth',
                    defaultDate: TODAY,

                    editable: true,
                    eventLimit: true, // allow "more" link when too many events
                    navLinks: true,
                    events: [

                        @foreach($schedules as $key => $schedule)
                        {
                            title: 'Meeting at: {{$schedule->ruangan}}',
                            url: '{{route('ms.book',['tanggal'=> base64_encode($schedule->tanggal),'id_room' => $schedule->id_ruangan])}}',
                            start: '{{$schedule->tanggal}}' + 'T{{$schedule->jam_masuk}}:00',
                            className: "fc-event-solid-info fc-event-light",
                            description: 'Start: {{$schedule->jam_masuk}} | End: {{$schedule->jam_keluar}}'
                        },
                        @endforeach
                    ],

                    eventRender: function(info) {
                        var element = $(info.el);

                        if (info.event.extendedProps && info.event.extendedProps.description) {
                            if (element.hasClass('fc-day-grid-event')) {
                                element.data('content', info.event.extendedProps.description);
                                element.data('placement', 'top');
                                KTApp.initPopover(element);
                            } else if (element.hasClass('fc-time-grid-event')) {
                                element.find('.fc-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
                            } else if (element.find('.fc-list-item-title').lenght !== 0) {
                                element.find('.fc-list-item-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
                            }
                        }
                    },

                });

                calendar.render();
            }
        };
    }();

    jQuery(document).ready(function() {
        KTCalendarBasic.init();
    });
</script>
@endsection
