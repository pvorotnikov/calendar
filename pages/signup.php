
<section id="page_<?php echo $page; ?>">
    <form id="signupForm" method="POST" action="ajax/passport.php?do=signup">
        <h1>Sign Up</h1>
        <div class="form-group">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" />
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="passwordrepeat" name="passwordrepeat" placeholder="Retype password">
        </div>
        <div class="form-group">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
        </div>
        <button type="submit" class="btn btn-primary btn-raised">Register</button>
        <a href="/" class="btn btn-default btn-raised pull-right">Log in</a>
    </form>
</section>
