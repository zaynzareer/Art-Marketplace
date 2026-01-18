<footer class="bg-black text-white py-12">
    <div class="container mx-auto px-6 grid md:grid-cols-4 gap-10">
    <div>
        <h3 class="font-semibold text-lg mb-3">Crafty</h3>
        <p class="text-gray-400 text-sm">Curating extraordinary art and collectibles for passionate collectors worldwide.</p>
    </div>
    <div>
        <h4 class="font-semibold mb-3">Quick Links</h4>
        <ul class="space-y-2 text-gray-400 text-sm">
        @auth
            @if(auth()->user()->role === 'buyer')
                <li><a href="{{ route('dashboard') }}" class="hover:text-white">Browse Collection</a></li>
            @else
                <li><a href="{{ route('products.index') }}" class="hover:text-white">My Products</a></li>
            @endif
            <li><a href="{{ route('about') }}" class="hover:text-white">About Us</a></li>
            <li><a href="{{ route('contact') }}" class="hover:text-white">Contact Us</a></li>
            <li><a href="{{ route('profile.show') }}" class="hover:text-white">My Profile</a></li>
        @else
            <li><a href="{{ route('login') }}" class="hover:text-white">Browse Collection</a></li>
            <li><a href="{{ route('about') }}" class="hover:text-white">About Us</a></li>
            <li><a href="{{ route('contact') }}" class="hover:text-white">Contact Us</a></li>
        @endauth
        </ul>
    </div>
    <div>
        <h4 class="font-semibold mb-3">Categories</h4>
        <ul class="space-y-2 text-gray-400 text-sm">
        @auth
            @if(auth()->user()->role === 'buyer')
                <li><a href="{{ route('dashboard') }}?category=paintings" class="hover:text-white">Paintings</a></li>
                <li><a href="{{ route('dashboard') }}?category=sculptures" class="hover:text-white">Sculptures</a></li>
                <li><a href="{{ route('dashboard') }}?category=pottery" class="hover:text-white">Pottery</a></li>
                <li><a href="{{ route('dashboard') }}?category=jewelry" class="hover:text-white">Jewelry</a></li>
                <li><a href="{{ route('dashboard') }}?category=textiles" class="hover:text-white">Textiles</a></li>
                <li><a href="{{ route('dashboard') }}?category=collectibles" class="hover:text-white">Collectibles</a></li>
            @else
                <li><a href="{{ route('products.index') }}" class="hover:text-white">Manage Products</a></li>
                <li><a href="{{ route('products.create') }}" class="hover:text-white">Add New Product</a></li>
                <li><a href="{{ route('seller.orders') }}" class="hover:text-white">My Orders</a></li>
            @endif
        @else
            <li><a href="{{ route('login') }}" class="hover:text-white">Paintings</a></li>
            <li><a href="{{ route('login') }}" class="hover:text-white">Sculptures</a></li>
            <li><a href="{{ route('login') }}" class="hover:text-white">Pottery</a></li>
            <li><a href="{{ route('login') }}" class="hover:text-white">Jewelry</a></li>
            <li><a href="{{ route('login') }}" class="hover:text-white">Textiles</a></li>
            <li><a href="{{ route('login') }}" class="hover:text-white">Collectibles</a></li>
        @endauth
        </ul>
    </div>
    <div>
        <h4 class="font-semibold mb-3">Contact Info</h4>
        <ul class="space-y-2 text-gray-400 text-sm">
        <li>support@crafty.com</li>
        <li>+1 (555) 123-4567</li>
        <li>New York, NY</li>
        </ul>
    </div>
    </div>
    <div class="border-t border-gray-800 mt-8 pt-6 text-center text-gray-400 text-sm">
        <p>&copy; {{ date('Y') }} Crafty. All rights reserved.</p>
    </div>
</footer>