{{-- resources/views/layouts/partials/status_badge.blade.php --}}

@if($item->qty == 0 && $item->max == 0 && $item->min == 0)
    <span class="inline-flex justify-center items-center px-4 py-1 rounded-full text-[10px] font-black bg-gray-100 text-gray-600 border border-gray-200 min-w-[80px]">NOT USED</span>

@elseif($item->qty == 0)
    <span class="inline-flex justify-center items-center px-4 py-1 rounded-full text-[10px] font-black bg-red-100 text-red-600 border border-red-200 min-w-[80px]">KOSONG</span>

@elseif($item->qty < $item->min)
    <span class="inline-flex justify-center items-center px-4 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 min-w-[80px]">SHORTAGE</span>

@elseif($item->qty > $item->max)
    <span class="inline-flex justify-center items-center px-4 py-1 rounded-full text-[10px] font-black bg-indigo-100 text-indigo-700 border border-indigo-200 min-w-[80px]">OVER</span>

@else
    <span class="inline-flex justify-center items-center px-4 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200 min-w-[80px]">AMAN</span>
@endif