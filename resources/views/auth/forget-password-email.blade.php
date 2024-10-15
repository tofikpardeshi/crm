<h1>Forget Password Email</h1>


You can reset password from below link:

<a href="{{ route('forget-password-send', $token) }}">Reset Password</a>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <strong>Dear User</strong>,
            <p class="text-justify"> We got a request to reset your Homents CRM Login Password. Please click on Password Reset Link to set a new password for your Login Account:</p>
            <p class="text-justify"> If you ignore this message, your password will not be changed. If you didn't request a password reset, let us know.</p>

            <strong>Thank You</strong>
        </div>
    </div>
</div>
