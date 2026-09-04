@extends('admin/layouts')

@section('title', isset($user) ? 'แก้ข้อมูลผู้ใช้งาน - ' .$user->name : 'เพิ่มบัญชีผู้ใช้งาน')

@section('content')


    <div class="flex flex-row items-center justify-between mb-5 sm:mb-8 gap-3">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <a href="javascript:history.back()" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm shrink-0">
                <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div class="min-w-0">
                @if($user)
                <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">แก้ไขข้อมูลสมาชิก</h1>
                <p class="text-slate-500 text-[11px] sm:text-sm leading-none mt-1 truncate">ID: #{{ $user->id }} — {{ $user->name }} {{ $user->surname }}</p>
                @else
                <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">เพิ่มสมาชิกในระบบ</h1>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-200 p-4 sm:p-8 shadow-sm">
        
        <form id="editUserForm" method="POST" action="{{ route('user.save', $user->id ?? '' ) }}" class="space-y-6 sm:space-y-10">
            @csrf 
            
            <div class="space-y-4 sm:space-y-6">
                <div class="flex items-center gap-2.5 mb-2 sm:mb-6">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="bi bi-card-list text-base sm:text-xl"></i>
                    </div>
                    <h3 class="font-bold text-base sm:text-lg text-slate-800">ข้อมูลพื้นฐาน </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-x-8 sm:gap-y-6">
                    <div>
                        <label for="name" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700">ชื่อหลัก </label>
                        <input type="text" id="name" name="name" value="{{ $user->name ?? '' }}" class="form-input text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5" placeholder="กรอกชื่อ 1-255 ตัวอักษร" required>
                        @error('name') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="surname" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700">นามสกุล </label>
                        <input type="text" id="surname" name="surname" value="{{ $user->surname ?? '' }}" class="form-input text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5" placeholder="กรอกนามสกุล 1-255 ตัวอักษร" required>
                        @error('surname') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="gender" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700"> เพศ </label>
                        <div class="relative group">
                            <i class="bi bi-gender-ambiguous absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base sm:text-lg group-focus-within:text-indigo-500 transition-colors"></i>
                            
                            <select id="gender" name="gender" class="form-input pl-10 sm:pl-11 text-xs sm:text-sm appearance-none cursor-pointer px-3 py-2 sm:px-4 sm:py-2.5">
                                <option value="" disabled @selected(empty($user->gender))>เลือกเพศ</option>
                                <option value="ชาย" @selected(($user->gender ?? '') == 'ชาย')>ชาย</option>
                                <option value="หญิง" @selected(($user->gender ?? '') == 'หญิง')>หญิง</option>
                                <option value="ไม่ระบุเพศ" @selected(($user->gender ?? '') == 'ไม่ระบุเพศ')>ไม่ระบุเพศ</option>
                            </select>
                            
                            <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-focus-within:text-indigo-500">
                                <i class="bi bi-chevron-down text-[10px] sm:text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="birthday" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700"> วันเกิด </label>
                        <div class="relative">
                            <i class="bi bi-calendar-event absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base sm:text-lg"></i>
                            <input type="date" id="birthday" name="birthdate" value="{{ $user?->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '' }}" class="form-input pl-10 sm:pl-11 text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5">
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label for="mail" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700">อีเมล</label>
                        <div class="relative">
                            <i class="bi bi-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base sm:text-lg"></i>
                            <input type="email" id="mail" name="email" value="{{ $user->email ?? '' }}" class="form-input pl-10 sm:pl-11 text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5" placeholder="example@gmail.com" >
                        </div>
                        <p class="text-[10px] sm:text-xs text-slate-400 mt-1"> ใช้เป็น Username สำหรับเข้าสู่ระบบ</p>
                        @error('email') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            @if(!$user)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-x-8 sm:gap-y-6 mt-4 sm:mt-6">
                <div>
                    <label for="password" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700"> รหัสผ่าน </label>
                    <div class="relative group">
                        <i class="bi bi-shield-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base sm:text-lg group-focus-within:text-indigo-500"></i>
                        <input type="password" id="password" name="password" class="form-input pl-10 sm:pl-11 text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5" placeholder="••••••••">
                    </div>
                </div>
                
                <div>
                    <label for="password_confirmation" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700"> ยืนยันรหัสผ่าน </label>
                    <div class="relative group">
                        <i class="bi bi-shield-check absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base sm:text-lg group-focus-within:text-indigo-500"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input pl-10 sm:pl-11 text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5" placeholder="••••••••" >
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="checkbox" id="show-password" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        <label for="show-password" class="text-xs sm:text-sm text-slate-600 cursor-pointer select-none"> แสดงรหัสผ่าน </label>
                    </div>
                </div>
            </div>
            @endif

            <div class="space-y-4 sm:space-y-6 pt-6 sm:pt-10 border-t border-slate-100">
                <div class="flex items-center gap-2.5 mb-2 sm:mb-6">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="bi bi-shield-check text-base sm:text-xl"></i>
                    </div>
                    <h3 class="font-bold text-base sm:text-lg text-slate-800"> สถานะและสิทธิ์ </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                    <div>
                        <label for="status" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700"> สถานะการใช้งาน </label>
                        <select id="status" name="status" class="form-input text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5">
                            <option value="1" {{ $user?->status == '1' ? 'selected' : '' }} class="text-emerald-600 font-semibold">Active (เปิดใช้งาน)</option>
                            <option value="0" {{ $user?->status == '0' ? 'selected' : '' }} class="text-rose-600 font-semibold">Inactive (ปิดใช้งาน)</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="role" class="form-label text-xs sm:text-sm font-semibold mb-1.5 block text-slate-700"> ระดับสิทธิ์ผู้ใช้ </label>
                        <select id="role" name="group" class="form-input text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5">
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ (optional($user?->groups->first())->id == $group->id) ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </form>
        
        <div class="mt-8 sm:mt-12 pt-5 sm:pt-8 border-t border-slate-100 flex flex-row items-center justify-end gap-3">
            <a href="javascript:history.back()" class="w-full sm:w-auto text-center bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-200 transition-all">
                ยกเลิก
            </a>
            <button type="submit" form="editUserForm" class="w-full sm:w-auto bg-indigo-600 text-white px-8 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-indigo-700 transition-all">
                บันทึกข้อมูล
            </button>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const showPasswordCheckbox = document.getElementById('show-password');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');

        if (showPasswordCheckbox) {
            showPasswordCheckbox.addEventListener('change', function() {
                const type = this.checked ? 'text' : 'password';
                if (passwordInput) passwordInput.type = type;
                if (confirmInput) confirmInput.type = type;
            });
        }
    });
</script>
@endpush