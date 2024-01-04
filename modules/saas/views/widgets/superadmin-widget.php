<div class="widget" id="widget-<?php echo basename(__FILE__, '.php'); ?>" data-name="<?php echo _l('tenants_stats'); ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body padding-10">
                    <div class="widget-dragger ui-sortable-handle"></div>
                    <p class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse tw-p-1.5">
                        <span class="tw-text-neutral-700"><?php echo _l('tenants_stats'); ?></span>
                    </p>
                    <hr class="-tw-mx-3 tw-mt-3 tw-mb-6">
                    <dl class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-6 tw-gap-3 sm:tw-gap-5">
                        <?php
                        foreach (getStatsForSuperadmin() as $key => $value) {
                            echo '<div class="tw-border tw-border-solid tw-border-neutral-200 tw-rounded-md tw-bg-white">
                            <div class="tw-px-4 tw-py-5 sm:tw-px-4 sm:tw-py-2">
                            <dt class="tw-font-medium text-success">'._l($key).'</dt>
                            <dd class="tw-mt-1 tw-flex tw-items-baseline tw-justify-between md:tw-block lg:tw-flex">
                            <div class="tw-flex tw-items-baseline tw-text-base tw-font-semibold tw-text-primary-600">'.$value.'</div>
                            </dd>
                            </div>
                            </div>';
                        }
                        ?>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>