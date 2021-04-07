<div class="wrap">

    <h2>Pan Redaktor</h2>
    <div class="notice notice-info">
    <p>Witaj na stronie konfiguracji Pana Redaktora, pluginu, który pomoże Ci wyświetlić tekst zgodnie z redułami łamania tekstu.</p>
    <p>Obecnie Pan Redaktor potrafi usuwać wiszące spójniki.</p>
    </div>

    <?php if($this->hasFlashMsg()): ?>
    <!--<div id="message" class="updated">-->
    <div id="message" class="<?php echo $this->getFlashMsgStatus(); ?>">
        <p><?php echo $this->getFlashMsg(); ?></p>
    </div>
    <?php endif; ?>


    <?php require_once $view; ?>

    <br style="clear: both;">

</div>
