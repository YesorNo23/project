@extends('admin/layouts')
@section('title', 'ประวัติการประเมินอารมณ์')

@section('content')
<div class="flex justify-between items-end mb-4 sm:mb-8 px-1">
    <div>
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">ประวัติการประเมินอารมณ์</h2>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 w-full overflow-hidden">
    <table id="logTable" class="w-full text-left border-collapse block sm:table">
        <thead class="bg-slate-50/70 text-xs text-slate-500 uppercase tracking-widest border-b border-slate-100 block hidden sm:table-header-group">
            <tr class="block sm:table-row">
                <th class="px-3 py-3.5 font-semibold sm:table-cell" style="width: 20%;">วัน/เวลา</th>
                <th class="px-3 py-3.5 font-semibold sm:table-cell" style="width: 35%;">ข้อมูลคำตอบ (Q1-Q5)</th>
                <th class="px-3 py-3.5 font-semibold sm:table-cell" style="width: 25%;">ผลการประเมิน</th>
                <th class="px-3 py-3.5 font-semibold sm:table-cell" style="width: 20%;">ความมั่นใจ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 block sm:table-row-group">
            @forelse($assessmentLogs as $row)
            <tr class="group hover:bg-slate-50/50 transition-all block mb-4 p-4 rounded-xl border border-slate-100 bg-slate-50/20 sm:p-0 sm:mb-0 sm:border-0 sm:bg-transparent sm:table-row">
                
                {{-- 1. วัน/เวลา --}}
                <td class="px-0 py-1 sm:px-3 sm:py-4 text-xs sm:text-sm text-slate-600 font-mono align-middle block sm:table-cell" data-sort="{{ $row->created_at }}">
                    @php $dt = \Carbon\Carbon::parse($row->created_at)->locale('th'); @endphp
                    <div class="flex items-center gap-2 sm:block">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider w-20 shrink-0 sm:hidden">เมื่อเวลา</span>
                        @if($dt->diffInDays() >= 1)
                            <span class="font-medium text-slate-700">{{ $dt->translatedFormat('H:i d/m/y') }}</span>
                        @else
                            <span class="font-medium text-indigo-600">{{ $dt->diffForHumans() }}</span> 
                        @endif
                    </div>
                </td>

                {{-- 2. ข้อมูลคำตอบ Q1 - Q5 --}}
                <td class="px-0 py-1.5 sm:px-3 sm:py-4 align-middle block sm:table-cell">
                    <div class="flex items-start gap-2 sm:flex-col sm:justify-center">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider w-20 shrink-0 sm:hidden mt-1">คำตอบ</span>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md text-xs font-mono shadow-sm">Q1: {{ $row->q1 }}</span>
                            <span class="bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md text-xs font-mono shadow-sm">Q2: {{ $row->q2 }}</span>
                            <span class="bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md text-xs font-mono shadow-sm">Q3: {{ $row->q3 }}</span>
                            <span class="bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md text-xs font-mono shadow-sm">Q4: {{ $row->q4 }}</span>
                            <span class="bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md text-xs font-mono shadow-sm">Q5: {{ $row->q5 }}</span>
                        </div>
                    </div>
                </td>

                {{-- 3. ผลการประเมิน (Predicted Mood) --}}
                <td class="px-0 py-1.5 sm:px-3 sm:py-4 align-middle block sm:table-cell">
                    <div class="flex items-center gap-2 text-slate-700">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider w-20 shrink-0 sm:hidden">อารมณ์</span>
                        <div class="flex items-center gap-1.5 min-w-0">
                            @if($row->predicted_mood)
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-full text-xs font-bold tracking-wide">
                                    {{ strtoupper($row->predicted_mood) }}
                                </span>
                            @else
                                <span class="text-slate-400 text-xs italic">- ไม่ระบุ -</span>
                            @endif
                        </div>
                    </div>
                </td>

                {{-- 4. ความมั่นใจ (Confidence) --}}
                <td class="px-0 py-1 sm:px-3 sm:py-4 font-mono text-[11px] sm:text-xs align-middle block sm:table-cell">
                    <div class="flex items-center gap-2 sm:block">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider w-20 shrink-0 sm:hidden">ความมั่นใจ</span>
                        @if($row->confidence)
                            <span class="font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">
                                {{ $row->confidence }}
                            </span>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </div>
                </td>
                
            </tr>
            @empty
            <tr class="block sm:table-row">
                <td colspan="4" class="px-4 py-12 text-center text-slate-400 align-middle block sm:table-cell">
                    <i class="bi bi-inbox text-4xl text-slate-200 block mb-2"></i>
                    <span class="font-medium text-sm">ยังไม่มีข้อมูลการประเมิน</span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
    /* CSS ล็อกตำแหน่ง Layout กล่องค้นหาและปุ่มหัวตาราง (เหมือนเดิมทั้งหมด) */
    table.dataTable thead th { position: relative; background-image: none !important; }
    .dt-top-layout { display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 1.25rem; gap: 1rem; }
    .dataTables_wrapper .dataTables_length label { font-size: 13px; color: #475569; font-weight: 500; }
    .dataTables_wrapper .dataTables_length select { padding: 4px 28px 4px 10px !important; font-size: 13px !important; border-radius: 8px !important; border: 1px solid #cbd5e1 !important; outline: none; height: 36px; }
    .dataTables_wrapper .dataTables_filter { margin: 0 !important; float: none !important; }
    .dataTables_wrapper .dataTables_filter label { font-size: 0 !important; margin: 0 !important; display: block; width: 100%; }
    .dataTables_wrapper .dataTables_filter input { width: 240px !important; margin: 0 !important; padding: 6px 14px !important; font-size: 13px !important; border-radius: 8px !important; border: 1px solid #cbd5e1 !important; background-color: #ffffff; height: 36px; outline: none; }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #4f46e5 !important; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1); }
    .dataTables_wrapper .dataTables_info { font-size: 12px; color: #64748b; padding-top: 1rem; }
    .dataTables_wrapper .dataTables_paginate { padding-top: 1rem; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.25rem 0.6rem !important; border-radius: 6px !important; margin: 0 2px !important; border: 1px solid #e2e8f0 !important; font-size: 12px; cursor: pointer; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #4f46e5 !important; color: white !important; border-color: #4f46e5 !important; }

    @media (max-width: 639px) {
        .dt-top-layout { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
        .dataTables_wrapper .dataTables_length { width: auto; }
        .dataTables_wrapper .dataTables_filter { width: 100% !important; }
        .dataTables_wrapper .dataTables_filter input { width: 100% !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.previous):not(.next):not(.current) { display: none !important; }
        .dataTables_wrapper .dataTables_info { text-align: center !important; width: 100%; }
        .dataTables_wrapper .dataTables_paginate { text-align: center !important; width: 100%; display: flex; justify-content: center; }
    }
</style>

<script>
    $(document).ready(function() {
        $('#logTable').DataTable({
            order: [[0, 'desc']], // เรียงตามวันที่ล่าสุด (Column 0)
            bAutoWidth: false, 
            
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json',
                search: "", 
                searchPlaceholder: "พิมพ์เพื่อค้นหา...", 
                lengthMenu: "_MENU_", 
                paginate: {
                    previous: '<i class="bi bi-chevron-left"></i>', 
                    next: '<i class="bi bi-chevron-right"></i>' 
                }
            },
            pageLength: 25, 
            lengthMenu: [10, 25, 50, 100],
            
            dom: '<"dt-top-layout"lf>rt<"flex flex-col sm:flex-row justify-between items-center w-full mt-2"ip>',
            
            columnDefs: [
                { orderable: false, targets: [1, 2, 3] } // ปิดการกดเรียงข้อมูลที่คอลัมน์ คำตอบ, อารมณ์, ความมั่นใจ
            ]
        });
    });
</script>
@endpush