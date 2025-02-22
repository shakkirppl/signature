<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\SupplierPaymentMaster;
use App\Observers\SupplierPaymentObserver;
use App\Models\PurchaseOrder;
use App\Observers\PurchaseOrderObserver;
use App\Models\SalesOrder;
use App\Observers\SalesOrderObserver;
use App\Models\CustomerPayment;
use App\Observers\CustomerPaymentObserver;
use App\Models\SalesPayment;
use App\Observers\SalesObserver;
use App\Models\PurchaseConformation;
use App\Observers\PurchaseConformationObserver;
use App\Models\OpeningBalance;
use App\Observers\OpeningBalanceObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        SupplierPaymentMaster::observe(SupplierPaymentObserver::class);
        PurchaseOrder::observe(PurchaseOrderObserver::class);
        SalesOrder::observe(SalesOrderObserver::class);
        CustomerPayment::observe(CustomerPaymentObserver::class);
        SalesPayment::observe(SalesObserver::class);
        PurchaseConformation::observe(PurchaseConformationObserver::class);
        OpeningBalance::observe(OpeningBalanceObserver::class);








    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
