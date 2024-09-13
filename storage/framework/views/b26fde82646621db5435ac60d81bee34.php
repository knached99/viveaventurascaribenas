<?php
    use Carbon\Carbon;

    $startDate = Carbon::parse($trip->tripStartDate)->format('Y-m-d');
    $endDate = Carbon::parse($trip->tripEndDate)->format('Y-m-d');
    $tripPhotos = json_decode($trip->tripPhoto, true);
    $acive = $trip->active; 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <!-- Card Header -->
                <div
                    class="card-header bg-slate-200 text-white d-flex justify-content-between align-items-center rounded-top">
                    <h3 class="mb-0">Trip Information for <?php echo e($trip->tripLocation); ?></h3>
                </div>

                <form wire:submit.prevent="editTrip" class="p-4" enctype="multipart/form-data">
                    <!-- Editable Images -->
                    <div class="text-center mb-4">
                        <label for="tripPhotos" class="form-label fw-semibold d-block mb-2">Trip Photos</label>
                        <div class="d-flex flex-wrap justify-content-center">
                            <!--[if BLOCK]><![endif]--><?php if($tripPhotos && count($tripPhotos) > 0): ?>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $tripPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="position-relative m-2">
                                        <!--[if BLOCK]><![endif]--><?php if(is_string($photo)): ?>
                                            <img src="<?php echo e($photo); ?>"
                                                class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer"
                                                style="max-width: 150px; height: 150px;" alt="Trip Image"
                                                wire:click="selectImageToReplace(<?php echo e($index); ?>)" />
                                        <?php elseif($photo instanceof \Livewire\TemporaryUploadedFile): ?>
                                            <img src="<?php echo e($photo->temporaryUrl()); ?>"
                                                class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer"
                                                style="max-width: 150px; height: 150px;" alt="Trip Image"
                                                wire:click="selectImageToReplace(<?php echo e($index); ?>)" />
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                        <button type="button" wire:click="removeImage(<?php echo e($index); ?>)"
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-1 me-1">
                                            <i class='bx bx-trash-alt'></i>
                                        </button>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                                <p>No images available.</p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!--[if BLOCK]><![endif]--><?php if(!is_null($replaceIndex)): ?>
                            <div class="mb-3">
                                <input type="file" wire:model="tripPhotos.<?php echo e($replaceIndex); ?>"
                                    class="form-control" />
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tripPhotos.' . $replaceIndex];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                <div wire:loading wire:target="tripPhotos.<?php echo e($replaceIndex); ?>">
                                    <span>Uploading...</span>
                                </div>
                            </div>

                            <button type="button" wire:loading.attr="disabled"
                                wire:target="tripPhotos.<?php echo e($replaceIndex); ?>"
                                wire:click="replaceImage(<?php echo e($replaceIndex); ?>)" class="btn btn-primary">
                                Replace Image
                            </button>
                        <?php else: ?>
                            <div class="mb-3">
                                <input type="file" wire:model="tripPhotos" class="form-control  <?php echo e($errors->has('tripPhotos.*') ? 'is-invalid' : ''); ?>" multiple />
                                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('tripPhotos.*'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('tripPhotos.*')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!--[if BLOCK]><![endif]--><?php if($imageReplaceSuccess): ?>
                            <div class="alert alert-success">
                                <?php echo e($imageReplaceSuccess); ?>

                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!--[if BLOCK]><![endif]--><?php if($imageReplaceError): ?>
                            <div class="alert alert-danger">
                                <?php echo e($imageReplaceError); ?>

                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Form Fields -->
                    <!-- Location -->
                    <div class="mb-3">
                        <label for="tripLocation" class="form-label">Trip Location</label>
                        <input type="text" id="tripLocation" wire:model="tripLocation" class="form-control <?php echo e($errors->has('tripLocation') ? 'is-invalid' : ''); ?>" />
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('tripLocation'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('tripLocation')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="tripDescription" class="form-label">Trip Description</label>
                        <textarea id="tripDescription" name="tripDescription" wire:model="tripDescription" class="form-control ckeditor <?php echo e($errors->has('tripDescription') ? 'is-invalid' : ''); ?>"
                            rows="4"><?php echo e($this->tripDescription); ?></textarea>
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('tripDescription'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('tripDescription')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                    </div>

                    <!-- Activities -->
                    <div class="mb-3">
                        <label for="tripActivities" class="form-label">Trip Activities</label>
                        <textarea id="tripActivities" name="tripActivities" wire:model="tripActivities" class="form-control ckeditor <?php echo e($errors->has('tripActivities') ? 'is-invalid' : ''); ?>"
                            rows="4"><?php echo e($this->tripActivities); ?></textarea>
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('tripActivities'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('tripActivities')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                    </div>

                    <!-- Dates -->
                    <div class="mb-3">
                        <label for="tripStartDate" class="form-label">Trip Start Date</label>
                        <input type="date" id="tripStartDate" wire:model="tripStartDate" class="form-control <?php echo e($errors->has('tripStartDate') ? 'is-invalid' : ''); ?>" />
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('tripStartDate'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('tripStartDate')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="tripEndDate" class="form-label">Trip End Date</label>
                        <input type="date" id="tripEndDate" wire:model="tripEndDate" class="form-control <?php echo e($errors->has('tripEndDate') ? 'is-invalid' : ''); ?>" />
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('tripEndDate'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('tripEndDate')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                    </div>

                    <!-- Landscape -->
                    <div class="mb-4">
                        <label for="tripLandscape" class="form-label">Trip Landscape</label>
                        <select id="tripLandscape" wire:model="tripLandscape" class="form-select <?php echo e($errors->has('tripLandscape') ? 'is-invalid' : ''); ?>">
                            <option value="" disabled>Select Landscape</option>
                            <option value="Beach">Beach</option>
                            <option value="City">City</option>
                            <option value="Country Side">Country Side</option>
                            <option value="Forested">Forested</option>
                            <option value="Mountainous">Mountainous</option>
                        </select>
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('tripLandscape'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('tripLandscape')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                    </div>

                    <!-- Availability -->
                    <div class="mb-3">
                        <label for="tripAvailability" class="form-label">Trip Availability</label>
                        <select id="tripAvailability" wire:model="tripAvailability" class="form-select <?php echo e($errors->has('tripAvailability' ? 'is-invalid' : '')); ?>">
                            <option value="" disabled>Select Availability</option>
                            <option value="available">Available</option>
                            <option value="coming_soon">Coming Soon</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('tripAvailability'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('tripAvailability')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                    </div>

                    <!-- Trip Price -->
                    <div class="mb-3">
                        <label for="tripPrice" class="form-label">Trip Price</label>
                        <input id="tripPrice" wire:model="tripPrice" class="form-control <?php echo e($errors->has('tripPrice') ? 'is-invalid' : ''); ?>" placeholder="$1.00" />
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('tripPrice'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('tripPrice')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                    </div>

                    


                        <div class="mb-4">
                            <label for="tripCosts" class="form-label">Trip Costs</label>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $tripCosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cost): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $index = (int) $index; // Ensure $index is an integer
                                ?>
                                <div class="input-group mb-2">
                                    <input type="text" placeholder="Cost Name" class="form-control"
                                        wire:model="tripCosts.<?php echo e($index); ?>.name" aria-label="Cost Name">

                                    <input type="number" placeholder="Cost Amount" class="form-control"
                                        wire:model="tripCosts.<?php echo e($index); ?>.amount"
                                        aria-label="Cost Amount">

                                    <button type="button" class="btn btn-danger"
                                        wire:click="removeCost(<?php echo e($index); ?>)">Remove</button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->


                            <button type="button" class="btn btn-success" wire:click="addCost">Add Cost</button>

                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('form.tripCosts'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('form.tripCosts')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                        </div>

                        <div class="mb-4">
                        <input type="text" placeholder="Enter number of available slots" wire:model="num_trips" name="num_trips" class="form-control <?php echo e($errors->has('num_trips') ? 'is-invalid' : ''); ?>" />
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('num_trips'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('num_trips')),'class' => 'invalid-feedback']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                        </div>

                         <!-- Active or Inactive --> 
                        <div class="mb-4">
                            <span class="text-secondary"><?php echo e($active ? 'This trip is accessible publicly' : 'This trip is inactive and not accessible publicly'); ?></span>

                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" wire:model="active">
                                <label class="form-check-label" for="active"><?php echo e($active ? 'Active' : 'Inactive'); ?></label>
                            </div>
                        </div>


                    <!-- Summary Section -->
                    <div class="mb-4">
                        <h4 class="mb-3">Financial Summary</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total Net Cost:</strong>
                            <span>$<?php echo e(number_format($totalNetCost, 2)); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Gross Profit:</strong>
                            <span>$<?php echo e(number_format($grossProfit, 2)); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Net Profit:</strong>
                            <span class="<?php echo e($netProfit < 0 ? 'text-danger' : 'text-success'); ?>">
                                $<?php echo e(number_format($netProfit, 2)); ?>

                            </span>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>

                    <!-- Success and Error Messages -->
                    <!--[if BLOCK]><![endif]--><?php if($success): ?>
                        <div class="alert alert-success">
                            <?php echo e($success); ?>

                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!--[if BLOCK]><![endif]--><?php if($error): ?>
                        <div class="alert alert-danger">
                            <?php echo e($error); ?>

                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/livewire/forms/edit-trip-form.blade.php ENDPATH**/ ?>