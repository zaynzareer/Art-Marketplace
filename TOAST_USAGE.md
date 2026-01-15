# Toast Notification System

## Overview
A modern, lightweight toast notification system built for Livewire components with auto-dismiss functionality and smooth animations.

## Features
- ✅ Auto-dismiss after 4 seconds
- ✅ Manual close button
- ✅ Smooth slide-in/slide-out animations
- ✅ Multiple notification types (success, error, warning, info)
- ✅ Queue support (multiple notifications stack)
- ✅ Matches app theme
- ✅ Accessible (ARIA labels, keyboard support)

## Usage

### In Any Livewire Component

Simply dispatch the `notify` event with a message and type:

```php
// Success notification
$this->dispatch('notify', message: 'Item added to cart', type: 'success');

// Error notification
$this->dispatch('notify', message: 'Failed to process request', type: 'error');

// Warning notification
$this->dispatch('notify', message: 'Low stock available', type: 'warning');

// Info notification
$this->dispatch('notify', message: 'New update available', type: 'info');
```

### Examples from CartPage

```php
public function updateQuantity($productId, $quantity)
{
    $response = Http::withToken(Session::get('api_token'))
        ->post(route('api.cart.store'), [
            'product_id' => $productId,
            'quantity'   => $quantity,
        ]);

    if ($response->successful()) {
        $this->dispatch('notify', message: 'Cart updated', type: 'success');
    } else {
        $this->dispatch('notify', message: 'Failed to update cart', type: 'error');
    }

    $this->fetchCart();
}
```

## Notification Types

| Type | Color | Icon | Use Case |
|------|-------|------|----------|
| `success` | Green | Checkmark | Successful operations |
| `error` | Red | Alert | Failed operations, errors |
| `warning` | Yellow | Warning | Warnings, cautions |
| `info` | Blue | Info | Informational messages |

## Customization

### Duration
To change auto-dismiss duration, edit the timeout in `resources/views/livewire/toast.blade.php`:

```javascript
setTimeout(() => {
    this.show = false;
    // Change 4000 (4 seconds) to your preferred duration
}, 4000);
```

### Position
The toast container is positioned at `top-4 right-4`. To change position:

```html
<!-- Current: top-right -->
<div class="fixed top-4 right-4 z-50 space-y-3">

<!-- Examples: -->
<!-- Top-left -->
<div class="fixed top-4 left-4 z-50 space-y-3">

<!-- Bottom-right -->
<div class="fixed bottom-4 right-4 z-50 space-y-3">

<!-- Top-center -->
<div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 space-y-3">
```

## Integration Checklist

To add toast notifications to other Livewire components:

1. ✅ Toast component is already in `app-layout.blade.php`
2. ✅ Just use `$this->dispatch('notify', ...)` in your component methods
3. ✅ That's it! No additional setup needed

## Technical Details

- **Component**: `App\Livewire\Toast`
- **View**: `resources/views/livewire/toast.blade.php`
- **Location**: Fixed position, top-right corner
- **Z-index**: 50 (appears above most content)
- **Max width**: 24rem (384px)
- **Animation**: 300ms slide-in/out from right
- **Auto-dismiss**: 4 seconds
- **Dependencies**: Alpine.js (already included with Livewire)
