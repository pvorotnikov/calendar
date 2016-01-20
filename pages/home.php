
<section id="page_<?php echo $page; ?>">
    <form id="loginForm" method="POST" action="ajax/passport.php?do=login">
        <h1>Login</h1>
        <div class="form-group">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" />
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary btn-raised">Login</button>
        <a href="/?page=signup" class="btn btn-default btn-raised pull-right">Sign up</a>
    </form>
</section>
