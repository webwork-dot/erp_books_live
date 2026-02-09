<?php
$orders = isset($stationery_orders) && is_array($stationery_orders) ? $stationery_orders : array();
?>
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Stationery Orders</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ul>
        </div>
    </div>
</div>

<div style="border-bottom: 1px solid #e0e0e0;padding-bottom: 10px;">
    <div class="card-header py-2">
        <h6 class="mb-0 fs-14">
            <i class="isax isax-shopping-bag me-2"></i>Stationery Orders
        </h6>
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem);">
                <a href="javascript:void(0);" class="text-decoration-none">
                    <div class="card border-primary order-counter-card" style="cursor: pointer; transition: all 0.3s ease;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">New Order</p>
                                    <h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($orders['new_order']) ? number_format($orders['new_order']) : 0; ?></h6>
                                </div>
                                <span class="avatar avatar-32 avatar-rounded bg-primary-subtle text-primary flex-shrink-0 ms-2">
                                    <i class="isax isax-add-circle fs-16"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
                <a href="javascript:void(0);" class="text-decoration-none">
                    <div class="card border-info order-counter-card" style="cursor: pointer; transition: all 0.3s ease;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Processing</p>
                                    <h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($orders['processing']) ? number_format($orders['processing']) : 0; ?></h6>
                                </div>
                                <span class="avatar avatar-32 avatar-rounded bg-info-subtle text-info-emphasis flex-shrink-0 ms-2">
                                    <i class="isax isax-refresh-2 fs-16"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
                <a href="javascript:void(0);" class="text-decoration-none">
                    <div class="card border-secondary order-counter-card" style="cursor: pointer; transition: all 0.3s ease;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Out For Delivery</p>
                                    <h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($orders['out_for_delivery']) ? number_format($orders['out_for_delivery']) : 0; ?></h6>
                                </div>
                                <span class="avatar avatar-32 avatar-rounded bg-secondary-subtle text-secondary-emphasis flex-shrink-0 ms-2">
                                    <i class="isax isax-truck-fast fs-16"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl" style="flex: 0 0 calc(20% - 0.4rem); max-width: calc(20% - 0.4rem); width: calc(20% - 0.4rem);">
                <a href="javascript:void(0);" class="text-decoration-none">
                    <div class="card border-success order-counter-card" style="cursor: pointer; transition: all 0.3s ease;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <p class="mb-0 text-truncate text-gray-9 fs-12" style="font-size: 0.875rem !important; line-height: 1.2 !important; margin-bottom: 4px !important;">Delivered</p>
                                    <h6 class="fs-16 fw-semibold mb-0" style="font-size: 1.5rem !important; line-height: 1.2 !important; margin-bottom: 0px !important;"><?php echo isset($orders['delivered']) ? number_format($orders['delivered']) : 0; ?></h6>
                                </div>
                                <span class="avatar avatar-32 avatar-rounded bg-success-subtle text-success-emphasis flex-shrink-0 ms-2">
                                    <i class="isax isax-tick-circle fs-16"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
