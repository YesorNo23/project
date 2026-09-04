@extends('admin/layouts')

@section('title', 'รายละเอียดกลุ่ม - ' .$group->name)

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
    
    <div class="max-w-5xl mx-auto px-1 sm:px-0">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 sm:mb-8 gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('group.def') }}" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 shrink-0 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="min-w-0">
                    <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">รายละเอียดกลุ่มผู้ใช้</h1>
                    <p class="text-slate-500 text-[11px] sm:text-sm truncate mt-0.5 sm:mt-1">ID: #{{ $group->id }} — {{ $group->name }}</p>
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
                            <a href="{{ route('group.edit', $group->id) }}" class="text-slate-700 hover:bg-indigo-50/60 hover:text-indigo-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium">
                                <i class="bi bi-pencil-square text-base text-indigo-500"></i> แก้ไขข้อมูลกลุ่มผู้ใช้
                            </a>
                        </div>
                        <div class="py-1">
                            <button onclick="confirmChangeStatus('{{ route('group.del', $group->id) }}')" class="text-rose-700 hover:bg-rose-50/60 hover:text-rose-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium w-full text-left">
                                <i class="bi bi-toggle-on text-base text-rose-500"></i> เปลี่ยนสถานะกลุ่มผู้ใช้
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="h-24 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                <div class="px-6 pb-6">
                    <div class="relative -mt-12 mb-4">
                        <div class="w-24 h-24 rounded-2xl bg-white p-1 shadow-md mx-auto">
                            <div class="w-full h-full rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl">
                                <i class="bi bi-shield-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mb-6">
                        <h2 class="text-lg sm:text-2xl font-bold text-slate-900 leading-snug break-words">{{ $group->name }}</h2>
                    </div>
                    
                    <div class="space-y-3 pt-6 border-t border-slate-50">
                        <div class="flex items-center justify-between text-xs sm:text-sm">
                            <span class="text-slate-400"><i class="bi bi-info-circle me-1.5"></i>สถานะการใช้งาน</span>
                            @if($group->status == 1)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 border border-emerald-200">ACTIVE</span>
                            @elseif($group->status == 0)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-600 border border-rose-200">IN ACTIVE</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between text-xs sm:text-sm">
                            <span class="text-slate-400"><i class="bi bi-people me-1.5"></i>จำนวนสมาชิก</span>
                            <span class="font-bold text-indigo-600">{{  $group->uig->count() }} ราย</span>
                        </div>
                       <div class="w-full text-xs sm:text-sm">
                            <span class="block font-medium text-slate-500 mb-1">คำอธิบายกลุ่มผู้ใช้</span>
                            
                            <div class="py-2 px-4 bg-slate-50 border border-slate-100 rounded-xl text-left shadow-sm">
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed break-words whitespace-pre-line">
                                    {{ $group->detail ?? 'ไม่มีคำอธิบายสำหรับเพลย์ลิสต์นี้' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6 gap-2">
                    <h3 class="font-bold text-slate-800 text-sm sm:text-base flex items-center gap-2 min-w-0">
                        <i class="bi bi-patch-check-fill text-indigo-500 shrink-0"></i>
                        <span class="truncate">รายการสิทธิ์ที่ได้รับ</span>
                    </h3>
                    <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest shrink-0 bg-slate-50 px-2 py-0.5 rounded-md">รวม {{ $group->apps->count() }} รายการ</span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    @php 
                        $acl = [
                        0 => 'ไม่สามารถเข้าถึงได้',
                        1 => 'สามารถดูข้อมูลได้',
                        2 => 'สามารถแก้ไขข้อมูลได้',
                        ];
                    @endphp

                    @foreach($group->apps as $app)
                        <div class="group flex items-center justify-between p-3 sm:p-4 rounded-2xl sm:rounded-3xl border border-slate-100 bg-white hover:border-indigo-200 hover:shadow-md hover:shadow-indigo-50/50 transition-all duration-300 gap-3">
                            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                <div class="shrink-0">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl flex flex-col items-center justify-center 
                                        @if($app->pivot->acclevel >= 2) bg-indigo-600 text-white 
                                        @elseif($app->pivot->acclevel >= 1) bg-indigo-100 text-indigo-600 
                                        @else bg-slate-100 text-slate-500 @endif transition-colors">
                                        <span class="text-[8px] sm:text-[10px] uppercase font-bold leading-none opacity-70">Lv</span>
                                        <span class="text-base sm:text-lg font-black leading-none mt-0.5">{{ $app->pivot->acclevel }}</span>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors truncate">{{ $app->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium truncate mt-0.5">{{ $acl[$app->pivot->acclevel] ?? 'ไม่มีข้อมูลสิทธิ์' }}</p>
                                </div>
                            </div>

                            <div class="hidden sm:flex items-center shrink-0">
                                <div class="w-7 h-7 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 group-hover:text-indigo-500 transition-colors">
                                    <i class="bi bi-chevron-right text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
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