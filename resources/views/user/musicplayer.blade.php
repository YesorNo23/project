<!-- Music Player Modal (ป๊อปอัพเล่นเพลง) -->
        <div class="modal-overlay" id="musicPlayerModal">
            <div class="modal-content" style="max-width: 400px; text-align: center; padding: 40px 25px;">
                <!-- ปุ่มกากบาทออก -->
                <button class="close-modal-btn" onclick="closePlayerModal()">&times;</button>
                
                <!-- ชื่อเพลง -->
                <h2 class="modal-title" id="playerSongName" style="margin-bottom: 20px; font-size: 20px;">ชื่อเพลง</h2>
                
                <!-- รูปภาพเพลง (ทำเป็นวงกลมให้เหมือนแผ่นเพลง) -->
                <div id="playerImage" style="width: 220px; height: 220px; margin: 0 auto 30px auto; border-radius: 50%; box-shadow: 0 8px 20px rgba(0,0,0,0.2); background-size: cover; background-position: center; border: 4px solid #f0f0f0;"></div>
                
                <!-- เครื่องเล่นเสียง (มีปุ่มเล่น/หยุด และหลอดเวลาในตัว) -->
                <audio id="mainAudioPlayer" controls style="width: 100%; outline: none;"></audio>
            </div>
        </div>