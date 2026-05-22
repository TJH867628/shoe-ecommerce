<!-- Footer -->
<footer class="bg-slate-950 text-white pt-24 pb-12 px-6 rounded-t-[3rem] mx-2 mt-auto">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-slate-800 pb-16">
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 mb-6 opacity-90">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-slate-900">
                        <span class="font-black text-lg italic">S</span>
                    </div>
                    <span class="text-xl font-black tracking-tighter text-white">SoleStore.</span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed max-w-xs">
                    Premium footwear for the modern era. Curated selections of the world's best sneakers.
                </p>
            </div>

            <div>
                <h4 class="font-bold mb-6 text-slate-200">Shop</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li><a href="{{ url('/shop') }}" class="hover:text-white transition-colors">New Arrivals</a></li>
                    <li><a href="{{ url('/shop') }}" class="hover:text-white transition-colors">Running</a></li>
                    <li><a href="{{ url('/shop') }}" class="hover:text-white transition-colors">Lifestyle</a></li>
                    <li><a href="{{ url('/shop') }}" class="hover:text-white transition-colors">Sale</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-6 text-slate-200">Support</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Shipping & Returns</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Size Guide</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold mb-6 text-slate-200">Newsletter</h4>
                <p class="text-slate-400 text-sm mb-4">Subscribe for exclusive drops and early access to sales.</p>
                <div class="flex bg-slate-900 p-1 rounded-full border border-slate-800">
                    <input type="email" placeholder="Your email" class="bg-transparent border-none outline-none px-4 text-sm w-full text-white placeholder:text-slate-500" />
                    <button class="bg-white text-slate-900 rounded-full px-6 py-2 text-sm font-bold hover:bg-slate-200 transition-colors">
                        Join
                    </button>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center pt-8 text-xs font-bold text-slate-600 uppercase tracking-widest gap-4">
            <p>&copy; {{ date('Y') }} SoleStore Inc. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white transition-colors">Privacy</a>
                <a href="#" class="hover:text-white transition-colors">Terms</a>
            </div>
        </div>
    </div>
</footer>