@extends('admin/layouts')

@section('title',isset($music) ? 'แก้ไขคลื่นเสียง - ' .$music->name : 'อัปโหลดคลื่นเสียง' )

@section('content')
    <!-- Loading Overlay (ซ่อนไว้เป็นค่าเริ่มต้น) -->
    <div id="loadingOverlay" class="fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-sm hidden flex-col items-center justify-center transition-opacity opacity-0 duration-300">
        <div class="bg-white p-6 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-2xl flex flex-col items-center max-w-xs w-full mx-4 transform scale-95 transition-transform duration-300" id="loadingModal">
            <!-- Spinner Icon -->
            <svg class="animate-spin h-10 w-10 sm:h-12 sm:w-12 text-indigo-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-1">กำลังบันทึกข้อมูล...</h3>
            <p class="text-xs sm:text-sm text-slate-500 text-center">กรุณารอสักครู่ อาจใช้เวลาสักพักขึ้นอยู่กับขนาดไฟล์ของคุณ</p>
        </div>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-5 sm:mb-8 gap-4 min-w-0">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
            <a href="javascript:history.back()" class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-white border border-slate-200 rounded-xl sm:rounded-2xl text-slate-600 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm shrink-0">
                <i class="bi bi-arrow-left text-base sm:text-lg group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div class="min-w-0">
                @if($music)
                <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">แก้ไขข้อมูลคลื่นเสียง</h1>
                <p class="text-slate-500 text-[11px] sm:text-sm leading-none mt-1 truncate">ID: #{{ $music->id }} — {{ $music->name }}</p>
                @else
                <h1 class="text-lg sm:text-2xl font-bold text-slate-900 truncate">เพิ่มคลื่นเสียงใหม่</h1>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-200 p-4 sm:p-8 shadow-sm">
        <form id="editMusicForm" method="POST" action="{{ route('music.save',$music->id ?? '')  }}" enctype="multipart/form-data" class="space-y-6 sm:space-y-8">
            @csrf 

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-10">
                <div class="lg:col-span-4 space-y-3 sm:space-y-4">
                    <div class="flex items-center gap-2 mb-1 sm:mb-2">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="bi bi-image text-sm sm:text-lg"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-slate-800">รูปภาพหน้าปก</h3>
                    </div>
                    
                    <div class="relative group mx-auto lg:mx-0 max-w-[240px] sm:max-w-none">
                        <div id="imagePreviewContainer" class="w-full aspect-square rounded-2xl sm:rounded-[2rem] bg-slate-50 border-2 border-dashed border-slate-200 overflow-hidden flex items-center justify-center relative shadow-inner">
                            <img id="imagePreview" src="{{ $music?->image ? asset('image/' . $music->image) : '#' }}" 
                                 class="{{ $music?->image ? '' : 'hidden' }} w-full h-full object-cover">
                            <div id="placeholderIcon" class="{{ $music?->image ? 'hidden' : '' }} text-slate-400 text-center p-4">
                                <i class="bi bi-cloud-arrow-up text-3xl sm:text-4xl"></i>
                                <p class="text-[11px] sm:text-xs font-bold mt-1.5">อัปโหลดรูปภาพ</p>
                            </div>
                        </div>
                        <div id="hoverOverlay" class="{{ isset($playlist) && $playlist->image ? '' : 'hidden' }} absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                            <span class="text-white text-xs sm:text-sm font-bold bg-black/50 px-3 sm:px-4 py-2 rounded-lg backdrop-blur-sm">
                                <i class="bi bi-camera mr-1"></i> เปลี่ยนรูปภาพ
                            </span>
                        </div>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <p class="text-[10px] sm:text-[11px] text-slate-400 text-center lg:text-left">แนะนำขนาด 500x500 px (JPG, PNG)</p>
                    @error('image') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div class="lg:col-span-8 space-y-4 sm:space-y-6">
                    <div class="flex items-center gap-2 mb-1 sm:mb-2">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="bi bi-music-note-beamed text-sm sm:text-lg"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-slate-800">ข้อมูลคลื่นเสียง</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 block">ไฟล์คลื่นเสียง</label>
                            <div class="relative">
                                <input type="file" name="file_path" accept="audio/*" class="form-input text-xs sm:text-sm pr-10 block w-full text-slate-500
                                    file:mr-3 sm:file:mr-4 file:py-1.5 file:px-3 sm:file:py-2 sm:file:px-4
                                    file:rounded-xl file:border-0
                                    file:text-[11px] sm:file:text-xs file:font-bold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100
                                    file:cursor-pointer transition-all
                                    border border-slate-200 rounded-xl sm:rounded-2xl p-1.5 sm:p-2 bg-slate-50/50">
                                <i class="bi bi-file-music absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            </div>
                            @error('file_path') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                            @if($music?->file_path)
                                <div class="mt-2 flex items-center gap-2 text-[10px] sm:text-xs text-indigo-600 bg-indigo-50 w-fit px-2.5 py-1 rounded-full max-w-full truncate">
                                    <i class="bi bi-play-circle-fill shrink-0"></i>
                                    <span class="truncate">ไฟล์ปัจจุบัน: {{ $music->file_path }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 block">ชื่อของคลื่นเสียง</label>
                            <input type="text" id="name" name="name" value="{{ $music->name ?? '' }}" class="form-input text-xs sm:text-sm px-3 py-2 sm:px-4 sm:py-2.5" placeholder="ระบุชื่อเพลงหรือชื่อคลื่นเสียง" required>
                            @error('name') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="description" class="text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 block">คำอธิบาย</label>
                            <textarea id="description" name="detail" rows="3" class="form-input py-2 sm:py-3 text-xs sm:text-sm border rounded-xl w-full px-3" placeholder="ระบุรายละเอียดเพิ่มเติม...">{{ $music->description ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-6 pt-2">
                @foreach($filters as $filter)
                    <div class="border-b border-slate-100 pb-4 last:border-0">
                        <p class="text-[10px] sm:text-[11px] font-bold text-indigo-600 uppercase tracking-widest mb-2.5 px-1 flex items-center">
                            <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></span>
                            {{ $filter->name }}
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-1.5">
                            @foreach($filter->value->where('status','=',1) as $value)
                                <label class="flex items-center gap-2.5 p-2 hover:bg-indigo-50/50 rounded-xl cursor-pointer transition-all border border-transparent hover:border-indigo-100/70 group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" 
                                            name="types[]" 
                                            value="{{ $value->id }}" 
                                            {{ $music?->filter->contains('catdetail_id',$value->id) ? 'checked' :'' }}
                                            class="w-4.5 h-4.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30 transition-all cursor-pointer">
                                    </div>
                                    <span class="text-xs sm:text-sm text-slate-600 group-hover:text-indigo-700 font-medium truncate">
                                        {{ $value->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 sm:mt-12 pt-5 sm:pt-8 border-t border-slate-100 flex flex-row items-center justify-end gap-3">
                <a href="javascript:history.back()" class="w-full sm:w-auto text-center bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-200 transition-all">
                    ยกเลิก
                </a>
                <button type="submit" id="submitBtn" class="w-full sm:w-auto inline-flex items-center justify-center bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-xs sm:text-sm font-semibold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 whitespace-nowrap">
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
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
        
        // ตัวแปรสำหรับ Popup อัปโหลด
        const editMusicForm = document.getElementById('editMusicForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const loadingModal = document.getElementById('loadingModal');
        const submitBtn = document.getElementById('submitBtn');

        // สคริปต์สำหรับตกแต่งสถานะ Radio Button
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

        // สคริปต์พรีวิวรูปภาพ
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
                
                if (hoverOverlay) hoverOverlay.classList.add('hidden');
            }
        });

        // สคริปต์แสดง Popup ระหว่างรอฟอร์มอัปโหลด
        editMusicForm.addEventListener('submit', function(e) {
            // เช็คว่าฟอร์มผ่าน validation ของ HTML5 (เช่น required) ครบถ้วนหรือไม่
            if (this.checkValidity()) {
                // แสดง Overlay
                loadingOverlay.classList.remove('hidden');
                loadingOverlay.classList.add('flex');
                
                // อนิเมชั่นเฟดอินให้ Overlay และ Modal เด้งขึ้นมา
                setTimeout(() => {
                    loadingOverlay.classList.remove('opacity-0');
                    loadingModal.classList.remove('scale-95');
                    loadingModal.classList.add('scale-100');
                }, 10);

                // เปลี่ยนข้อความบนปุ่มและ Disable ป้องกันการกดซ้ำ
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> กำลังอัปโหลด...';
            }
        });
    });
</script>
@endpush