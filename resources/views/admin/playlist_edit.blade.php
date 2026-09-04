@extends('admin/layouts')

@section('title', isset($playlist) ? 'แก้ไขข้อมูลเพลย์ลิสต์ - ' .$playlist->name : 'สร้างเพลย์ลิสต์')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-5 sm:mb-8 gap-4 min-w-0">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <a href="javascript:history.back()" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm shrink-0">
                <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div class="min-w-0">
                @if(isset($playlist))
                <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">แก้ไขข้อมูลเพลย์ลิสต์</h1>
                <p class="text-slate-500 text-[11px] sm:text-sm leading-none mt-1 truncate">ID: #{{ $playlist->id }} — {{ $playlist->name }}</p>
                @else
                <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">สร้างเพลย์ลิสต์ใหม่</h1>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Container Card --}}
    <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-200 p-4 sm:p-8 shadow-sm">
        <form id="editPlaylistForm" method="POST" action="{{ route('playlist.save', $playlist->id ?? '') }}" enctype="multipart/form-data" class="space-y-6 sm:space-y-8">
            @csrf 

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-10">
                
                {{-- ส่วนที่ 1: อัปโหลดรูปภาพ --}}
                <div class="lg:col-span-4 space-y-3 sm:space-y-4">
                    <div class="flex items-center gap-2 mb-1 sm:mb-2">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="bi bi-image text-sm sm:text-lg"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-slate-800">รูปภาพหน้าปก</h3>
                    </div>
                    
                    <div class="relative group mx-auto lg:mx-0 max-w-[240px] sm:max-w-none">
                        <div id="imagePreviewContainer" class="w-full aspect-square rounded-2xl sm:rounded-[2rem] bg-slate-50 border-2 {{ isset($playlist) && $playlist->image ? 'border-solid border-indigo-200' : 'border-dashed border-slate-200' }} overflow-hidden flex items-center justify-center relative shadow-inner">
                            
                            {{-- รูปภาพ Preview --}}
                            <img id="imagePreview" src="{{ isset($playlist) && $playlist->image ? asset('image/' . $playlist->image) : '#' }}" 
                                 class="{{ isset($playlist) && $playlist->image ? 'opacity-100' : 'hidden opacity-0' }} w-full h-full object-cover transition-opacity duration-300">
                            
                            {{-- ไอคอนเมื่อยังไม่มีรูป --}}
                            <div id="placeholderIcon" class="{{ isset($playlist) && $playlist->image ? 'hidden' : '' }} text-slate-400 text-center p-4">
                                <i class="bi bi-cloud-arrow-up text-3xl sm:text-4xl"></i>
                                <p class="text-[11px] sm:text-xs font-bold mt-1.5">อัปโหลดรูปภาพ</p>
                            </div>

                            {{-- Overlay ตอน Hover (สำหรับแก้รูป) --}}
                            <div id="hoverOverlay" class="{{ isset($playlist) && $playlist->image ? '' : 'hidden' }} absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <span class="text-white text-xs sm:text-sm font-bold bg-black/50 px-3 sm:px-4 py-2 rounded-lg backdrop-blur-sm">
                                    <i class="bi bi-camera mr-1"></i> เปลี่ยนรูปภาพ
                                </span>
                            </div>
                        </div>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <p class="text-[10px] sm:text-[11px] text-slate-400 text-center lg:text-left">แนะนำขนาด 500x500 px (JPG, PNG)</p>
                    @error('image') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                </div>

                {{-- ส่วนที่ 2: ข้อมูล Playlist --}}
                <div class="lg:col-span-8 space-y-4 sm:space-y-6">
                    <div class="flex items-center gap-2 mb-1 sm:mb-2">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="bi bi-collection-play text-sm sm:text-lg"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-slate-800">ข้อมูลเพลย์ลิสต์</h3>
                    </div>

                    <div class="space-y-4 sm:space-y-5">
                        {{-- ชื่อ Playlist --}}
                        <div>
                            <label class="text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 block">ชื่อเพลย์ลิสต์</label>
                            <input type="text" id="name" name="name" value="{{ $playlist->name ?? '' }}" class="form-input w-full text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500" placeholder="ตั้งชื่อเพลย์ลิสต์ของคุณ" required>
                            @error('name') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        {{-- คำอธิบาย Playlist --}}
                        <div>
                            <label for="description" class="text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 block">คำอธิบาย</label>
                            <textarea id="description" name="detail" rows="4" class="form-input w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2 sm:py-3 text-xs sm:text-sm" placeholder="ระบุรายละเอียด หรือความรู้สึกของเพลย์ลิสต์นี้...">{{ $playlist->detail ?? '' }}</textarea>
                        </div>

                        {{-- สถานะ (ส่วนตัว/สาธารณะ) - ปรับปรุงสไตล์กริดให้ขยายเต็มขนาดบนมือถือ (grid-cols-1 sm:flex) --}}
                        <div>
                            <label class="text-xs sm:text-sm font-semibold text-slate-700 mb-2 block">สถานะความเป็นส่วนตัว</label>
                            <div class="grid grid-cols-1 sm:flex sm:flex-wrap gap-3 sm:gap-4">
                                <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none {{ ($playlist->status ?? '2') == 2 ? 'ring-1 ring-indigo-500 border-indigo-500 bg-indigo-50/30' : '' }}">
                                    <input type="radio" name="status" value="2" {{ ($playlist->status ?? '2' ) == 2 ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 cursor-pointer">
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-xs sm:text-sm font-bold text-slate-800 flex items-center gap-1.5">
                                            <i class="bi bi-globe text-indigo-500"></i> สาธารณะ
                                        </span>
                                        <span class="text-[11px] sm:text-xs text-slate-500 mt-0.5 break-words">ทุกคนสามารถค้นหาและฟังได้</span>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-all select-none {{ ($playlist->status ?? '') == 1 ? 'ring-1 ring-indigo-500 border-indigo-500 bg-indigo-50/30' : '' }}">
                                    <input type="radio" name="status" value="1" {{ ($playlist->status ?? '') == 1 ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 cursor-pointer">
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-xs sm:text-sm font-bold text-slate-800 flex items-center gap-1.5">
                                            <i class="bi bi-lock-fill text-slate-400"></i> ส่วนตัว
                                        </span>
                                        <span class="text-[11px] sm:text-xs text-slate-500 mt-0.5 break-words">เฉพาะคุณเท่านั้นที่เห็นเพลย์ลิสต์นี้</span>
                                    </div>
                                </label>
                            </div>
                            @error('status') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-8 pt-5 sm:pt-8 border-t border-slate-100 flex flex-row items-center justify-end gap-3">
                <a href="javascript:history.back()" class="w-full sm:w-auto text-center bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-200 transition-all">
                    ยกเลิก
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 whitespace-nowrap">
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const placeholderIcon = document.getElementById('placeholderIcon');
        const hoverOverlay = document.getElementById('hoverOverlay');
        const previewContainer = document.getElementById('imagePreviewContainer');

        // สคริปต์ตกแต่งกรอบ Radio Button ยึดตามการคลิกเลือกจริง
        const statusRadios = document.querySelectorAll('input[name="status"]');
        statusRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="status"]').forEach(r => {
                    const parent = r.closest('label');
                    if(r.checked) {
                        parent.classList.add('ring-1', 'ring-indigo-500', 'border-indigo-500', 'bg-indigo-50/30');
                    } else {
                        parent.classList.remove('ring-1', 'ring-indigo-500', 'border-indigo-500', 'bg-indigo-50/30');
                    }
                });
            });
        });

        // สคริปต์พรีวิวอัปโหลดรูปภาพหน้าปก
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    
                    placeholderIcon.classList.add('hidden');
                    imagePreview.classList.remove('hidden');
                    
                    previewContainer.classList.remove('border-dashed', 'bg-slate-50');
                    previewContainer.classList.add('border-solid', 'border-indigo-200');
                    
                    if (hoverOverlay) hoverOverlay.classList.remove('hidden');
                    
                    setTimeout(() => {
                        imagePreview.classList.add('opacity-100');
                    }, 50);
                }
                
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = "#";
                imagePreview.classList.add('hidden', 'opacity-0');
                placeholderIcon.classList.remove('hidden');
                
                previewContainer.classList.add('border-dashed', 'bg-slate-50');
                previewContainer.classList.remove('border-solid', 'border-indigo-200');
                
                if (hoverOverlay) hoverOverlay.add('hidden');
            }
        });
    });
</script>
@endpush