<script src="calendar/js/jquery.min.js"></script>
<script src="calendar/js/moment.min.js"></script>
<script src="calendar/js/fullcalendar.min.js"></script>

<link href='calendar/css/fullcalendar.min.css' rel='stylesheet' />
<script src="calendar/js/fullcalendar.mn.js"></script>
<script src='calendar/js/locale-all.js'></script>
<script src='calendar/js/fullcalendar/fullcalendar.js'></script>
<script src='calendar/js/fullcalendar/lang-all.js'></script>

<div id="calendar"></div>
<script>
   
$('#calendar').fullCalendar({
    lang: 'es',
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
    },
      defaultDate: '<?php echo date('Y-m-d');?>',      
      buttonIcons: true, // show the prev/next text
      weekNumbers: true,
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      
    events: [
        <?php foreach ($events as $event): ?>
        {
            id:    <?php echo "'".$event->id."'";?>,
            title: <?php echo "'".$event->asunto." - ".$event->nombres." - ".$event->sede_fk."'";?>,
            start: <?php echo "'".$event->fechai."'";?>,
            end:   <?php echo "'".$event->fechat."'";?>,
            color: <?php echo "'".$event->color."'";?>
        },
        <?php endforeach; ?>
    ]
});

	</script>
