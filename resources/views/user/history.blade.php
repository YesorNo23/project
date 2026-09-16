@extends('user/layouts')

@section('content')

<div class="page-wrapper">
    <div class="profile-content">

        <div class="history-header">
            <h2 class="history-title">ประวัติการฟัง</h2>
            <p class="history-stats" id="historyStats"></p>
        </div>

        <!-- กลุ่มประวัติจะถูกสร้างที่นี่โดย JS: แต่ละกลุ่ม = 1 วัน -->
        <div id="historyGroups"></div>

        <div class="history-loadmore-wrap">
            <button id="loadMoreHistoryBtn" class="history-loadmore">โหลดเพิ่มเติม</button>
        </div>

    </div>
</div>

@endsection