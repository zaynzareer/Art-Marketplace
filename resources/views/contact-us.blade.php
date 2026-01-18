<x-app-layout>
  <!-- Contact Us Page -->
  <div class="container mx-auto px-6 py-16">
    <!-- Intro -->
    <div class="max-w-3xl mb-16">
      <h1 class="text-3xl font-bold text-gray-900 mb-4">Contact Us</h1>
      <p class="text-gray-600 leading-relaxed">
        Have a question about an order, artwork, or becoming a seller?
        We’re here to help. Reach out and our team will get back to you as soon as possible.
      </p>
    </div>

    <!-- Content -->
    <div class="grid md:grid-cols-2 gap-12 items-start">
      <!-- Contact Form -->
      <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Send Us a Message</h2>

        <form action="/contact/send" method="POST" class="space-y-6">
          <!-- Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" id="name" name="name" required
              class="mt-1 block w-full rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-black focus:ring-black">
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" id="email" name="email" required
              class="mt-1 block w-full rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-black focus:ring-black">
          </div>

          <!-- Subject -->
          <div>
            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
            <select id="subject" name="subject" required
              class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm focus:border-black focus:ring-black">
              <option value="">Select a topic</option>
              <option value="order">Order Inquiry</option>
              <option value="product">Product Question</option>
              <option value="seller">Seller Support</option>
              <option value="general">General Question</option>
            </select>
          </div>

          <!-- Message -->
          <div>
            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
            <textarea id="message" name="message" rows="5" required
              class="mt-1 block w-full rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-black focus:ring-black"></textarea>
          </div>

          <!-- Submit -->
          <button type="submit"
            class="bg-black text-white px-6 py-2 rounded-md text-sm font-semibold hover:brightness-95">
            Send Message
          </button>
        </form>
      </div>

      <!-- Contact Information -->
      <div class="space-y-8">
        <!-- Support Info -->
        <div class="bg-gray-50 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Customer Support</h3>
          <p class="text-sm text-gray-600 mb-3">
            Our support team is available Monday – Friday, 9am – 6pm (EST).
          </p>
          <p class="text-sm text-gray-700">
            Email: <span class="font-medium">support@crafty.com</span>
          </p>
        </div>

        <!-- Seller Info -->
        <div class="bg-gray-50 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Seller Inquiries</h3>
          <p class="text-sm text-gray-600 mb-3">
            Interested in selling your art on Crafty?
          </p>
          <p class="text-sm text-gray-700">
            Email: <span class="font-medium">artists@crafty.com</span>
          </p>
        </div>

        <!-- Location -->
        <div class="bg-gray-50 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Our Office</h3>
          <p class="text-sm text-gray-600">
            123 Artisan Street<br>
            Creative District<br>
            New York, NY 10001
          </p>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>