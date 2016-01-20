<?php
$username = '';
$email = '';
$error = NULL;

// query the database
$query = "SELECT username, email FROM user WHERE id=" . $_SESSION['userid'];
if ($result = $db->conn->query($query)) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $email = $row['email'];
    $result->close();
} else {
    $error = $db->conn->error;
}
?>

<section id="page_<?php echo $page; ?>">

    <?php include_once('fragments/nav.php'); ?>

    <?php if ($error): ?>
    <div class="alert alert-warning" role="alert"><?php echo $error; ?></div>
    <?php endif; ?>

    <form id="settingsForm" method="POST" action="ajax/settings.php?do=update">
        <h1>Settings</h1>
        <div class="form-group">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $username; ?>" disabled />
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Change password">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="passwordrepeat" name="passwordrepeat" placeholder="Retype password">
        </div>
        <div class="form-group">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $email; ?>">
        </div>
        <button type="submit" class="btn btn-primary btn-raised pull-right">Save</button>
        <div class="clearfix"></div>
    </form>

</section>
