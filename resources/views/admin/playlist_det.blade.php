@extends('admin/layouts')

@section('title', 'รายละเอียดเพลย์ลิสต์ - ' . $playlist->name)

@section('content')

@if(session('success'))
    <div id="success-alert" class="max-w-5xl mx-auto mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-xl"></i>
            <p class="text-sm font-medium">บันทึกการแก้ไขข้อมูลเรียบร้อยแล้ว!</p>
        </div>
        
        <button type="button" onclick="document.getElementById('success-alert').remove()" class="text-emerald-500 hover:text-emerald-700 transition-colors">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
@endif

    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 sm:mb-8 gap-4">
        <div class="flex items-center gap-3 sm:gap-4">
            <a href="{{ route('playlist.def') }}" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 shrink-0 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm">
                <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div class="min-w-0">
                <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">รายละเอียดข้อมูลเพลย์ลิสต์</h1>
                <p class="text-slate-500 text-[11px] sm:text-sm truncate mt-0.5 sm:mt-1">ID: #{{ $playlist->id }} — {{ $playlist->name }}</p>
            </div>
        </div>
        
        <div class="flex justify-end w-full sm:w-auto">
            <div class="relative inline-block text-left w-auto"> 
                <input type="checkbox" id="dropdown-toggle" class="peer hidden" />

                <label for="dropdown-toggle" class="cursor-pointer inline-flex items-center justify-end gap-2 w-auto bg-slate-900 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-800 transition-all shadow-md select-none">
                    <i class="bi bi-gear-fill"></i> จัดการ
                    <i class="bi bi-chevron-down ms-1 text-xs"></i>
                </label>

                <div class="hidden peer-checked:block absolute right-0 z-50 mt-2 w-48 sm:w-56 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none divide-y divide-slate-100 overflow-hidden border border-slate-100">
                    <div class="py-1">
                        <a href="{{ route('playlist.edit', $playlist->id) }}" class="text-slate-700 hover:bg-indigo-50/60 hover:text-indigo-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium">
                            <i class="bi bi-pencil-square text-base text-indigo-500"></i> แก้ไขข้อมูลเพลย์ลิสต์
                        </a>
                    </div>
                     <div class="py-1">
                        <a href="{{ route('playlist.manage', $playlist->id) }}" class="text-slate-700 hover:bg-blue-50/60 hover:text-blue-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium">
                            <i class="bi bi-journal-text text-base text-blue-500"></i> จัดการเพลย์ลิสต์
                        </a>
                    </div>
                    <div class="py-1">
                        <button class="text-slate-700 hover:bg-amber-50/60 hover:text-amber-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium" onclick="confirmChangeStatus('{{ route('playlist.del', $playlist->id) }}')">
                            <i class="bi bi-toggle-on text-base text-amber-500"></i> เปลี่ยนสถานะเพลย์ลิสต์
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
        
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-200 p-5 sm:p-8 shadow-sm text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-16 sm:h-24 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                
                <div class="relative mt-2 sm:mt-4">
                    @if($playlist->image)
                        <img src="{{ asset('image/' . $playlist->image) }}" class="w-32 h-32 sm:w-48 sm:h-48 rounded-xl sm:rounded-2xl mx-auto border-4 border-white shadow-md sm:shadow-xl object-cover">
                    @else
                        <div class="w-32 h-32 sm:w-48 sm:h-48 rounded-xl sm:rounded-2xl mx-auto border-4 border-white shadow-md sm:shadow-xl bg-slate-100 flex items-center justify-center">
                            <i class="bi bi-music-note-list text-4xl sm:text-5xl text-slate-300"></i>
                        </div>
                    @endif
                </div>

                <div class="mt-4"> 
                    <h2 class="text-lg sm:text-2xl font-bold text-slate-900 leading-snug break-words">{{ $playlist->name ?? ""}}</h2> 
                    <p class="text-indigo-600 text-xs sm:text-sm font-medium mt-0.5">คอลเลกชันเพลย์ลิสต์</p>
                    
                    <div class="mt-4 p-3 sm:p-3.5 bg-slate-50 rounded-xl sm:rounded-2xl text-left border border-slate-100">
                        <p class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">คำอธิบาย</p>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed break-words whitespace-pre-line">
                            {{ $playlist->detail ?? 'ไม่มีคำอธิบายสำหรับเพลย์ลิสต์นี้' }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-100 space-y-3 sm:space-y-4">
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-person me-1.5"></i> เจ้าของเพลย์ลิสต์</span>
                        <span class="text-slate-700 font-bold truncate max-w-[150px] text-right">{{ $playlist->user->name ?? ""}}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-play-circle me-1.5"></i> จำนวนการฟังรวม</span>
                        <span class="text-indigo-600 font-bold">{{ $playlist->music->first()->playlist_order ?? 0 }} ครั้ง</span>
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-info-circle me-1.5"></i> สถานะเพลย์ลิสต์</span>
                        <div>
                            @if($playlist->status == 1)
                                <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 text-[10px] sm:text-xs px-2 py-0.5 sm:py-1 rounded-full font-medium">
                                    <i class="bi bi-lock-fill"></i> Private
                                </span>
                            @elseif($playlist->status == 2)
                                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 text-[10px] sm:text-xs px-2 py-0.5 sm:py-1 rounded-full font-medium">
                                    <i class="bi bi-globe2"></i> Public
                                </span>
                            @elseif($playlist->status == 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-600 border border-rose-200">IN ACTIVE</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-calendar-plus me-1.5"></i> วันที่สร้าง</span>
                        <span class="text-slate-700 font-medium text-right text-[11px] sm:text-sm">
                            {{ $playlist->created_at->isoFormat('HH:mm [น.] D MMMM') }} {{ $playlist->created_at->year + 543 }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-pencil me-1.5"></i> แก้ไขล่าสุด</span>
                        <span class="text-slate-700 font-medium text-right text-[11px] sm:text-sm">
                            {{ $playlist->updated_at->format('H:i') }} น. 
                            {{ $playlist->updated_at->day }} 
                            {{ $playlist->updated_at->locale('th')->monthName }} 
                            {{ $playlist->updated_at->year + 543 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 sm:px-8 sm:py-6 border-b border-slate-100 flex flex-row items-center justify-between bg-slate-50/30 gap-3">
                    <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="bi bi-music-note-beamed text-lg sm:text-xl"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-slate-800">คลื่นเสียง</h3>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[10px] sm:text-xs font-semibold bg-indigo-50 text-indigo-600 px-2.5 py-0.5 sm:py-1 rounded-full whitespace-nowrap">
                            ทั้งหมด {{ $playlist->music->count() }} รายการ
                        </span>
                    </div>
                </div>
                
                <div class="p-3 sm:p-6">
                    {{-- เพิ่มคลาส custom-scrollbar และเว้นขวาเล็กน้อย pr-2 เพื่อไม่ให้ทับกับไอคอนดวงตา --}}
                    <div class="max-h-[550px] overflow-y-auto pr-2 custom-scrollbar">
                        <table class="w-full text-left border-collapse table-fixed">
                            <tbody class="divide-y divide-slate-100">
                                @forelse($playlist->music as $index => $row)
                                <tr class="group hover:bg-slate-50/60 transition-all">
                                    <td class="py-3 px-1 text-slate-400 font-mono text-xs sm:text-sm font-medium w-8 sm:w-12 text-center align-middle">
                                        {{ $index + 1 }}
                                    </td>
                                    
                                    <td class="py-3 px-2 align-middle overflow-hidden">
                                        <div class="flex items-center gap-2.5 sm:gap-4 min-w-0">
                                            <div class="w-10 h-10 sm:w-14 sm:h-14 shrink-0 rounded-lg sm:rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden border border-slate-200 shadow-sm">
                                                <img class="w-full h-full object-cover" src="{{ asset('image/' . $row->image) }}" onerror="this.src='{{ asset('image/default-music.png') }}'">
                                            </div>
                                            <div class="min-w-0 space-y-0.5">
                                                <p class="font-bold text-slate-800 text-xs sm:text-sm truncate group-hover:text-indigo-600 transition-colors">
                                                    {{ $row->name ?? 'ไม่มีชื่อคลื่นเสียง' }}
                                                </p>
                                                <p class="text-[10px] sm:text-xs font-mono text-slate-400 font-medium">
                                                    <i class="bi bi-clock text-[9px] sm:text-[11px] me-0.5"></i>
                                                    {{ $row->duration ? \Carbon\CarbonInterval::seconds($row->duration)->cascade()->format('%I:%S') : '00:00' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-1 text-right w-12 sm:w-16 align-middle">
                                        <a href="{{ route('music.det', $row->id) }}" class="inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all" title="ดูรายละเอียดคลื่นเสียง">
                                            <i class="bi bi-eye-fill text-sm sm:text-base"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-16 text-center text-slate-400">
                                        <i class="bi bi-music-note-beamed text-4xl block mb-3 text-slate-300"></i>
                                        <p class="text-sm font-medium">ยังไม่มีคลื่นเสียงในเพลย์ลิสต์นี้</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

{{-- แทรก CSS สำหรับทำ Custom Scrollbar เข้าไปในระบบ --}}
@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px; /* ความกว้างแนวตั้ง */
        height: 5px; /* ความสูงแนวนอน */
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(241, 245, 249, 0.5); /* สีพื้นหลังรางเลื่อน (Slate 100 จางๆ) */
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1; /* สีตัวเลื่อน (Slate 300) */
        border-radius: 10px;
        border: 1px solid transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; /* สีตอนเอาเมาส์ไปชี้ (Slate 400) */
    }

    /* ปรับขนาดแถบเลื่อนบนมือถือให้บางลงอีกนิด เพื่อไม่ให้รบกวนสายตา */
    @media (max-width: 640px) {
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
    }
</style>
@endpush
@push('scripts')
<script>
    function confirmChangeStatus(url) {
        Swal.fire({
            title: 'ยืนยันการทำรายการ?',
            text: "คุณต้องการเปลี่ยนสถานะข้อมูลรายการนี้ใช่หรือไม่",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#f43f5e',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }
</script>
@endpush