@extends('admin/layouts')

@section('title', isset($filter) ? 'แก้ไขตัวกรอง - ' .$filter->name : 'เพิ่มหมวดหมู่ตัวกรอง')

@section('content')

    
    <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-8 min-w-0">
        <a href="javascript:history.back()" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm shrink-0">
            <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">{{ isset($filter) ? 'แก้ไขตัวกรอง' : 'สร้างประเภทตัวกรองใหม่' }}</h1>
            <p class="text-slate-500 text-[11px] sm:text-sm mt-0.5 truncate">ตั้งค่าชื่อประเภทและรายการค่าสำหรับใช้กรอกข้อมูลในระบบ</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-200 p-4 sm:p-8 shadow-sm">
        <form id="filterForm" method="POST" action="{{ route('filter.save', $filter->id ?? '') }}" class="space-y-6 sm:space-y-10">
            @csrf

            <div class="space-y-4 sm:space-y-6">
                <div class="flex items-center gap-2.5 mb-1 sm:mb-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="bi bi-filter-square text-base sm:text-xl"></i>
                    </div>
                    <h3 class="font-bold text-sm sm:text-base text-slate-800"> ส่วนที่ 1: ชื่อประเภทตัวกรอง </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <label class="form-label font-semibold text-xs sm:text-sm text-slate-700 mb-1.5 block">ชื่อประเภท</label>
                        <input type="text" name="name" value="{{ $filter->name ?? '' }}" class="form-input w-full text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500" placeholder="หมวดหมู่ตัวกรองเช่น แนวเพลง, อารมณ์..." required>
                        @error('name') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <div class="space-y-4 sm:space-y-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="bi bi-plus-circle text-base sm:text-xl"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-slate-800">ส่วนที่ 2: รายการค่าในตัวกรอง</h3>
                    </div>
                    
                    <button type="button" id="add-item-btn" class="flex items-center gap-1.5 px-3 py-2 sm:px-4 sm:py-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs sm:text-sm font-bold hover:bg-indigo-100 transition-all shrink-0">
                        <i class="bi bi-plus-lg"></i>
                        <span>เพิ่มค่าตัวกรอง</span>
                    </button>
                </div>

                <div id="dynamic-list" class="space-y-3">
                    @php
                       $filterItems = (isset($filter) && $filter->value && count($filter->value) > 0) ? $filter->value->where('status','>',0) : [null];
                    @endphp

                    @foreach($filterItems as $option)
                    <div class="item-row flex items-center gap-2 sm:gap-3 group animate-in fade-in slide-in-from-top-1 duration-200">
                        <div class="flex-1 relative">
                            <div class="absolute left-3.5 sm:left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="bi bi-hash text-xs sm:text-sm"></i>
                            </div>
                            <input type="text" 
                                   name="filter_values[]" 
                                   value="{{ $option->name ?? '' }}" 
                                   class="form-input w-full pl-9 sm:pl-11 text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500" 
                                   placeholder="ระบุค่าตัวเลือก..." 
                                   required>
                            @error('filter_values.' . $loop->index) <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        
                        <button type="button" class="remove-btn p-2 sm:p-3 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all opacity-100 sm:opacity-0 group-hover:opacity-100 shrink-0">
                            <i class="bi bi-trash3 text-base sm:text-lg"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 sm:mt-12 pt-5 sm:pt-8 border-t border-slate-100 flex flex-row items-center justify-end gap-3">
                <a href="javascript:history.back()" class="w-full sm:w-auto text-center bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-200 transition-all">
                    ยกเลิก
                </a>
                <button type="button" onclick="confirmSubmit('filterForm')" class="w-full sm:w-auto inline-flex items-center justify-center bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 whitespace-nowrap">
                    บันทึกข้อมูลตัวกรอง
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const listContainer = document.getElementById('dynamic-list');
        const addBtn = document.getElementById('add-item-btn');

        // ฟังก์ชันจัดการแสดง/ซ่อนปุ่มลบอย่างถูกต้องและเคลียร์ค่าแปลกปลอม
        function refreshButtons() {
            const rows = listContainer.querySelectorAll('.item-row');
            rows.forEach(row => {
                const btn = row.querySelector('.remove-btn');
                if (rows.length > 1) {
                    btn.style.setProperty('display', 'block', 'important');
                } else {
                    btn.style.setProperty('display', 'none', 'important');
                }
            });
        }

        // เพิ่มช่องกรอกค่าตัวกรองใหม่
        addBtn.addEventListener('click', function() {
            const firstRow = listContainer.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);
            
            // เคลียร์ข้อมูลที่ติดมาจากแถวแรกให้สะอาด
            const input = newRow.querySelector('input');
            input.value = '';
            
            // ลบข้อความ Error เก่าที่อาจค้างมาตอนคัดลอกโครงสร้าง
            const oldError = newRow.querySelector('.text-rose-500');
            if(oldError) oldError.remove();
            
            // เพิ่มแถวเข้าไปในลิสต์คอนเทนเนอร์
            listContainer.appendChild(newRow);
            
            // รีเฟรชตรวจสอบการแสดงผลของปุ่มถังขยะลบข้อมูล
            refreshButtons();
            
            // สั่งโฟกัสอินพุตที่สร้างขึ้นมาใหม่ทันทีเพื่อความต่อเนื่องในการกรอก
            input.focus();
        });

        // ลบช่องกรอกข้อมูลผ่านระบบ Event Delegation
        listContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-btn')) {
                const row = e.target.closest('.item-row');
                const rows = listContainer.querySelectorAll('.item-row');
                
                if (rows.length > 1) {
                    row.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        row.remove();
                        refreshButtons();
                    }, 150);
                }
            }
        });

        // รันคำสั่งตรวจสอบสิทธิ์และสถานะปุ่มในครั้งแรกที่โหลดหน้าจอ
        refreshButtons();
    });

    // ฟังก์ชันกล่องตรวจสอบ SweetAlert2 ก่อนส่งฟอร์ม (Submit)
    function confirmSubmit(formId) {
        const form = document.getElementById(formId);

        // เช็คความครบถ้วนของแอตทริบิวต์ Required ในระบบ HTML5 Form Validation
        if (!form.reportValidity()) {
            return; 
        }

        Swal.fire({
            title: 'ยืนยันการบันทึกข้อมูล?',
            html: `
                <div class="text-slate-600 text-sm sm:text-base">คุณตรวจสอบข้อมูลและตัวกรองครบถ้วนแล้วใช่หรือไม่</div>
                <div class="mt-2 text-rose-500 font-semibold text-xs sm:text-sm">
                    (การเปลี่ยนแปลงข้อมูลอาจมีผลต่อการแบ่งประเภทคลื่นเสียง)
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ตกลง, บันทึกข้อมูล',
            cancelButtonText: 'ย้อนกลับไปตรวจสอบ',
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        })
    }
</script>
@endpush