<?php
$pageTitle = "Connexion";
require "../includes/header.php";
?>

<div class="auth-split">
    <div class="auth-image">
        <h2><?php echo __('auth_welcome'); ?></h2>
        <p><?php echo __('auth_welcome_subtitle'); ?></p>
        <div style="margin-top:40px; font-size:3rem; opacity:0.2;">🎓</div>
    </div>
    <div class="auth-form-side">
        <div class="auth-header">
            <h3><?php echo __('auth_login_title'); ?></h3>
            <p><?php echo __('auth_login_subtitle'); ?></p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="msg msg-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="msg msg-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <form method="POST" action="../login_process.php">
            <div class="form-group">
                <label><?php echo __('auth_email_label'); ?></label>
                <input type="email" name="email" placeholder="nom@exemple.com" required>
            </div>

            <div class="form-group">
                <label><?php echo __('auth_password_label'); ?></label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="padding: 14px;"><?php echo __('auth_login_btn'); ?></button>
        </form>

        <div class="form-footer">
            <p><?php echo __('auth_no_account'); ?> <a href="register.php"><?php echo __('auth_register_free'); ?></a></p>
        </div>
    </div>
</div>

<?php require "../includes/footer.php"; ?>