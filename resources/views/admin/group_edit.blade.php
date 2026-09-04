@extends('admin/layouts')

@section('title', isset($group) ? 'แก้ไขกลุ่มผู้ใช้ - ' .$group->name : 'เพิ่มกลุ่มผู้ใช้งาน' )

@section('content')

    <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-8 min-w-0">
        <a href="javascript:history.back()" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm shrink-0">
            <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div class="min-w-0">
            @if(isset($group))
            <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">แก้ไขกลุ่มผู้ใช้และสิทธิ์</h1>
            <p class="text-slate-500 text-[11px] sm:text-sm leading-none mt-1 truncate">ID: #{{ $group->id }} — {{ $group->name }} </p>
            @else
            <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">เพิ่มกลุ่มผู้ใช้และสิทธิ์</h1>
            <p class="text-slate-500 text-[11px] sm:text-sm leading-none mt-1 truncate">ตั้งค่าข้อมูลกลุ่มและกำหนดระดับสิทธิ์การเข้าถึงระบบ</p>
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-200 p-4 sm:p-8 shadow-sm">
        <form id="editGroupForm" method="POST" action="{{ route('group.save', $group->id ?? '') }}" class="space-y-6 sm:space-y-10">
            @csrf
            
            <div class="space-y-4 sm:space-y-6">
                <div class="flex items-center gap-2.5 mb-2 sm:mb-6">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="bi bi-card-list text-base sm:text-xl"></i>
                    </div>
                    <h3 class="font-bold text-base sm:text-lg text-slate-800">ข้อมูลกลุ่มผู้ใช้</h3>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:gap-6">
                    <div class="group">
                        <label for="name" class="text-[10px] sm:text-[11px] text-slate-400 uppercase tracking-widest font-bold block mb-1.5 group-focus-within:text-indigo-600 transition-colors">
                            ชื่อกลุ่มผู้ใช้งาน <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ $group->name ?? '' }}" class="form-input text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5" placeholder="กรอกชื่อกลุ่มผู้ใข้ ไม่เกิน 50 ตัวอักษร" required>
                        @error('name') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="group">
                        <label for="detail" class="text-[10px] sm:text-[11px] text-slate-400 uppercase tracking-widest font-bold block mb-1.5 group-focus-within:text-indigo-600 transition-colors">
                            รายละเอียดกลุ่ม
                        </label>
                        <textarea id="detail" name="detail" rows="3" class="form-input text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5" placeholder="กรอกรายละเอียดได้ไม่เกิน 50 ตัวอักษร">{{ $group->detail ?? ''}}</textarea>
                        @error('detail') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div class="space-y-4 sm:space-y-6 pt-6 sm:pt-10 border-t border-slate-100">
                <div class="flex items-center gap-2.5 mb-2 sm:mb-6">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="bi bi-shield-lock text-base sm:text-xl"></i>
                    </div>
                    <h3 class="font-bold text-base sm:text-lg text-slate-800">จัดการระดับสิทธิ์</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    @php
                        $access = [ 
                            0 => 'No Access',
                            1 => 'Read',
                            2 => 'Write'
                        ];
                    @endphp

                    @foreach($apps as $app)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 sm:p-4 bg-slate-50 rounded-xl sm:rounded-2xl border border-slate-100/50 hover:border-indigo-100 hover:bg-white transition-all group gap-3">
                        <div class="flex flex-col min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-700 truncate group-hover:text-indigo-600 transition-colors">{{ $app->name }}</h4>
                            <span class="text-[9px] sm:text-[10px] font-mono text-slate-400 uppercase tracking-tighter mt-0.5 truncate">{{ $app->dir }}</span>
                        </div>
                        
                        <div class="w-full sm:w-auto sm:min-w-[140px]">
                            <select name="apps[{{ $app->id }}]" class="w-full bg-white border border-slate-200 rounded-lg sm:rounded-xl px-2.5 py-1.5 sm:px-3 sm:py-2 text-xs font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500/20 outline-none cursor-pointer shadow-sm">
                                @foreach($access as $index => $text)
                                    <option value="{{ $index }}" {{ ($group?->apps->where('id', $app->id)->first()?->pivot->acclevel == $index) ? 'selected' : '' }} >ระดับสิทธิ์ {{ $text }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 sm:mt-12 pt-5 sm:pt-8 border-t border-slate-100 flex flex-row items-center justify-end gap-3">
                <a href="javascript:history.back()" class="w-full sm:w-auto text-center bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-200 transition-all">
                    ยกเลิก
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 whitespace-nowrap">
                    <i class="bi bi-check-lg me-1.5 text-sm"></i>บันทึกข้อมูลและสิทธิ์
                </button>
            </div>
        </form>
    </div>

    
    <div class="mt-4 sm:mt-6 flex items-start sm:items-center gap-3 p-3.5 sm:p-5 bg-amber-50 rounded-xl sm:rounded-2xl border border-amber-100/70 shadow-sm mx-1 sm:mx-0">
        <i class="bi bi-info-circle text-amber-600 text-base sm:text-lg shrink-0 mt-0.5 sm:mt-0"></i>
        <p class="text-[11px] sm:text-xs text-amber-700 leading-relaxed sm:leading-normal">
            <strong>คำแนะนำ:</strong> การเปลี่ยนระดับสิทธิ์จะมีผลกับผู้ใช้งานทุกคนในกลุ่มนี้ทันที โปรดตรวจสอบความถูกต้องก่อนยืนยันการบันทึก
        </p>
    </div>

@endsection