@extends('admin/layouts')

@section('title', 'รายละเอียดคลื่นเสียง - ' . $music->name)

@section('content')

@if(session('success'))
    <div id="success-alert" class="max-w-4xl mx-auto mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-between gap-3">
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
            <a href="{{ str_contains(request()->server('HTTP_REFERER'), 'admin/music') ? route('music.def') : 'javascript:history.back()' }}" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 shrink-0 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm">
                <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div class="min-w-0">
                <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">รายละเอียดข้อมูลคลื่นเสียง</h1>
                <p class="text-slate-500 text-[11px] sm:text-sm truncate mt-0.5 sm:mt-1">ID: #{{ $music->id }} — {{ $music->name }}</p>
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
                        <a href="{{ route('music.edit', $music->id) }}" class="text-slate-700 hover:bg-indigo-50/60 hover:text-indigo-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium">
                            <i class="bi bi-pencil-square text-base text-indigo-500"></i> แก้ไขข้อมูลคลื่นเสียง
                        </a>
                    </div>
                    <div class="py-1">
                        <button class="text-slate-700 hover:bg-amber-50/60 hover:text-amber-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium" onclick="confirmChangeStatus('{{ route('music.del', $music->id) }}')">
                            <i class="bi bi-toggle-on text-base text-amber-500"></i> เปลี่ยนสถานะคลื่นเสียง
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[2rem] border border-slate-200 p-8 shadow-sm text-center profile-card relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                
                <div class="relative mt-4">
                    @if($music->image)
                        <img src="{{ asset('image/' . $music->image) }}" class="w-48 h-48 rounded-[1.0rem] mx-auto border-4 border-white shadow-xl object-cover">
                    @else
                        <div class="w-32 h-32 rounded-[2.5rem] mx-auto border-4 border-white shadow-xl bg-slate-100 flex items-center justify-center">
                            <i class="bi bi-music-note-beamed text-4xl text-slate-300"></i>
                        </div>
                    @endif
                    
                </div>

                <div class="mt-4"> 
                    <h2 class="text-lg sm:text-2xl font-bold text-slate-900 leading-snug break-words">{{ $music->name }}</h2> 
                </div>

                <div class="mt-4 pt-4 border-t border-slate-50 space-y-4">
                <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-clock me-1"></i> ระยะเวลา</span>
                        <span class="text-slate-700 font-bold">{{ $music->duration ? \Carbon\CarbonInterval::seconds($music->duration)->cascade()->format('%I:%S') : '00:00' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-play-circle me-1"></i> จำนวนการฟัง</span>
                        <span class="text-indigo-600 font-bold">{{ $music->play_count}} ครั้ง</span>
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-info-circle me-1.5"></i> สถานะ</span>
                        <div>
                            @if($music->status > 0)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 border border-emerald-200">ACTIVE</span>
                            @elseif($music->status < 1)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-600 border border-rose-200">IN ACTIVE</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-calendar-plus me-1.5"></i>วันที่เพิ่ม</span>
                        <span class="text-slate-700 font-medium text-right text-[11px] sm:text-sm">
                            {{ $music->created_at->isoFormat('HH:mm [น.] D MMMM') }} {{ $music->created_at->year + 543 }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-pencil me-1.5"></i>แก้ไขล่าสุด</span>
                        <span class="text-slate-700 font-medium text-right text-[11px] sm:text-sm">
                            {{ $music->updated_at->format('H:i') }} น. 
                            {{ $music->updated_at->day }} 
                            {{ $music->updated_at->locale('th')->monthName }} 
                            {{ $music->updated_at->year + 543 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="bi bi-info-circle text-xl"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">รายละเอียดเพิ่มเติม</h3>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    <div class="group">
                        <label class="text-[11px] text-slate-400 uppercase tracking-[0.15em] font-bold block mb-3">
                            ประเภทคลื่นเสียง
                        </label>
                        <div class="flex flex-wrap gap-x-6 gap-y-3">
                            @foreach($music->filterDetails as $filter)
                                
                                        
                                        <div class="flex items-center gap-2 group/item">
                                            <div class="flex gap-0.5 items-end h-3">
                                                <div class="w-0.5 h-1.5 bg-indigo-400 rounded-full animate-pulse"></div>
                                                <div class="w-0.5 h-3 bg-indigo-500 rounded-full"></div>
                                                <div class="w-0.5 h-2 bg-indigo-400 rounded-full animate-pulse"></div>
                                            </div>
                                            
                                            <span class="text-sm font-medium text-slate-700 group-hover/item:text-indigo-600 transition-colors">
                                                {{ $filter->name }}
                                            </span>
                                        </div>

                                
                            @endforeach
                        </div>
                    </div>

                    <div class="group">
                        <label class="text-[11px] text-slate-400 uppercase tracking-widest font-bold block mb-1.5 group-hover:text-indigo-500 transition-colors"> คำอธิบาย </label>
                        <div class="prose prose-slate max-w-none">
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                {{ $music->detail ?? 'ไม่มีรายละเอียดข้อมูลสำหรับคลื่นเสียงนี้' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
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