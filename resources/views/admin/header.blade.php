<header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 px-4 sm:px-8 flex items-center justify-between">
    
    <div class="flex items-center gap-3">
        <button id="mobile-menu-toggle" class="lg:hidden p-2 -ml-2 rounded-lg text-slate-600 hover:bg-slate-100 focus:outline-none transition-all">
            <i class="bi bi-list text-2xl"></i>
        </button>

        
    </div>

    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
            <p class="text-xs font-bold text-slate-800 leading-none">{{ Auth::user()->name }}</p>
            <p class="text-[10px] text-emerald-500 font-medium mt-1">Status: Online</p>
        </div>
        <img src="https://ui-avatars.com/api/?name=Admin&background=f1f5f9&color=64748b" class="w-9 h-9 rounded-full border border-slate-200">
    </div>
    
</header>