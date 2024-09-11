<?php
    use Carbon\Carbon;

    $startDate = Carbon::parse($trip->tripStartDate)->format('Y-m-d');
    $endDate = Carbon::parse($trip->tripEndDate)->format('Y-m-d');
    $tripPhotos = json_decode($trip->tripPhoto, true);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <!-- Card Header -->
                <div
                    class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center rounded-top">
                    <h3 class="mb-0 fw-bold">Trip Information for <?php echo e($trip->tripLocation); ?></h3>
                </div>

                <form wire:submit.prevent="editTrip" class="p-4" enctype="multipart/form-data">
                    <!-- Editable Images -->
                    <div class="text-center mb-4">
                        <label for="tripPhotos" class="form-label fw-semibold d-block">
                            <div class="d-flex flex-wrap justify-content-center">
                                <!-- Check if there are any trip photos -->
                                <!--[if BLOCK]><![endif]--><?php if($tripPhotos && count($tripPhotos) > 0): ?>
                                    <!-- Loop through each trip photo and display with delete or replace option -->
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $tripPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="position-relative m-2">
                                            <!--[if BLOCK]><![endif]--><?php if(is_string($photo)): ?>
                                                <!-- Display existing photo (URL stored in the database) -->
                                                <img src="<?php echo e($photo); ?>"
                                                    class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer hover:opacity-50 transition-opacity duration-300"
                                                    style="max-width: 200px; height: 200px;" alt="Trip Image"
                                                    wire:click="selectImageToReplace(<?php echo e($index); ?>)" />
                                            <?php elseif($photo instanceof \Livewire\TemporaryUploadedFile): ?>
                                                <!-- Display new uploaded photo -->
                                                <img src="<?php echo e($photo->temporaryUrl()); ?>"
                                                    class="img-fluid img-thumbnail rounded shadow-sm cursor-pointer hover:opacity-50 transition-opacity duration-300"
                                                    style="max-width: 200px; height: 200px;" alt="Trip Image"
                                                    wire:click="selectImageToReplace(<?php echo e($index); ?>)" />
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                            <!-- Delete button to remove image -->
                                            <button type="button" wire:click="removeImage(<?php echo e($index); ?>)"
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-1 me-1">
                                                <i class='bx bx-trash-alt'></i>
                                            </button>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <?php else: ?>
                                    <!-- Default message when no images are available -->
                                    <p>No images available.</p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </label>

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

                                <!-- Display loading message or spinner when uploading -->
                                <div wire:loading wire:target="tripPhotos.<?php echo e($replaceIndex); ?>">
                                    Uploading...
                                </div>
                            </div>

                            <!-- Disable button when loading -->
                            <button type="button" wire:loading.attr="disabled"
                                wire:target="tripPhotos.<?php echo e($replaceIndex); ?>"
                                wire:click="replaceImage(<?php echo e($replaceIndex); ?>)" class="btn btn-primary">
                                Replace Image
                            </button>
                        <?php else: ?>
                            <!-- Button to add new image -->
                            <div class="mb-3">
                                <input type="file" wire:model="tripPhotos" class="form-control" multiple />
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tripPhotos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Success and Error Messages -->
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
                        <input type="text" id="tripLocation" wire:model="tripLocation" class="form-control" />
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tripLocation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        

                        <label for="tripDescription" class="form-label">Trip Description</label>
                        <textarea id="tripDescription" name="tripDescription" wire:model="tripDescription" class="form-control ckeditor"
                            rows="4"><?php echo e($this->tripDescription); ?></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tripDescription'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Activities -->
                    <div class="mb-3">
                        
                        <label for="tripActivities" class="form-label">Trip Activities</label>
                        <textarea id="tripActivities" name="tripActivities" wire:model="tripActivities" class="form-control ckeditor"
                            rows="4"><?php echo e($this->tripActivities); ?></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tripActivities'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>



                    <!-- Dates -->
                    <div class="mb-3">
                        <label for="tripStartDate" class="form-label">Trip Start Date</label>
                        <input type="date" id="tripStartDate" wire:model="tripStartDate" class="form-control" />
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tripStartDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div class="mb-3">
                        <label for="tripEndDate" class="form-label">Trip End Date</label>
                        <input type="date" id="tripEndDate" wire:model="tripEndDate" class="form-control" />
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tripEndDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Landscape -->
                    <div class="mb-4">
                        <label for="tripLandscape" class="form-label">Trip Landscape</label>
                        <select id="tripLandscape" name="form.tripLandscape"
                            class="form-select <?php echo e($errors->has('form.tripLandscape') ? 'is-invalid' : ''); ?>"
                            wire:model="form.tripLandscape">
                            <option value="<?php echo e($this->tripLandscape); ?>" selected disabled><?php echo e($this->tripLandscape); ?>

                            </option>
                            <option value="Beach">Beach</option>
                            <option value="City">City</option>
                            <option value="Country Side">Country Side</option>
                            <option value="Forested">Forested</option>
                            <option value="Mountainous">Mountainous</option>
                        </select>
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('form.tripLandscape'),'class' => 'invalid-feedback']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('form.tripLandscape')),'class' => 'invalid-feedback']); ?>
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
                        <select id="tripAvailability" wire:model="tripAvailability" class="form-select">
                            <option value="">Select Availability</option>
                            <option value="available">Available</option>
                            <option value="coming_soon">Coming Soon</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tripAvailability'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
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
</div><?php /**PATH /Applications/MAMP/htdocs/viveaventurascaribenas/resources/views/livewire/forms/edit-trip-form.blade.php ENDPATH**/ ?>