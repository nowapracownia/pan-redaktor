<script type="text/javascript">
    console.log('Pan Redaktor przystępuje do pracy');
    jQuery('<?php echo $selectors; ?>').each(function() {
        var textReplace = jQuery(this).html();
        var lettersToReplace = ["a","i","o","u","w","z","A","I","O","U","W","Z"];
        var arrayLength = lettersToReplace.length;
        for (var i = 0; i < arrayLength; i++) {
            var textSplit = textReplace.split(' ' + lettersToReplace[i] + ' ');
            var textReplace = textSplit.join(' ' + lettersToReplace[i] + '&nbsp;');
        }
        jQuery(this).empty();
        jQuery(this).html(textReplace);
    });
</script>
