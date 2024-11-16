<div class="login-wrapper">
    <img src="/img/login-page-img.png" alt="img" class="login-image">
    <div class="login-form-wrapper">
        <?= $this->Form->create(null, ['class' => 'validation_engine']) ?>
        <?php echo $this->Form->control('type', ['type' => 'hidden', 'value' => '0', 'id' => 'Login-type']) ?>
        <div class="login-logo">
            <img src="/img/login-page-img.png" alt="logo">
        </div>
        <h3>Login to your account</h3>
        <div class="loginForm auth_form_design">
            <div class="input_group">

                <?= $this->Form->control('email', ['class' => 'form-control validate[required,custom[email]]', 'required' => false, 'type' => 'text']) ?>

            </div>

            <div class="password-container">
                <label for="password">Password</label>
                <div class="passwordbox">
                    <input type="password" name="password" required="required" id="password" class="form-control" aria-required="true">
                    <a href="javascript:void(0)" class="show-password"><i class="fa fa-eye" aria-hidden="true"></i></a>
                </div>
                <div class="forgot-password">
                    <a href="javascript:void(0);" class="flip-login">Forget Password?</a>
                </div>
            </div>
        </div>

        <div class="forget-passwordform auth_form_design" style="display:none;">
            <div class="input_group">
                <?= $this->Form->control('email_reset', ['class' => 'form-control validate[required,custom[email]]', 'required' => false, 'type' => 'text']) ?>
            </div>
            <div class="forgot-password">
                <a href="javascript:void(0);" class="flip-login">Back To Login</a>
            </div>
        </div>
        <div class="form-action">
            <?= $this->Form->submit(__('Submit'), ['class' => 'submit-btn']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<script>
    $(document).on('click', '.show-password', function() {
        $(this).parent().find('input').attr('type', $(this).parent().find('input').attr('type') == 'password' ? 'text' : 'password');
        $(this).children().toggleClass('fa-eye-slash');
    })
</script>

<script>
    $('.flip-login').on('click', function() {
        $('.loginForm').toggle();
        $('.forget-passwordform').toggle();

        if ($('#Login-type').val() == 0) {
            $('#Login-type').val(1);
        } else {
            $('#Login-type').val(0);
        }
    })
    //     function togglePasswordVisibility() {
    //     var passwordField = document.getElementById("password");
    //     var eyeIcon = document.querySelector(".eye-icon img");

    //     if (passwordField.type === "password") {
    //       passwordField.type = "text";
    //       eyeIcon.src = "eye-open.png";
    //     } else {
    //       passwordField.type = "password";
    //       eyeIcon.src = "eye-closed.png";
    //     }
    //   }
</script>