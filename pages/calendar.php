
<section id="page_<?php echo $page; ?>">

    <?php include_once('fragments/nav.php'); ?>

    <?php if ($error): ?>
    <div class="alert alert-warning" role="alert"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="panel panel-default">

        <div class="panel-heading">
            <a href="#addEventPanel" class="btn btn-primary btn-raised" id="addEvent">
                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add Event
            </a>
        </div>

        <div class="panel-body hidden" id="addEventPanel">
            <form id="eventForm" class="form-inline" method="POST" action="ajax/calendar.php?do=add">
                <fieldset>
                    <div class="form-group">
                        <div class='input-group date'>
                            <input type='text' class="form-control" required id="startTime" name="startTime" placeholder="Start" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='input-group date'>
                            <input type='text' class="form-control" id="endTime" name="endTime" placeholder="End" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <div class="form-group width-100">
                        <input type="text" class="form-control" required id="eventName" name="eventName" placeholder="Name" />
                    </div>
                </fieldset>
                <fieldset>
                    <div class="form-group width-100">
                        <textarea class="form-control" id="eventDescription" name="eventDescription" placeholder="Event Description"></textarea>
                    </div>
                </fieldset>
                <fieldset>
                    <button type="submit" class="btn btn-primary btn-raised pull-right">Save</button>
                </fieldset>
                <div class="clearfix"></div>
            </form>
        </div>

        <div class="panel-heading" id="calendarControl">
            <a href="#" class="btn btn-primary pull-right" data-action='today'>Today</a>
            <a href="#" class="btn btn-primary pull-right" data-action='next'>
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            </a>
            <div class="btn-group pull-right">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Month <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#" data-action="day">Day</a></li>
                    <li><a href="#" data-action="week">Week</a></li>
                    <li><a href="#" data-action="month">Month</a></li>
                    <li><a href="#" data-action="year">Year</a></li>
                </ul>
            </div>
            <a href="#" class="btn btn-primary pull-right" data-action='prev'>
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            </a>
            <h2 id="calendarHeading"></h2>
        </div>

        <div class="panel-body" id="calendar"></div>
    </div>



</section>

<div class="modal fade" id="events-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>Event</h3>
            </div>
            <div class="modal-body" style="height: 400px">
            </div>
            <div class="modal-footer">
                <a href="#" data-dismiss="modal" class="btn">Close</a>
            </div>
        </div>
    </div>
</div>
