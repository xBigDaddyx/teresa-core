<?php

namespace App\Listeners;

use App\Events\MakeOrderEvent;
use Domain\Purchases\Models\Order;
use Domain\Purchases\Models\OrderItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use League\CommonMark\Node\Query\OrExpr;

class MakeOrderListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MakeOrderEvent $event): void
    {
        //========== start create order =============//
        $new = new Order();
        $new->orderable_id = $event->request->approvable_id;
        $new->orderable_type = $event->request->approvable_type;
        $new->supplier_id = $event->data['supplier_id'];
        $new->category_id = $event->data['category_id'];
        $new->delivery_date = $event->data['delivery_date'];
        $new->payment_term = $event->data['payment_term'];
        $new->included_tax = $event->data['included_tax'];
        $new->tax_type = $event->data['tax_type'] ?? null;
        $new->capex_code = $event->data['capex_code'] ?? null;
        $new->comment = $event->data['comment'];
        $new->save();
        //========== end create order =============//

        //========== start create order items ==============/
        $requestItems = $event->request->approvable->requestItems;
        foreach ($requestItems as $item) {
            $new->orderItems()->saveMany([
                new OrderItem([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => 0,
                    'remark' => $item->remark,
                    'company_id' => auth('ldap')->user()->company->id,
                ]),
            ]);
        }
        //========== end create order items ==============/
    }
}
