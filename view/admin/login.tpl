<div class="row">
    <div class="col-sm-3">&nbsp;</div>
    <div class="col-sm-6">
        <form method="POST" action="/admin/login/" class="login-form">
            <div class="form-group">
                <input class="form-control login-field" value="" name="user_name" placeholder="Enter your name" id="user_name" type="text">
                <label class="login-field-icon fui-user" for="login-name"></label>
            </div>

            <div class="form-group">
                <input class="form-control login-field" value="" placeholder="Password" name="user_password" id="login-password" type="password">
                <label class="login-field-icon fui-lock" for="login-pass"></label>
            </div>

            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Log in">
            <a class="login-link" href="/admin/lostpassword/">Lost your password?</a>
        </form>
    </div>
    <div class="col-sm-3">&nbsp;</div>
</div>
