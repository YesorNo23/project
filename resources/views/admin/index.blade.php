@extends('admin/layouts')

@section('title', 'ภาพรวมเว็ปไซต์')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Dashboard</h2>
            
        </div>
        <div class="flex gap-2">
            <button class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-all shadow-lg" onclick="window.location.href='{{ route('music.edit') }}'">
                <i class="bi bi-cloud-arrow-up-fill me-1"></i> อัปโหลดคลื่นเสียง
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden">
        <p class="text-sm text-slate-500 font-medium mb-1 flex items-center gap-1.5">
            <i class="bi bi-play-circle text-indigo-500"></i> ยอดฟังทั้งหมด
        </p>
        <div class="flex items-center gap-1 mb-2">
            <h3 class="text-3xl font-bold text-slate-800 mb-2">{{ $totalListens }}</h3>
            <span class="text-lg font-medium text-slate-500 mb-0.5">ครั้ง</span>
        </div>
       
        <i class="bi bi-play-circle absolute -bottom-4 -right-2 text-6xl text-slate-100 opacity-40"></i>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden">
        <p class="text-sm text-slate-500 font-medium mb-1 flex items-center gap-1.5">
            <i class="bi bi-people text-emerald-500"></i> ผู้ใช้งานจริง
        </p>
        <div class="flex items-center gap-1 mb-2">
            <h3 class="text-3xl font-bold text-slate-800 mb-2">{{ $userNum }}</h3>
            <span class="text-lg font-medium text-slate-500 mb-0.5">คน</span>
        </div>
       
        <i class="bi bi-people absolute -bottom-4 -right-2 text-6xl text-slate-100 opacity-40"></i>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden">
        <p class="text-sm text-slate-500 font-medium mb-1 flex items-center gap-1.5">
            <i class="bi bi-clock text-amber-500"></i> ระยะเวลาฟังเฉลี่ย/คน
        </p>
        <div class="flex items-end gap-1 mb-2">
            <h3 class="text-3xl font-bold text-slate-800">{{ $AverageListen ?? 0 }}</h3>
            <span class="text-lg font-medium text-slate-500 mb-0.5">นาที</span>
        </div>
        
        <i class="bi bi-clock absolute -bottom-4 -right-2 text-6xl text-slate-100 opacity-40"></i>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden">
        <p class="text-sm text-slate-500 font-medium mb-1 flex items-center gap-1.5">
            <i class="bi bi-file-earmark-text text-rose-500"></i> จำนวนการทำแบบประเมิน
        </p>
        <div class="flex items-end gap-1 mb-2">
            <h3 class="text-3xl font-bold text-slate-800">4,200</h3>
            <span class="text-lg font-medium text-slate-500 mb-0.5">ครั้ง</span>
        </div>
       
        <i class="bi bi-file-earmark-text absolute -bottom-4 -right-2 text-6xl text-slate-100 opacity-40"></i>
    </div>  

</div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 flex flex-col gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-full flex flex-col">
            
                <div class="flex justify-between items-center mb-6 shrink-0">
                    <h3 class="font-bold text-slate-800">
                        ยอดฟังทั้งหมดวันนี้ {{ number_format($dailyListens?->sum('total') ?? 0) }} ครั้ง
                    </h3>
                </div>

                <div class="flex-1 overflow-y-auto pr-1 max-h-[350px] sm:max-h-none">
                    <table id="historyTable" class="w-full text-left block sm:table sm:table-fixed">
                        <tbody class="divide-y divide-slate-100 block sm:table-row-group">
                        
                            @forelse($dailyListens as $row)
                            <tr class="group hover:bg-slate-50/50 transition-all block mb-3.5 p-3.5 rounded-xl border border-slate-100 bg-slate-50/10 sm:p-0 sm:mb-0 sm:border-0 sm:bg-transparent sm:table-row">
                                
                                <td class="px-0 py-2 sm:px-4 sm:py-4 overflow-hidden align-middle text-left block sm:table-cell sm:w-[75%]">
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
                                        <div class="flex items-center gap-1 sm:hidden text-indigo-600 font-sans text-[10px] bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">
                                            <i class="bi bi-headphones text-[11px]"></i>
                                            <span class="font-semibold">{{ number_format($row->total ?? 0) }} ครั้ง</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 overflow-hidden align-middle text-left hidden sm:table-cell sm:w-[25%]">
                                    <div class="flex items-center justify-start gap-2.5">
                                        <div class="w-8 h-8 shrink-0 rounded-full bg-indigo-50 flex items-center justify-center border border-indigo-100 text-indigo-600 shadow-sm">
                                            <i class="bi bi-headphones text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold truncate leading-tight text-slate-700">
                                                {{ number_format($row->total ?? 0) }} <span class="text-xs text-slate-400 font-normal">ครั้ง</span>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            
                            <tr class="block sm:table-row">
                                <td colspan="2" class="py-12 text-center text-slate-400 block sm:table-cell">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center border border-slate-100 text-slate-300">
                                            <i class="bi bi-headphones text-2xl"></i>
                                        </div>
                                        <p class="text-xs sm:text-sm font-medium text-slate-500">ไม่พบข้อมูลการฟัง</p>
                                        <p class="text-[11px] text-slate-400">ยังไม่มีประวัติการฟังในช่วงเวลานี้</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800">หมวดหมู่คลื่นเสียงยอดนิยม</h3>
                </div>
                <div class="p-6 space-y-6">
                    @foreach($popularCategories as $category)
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-bold text-slate-700">{{ $category->name }}</span>
                            <span class="text-slate-500 font-mono">{{ $category->percentage }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full" style="width:{{ $category->percentage }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
                        
      
@endpush