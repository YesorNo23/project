@extends('admin/layouts')

@section('title', 'รายละเอียดผู้ใช้ - ' .$user->name)

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
            <a href="{{ route('user.def') }}" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 shrink-0 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm">
                <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div class="min-w-0">
                <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">รายละเอียดข้อมูลผู้ใช้</h1>
                <p class="text-slate-500 text-[11px] sm:text-sm truncate mt-0.5 sm:mt-1">ID: #{{ $user->id }} — {{ $user->name }} {{ $user->surname }}</p>
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
                        <a href="{{ route('user.editinfo', $user->id) }}" class="text-slate-700 hover:bg-indigo-50/60 hover:text-indigo-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium">
                            <i class="bi bi-pencil-square text-base text-indigo-500"></i> แก้ไขข้อมูลผู้ใช้
                        </a>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('user.editpass', $user->id) }}" class="text-slate-700 hover:bg-amber-50/60 hover:text-amber-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium">
                            <i class="bi bi-key text-base text-amber-500"></i> เปลี่ยนรหัสผ่าน
                        </a>
                    </div>
                    <div class="py-1">
                        <button onclick="confirmChangeStatus('{{ route('user.del', $user->id) }}')" class="text-rose-700 hover:bg-rose-50/60 hover:text-rose-600 flex items-center gap-2.5 px-4 py-2.5 text-xs sm:text-sm transition-colors font-medium w-full text-left">
                            <i class="bi bi-toggle-on text-base text-rose-500"></i> เปลี่ยนสถานะผู้ใช้
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
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTucPAL08gN2B5ee90XA1IiphwnwIrBOb2xgQ&s" class="w-32 h-32 rounded-[2.5rem] mx-auto border-4 border-white shadow-xl">
                    <div class="absolute bottom-1 right-1/2 translate-x-12 w-6 h-6 bg-emerald-500 border-4 border-white rounded-full shadow-sm" title="Active"></div>
                </div>

                <div class="mt-6"> 
                    <h2 class="text-lg sm:text-2xl font-bold text-slate-900 leading-snug break-words">{{$user->name}} {{$user->surname}}</h2>  
                </div>

                <div class="flex flex-wrap justify-center gap-2 mt-6">
                    <span class="px-4 py-1.5 rounded-xl text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-wider">{{$user->usertype}} Member</span>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-50 space-y-4">
                   <div class="flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-slate-400"><i class="bi bi-info-circle me-1.5"></i>สถานะการใช้งาน</span>
                            @if($user->status == 1)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 border border-emerald-200">ACTIVE</span>
                            @elseif($user->status == 0)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-600 border border-rose-200">IN ACTIVE</span>
                            @endif
                        </span>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-6 sm:space-y-8">
            
    <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
        {{-- ส่วนหัวการ์ด: ปรับลด padding บนมือถือลงเล็กน้อยให้ดูสมส่วน --}}
        <div class="px-5 py-4 sm:px-8 sm:py-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="bi bi-card-list text-lg sm:text-xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm sm:text-base"> ข้อมูลพื้นฐาน </h3>
            </div>
        </div>

        
        <div class="p-5 sm:p-8 grid grid-cols-2 gap-y-5 gap-x-4 sm:gap-y-8 sm:gap-x-12">
            <div class="group col-span-1">
                <label class="text-[10px] sm:text-[11px] text-slate-400 uppercase tracking-widest font-bold block mb-1 group-hover:text-indigo-500 transition-colors">ชื่อของผู้ใช้</label>
                <p class="text-slate-800 text-sm sm:text-base font-semibold border-b border-transparent group-hover:border-slate-100 pb-1 truncate">{{$user->name}}</p>
            </div>

            <div class="group col-span-1">
                <label class="text-[10px] sm:text-[11px] text-slate-400 uppercase tracking-widest font-bold block mb-1 group-hover:text-indigo-500 transition-colors">นามสกุล</label>
                <p class="text-slate-800 text-sm sm:text-base font-semibold border-b border-transparent group-hover:border-slate-100 pb-1 truncate">{{$user->surname}}</p>
            </div>

            {{-- อีเมล: ใช้ col-span-2 เต็มแถวบนมือถือ เพื่อป้องกันปัญหานักพิมพ์อีเมลยาวๆ จนตัวอักษรตกขอบ --}}
            <div class="group col-span-2 sm:col-span-1">
                <label class="text-[10px] sm:text-[11px] text-slate-400 uppercase tracking-widest font-bold block mb-1 group-hover:text-indigo-500 transition-colors">อีเมล</label>
                <p class="text-slate-800 text-sm sm:text-base font-semibold border-b border-transparent group-hover:border-slate-100 pb-1 break-all">{{$user->email}}</p>
            </div>

            <div class="group col-span-1">
                <label class="text-[10px] sm:text-[11px] text-slate-400 uppercase tracking-widest font-bold block mb-1 group-hover:text-indigo-500 transition-colors">วันเกิด</label>
                <p class="text-indigo-600 text-sm sm:text-base font-semibold border-b border-transparent group-hover:border-slate-100 pb-1">{{$user->birthdate}}</p>
            </div>

            <div class="group col-span-1">
                <label class="text-[10px] sm:text-[11px] text-slate-400 uppercase tracking-widest font-bold block mb-1 group-hover:text-indigo-500 transition-colors">เพศ</label>
                <p class="text-slate-800 text-sm sm:text-base font-semibold border-b border-transparent group-hover:border-slate-100 pb-1">{{$user->gender}}</p>
            </div>

            <div class="group col-span-1">
                <label class="text-[10px] sm:text-[11px] text-slate-400 uppercase tracking-widest font-bold block mb-1 group-hover:text-indigo-500 transition-colors">ระดับสิทธิ์</label>
                <p class="text-slate-800 text-sm sm:text-base font-semibold border-b border-transparent group-hover:border-slate-100 pb-1">
                    <span class="inline-block bg-slate-50 text-slate-700 px-2 py-0.5 rounded-md text-xs border border-slate-100">{{$user->usertype}}</span>
                </p>
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