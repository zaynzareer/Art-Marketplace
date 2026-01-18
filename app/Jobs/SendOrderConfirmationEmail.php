<?php

namespace App\Jobs;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Order $order)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Load relationships to avoid N+1 queries
        $this->order->load('buyer', 'seller', 'orderItems.product');
        
        // Send confirmation email to the buyer
        Mail::to($this->order->buyer->email)
            ->send(new OrderConfirmation($this->order, 'buyer'));
        
        // Send notification email to the seller
        Mail::to($this->order->seller->email)
            ->send(new OrderConfirmation($this->order, 'seller'));
    }
}
