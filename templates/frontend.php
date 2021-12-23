<script id="pan-redaktor-frontend-script" type="text/javascript">
    ( function ( $ ) {
        'use strict';
        console.log('Pan Redaktor przystępuje do pracy');
        $('<?php echo $selectors; ?>').each(function() {
            let textReplace = $(this).html();
            const lettersToReplace = ["a","i","o","u","w","z","A","I","O","U","W","Z"];
            const arrayLength = lettersToReplace.length;
            for (let i = 0; i < arrayLength; i++) {
                const textSplit = textReplace.split(' ' + lettersToReplace[i] + ' ');
                textReplace = textSplit.join(' ' + lettersToReplace[i] + '&nbsp;');
            }
            $(this).empty();
            $(this).html(textReplace);
        });
    }) (jQuery);
</script>
