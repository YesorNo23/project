@extends('user/layouts')

@section('content')

<div class="page-wrapper">
    <div class="profile-content">
        <!-- Header: ปกวงกลม + ชื่อ + รายละเอียด -->
        <div class="pld-header">
            <div class="pld-cover" id="pldCover">
                <div class="pld-cover-dot"></div>
            </div>
            <div class="pld-header-info">
                <div class="pld-label">เพลย์ลิสต์</div>
                <h1 class="pld-title" id="pldName"></h1>
                <p class="pld-desc" id="pldDesc"></p>
                <div class="pld-meta" id="pldMeta"></div>
            </div>
        </div>

        <!-- Action row -->
        <div class="pld-actions">
            <button class="pld-play-btn" id="pldPlayBtn">▶</button>
            <button class="pld-action-btn" id="pldShuffleBtn"><i class="fa-solid fa-shuffle"></i> สลับเล่น</button>
            
            <!-- Dropdown Wrapper -->
            <div class="pld-dropdown-wrapper">
                <button class="pld-more-btn" id="pldMoreBtn" type="button">•••</button>
                
                <div class="pld-dropdown-menu" id="pldDropdownMenu">
                    <button type="button" class="pld-dropdown-item" onclick="openEditPlaylistModal({{ $id ?? 'null' }})">
                        <i class="fa-solid fa-pen-to-square"></i> แก้ไขเพลย์ลิสต์
                    </button>
                    
                    <!-- เส้นคั่นแบบในรูป -->
                    <div class="pld-dropdown-divider"></div>

                            <!-- ปุ่ม Action สีแดง -->
                            <button type="button" class="pld-dropdown-item danger" onclick="openConfirmDeleteModal(window.currentPlaylistId)">
                            <i class="bi bi-trash"></i> ลบเพลย์ลิสต์
                    </button>
                </div>
            </div>
        </div>

        <!-- Song list -->
        <div class="pld-song-list">
            <div id="pldSongRows"></div>
        </div>

    </div>
</div>

<script>
    window.currentPlaylistId = "{{ $id }}";
    
    const moreBtn = document.getElementById('pldMoreBtn');
    const dropdownMenu = document.getElementById('pldDropdownMenu');

    // สลับการเปิด/ปิด Dropdown
    moreBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });

    // ปิด Dropdown เมื่อคลิกส่วนอื่น
    document.addEventListener('click', (e) => {
        if (!dropdownMenu.contains(e.target) && e.target !== moreBtn) {
            dropdownMenu.classList.remove('show');
        }
    });

    // ฟังก์ชันสำหรับเปิด Modal ยืนยันการลบ พร้อมใส่ ID ให้อัตโนมัติ
    function openConfirmDeleteModal(id) {
        const playlistId = id || window.currentPlaylistId;
        
        // ยัด ID ใส่ hidden input ใน Modal (ถ้ามี)
        const inputElem = document.getElementById('epmPlaylistId');
        if (inputElem) {
            inputElem.value = playlistId;
        }

        // แสดง Modal ยืนยันการลบ
        const confirmModal = document.getElementById('confirmDeletePlaylistModal');
        if (confirmModal) {
            confirmModal.classList.add('show');
        }
    }
</script>

@endsection