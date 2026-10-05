<style>
.modal-content{
    max-width: 460px; 
    text-align: center; 
    padding: 35px 25px;
}
.team-member-list{
    display:flex;
    flex-direction:
    column;gap:14px;
    margin-top:22px;
}
.team-member-card{
    display:flex;
    align-items:center;
    gap:14px;
    text-align:left;
    padding:12px 16px;
    border-radius:14px;
    background:#f5f5f8;
}
.pg{
    width:62px;
    height:70px;
    flex-shrink:0;
    border-radius:50%;
    background:#e0e0e8;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
}
.name{
    font-weight:700;
    font-size:15px;
    color:#000;
    }
.le{
    font-size:13px;
    color:#888;
    margin-top:2px;
}
</style>
<!-- Footer แบบมีรูปภาพพื้นหลัง -->
    <footer class="site-footer">
    <div class="footer-overlay"></div>
    <div class="footer-content">
        <!-- ส่วนแบรนด์/โลโก้ -->
        <div class="footer-brand">
            <div class="footer-logo">
                <i class="fa-solid fa-headphones-simple"></i> SoundWave
            </div>
            <p>คณะวิศวกรรมศาสตร์</p>
            <p>สาขาคอมพิวเตอร์และระบบไอโอที</p>
        </div>

        <!-- ส่วนลิงก์ -->
        <div class="footer-links">
            <a href="https://project-zxo5.onrender.com/" class="footer-link">หน้าแรก</a>
            <a class="footer-link" id="contactUsLink">ติดต่อเรา</a>
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSdsoPngdC3iNH9Jdp4jXuAYUNQkNi-sqzUFfftf1MrHN0loJw/viewform?usp=publish-editor" class="footer-link">แบบฟอร์มความคิดเห็น</a>
        </div>

        <!-- ส่วนลิขสิทธิ์ -->
        <div class="footer-bottom">
            <p>&copy; 2026 SoundWave Platform. สงวนลิขสิทธิ์ทุกประการ ใช้ในโปเจ๊คนี้เท่านั้น</p>
        </div>
    </div>

</footer>

<!-- ป๊อปอัพ "ติดต่อเรา" แนะนำทีมผู้พัฒนา -->
<div class="modal-overlay" id="contactUsModal">
    <div class="modal-content">
        <button class="close-modal-btn" id="closeContactUsModalBtn">&times;</button>

        <h2 class="modal-title">ติดต่อเรา</h2>
        <p class="modal-subtitle">ทีมผู้พัฒนา SoundWave</p>

        <div class="team-member-list" >

            <!-- ===== สมาชิกคนที่ 1  ===== -->
            <div class="team-member-card" >
                <div class="pg" ><img src="{{ asset('image/p.png') }}" class="pg"></a></div>
                <div>
                    <div class="name">นางสาว ไพรจิตรา เหลืองเจริญโต</div>
                    <div class="le">นักศึกษาชั้นปีที่ 4</div>
                </div>
            </div>

            <!-- ===== สมาชิกคนที่ 2  ===== -->
            <div class="team-member-card" >
                <div class="pg" ><img src="{{ asset('image/e.png') }}" class="pg"></div>
                <div>
                    <div class="name">นาย พิชาญ กล้าวิเศษ</div>
                    <div class="le">นักศึกษาชั้นปีที่ 4</div>
                </div>
            </div>

            <!-- ===== สมาชิกคนที่ 3  ===== -->
            <div class="team-member-card" >
                <div class="pg" ><img src="{{ asset('image/s.png') }}" class="pg"></div>
                <div>
                    <div class="name">นาย สรยุทธ เหมหงษ์</div>
                    <div class="le">นักศึกษาชั้นปีที่ 4</div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {
    const contactLink  = document.getElementById('contactUsLink');
    const contactModal = document.getElementById('contactUsModal');
    const closeBtn      = document.getElementById('closeContactUsModalBtn');

    if (!contactLink || !contactModal) return;

    contactLink.addEventListener('click', function (e) {
        e.preventDefault();
        contactModal.classList.add('show');
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            contactModal.classList.remove('show');
        });
    }

    // กดพื้นหลังด้านนอกเพื่อปิด
    window.addEventListener('click', function (e) {
        if (e.target === contactModal) contactModal.classList.remove('show');
    });
});
</script>