@extends('admin/layouts')

@section('title', 'ประวัติการฟังคลื่นเสียง')

@section('content')
            
    <div class="flex justify-between items-end mb-4 sm:mb-8 px-1">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900">ประวัติการฟัง</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 hidden sm:block">รายการประวัติการเข้าฟังคลื่นเสียงของผู้ใช้งานทั้งหมด</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-3 sm:p-6 w-full overflow-hidden">
        <table id="historyTable" class="w-full text-left block sm:table sm:table-fixed">
            <thead class="bg-slate-50/50 text-[10px] sm:text-xs text-slate-500 uppercase tracking-widest border-b border-slate-100 block hidden sm:table-header-group">
                <tr class="block sm:table-row">
                    <th class="px-4 py-4 w-[25%] text-left sm:table-cell">วันเวลา</th>
                    <th class="px-4 py-4 w-[50%] text-left sm:table-cell">ชื่อคลื่นเสียง</th>
                    <th class="px-4 py-4 w-[25%] text-left sm:table-cell">ชื่อผู้ฟัง</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 block sm:table-row-group">
                @foreach($histories as $row)
                <tr class="group hover:bg-slate-50/50 transition-all block mb-3.5 p-3.5 rounded-xl border border-slate-100 bg-slate-50/10 sm:p-0 sm:mb-0 sm:border-0 sm:bg-transparent sm:table-row">
                    <td class="px-0 py-1 sm:px-4 sm:py-4 text-slate-500 text-[11px] sm:text-sm font-mono text-left align-middle leading-tight block sm:table-cell" data-sort="{{ $row->created_at }}">
                        <div class="flex flex-row sm:flex-col justify-between items-center sm:items-start gap-1">
                            <div>
                                <span class="text-slate-700 sm:text-slate-500 font-semibold sm:font-normal">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/y') }}</span>
                                <span class="text-slate-400 sm:text-slate-500 ml-1 sm:ml-0 sm:block sm:mt-0.5">{{ \Carbon\Carbon::parse($row->created_at)->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-1 sm:hidden text-slate-400 font-sans text-[10px] bg-slate-100 px-2 py-0.5 rounded-md">
                                <i class="bi bi-person text-[11px]"></i>
                                <span class="truncate font-medium text-slate-600 max-w-[100px]">{{ $row->user->name ?? 'ทั่วไป' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-0 py-2 sm:px-4 sm:py-4 overflow-hidden align-middle text-left block sm:table-cell">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 shrink-0 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden border border-slate-100 shadow-sm">
                                <img class="w-full h-full object-cover" src="{{ asset('image/' . $row->music->image) }}">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-800 text-xs sm:text-sm truncate leading-snug group-hover:text-indigo-600 transition-colors">{{ $row->music->name ?? 'ไม่ทราบชื่อ' }}</p>
                                <p class="text-[10px] sm:text-xs text-slate-400 mt-0.5 truncate flex items-center gap-1">
                                    <i class="bi bi-clock text-[9px] sm:text-[11px]"></i>
                                    {{ $row->music->duration ? \Carbon\CarbonInterval::seconds($row->music->duration)->cascade()->format('%I:%S') : '00:00' }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 overflow-hidden align-middle text-left hidden sm:table-cell">
                        <div class="flex items-center justify-start gap-2 text-slate-700">
                            <div class="w-8 h-8 shrink-0 rounded-full bg-slate-50 flex items-center justify-center border border-slate-200 shadow-sm">
                                <i class="bi bi-person-fill text-slate-400 text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium truncate leading-tight text-slate-700">{{ $row->user->name ?? 'ทั่วไป' }}</p>
                            </div>
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
       สไตล์ควบคุมโครงสร้างคอนโทรลเลอร์ DataTables ทั้งหมด 
    ----------------------------------------------------------- */
    table.dataTable thead th {
        position: relative;
        background-image: none !important;
    }

    /* จัดโครงสร้างกล่องควบคุมบน Desktop (แถวแนวนอนแบ่งฝั่งซ้าย-ขวา) */
    .dt-top-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-bottom: 1.25rem;
        gap: 1rem;
    }

    /* ตกแต่งส่วนดึงความยาวแถวข้อมูล */
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

    /* ตกแต่งกล่องและช่องพิมพ์ค้นหา */
    .dataTables_wrapper .dataTables_filter {
        margin: 0 !important;
        float: none !important;
    }
    .dataTables_wrapper .dataTables_filter label {
        font-size: 0 !important; /* ลบหัวคำว่า ค้นหา: */
        margin: 0 !important;
        display: block;
    }
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

    /* สไตล์ปรับแต่ง Pagination และรายงานด้านล่าง */
    .dataTables_wrapper .dataTables_info {
        font-size: 12px;
        color: #64748b;
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

    /* -----------------------------------------------------------
       สไตล์ Responsive สำหรับหน้าจอมือถือ (Mobile)
    ----------------------------------------------------------- */
    @media (max-width: 639px) {
        /* ยุบเมนูด้านบนรวมเป็นแถวแนวตั้งแบบการ์ด */
        .dt-top-controls {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .dataTables_wrapper .dataTables_length {
            width: auto;
        }

        /* ขยายฟิลด์พิมพ์ค้นหาให้เต็มขอบ 100% */
        .dataTables_wrapper .dataTables_filter {
            width: 100% !important;
        }
        .dataTables_wrapper .dataTables_filter label {
            width: 100%;
        }
        .dataTables_wrapper .dataTables_filter input {
            width: 100% !important;
        }

        /* ตัดตัวเลขเปลี่ยนหน้าเยอะๆ ออกเหลือเฉพาะปุ่มเดินหน้า-ถอยหลัง */
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.previous):not(.next):not(.current) {
            display: none !important;
        }
        .dataTables_wrapper .dataTables_info {
            text-align: center !important;
            width: 100%;
            margin-bottom: 8px;
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
        $('#historyTable').DataTable({
            order: [[0, 'desc']], 
            bAutoWidth: false,
            
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json',
                search: "", 
                searchPlaceholder: "พิมพ์เพื่อค้นหาประวัติ...", 
                lengthMenu: "_MENU_", 
                paginate: {
                    previous: '<i class="bi bi-chevron-left"></i>', 
                    next: '<i class="bi bi-chevron-right"></i>' 
                }
            },
            pageLength: 10, 
            lengthMenu: [10, 25, 50],
            
            // ใช้โครงสร้าง Flexbox จัดสัดส่วนคอนโทรลทั้งส่วนบนและส่วนล่าง
            dom: '<"dt-top-controls"lf>rt<"flex flex-col sm:flex-row justify-between items-center w-full mt-4 gap-2"ip>',
            
            columnDefs: [
                { orderable: false, targets: [1, 2] } 
            ]
        });
    });
</script>
@endpush