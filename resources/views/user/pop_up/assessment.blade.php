<!-- ประเมินอารมณ์ -->
    <div class="modal-overlay" id="moodModal">
        <div class="modal-content">
            <button class="close-modal-btn" id="closeModalBtn">&times;</button>
            
            <h2 class="modal-title">เป้าหมายการใช้งาน</h2>
            <p class="modal-subtitle">แบบประเมิน เพื่อเพิ่มประสิทธิภาพการเลือกคลื่นเสียง</p>
            
            <div class="mood-options">
                <ol class="quiz-list">
                    <li>
                        <span class="question-text">ตอนนี้คุณกำลังทำอะไรอยู่</span>
                        <div class="checkbox-group">
                            <label><input type="radio" name="q1" value="work"> ทำงาน/อ่านหนังสือ</label>
                            <label><input type="radio" name="q1" value="relax"> พักผ่อน</label>
                            <label><input type="radio" name="q1" value="sleep"> เตรียมตัวเข้านอน</label>
                            <label><input type="radio" name="q1" value="other"> อื่นๆ</label>
                        </div>
                    </li>
                    <li>
                        <span class="question-text">คุณใช้งานในช่วงเวลาใด</span>
                        <div class="checkbox-group">
                            <label><input type="radio" name="q2" value="morning"> เช้า</label>
                            <label><input type="radio" name="q2" value="noon"> กลางวัน</label>
                            <label><input type="radio" name="q2" value="evening"> เย็น</label>
                            <label><input type="radio" name="q2" value="bedtime"> ก่อนนอน</label>
                        </div>
                    </li>
                    <li>
                        <span class="question-text">เคยใช้คลื่นเสียงนี้มาก่อนหรือไม่</span>
                        <div class="checkbox-group">
                            <label><input type="radio" name="q3" value="never"> ไม่เคย</label>
                            <label><input type="radio" name="q3" value="good"> เคยใช้และได้ผลดี</label>
                            <label><input type="radio" name="q3" value="bad"> เคยใช้แต่ไม่ได้ผล</label>
                        </div>
                    </li>
                    <li>
                        <span class="question-text">เป้าหมายหลักต้องการใช้คลื่นเสียงช่วยอะไร</span>
                        <div class="checkbox-group">
                            <label><input type="radio" name="q4" value="focus"> เพิ่มสมาธิ</label>
                            <label><input type="radio" name="q4" value="stress-relief"> ลดความเครียด</label>
                            <label><input type="radio" name="q4" value="sleep-aid"> นอนหลับ</label>
                            <label><input type="radio" name="q4" value="mood-boost"> ปรับอารมณ์ให้ดีขึ้น</label>
                            <label><input type="radio" name="q4" value="general-relax"> ผ่อนคลายทั่วไป</label>
                        </div>
                    </li>
                    <li>
                        <span class="question-text">สถานะปัจจุบันตอนนี้คุณรู้สึกยังไง</span>
                        <div class="checkbox-group">
                            <label><input type="radio" name="q5" value="stressed"> เครียด</label>
                            <label><input type="radio" name="q5" value="sleepy"> ง่วง</label>
                            <label><input type="radio" name="q5" value="bored"> เบื่อ</label>
                            <label><input type="radio" name="q5" value="normal"> ปกติ</label>
                            <label><input type="radio" name="q5" value="distracted"> ฟุ้งซ่าน</label>
                        </div>
                    </li>
                </ol>
            </div>
            <div style="text-align: center; margin-top: 25px;">
                <button class="btn btn-round" id="submitMoodBtn">บันทึกข้อมูล</button>
            </div>
        </div>
    </div>