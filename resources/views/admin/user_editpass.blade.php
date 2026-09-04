@extends('admin/layouts')

@section('title', 'เปลี่ยนรหัสผ่าน')

@section('content')

<div class="max-w-3xl mx-auto">    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div class="flex items-center gap-4">
            <a href="javascript:history.back()" class="group flex items-center justify-center w-12 h-12 bg-white border border-slate-200 rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm">
                <i class="bi bi-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">เปลี่ยนรหัสผ่าน</h1>
                <p class="text-slate-500 text-sm leading-none mt-1">อัปเดตและรักษาความปลอดภัยบัญชีของคุณ</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-200 p-8 shadow-sm">
        
        {{-- อย่าลืมเปลี่ยน route เป็นชื่อ route สำหรับอัปเดตรหัสผ่านของคุณ --}}
        <form id="changePasswordForm" method="POST" action="{{ route('user.savepass' , $userId)  }}" class="space-y-8">
            @csrf 
            
            
            <div class="space-y-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="bi bi-shield-lock text-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-800">จัดการรหัสผ่าน</h3>
                </div>
                
                <div class="grid grid-cols-1 gap-y-6">
                    
                    <div class="col-span-1">
                        <label for="old_password" class="form-label block text-sm font-medium text-slate-700 mb-1.5">รหัสผ่านปัจจุบัน <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <i class="bi bi-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-indigo-500"></i>
                            <input type="password" id="old_password" name="old_password" class="form-input w-full pl-11 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5" placeholder="••••••••" required>
                        </div>
                        @error('old_password') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <hr class="border-slate-100 my-2">

                    <div class="col-span-1">
                        <label for="new_password" class="form-label block text-sm font-medium text-slate-700 mb-1.5">รหัสผ่านใหม่ <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <i class="bi bi-shield-plus absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-indigo-500"></i>
                            <input type="password" id="new_password" name="new_password" class="form-input w-full pl-11 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5" placeholder="••••••••" required>
                        </div>
                        @error('new_password') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="new_password_confirmation" class="form-label block text-sm font-medium text-slate-700 mb-1.5">ยืนยันรหัสผ่านใหม่ <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <i class="bi bi-shield-check absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-indigo-500"></i>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-input w-full pl-11 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="flex items-center gap-2 mt-2">
                            <input type="checkbox" id="show-password" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            <label for="show-password" class="text-sm text-slate-600 cursor-pointer select-none">แสดงรหัสผ่านทั้งหมด</label>
                        </div>
                    </div>

                </div>
            </div>

        </form>
        
        <div class="mt-10 pt-8 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex justify-end items-center gap-3 w-full">
                <a href="javascript:history.back()" class="bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-all">ยกเลิก</a>
                <button type="submit" form="changePasswordForm" class="bg-indigo-600 text-white px-8 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-all">อัปเดตรหัสผ่าน</button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const showPasswordCheckbox = document.getElementById('show-password');
        const oldPasswordInput = document.getElementById('old_password');
        const newPasswordInput = document.getElementById('new_password');
        const confirmInput = document.getElementById('new_password_confirmation');

        if (showPasswordCheckbox) {
            showPasswordCheckbox.addEventListener('change', function() {
                // สลับ Type ระหว่าง password และ text ให้ครบทั้ง 3 ช่อง
                const type = this.checked ? 'text' : 'password';
                
                if (oldPasswordInput) oldPasswordInput.type = type;
                if (newPasswordInput) newPasswordInput.type = type;
                if (confirmInput) confirmInput.type = type;
            });
        }
    });
</script>
@endpush