<?php
$pageTitle = "Inscription";
require "../includes/header.php";
?>

<div class="auth-split">
    <div class="auth-image">
        <h2><?php echo __('auth_welcome'); ?></h2>
        <p><?php echo __('auth_welcome_subtitle'); ?></p>
        <div style="margin-top:40px; font-size:3rem; opacity:0.2;">🚀</div>
    </div>
    <div class="auth-form-side">
        <div class="auth-header">
            <h3><?php echo __('auth_register_title'); ?></h3>
            <p><?php echo __('auth_register_subtitle'); ?></p>
        </div>

        <form method="POST" action="../register_process.php">
            <div class="form-group">
                <label><?php echo __('auth_name_label'); ?></label>
                <input type="text" name="name" placeholder="Ex: Ahmed Alaoui" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="ahmed@exemple.com" required>
            </div>

            <div class="form-group">
                <label><?php echo __('auth_password_label'); ?></label>
                <input type="password" name="password" placeholder="Minimum 8 caractères" required>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label><?php echo __('auth_bac_label'); ?></label>
                    <select name="bac_branch" required>
                        <option value="Sciences Math">Sciences Math</option>
                        <option value="PC">PC</option>
                        <option value="SVT">SVT</option>
                        <option value="Economie">Economie</option>
                        <option value="Technique">Technique</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo __('auth_average_label'); ?></label>
                    <input type="number" step="0.01" name="average" placeholder="Ex: 15.5" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="padding: 14px;"><?php echo __('auth_register_btn'); ?></button>
        </form>

        <div class="form-footer">
            <p><?php echo __('auth_have_account'); ?> <a href="login.php"><?php echo __('login'); ?></a></p>
        </div>
    </div>
</div>

<?php require "../includes/footer.php"; ?>