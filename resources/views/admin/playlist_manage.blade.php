@extends('admin/layouts')

@section('title', 'จัดการเพลย์ลิสต์ - ' . $playlist->name)

@section('content')

@if(session('success'))
    <div id="success-alert" class="mx-auto mb-4 p-3.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-between gap-3 shadow-sm text-xs sm:text-sm">
        <div class="flex items-center gap-2">
            <i class="bi bi-check-circle-fill text-base sm:text-xl shrink-0"></i>
            <p class="font-medium">บันทึกการแก้ไขข้อมูลเรียบร้อยแล้ว!</p>
        </div>
        <button type="button" onclick="document.getElementById('success-alert').remove()" class="text-emerald-500 hover:text-emerald-700 transition-colors shrink-0 p-1">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
@endif

<div class="max-w-7xl mx-auto pb-6 sm:pb-10 px-2 sm:px-6">
    <form action="{{ route('playlist.update',$playlist->id) }}" method="POST" id="playlistForm" class="flex flex-col">
        @csrf
        
        {{-- ================= HEADER ================= --}}
        <div class="flex items-center mb-5 sm:mb-8 gap-3 shrink-0">
            <a href="javascript:history.back()" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm shrink-0">
                <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div class="min-w-0">
                <h1 class="text-base sm:text-2xl font-bold text-slate-900 truncate">จัดการลำดับเพลย์ลิสต์</h1>
                <p class="text-slate-500 text-[11px] sm:text-sm leading-none mt-1 truncate">ID: #{{ $playlist->id }} — {{ $playlist->name }}</p>
            </div>
        </div>

        {{-- ================= EDITOR (Drag & Drop) ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 lg:gap-10 h-auto md:h-[680px] min-w-0">
            
            {{-- 1. คลังคลื่นเสียง (Library) --}}
            <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-100 shadow-sm flex flex-col h-[420px] md:h-full min-h-0 p-3 sm:p-4">
                
                {{-- Header & ตัวกรองคู่ --}}
                <div class="flex flex-row items-center justify-between px-1 py-1.5 gap-2 mb-2 shrink-0">
                    <h2 class="text-sm sm:text-lg font-bold text-slate-800 flex items-center gap-1.5 truncate">
                        <i class="bi bi-collection text-slate-400 shrink-0"></i> คลังเสียงทั้งหมด
                    </h2>
                    
                    <div class="flex gap-2 w-1/2 sm:w-auto max-w-[200px] shrink-0">
                        {{-- ช่องพิมพ์ค้นหาชื่อ --}}
                        <div class="relative w-full">
                            <input type="text" id="searchInput" class="w-full pl-8 pr-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:ring-4 focus:ring-indigo-50 focus:border-indigo-300 focus:bg-white transition-all outline-none" placeholder="ค้นหา...">
                            <i class="bi bi-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- ส่วนแสดงรายชื่อในคลังเสียง --}}
                <div id="libraryList" class="flex-1 overflow-y-auto min-h-0 p-0.5 space-y-2 custom-scrollbar">
                    @if(isset($musics))
                        @foreach($musics as $music)
                            @if(!in_array($music->id, $playlist->music->pluck('id')->toArray()))
                                <div class="music-item group flex items-center gap-2 sm:gap-4 p-2 sm:p-3 bg-white border border-slate-100 rounded-xl sm:rounded-2xl hover:border-indigo-200 hover:bg-indigo-50/50 hover:shadow-sm transition-all duration-300" data-id="{{ $music->id }}" data-name="{{ strtolower($music->name) }}" data-type="{{ $music->type ?? '' }}">
                                    
                                    <div class="drag-handle px-0.5 hidden text-slate-300 hover:text-indigo-400 cursor-grab">
                                        <i class="bi bi-grip-vertical text-lg"></i>
                                    </div>
                                    
                                    <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-lg sm:rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden shrink-0 border border-slate-200 shadow-sm relative">
                                        <img src="{{ asset('image/' . $music->image) }}" onerror="this.src='https://placehold.co/150/e2e8f0/64748b?text=No+Image'" class="w-full h-full object-cover">
                                    </div>
                                    
                                    <div class="flex-1 min-w-0 pl-0.5">
                                        <p class="font-bold text-xs sm:text-[15px] text-slate-800 truncate group-hover:text-indigo-600 transition-colors">{{ $music->name ?? 'ไม่มีชื่อ' }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <p class="text-[10px] sm:text-xs truncate text-slate-500 font-mono time-text">
                                                <i class="bi bi-clock me-1"></i>{{ $music->duration ? \Carbon\CarbonInterval::seconds($music->duration)->cascade()->format('%I:%S') : '00:00' }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="action-btn w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center transition-all duration-300 shrink-0 text-indigo-500 bg-indigo-50 hover:bg-indigo-600 hover:text-white">
                                        <i class="bi bi-plus-lg text-sm sm:text-lg"></i>
                                    </button>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- 2. เพลย์ลิสต์ (Playlist) --}}
            <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-100 shadow-sm flex flex-col h-[420px] md:h-full min-h-0 p-3 sm:p-4">
                
                {{-- Header --}}
                <div class="flex items-center justify-between px-1 py-1.5 gap-2 mb-2 shrink-0">
                    <h2 class="text-sm sm:text-lg font-bold text-slate-800 flex items-center gap-1.5">
                        <i class="bi bi-music-note-list text-indigo-500"></i> ลำดับเพลย์ลิสต์
                    </h2>
                    <span class="text-[11px] sm:text-sm font-bold bg-white text-indigo-600 px-2.5 sm:px-4 py-1 rounded-full shadow-sm border border-slate-200 shrink-0">
                        <span id="playlistCount">0</span> รายการ
                    </span>
                </div>

                <div id="playlistList" class="bg-gradient-to-b from-indigo-50/10 to-white flex-1 overflow-y-auto min-h-0 p-0.5 space-y-2 custom-scrollbar">
                    
                    @if(isset($playlist->music))
                        @foreach($playlist->music as $music)
                            @if($music)
                                {{-- นำคลาส touch-none ออกไปแล้ว --}}
                                <div class="music-item group flex items-center gap-1.5 sm:gap-4 p-2 sm:p-3 bg-white border border-indigo-100 shadow-sm rounded-xl sm:rounded-2xl hover:shadow-md transition-all duration-300" data-id="{{ $music->id }}" data-name="{{ strtolower($music->name) }}">
                                    
                                    {{-- เพิ่มคลาส touch-none ไว้ที่ตัวจับลากแทน เพื่อบอกว่าตรงนี้ห้ามเลื่อนจอ ให้ใช้ลากเพลง --}}
                                    <div class="drag-handle px-2 block text-slate-300 hover:text-indigo-500 transition-colors cursor-grab active:cursor-grabbing touch-none">
                                        <i class="bi bi-grip-vertical text-base sm:text-xl"></i>
                                    </div>
                                    
                                    <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-lg sm:rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden shrink-0 border border-slate-200">
                                        <img src="{{ asset('image/' . $music->image) }}" onerror="this.src='https://placehold.co/150/e2e8f0/64748b?text=No+Image'" class="w-full h-full object-cover">
                                    </div>
                                    
                                    <div class="flex-1 min-w-0 pl-0.5">
                                        <p class="font-bold text-xs sm:text-[15px] text-slate-800 truncate">
                                            {{ $music->name ?? 'ไม่มีชื่อ' }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <p class="text-[10px] sm:text-xs truncate text-indigo-500/70 font-mono time-text">
                                                <i class="bi bi-clock me-1"></i>{{ $music->duration ? \Carbon\CarbonInterval::seconds($music->duration)->cascade()->format('%I:%S') : '00:00' }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="action-btn w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center transition-all duration-300 shrink-0 text-rose-400 bg-rose-50 hover:bg-rose-500 hover:text-white">
                                        <i class="bi bi-dash-lg text-sm sm:text-lg"></i>
                                    </button>

                                    <input type="hidden" name="music_ids[]" value="{{ $music->id }}">
                                </div>
                            @endif
                        @endforeach
                    @endif
           
                    {{-- Empty State --}}
                    <div id="emptyState" class="hidden h-full flex-col items-center justify-center text-slate-400 py-8">
                        <div class="w-12 h-12 sm:w-20 sm:h-20 bg-white rounded-full shadow-sm flex items-center justify-center mb-2.5 sm:mb-4">
                            <i class="bi bi-music-note-beamed text-xl sm:text-3xl text-indigo-200"></i>
                        </div>
                        <p class="text-xs sm:text-base font-medium">ยังไม่ได้เลือกคลื่นเสียง</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4 pt-3 border-t border-slate-100 flex flex-row items-center justify-end gap-2 sm:gap-3 shrink-0">
                    <a href="javascript:history.back()" class="w-full sm:w-auto text-center bg-slate-100 text-slate-700 px-4 sm:px-6 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-200 transition-all">ยกเลิก</a>
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-indigo-600 text-white px-5 sm:px-10 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 whitespace-nowrap">
                        บันทึกข้อมูล
                    </button>
                </div>
                
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const libraryList = document.getElementById('libraryList');
        const playlistList = document.getElementById('playlistList');
        const searchInput = document.getElementById('searchInput');
        const typeFilter = document.getElementById('typeFilter');
        const playlistCount = document.getElementById('playlistCount');
        const emptyState = document.getElementById('emptyState');

        function updateCount() {
            const items = playlistList.querySelectorAll('.music-item');
            playlistCount.textContent = items.length;
            if(items.length === 0) {
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
            }
        }

        function filterLibraryItems() {
            const query = searchInput.value.toLowerCase();
            const selectedType = typeFilter ? typeFilter.value : "";
            const items = libraryList.querySelectorAll('.music-item');
            
            items.forEach(item => {
                const matchesSearch = item.dataset.name.includes(query);
                const matchesType = selectedType === "" || item.dataset.type === selectedType;
                
                if (matchesSearch && matchesType) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        if(searchInput) searchInput.addEventListener('input', filterLibraryItems);
        if(typeFilter) typeFilter.addEventListener('change', filterLibraryItems);

        // ปรับแต่ง Sortable ให้แยกขาดระหว่างการเลื่อนจอกับการลากการ์ดเพลง
        new Sortable(playlistList, {
            handle: '.drag-handle', // บังคับลากได้เฉพาะที่ปุ่มไอคอนขีดสามขีดเท่านั้น
            animation: 250,
            easing: "cubic-bezier(1, 0, 0, 1)", 
            ghostClass: 'opacity-0', 
            dragClass: 'shadow-2xl',
            filter: '#emptyState'
        });

        // ควบคุมการทำงานของปุ่มกดเพิ่ม/ลด คลื่นเสียงสลับฝั่งอย่างสมบูรณ์
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.action-btn');
            if(!btn) return;
            
            const item = btn.closest('.music-item');
            const isInPlaylist = item.parentNode === playlistList;
            const musicId = item.dataset.id;
            
            const timeText = item.querySelector('.time-text');
            const typeBadge = item.querySelector('.type-badge');

            if (isInPlaylist) {
                // ย้ายกลับไปคลังเสียง (ลดออกจาก Playlist)
                item.className = 'music-item group flex items-center gap-2 sm:gap-4 p-2 sm:p-3 bg-white border border-slate-100 rounded-xl sm:rounded-2xl hover:border-indigo-200 hover:bg-indigo-50/50 hover:shadow-sm transition-all duration-300';
                
                if(timeText) timeText.classList.replace('text-indigo-500/70', 'text-slate-500');
                if(typeBadge) typeBadge.className = 'type-badge hidden md:inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600';
                
                item.querySelector('.drag-handle').classList.replace('block', 'hidden');
                
                btn.className = 'action-btn w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center transition-all duration-300 shrink-0 text-indigo-500 bg-indigo-50 hover:bg-indigo-600 hover:text-white';
                btn.innerHTML = '<i class="bi bi-plus-lg text-sm sm:text-lg"></i>';
                
                const input = item.querySelector('input[name="music_ids[]"]');
                if(input) input.remove();

                libraryList.appendChild(item);
                filterLibraryItems();

           } else {
                // ย้ายมาที่เพลย์ลิสต์ (เพิ่มเข้า Playlist)
                item.className = 'music-item group flex items-center gap-1.5 sm:gap-4 p-2 sm:p-3 bg-white border border-indigo-100 shadow-sm rounded-xl sm:rounded-2xl hover:shadow-md transition-all duration-300';
                
                if(timeText) timeText.classList.replace('text-slate-500', 'text-indigo-500/70');
                if(typeBadge) typeBadge.className = 'type-badge hidden md:inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-500';
                
                // จัดการปุ่มลากสลับฝั่ง
                const dragHandle = item.querySelector('.drag-handle');
                if(dragHandle) {
                    dragHandle.className = 'drag-handle px-2 block text-slate-300 hover:text-indigo-500 transition-colors cursor-grab active:cursor-grabbing touch-none';
                }
                
                btn.className = 'action-btn w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center transition-all duration-300 shrink-0 text-rose-400 bg-rose-50 hover:bg-rose-500 hover:text-white';
                btn.innerHTML = '<i class="bi bi-dash-lg text-sm sm:text-lg"></i>';
                
                item.insertAdjacentHTML('beforeend', `<input type="hidden" name="music_ids[]" value="${musicId}">`);

                item.style.display = 'flex';
                playlistList.appendChild(item);
            }
            
            updateCount();
        });

        updateCount();
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    @media (min-width: 768px) {
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    }
</style>
@endpush