@extends('admin/layouts')

@section('title', isset($app->id) ? 'แก้ไขแอป - ' .$app->name : 'เพิ่มแอปพลิเคชั่น' )

@section('content')

    <div class="flex items-center gap-3 sm:gap-4 mb-5 sm:mb-8 min-w-0">
        <a href="javascript:history.back()" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm shrink-0">
            <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div class="min-w-0">
            @if($app->id)
            <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">แก้ไขข้อมูลแอปพลิเคชัน</h1>
            <p class="text-slate-500 text-[11px] sm:text-sm leading-none mt-1 truncate">ID: #{{ $app->id }} — {{ $app->name }} </p>
            @else
            <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">เพิ่มแอปพลิเคชัน</h1>
            <p class="text-slate-500 text-[11px] sm:text-sm leading-none mt-1 truncate">แอปพลิเคชัน</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-200 p-4 sm:p-8 shadow-sm">
        <form id="editGroupForm" method="POST" action="{{ route('app.save', $app->id ?? '' ) }}" class="space-y-6 sm:space-y-10">
            @csrf
            
            <div class="space-y-4 sm:space-y-6">
                <div class="flex items-center gap-2.5 mb-2 sm:mb-6">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="bi bi-card-list text-base sm:text-xl"></i>
                    </div>
                    <h3 class="font-bold text-base sm:text-lg text-slate-800"> ข้อมูลแอปพลิเคชัน </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-x-8 sm:gap-y-6">
                    <div class="col-span-1">
                        <label for="name" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700">ชื่อแอป</label>
                        <input type="text" id="name" name="name" value="{{ $app->name ?? '' }}" class="form-input text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5" placeholder="กรุณากรอกชื่อแอป" required>
                        @error('name') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-span-1">
                        <label for="dir" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700">direction</label>
                        <input type="text" id="dir" name="dir" value="{{ $app->dir ?? '' }}" class="form-input text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5" placeholder="กรุณากรอก direction" required>
                        @error('dir') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div class="mt-8 sm:mt-12 pt-5 sm:pt-8 border-t border-slate-100 flex flex-row items-center justify-end gap-3">
                <a href="javascript:history.back()" class="w-full sm:w-auto text-center bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-200 transition-all">
                    ยกเลิก
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 whitespace-nowrap">
                    <i class="bi bi-check-lg me-1.5 text-sm"></i>บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
    <div class="mt-4 sm:mt-6 flex items-start sm:items-center gap-3 p-3.5 sm:p-5 bg-amber-50 rounded-xl sm:rounded-2xl border border-amber-100/70 shadow-sm mx-1 sm:mx-0">
        <i class="bi bi-info-circle text-amber-600 text-base sm:text-lg shrink-0 mt-0.5 sm:mt-0"></i>
        <p class="text-[11px] sm:text-xs text-amber-700 leading-relaxed sm:leading-normal">
            <strong>คำแนะนำ:</strong> โปรดตรวจสอบความถูกต้องของชื่อแอปและโครงสร้างระบบ (Direction) ให้ถูกต้องก่อนยืนยันการบันทึกข้อมูล
        </p>
    </div>

@endsection