<?php
$action_params = array('view' => 'form', 'action' => 'save');
?>

<form action="<?php echo $this->getAdminPageUrl($action_params); ?>" method="post" id="pan-redaktor-settings-form">

    <?php wp_nonce_field($this->action_token); ?>

    <div class="pr-option-wrapper">
        <label>Selektory jQuery/CSS</label>
        <input type="text" class="pr-input" name="settings[pr_selectors]" id="pr-selectors" value="<?php echo $Settings->getField('pr_selectors'); ?>" />
        <?php if($Settings->hasError('pr_selectors')): ?>
        <p class="description error"><?php echo $Settings->getError('pr_selectors'); ?></p>
        <?php else: ?>
        <p class="description"><?php _e('To pole powinno zawierać selektor jQuery/CSS lub pozostać puste', 'pan-redaktor'); ?></p>
        <?php endif; ?>
    </div>

    <div class="pr-option-wrapper">
        <label>Usuwaj przy użyciu filtrów WordPress lub JavaScriptu</label>
        <select name="settings[pr_mode]" class="pr-input" id="pr-mode">
            <option value="">--</option>
            <option value="script" <?php if($Settings->getField('pr_mode')=='script') echo 'selected="selected"'; ?>>JavaScript</option>
            <option value="filter" <?php if($Settings->getField('pr_mode')=='filter') echo 'selected="selected"'; ?> disabled>Filtr WordPressa</option>
        </select>
        <?php if($Settings->hasError('pr_mode')): ?>
        <p class="description error"><?php echo $Settings->getError('pr_mode'); ?></p>
        <?php else: ?>
        <p class="description">Pan Redaktor może formatować tekst przy użyciu JavaScriptu po załadowaniu treści, lub poprzez filtry WordPressa przed jej wygenerowaniem. Wybierz któryś z tych trybów.</p>
        <?php endif; ?>
    </div>

    <p class="submit">
        <input type="submit" class="button-primary" value="Zapisz zmiany" />
    </p>

</form>
