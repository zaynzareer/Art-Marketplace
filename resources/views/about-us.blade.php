<x-app-layout>
  <!-- About Us Page -->
  <div class="container mx-auto px-6 py-16">
    <!-- Intro -->
    <div class="max-w-3xl mb-16">
      <h1 class="text-3xl font-bold text-gray-900 mb-4">About Crafty</h1>
      <p class="text-gray-600 leading-relaxed">
        Crafty is a curated marketplace dedicated to connecting collectors with extraordinary
        artworks and handcrafted pieces from independent artists around the world.
        Every piece on our platform tells a story — of craftsmanship, culture, and creativity.
      </p>
    </div>

    <!-- Mission Section -->
    <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
      <div>
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Mission</h2>
        <p class="text-gray-600 mb-4">
          We believe art should be personal, meaningful, and accessible. Our mission is to empower
          artists by providing them with a platform to share their work while offering collectors
          a trusted space to discover rare and authentic creations.
        </p>
        <p class="text-gray-600">
          From timeless paintings to modern sculptures and functional art, we celebrate creativity
          in all its forms.
        </p>
      </div>
      <div>
        <img src="{{ asset('storage/img1.jpg') }}" alt="Artist Studio"
          class="rounded-lg shadow-md">
      </div>
    </div>

    <!-- Values -->
    <div class="bg-gray-50 rounded-lg p-10 mb-20">
      <h2 class="text-2xl font-semibold text-gray-900 mb-8 text-center">Our Values</h2>

      <div class="grid md:grid-cols-3 gap-8 text-center">
        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h3 class="font-semibold text-gray-900 mb-2">Authenticity</h3>
          <p class="text-sm text-gray-600">
            Every piece is reviewed to ensure originality, quality, and artistic integrity.
          </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h3 class="font-semibold text-gray-900 mb-2">Artist First</h3>
          <p class="text-sm text-gray-600">
            We put creators at the center, ensuring fair exposure and control over their work.
          </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
          <h3 class="font-semibold text-gray-900 mb-2">Craftsmanship</h3>
          <p class="text-sm text-gray-600">
            We celebrate the process, skill, and passion behind every creation.
          </p>
        </div>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid md:grid-cols-4 gap-8 text-center mb-20">
      <div>
        <p class="text-3xl font-bold text-gray-900">2000+</p>
        <p class="text-gray-500 text-sm mt-1">Artworks Sold</p>
      </div>
      <div>
        <p class="text-3xl font-bold text-gray-900">150+</p>
        <p class="text-gray-500 text-sm mt-1">Independent Artists</p>
      </div>
      <div>
        <p class="text-3xl font-bold text-gray-900">40+</p>
        <p class="text-gray-500 text-sm mt-1">Countries Reached</p>
      </div>
      <div>
        <p class="text-3xl font-bold text-gray-900">98%</p>
        <p class="text-gray-500 text-sm mt-1">Customer Satisfaction</p>
      </div>
    </div>

    <!-- Call to Action -->
    <div class="text-center max-w-2xl mx-auto">
      <h2 class="text-2xl font-semibold text-gray-900 mb-4">
        Supporting Artists. Inspiring Collectors.
      </h2>
      <p class="text-gray-600 mb-6">
        Whether you’re an artist looking to share your work or a collector searching for
        something special, Crafty is where meaningful connections begin.
      </p>
      <a href="{{ route('login') }}"
        class="inline-block bg-black text-white px-6 py-2 rounded-md hover:brightness-95">
        Explore the Collection
      </a>
    </div>
  </div>
</x-app-layout>