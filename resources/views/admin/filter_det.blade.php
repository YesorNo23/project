@extends('admin/layouts')

@section('title', 'รายละเอียดตัวกรอง - ' . $filter->name)

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
                <a href="{{ route('filter.def') }}" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 shrink-0 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="min-w-0">
                    <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">รายละเอียดตัวกรอง</h1>
                    <p class="text-slate-500 text-[11px] sm:text-sm truncate mt-0.5 sm:mt-1">ID: #{{ $filter->id }} — {{ $filter->name }}</p>
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
                            <a href="{{ route('filter.edit', $filter->id) }}" class="text-slate-700 hover:bg-indigo-50/60 hover:text-indigo-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium">
                                <i class="bi bi-pencil-square text-base text-indigo-500"></i> แก้ไขข้อมูลตัวกรอง
                            </a>
                        </div>
                        <div class="py-1">
                            <button onclick="confirmChangeStatus('{{ route('filter.del', $filter->id) }}')" class="text-rose-700 hover:bg-rose-50/60 hover:text-rose-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium w-full text-left">
                                <i class="bi bi-toggle-on text-base text-rose-500"></i> เปลี่ยนสถานะตัวกรอง
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-200 p-5 sm:p-8 shadow-sm">
            <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-8">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <i class="bi bi-grid-fill text-lg sm:text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm sm:text-base">ชื่อประเภทหลัก</h3>
                    <p class="text-slate-500 text-xs sm:text-sm">{{ $filter->name }}</p>
                </div>
            </div>

            <hr class="border-slate-100 mb-5 sm:mb-8">

            <div class="space-y-3 sm:space-y-4">
                <div class="flex items-center justify-between gap-2">
                    <h4 class="font-bold text-slate-800 text-xs sm:text-sm flex items-center gap-1.5 sm:gap-2">
                        <i class="bi bi-list-stars text-indigo-500"></i>
                        ค่าตัวเลือกที่มีในประเภทนี้
                    </h4>
                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 text-[10px] sm:text-xs font-bold rounded-full shrink-0">
                        ทั้งหมด {{ count($filter->value->where('status','>', 0)) }} รายการ
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 sm:gap-3">
                    @forelse($filter->value->where('status','>', 0) as $item)
                       
                        <div class="flex items-center gap-2.5 sm:gap-3 p-3 sm:p-4 bg-slate-50/50 border border-slate-100 rounded-xl sm:rounded-2xl hover:border-indigo-200 hover:bg-white transition-all group">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-indigo-500 group-hover:border-indigo-100 transition-colors shrink-0">
                                <i class="bi bi-check2-circle text-xs sm:text-sm"></i>
                            </div>
                            <span class="font-semibold text-slate-700 text-xs sm:text-sm break-all">{{ $item->name }}</span>
                        </div>
                        
                    @empty
                        <div class="col-span-full py-8 sm:py-12 text-center bg-slate-50 rounded-xl sm:rounded-[2rem] border border-dashed border-slate-200">
                            <i class="bi bi-slash-circle text-2xl sm:text-3xl text-slate-300 mb-1.5 block"></i>
                            <p class="text-slate-500 text-xs sm:text-sm">ไม่มีข้อมูลค่าภายในประเภทนี้</p>
                        </div>
                    @endforelse
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