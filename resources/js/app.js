import './bootstrap';


function confirmSubmit(formId, title = 'ยืนยันการบันทึกข้อมูล?', text = 'คุณตรวจสอบข้อมูลครบถ้วนแล้วใช่หรือไม่', icon = 'question') {
    const form = document.getElementById(formId);

    // ตรวจสอบ Validation เบื้องต้น (required ต่างๆ)
    if (!form.reportValidity()) {
        return; 
    }

    Swal.fire({
        title: title,
        html: `<div class="text-slate-600">${text}</div>`,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            // แสดง Loading ตอนกำลังส่งข้อมูล
            Swal.fire({
                title: 'กำลังประมวลผล...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            form.submit();
        }
    });
}