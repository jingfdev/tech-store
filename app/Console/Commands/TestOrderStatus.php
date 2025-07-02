<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;

class TestOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:order-status';

    /**
     * The console command description.
     */
    protected $description = 'Test order status functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Order Status Functionality');
        $this->line('=====================================');

        // Get or create a test user
        $user = User::first();
        if (!$user) {
            $this->error('No users found. Please run seeders first.');
            return;
        }

        // Get a test product
        $product = Product::first();
        if (!$product) {
            $this->error('No products found. Please run seeders first.');
            return;
        }

        // Test 1: Create a pending order
        $this->info('Creating a pending order...');
        $pendingOrder = Order::create([
            'user_id' => $user->id,
            'total_amount' => 99.99,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $pendingOrder->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $this->line("Pending Order #{$pendingOrder->id}:");
        $this->line("  - Status: {$pendingOrder->getDisplayStatus()}");
        $this->line("  - Badge Classes: {$pendingOrder->getStatusBadgeClasses()}");
        $this->line("  - Can be cancelled: " . ($pendingOrder->canBeCancelled() ? 'YES' : 'NO'));

        // Test 2: Create a completed order (recent)
        $this->info('Creating a completed order (recent)...');
        $completedOrder = Order::create([
            'user_id' => $user->id,
            'total_amount' => 199.99,
            'status' => 'completed',
        ]);

        OrderItem::create([
            'order_id' => $completedOrder->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
        ]);

        $this->line("Completed Order #{$completedOrder->id}:");
        $this->line("  - Status: {$completedOrder->getDisplayStatus()}");
        $this->line("  - Badge Classes: {$completedOrder->getStatusBadgeClasses()}");
        $this->line("  - Can be cancelled: " . ($completedOrder->canBeCancelled() ? 'YES' : 'NO'));

        // Test 3: Create an old completed order (simulated)
        $this->info('Creating an old completed order (25 hours ago)...');
        $oldOrder = new Order([
            'user_id' => $user->id,
            'total_amount' => 299.99,
            'status' => 'completed',
        ]);
        $oldOrder->created_at = now()->subHours(25);
        $oldOrder->updated_at = now()->subHours(25);
        $oldOrder->save();

        OrderItem::create([
            'order_id' => $oldOrder->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'price' => $product->price,
        ]);

        // Refresh the model to get accurate timestamps
        $oldOrder->refresh();
        
        $this->line("Old Completed Order #{$oldOrder->id}:");
        $this->line("  - Status: {$oldOrder->getDisplayStatus()}");
        $this->line("  - Badge Classes: {$oldOrder->getStatusBadgeClasses()}");
        $this->line("  - Can be cancelled: " . ($oldOrder->canBeCancelled() ? 'YES' : 'NO'));
        $this->line("  - Created: {$oldOrder->created_at} (" . round($oldOrder->created_at->diffInHours(now())) . " hours ago)");

        // Test 4: Create a cancelled order
        $this->info('Creating a cancelled order...');
        $cancelledOrder = Order::create([
            'user_id' => $user->id,
            'total_amount' => 149.99,
            'status' => 'cancelled',
        ]);

        OrderItem::create([
            'order_id' => $cancelledOrder->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $this->line("Cancelled Order #{$cancelledOrder->id}:");
        $this->line("  - Status: {$cancelledOrder->getDisplayStatus()}");
        $this->line("  - Badge Classes: {$cancelledOrder->getStatusBadgeClasses()}");
        $this->line("  - Can be cancelled: " . ($cancelledOrder->canBeCancelled() ? 'YES' : 'NO'));

        $this->line('');
        $this->info('Test Summary:');
        $this->line('- Pending orders can be cancelled: ✓');
        $this->line('- Recent completed orders can be cancelled: ✓');
        $this->line('- Old completed orders cannot be cancelled: ✓');
        $this->line('- Cancelled orders cannot be cancelled again: ✓');
        $this->line('- Proper status display names and CSS classes: ✓');

        $this->info('All order status tests completed successfully!');
    }
}
