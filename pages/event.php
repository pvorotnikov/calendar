<?php
$error = NULL;
$name = NULL;
$description = NULL;
$start = NULL;
$end = NULL;

$id = $_GET['id'];

// query the database
$query = "SELECT * FROM event WHERE id=$id AND userId=" . $_SESSION['userid'];
if ($result = $db->conn->query($query)) {

    if (0 == $result->num_rows) {
        $error = "Event not found";
    } else {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $description = $row['description'];

        // format the start and the end to match the datetime picker format
        $start = new DateTime($row['start']);
        $start = $start->format('m/d/Y g:i A');
        $end = new DateTime($row['end']);
        $end = $end->format('m/d/Y g:i A');
    }
    $result->close();

} else {
    $error = $db->conn->error;
}
?>

<section id="page_<?php echo $page; ?>">

    <?php include_once('fragments/nav.php'); ?>

    <?php if ($error): ?>
    <div class="alert alert-warning" role="alert"><?php echo $error; ?></div>
    <?php else: ?>

    <form id="eventEditForm" class="form-inline" method="POST" action="ajax/calendar.php?do=update">
        <h1>Event</h1>
        <input type='hidden' id="eventId" name="eventId" value="<?php echo $id; ?>" />
        <fieldset>
            <div class="form-group">
                <div class='input-group date'>
                    <input type='text' class="form-control" required id="startTime" name="startTime" placeholder="Start" value="<?php echo $start; ?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class='input-group date'>
                    <input type='text' class="form-control" id="endTime" name="endTime" placeholder="End" value="<?php echo $end; ?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group width-100">
                <input type="text" class="form-control" required id="eventName" name="eventName" placeholder="Name" value="<?php echo $name; ?>" />
            </div>
        </fieldset>
        <fieldset>
            <div class="form-group width-100">
                <textarea class="form-control" id="eventDescription" name="eventDescription" placeholder="Event Description"><?php echo $description; ?></textarea>
            </div>
        </fieldset>
        <fieldset>
            <a href="#" class="btn btn-danger btn-raised" id="eventDelete" data-eventid="<?php echo $id; ?>">Delete</a>
            <button type="submit" class="btn btn-primary btn-raised pull-right">Save</button>
            <a href="/?page=calendar" class="btn btn-default btn-raised pull-right">Cancel</a>
        </fieldset>
        <div class="clearfix"></div>
    </form>

    <?php endif; ?>

</section>
