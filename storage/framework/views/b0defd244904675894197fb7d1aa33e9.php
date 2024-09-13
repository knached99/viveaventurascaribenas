<section class="ftco-section py-5">
    <div class="container">
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 heading-section text-center ">
                <h2 class="mb-4">Share Your Travel Experience with Us!</h2>
                <p class="text-secondary">Your feedback helps us improve and inspires other travelers. We'd love to hear
                    about your journey!</p>
                <div>
                    <!--[if BLOCK]><![endif]--><?php if($status): ?>
                        <div class="mb-4 alert alert-success" role="alert">
                            <?php echo e($status); ?>

                        </div>
                    <?php elseif($error): ?>
                        <div class="mb-4 alert alert-danger" role="alert">
                            <?php echo e($error); ?>

                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="blog-entry p-4  shadow-md rounded bg-light">
                        <form class="row g-3" wire:submit.prevent="submitTestimonialForm">
                            <?php if (isset($component)) { $__componentOriginal8fff30dd5a0e966db3cfb0c9de86d446 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fff30dd5a0e966db3cfb0c9de86d446 = $attributes; } ?>
<?php $component = Spatie\Honeypot\View\HoneypotComponent::resolve(['livewireModel' => 'extraFields'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('honeypot'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Spatie\Honeypot\View\HoneypotComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fff30dd5a0e966db3cfb0c9de86d446)): ?>
<?php $attributes = $__attributesOriginal8fff30dd5a0e966db3cfb0c9de86d446; ?>
<?php unset($__attributesOriginal8fff30dd5a0e966db3cfb0c9de86d446); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fff30dd5a0e966db3cfb0c9de86d446)): ?>
<?php $component = $__componentOriginal8fff30dd5a0e966db3cfb0c9de86d446; ?>
<?php unset($__componentOriginal8fff30dd5a0e966db3cfb0c9de86d446); ?>
<?php endif; ?>
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="text" wire:model="name" name="name"
                                        class="form-control <?php echo e($errors->has('name') ? 'border border-danger' : ''); ?>"
                                        placeholder="First Name">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
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
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="email" wire:model="email"
                                        class="form-control <?php echo e($errors->has('email') ? 'border border-danger' : ''); ?>"
                                        placeholder="Email (required for follow-up only)">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
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
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <select
                                    class="form-control <?php echo e($errors->has('tripID') ? 'border border-danger' : ''); ?>"
                                        wire:model="tripID">
                                        <option value="" disabled selected>Where did you travel with us?</option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($trip['tripID']); ?>"> <?php echo e($trip['tripLocation']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>

                                    
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tripID'];
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
                            </div>
                              <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Travel Date</label>
                                <input wire:model="trip_date" type="month" max="<?php echo e(date('Y-m')); ?>" value="<?php echo e(date('Y-m')); ?>" class="form-control <?php echo e($errors->has('trip_date') ? 'border border-danger' : ''); ?>">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['trip_date'];
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
                        </div>
                            <div class="col-12">
                                <div
                                    class="form-group <?php echo e($errors->has('trip_rating') ? 'border border-danger' : ''); ?>">
                                    <label class="form-label">Rate Your Experience (1-5 Stars)</label>
                                    <div class="rating">
                                        <input wire:model="trip_rating" type="radio" name="rating" value="5"
                                            id="5">
                                        <label for="5">☆</label>
                                        <input wire:model="trip_rating" type="radio" name="rating" value="4"
                                            id="4">
                                        <label for="4">☆</label>
                                        <input wire:model="trip_rating" type="radio" name="rating" value="3"
                                            id="3">
                                        <label for="3">☆</label>
                                        <input wire:model="trip_rating" type="radio" name="rating" value="2"
                                            id="2">
                                        <label for="2">☆</label>
                                        <input wire:model="trip_rating" type="radio" name="rating" value="1"
                                            id="1">
                                        <label for="1">☆</label>

                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['trip_rating'];
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
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea wire:model="testimonial" class="form-control <?php echo e($errors->has('testimonial') ? 'border border-danger' : ''); ?>"
                                        rows="7" placeholder="Tell us about your experience (What made your trip special?)"></textarea>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['testimonial'];
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
                            </div>
                            <div class="col-12">
                                <div
                                    class="form-group d-flex align-items-center <?php echo e($errors->has('consent') ? 'border border-danger' : ''); ?>">
                                    <input wire:model="consent" type="checkbox" class="form-check-input me-2"
                                        id="consent" name="consent">
                                    <label class="form-label mb-0 m-3" for="consent">I consent to my testimonial being
                                        used on the website</label>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['consent'];
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
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100 py-3"
                                        wire:loading.remove>Submit Testimonial</button>
                                    <div class="spinner-border text-primary" role="status" wire:loading></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</section>
<?php /**PATH C:\xampp\htdocs\viveaventurascaribenas\resources\views/livewire/forms/testimonial-form.blade.php ENDPATH**/ ?>