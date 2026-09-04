@extends('admin/layouts')

@section('title', 'จัดการเพลย์ลิสต์')

@section('content')
            
    <div class="flex justify-between items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900">เพลย์ลิสต์ทั้งหมด</h2>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-all shadow-lg flex items-center shrink-0" onclick="window.location.href='{{ route('playlist.edit') }}' ">
            <i class="bi bi-plus-circle-fill me-1 sm:me-2"></i> สร้างเพลย์ลิสต์
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 w-full">
        <table id="playlistTable" class="w-full text-left">
            <thead class="bg-slate-50/50 text-xs text-slate-400 uppercase tracking-widest">
                <tr>
                    <th class="px-4 py-4 hidden md:table-cell">ID</th>
                    <th class="px-4 py-4">Playlist</th>
                    <th class="px-4 py-4 hidden md:table-cell">Status</th>
                    <th class="px-4 py-4 hidden md:table-cell text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($playLists as $row)
                <tr class="group hover:bg-slate-50/50 transition-all">
                    <td class="px-4 py-4 text-slate-400 font-mono text-sm hidden md:table-cell">
                        <a href="{{ route('playlist.det',$row->id) }}">#{{ $row->id }}</a>
                    </td>
                    <td class="px-4 py-4">
                        <a href="{{ route('playlist.det',$row->id) }}">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 shrink-0 rounded-xl bg-indigo-100 flex items-center justify-center overflow-hidden border border-slate-200">
                                <img class="w-full h-full object-cover" src="{{ asset('image/' . $row->image) }}">
                            </div>
                            <div class="break-words max-w-[200px] sm:max-w-none">
                                <p class="font-bold text-slate-800 text-sm whitespace-normal">{{ $row->name ?? '' }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $row->music->count() ?? 0 }} คลื่นเสียง</p>
                            </div>
                        </div>
                        </a>
                    </td>
                    <td class="px-4 py-4 hidden md:table-cell">
                        @if($row->status > 0)
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 border border-emerald-200">ACTIVE</span>
                        @else
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-600 border border-rose-200">IN ACTIVE</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 hidden md:table-cell text-right">
                        <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-all">
                            <button title="จัดการคลื่นเสียงในเพลย์ลิสต์" class="w-8 h-8 flex items-center justify-center hover:bg-white rounded-lg shadow-sm text-indigo-500 border border-transparent hover:border-slate-100" onclick="window.location.href='{{ route('playlist.manage', $row->id) }}'">
                                <i class="bi bi-journal"></i>
                            </button>
                            <button title="แก้ไขข้อมูลเพลย์ลิสต์" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded-lg shadow-sm text-slate-600 border border-transparent hover:border-slate-200" onclick="window.location.href=''">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button title="เปลี่ยนสถาะเพลย์ลิสต์" class="w-8 h-8 flex items-center justify-center hover:bg-rose-50 rounded-lg shadow-sm text-rose-500 border border-transparent hover:border-rose-200" onclick="confirmDelete('')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection    
        
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
    /* -----------------------------------------------------------
       CSS ล็อกตำแหน่ง Layout กล่องค้นหาและปุ่มหัวตารางให้ตรงระเบียบ
    ----------------------------------------------------------- */
    
    /* สไตล์ปุ่มจัดเรียงหัวตารางให้ตรงระเบียบ ไม่เบี้ยว */
    table.dataTable thead th {
        position: relative;
        background-image: none !important;
    }

    /* จัดโครงสร้างกล่องแถวบนของตาราง */
    .dt-top-layout {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-bottom: 1.25rem;
        gap: 1rem;
    }

    /* ตกแต่งตัวเลือกจำนวนแถวการแสดงผล */
    .dataTables_wrapper .dataTables_length label {
        font-size: 13px;
        color: #475569;
        font-weight: 500;
    }
    .dataTables_wrapper .dataTables_length select {
        padding: 4px 28px 4px 10px !important;
        font-size: 13px !important;
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        outline: none;
        height: 36px;
    }

    /* เอาคำว่า "ค้นหา:" ลบออกไปอย่างถาวร */
    .dataTables_wrapper .dataTables_filter {
        margin: 0 !important;
        float: none !important;
    }
    .dataTables_wrapper .dataTables_filter label {
        font-size: 0 !important; 
        margin: 0 !important;
        display: block;
        width: 100%;
    }
    /* ออกแบบดีไซน์ช่องพิมพ์ค้นหา */
    .dataTables_wrapper .dataTables_filter input {
        width: 240px !important;
        margin: 0 !important;
        padding: 6px 14px !important;
        font-size: 13px !important;
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff;
        height: 36px;
        outline: none;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
    }

    /* สไตล์ปรับแต่งตัวเปลี่ยนหน้า Pagination ด้านล่าง */
    .dataTables_wrapper .dataTables_info {
        font-size: 12px;
        color: #64748b;
        padding-top: 1rem;
    }
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.6rem !important;
        border-radius: 6px !important;
        margin: 0 2px !important;
        border: 1px solid #e2e8f0 !important;
        font-size: 12px;
        cursor: pointer;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #4f46e5 !important;
        color: white !important;
        border-color: #4f46e5 !important;
    }

    /* สไตล์ปรับเยื้องสลับบรรทัดเฉพาะเมื่อแสดงผลบนจอสมาร์ทโฟน (Mobile) */
    @media (max-width: 639px) {
        .dt-top-layout {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .dataTables_wrapper .dataTables_length {
            width: auto;
        }
        /* ยืดช่องค้นหาลงมาบรรทัดใหม่ แบบเต็มขอบด้านกว้าง 100% ไม่เบียดตาราง */
        .dataTables_wrapper .dataTables_filter {
            width: 100% !important;
        }
        .dataTables_wrapper .dataTables_filter input {
            width: 100% !important;
        }
        /* กระชับปุ่มเปลี่ยนหน้าบนมือถือ */
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.previous):not(.next):not(.current) {
            display: none !important;
        }
        .dataTables_wrapper .dataTables_info {
            text-align: center !important;
            width: 100%;
        }
        .dataTables_wrapper .dataTables_paginate {
            text-align: center !important;
            width: 100%;
            display: flex;
            justify-content: center;
        }
    }
</style>

<script>
    $(document).ready(function() {
        $('#playlistTable').DataTable({
            order: [], 
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json',
                search: "",
                searchPlaceholder: " ค้นหาเพลย์ลิสต์...",
                lengthMenu: "_MENU_", 
                paginate: {
                    previous: '<i class="bi bi-chevron-left"></i>',
                    next: '<i class="bi bi-chevron-right"></i>'
                }
            },
            pageLength: 5, 
            lengthMenu: [5, 10, 25, 50],
            dom: '<"flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4 w-full"lf>rt<"flex flex-col sm:flex-row justify-between items-center mt-4 gap-4 w-full"ip>',
            columnDefs: [
                { type: 'num', targets: 0 }, 
                { orderable: false, targets: 3 } 
            ]
        });
    });

    function confirmDelete(url) {
        Swal.fire({
            title: 'ยืนยันการลบเพลย์ลิสต์?',
            text: "คุณต้องการลบเพลย์ลิสต์นี้ออกจากระบบใช่หรือไม่",
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