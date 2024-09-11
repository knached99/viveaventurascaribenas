<!--[if BLOCK]><![endif]--><?php if($enabled): ?>
    <div id="<?php echo e($nameFieldName); ?>_wrap" style="display: none" aria-hidden="true">
        <input id="<?php echo e($nameFieldName); ?>"
               name="<?php echo e($nameFieldName); ?>"
               type="text"
               value=""
               <?php if($livewireModel ?? false): ?> wire:model.defer="<?php echo e($livewireModel); ?>.<?php echo e($unrandomizedNameFieldName); ?>" <?php endif; ?>
               autocomplete="nope"
               tabindex="-1">
        <input name="<?php echo e($validFromFieldName); ?>"
               type="text"
               value="<?php echo e($encryptedValidFrom); ?>"
               <?php if($livewireModel ?? false): ?> wire:model.defer="<?php echo e($livewireModel); ?>.<?php echo e($validFromFieldName); ?>" <?php endif; ?>
               autocomplete="off"
               tabindex="-1">
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH /Applications/MAMP/htdocs/viveaventurascaribenas/vendor/spatie/laravel-honeypot/src/../resources/views/honeypotFormFields.blade.php ENDPATH**/ ?>