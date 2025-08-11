<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/head.php');
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Malgun Gothic', sans-serif; background: #f5f5f5; }
        .calendar-container { max-width: 1200px; margin: 20px auto; padding: 0 15px; }
        .calendar-header { background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .calendar-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .btn { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-add { background: #28a745; }
        .btn-add:hover { background: #1e7e34; }
        .current-month { font-size: 24px; font-weight: bold; color: #333; }
        .calendar-grid { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); }
        .day-header { background: #f8f9fa; padding: 15px 10px; text-align: center; font-weight: bold; border-bottom: 1px solid #dee2e6; }
        .day-cell { min-height: 120px; border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6; padding: 5px 0px; position: relative; }
        .day-cell:nth-child(7n) { border-right: none; }
        .day-number { font-weight: bold; color: #333; margin-bottom: 5px; }
        .other-month { color: #ccc; }
        .today { background: #e3f2fd; }
        .event { margin-bottom: 2px; padding: 2px 5px; border-radius: 3px; font-size: 11px; cursor: pointer; overflow: hidden; }
        .event:hover { opacity: 0.8; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 5% auto; padding: 20px; width: 90%; max-width: 500px; border-radius: 10px; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close:hover { color: black; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .form-group textarea { height: 80px; resize: vertical; }
        .color-picker { display: flex; gap: 10px; flex-wrap: wrap; }
        .color-option { width: 30px; height: 30px; border-radius: 50%; cursor: pointer; border: 2px solid transparent; }
        .color-option.selected { border-color: #333; }
        
        @media (max-width: 768px) {
            .calendar-controls { flex-direction: column; gap: 10px; text-align: center; }
            .day-cell { min-height: 80px; padding: 3px; }
            .event { font-size: 10px; padding: 1px 3px; }
            .modal-content { margin: 10% auto; width: 95%; }
        }
        
        @media (max-width: 480px) {
            .day-header { padding: 10px 5px; font-size: 12px; }
            .day-cell { min-height: 60px; }
            .day-number { font-size: 12px; }
            .event { font-size: 9px; }
        }
    </style>
</head>
<body>
    <div class="calendar-container">
        <div class="calendar-header">
            <div class="calendar-controls">
                <div>
                    <button class="btn" id="prevBtn">◀ 이전</button>
                    <button class="btn" id="todayBtn">오늘</button>
                    <button class="btn" id="nextBtn">다음 ▶</button>
                </div>
                <div class="current-month" id="currentMonth"></div>
                <button class="btn btn-add" id="addEventBtn">일정 추가</button>
            </div>
        </div>
        
        <div class="calendar-grid">
            <div class="calendar-days">
                <div class="day-header">일</div>
                <div class="day-header">월</div>
                <div class="day-header">화</div>
                <div class="day-header">수</div>
                <div class="day-header">목</div>
                <div class="day-header">금</div>
                <div class="day-header">토</div>
            </div>
            <div class="calendar-days" id="calendarDays"></div>
        </div>
    </div>

    <!-- 일정 추가/수정 모달 -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">일정 추가</h2>
            <form id="eventForm">
                <input type="hidden" id="eventId" name="event_id">
                
                <div class="form-group">
                    <label for="startDate">시작일:</label>
                    <input type="date" id="startDate" name="start_date" required>
                </div>
                
                <div class="form-group">
                    <label for="endDate">종료일:</label>
                    <input type="date" id="endDate" name="end_date" required>
                </div>
                
                <div class="form-group">
                    <label for="eventTitle">제목:</label>
                    <input type="text" id="eventTitle" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="eventContent">내용:</label>
                    <textarea id="eventContent" name="content"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="eventUrl">URL:</label>
                    <input type="url" id="eventUrl" name="url" placeholder="http://">
                </div>
                
                <div class="form-group">
                    <label for="eventHeight">표기 높이:</label>
                    <select id="eventHeight" name="height">
                        <option value="1">낮음</option>
                        <option value="2" selected>보통</option>
                        <option value="3">높음</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>색상:</label>
                    <div class="color-picker">
                        <div class="color-option selected" data-color="#007bff" style="background: #007bff;"></div>
                        <div class="color-option" data-color="#28a745" style="background: #28a745;"></div>
                        <div class="color-option" data-color="#dc3545" style="background: #dc3545;"></div>
                        <div class="color-option" data-color="#ffc107" style="background: #ffc107;"></div>
                        <div class="color-option" data-color="#6f42c1" style="background: #6f42c1;"></div>
                        <div class="color-option" data-color="#20c997" style="background: #20c997;"></div>
                        <div class="color-option" data-color="#fd7e14" style="background: #fd7e14;"></div>
                        <div class="color-option" data-color="#6c757d" style="background: #6c757d;"></div>
                    </div>
                    <input type="hidden" id="eventColor" name="color" value="#007bff">
                </div>
                
                <div style="text-align: right; margin-top: 20px;">
                    <button type="button" class="btn" id="deleteBtn" style="background: #dc3545; display: none;">삭제</button>
                    <button type="button" class="btn" onclick="closeModal()">취소</button>
                    <button type="submit" class="btn btn-add">저장</button>
                </div>
            </form>
        </div>
    </div>

    <script>
			console.log("asdf");
        let currentDate = new Date();
        let selectedColor = '#007bff';
        
        $(document).ready(function() {
            loadCalendar();
            bindEvents();
        });
        
        function bindEvents() {
            $('#prevBtn').click(() => { currentDate.setMonth(currentDate.getMonth() - 1); loadCalendar(); });
            $('#nextBtn').click(() => { currentDate.setMonth(currentDate.getMonth() + 1); loadCalendar(); });
            $('#todayBtn').click(() => { currentDate = new Date(); loadCalendar(); });
            $('#addEventBtn').click(() => openModal());
            $('.close').click(closeModal);
            
            // 색상 선택
            $('.color-option').click(function() {
                $('.color-option').removeClass('selected');
                $(this).addClass('selected');
                selectedColor = $(this).data('color');
                $('#eventColor').val(selectedColor);
            });
            
            // 모달 외부 클릭시 닫기
            $(window).click(function(e) {
                if (e.target.id === 'eventModal') closeModal();
            });
            
            // 폼 제출
            $('#eventForm').submit(function(e) {
                e.preventDefault();
                saveEvent();
            });
            
            // 삭제 버튼
            $('#deleteBtn').click(function() {
                if(confirm('이 일정을 삭제하시겠습니까?')) {
                    deleteEvent($('#eventId').val());
                }
            });
        }
        
        function loadCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            $('#currentMonth').text(`${year}년 ${month + 1}월`);
            
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());
            
            let calendarHtml = '';
            let date = new Date(startDate);
            
            for (let week = 0; week < 6; week++) {
                for (let day = 0; day < 7; day++) {
                    const isCurrentMonth = date.getMonth() === month;
                    const isToday = date.toDateString() === new Date().toDateString();
                    
                    let cellClass = 'day-cell';
                    if (isToday) cellClass += ' today';
                    
                    calendarHtml += `
                        <div class="${cellClass}" data-date="${formatDate(date)}">
                            <div class="day-number ${!isCurrentMonth ? 'other-month' : ''}">${date.getDate()}</div>
                            <div class="events-container"></div>
                        </div>
                    `;
                    
                    date.setDate(date.getDate() + 1);
                }
            }
            
            $('#calendarDays').html(calendarHtml);
            loadEvents();
        }
        
        function loadEvents() {
            const startDate = formatDate(new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1));
            const endDate = formatDate(new Date(currentDate.getFullYear(), currentDate.getMonth() + 2, 0));
            
            $.ajax({
                url: '<?=G5_THEME_URL?>/calendar_ajax.php',
                method: 'POST',
                data: { action: 'get_events', start_date: startDate, end_date: endDate },
                dataType: 'json',
                success: function(events) {
                    $('.events-container').empty();
                    
                    events.forEach(function(event) {
                        const startDate = new Date(event.start_date);
                        const endDate = new Date(event.end_date);
                        
                        for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
                            const dateStr = formatDate(d);
                            const $cell = $(`.day-cell[data-date="${dateStr}"] .events-container`);
                            
                            if ($cell.length) {
                                const eventHtml = `
                                    <div class="event" 
                                         style="background: ${event.color}; color: white; height: ${event.height * 15}px; line-height: ${event.height * 15}px;"
                                         data-event-id="${event.id}"
                                         onclick="editEvent(${event.id})">
                                        ${event.title}
                                    </div>
                                `;
                                $cell.append(eventHtml);
                            }
                        }
                    });
                }
            });
        }
        
        function openModal(eventData = null) {
            if (eventData) {
                $('#modalTitle').text('일정 수정');
                $('#eventId').val(eventData.id);
                $('#startDate').val(eventData.start_date);
                $('#endDate').val(eventData.end_date);
                $('#eventTitle').val(eventData.title);
                $('#eventContent').val(eventData.content);
                $('#eventUrl').val(eventData.url);
                $('#eventHeight').val(eventData.height);
                $('#eventColor').val(eventData.color);
                
                $('.color-option').removeClass('selected');
                $(`.color-option[data-color="${eventData.color}"]`).addClass('selected');
                
                $('#deleteBtn').show();
            } else {
                $('#modalTitle').text('일정 추가');
                $('#eventForm')[0].reset();
                $('#eventId').val('');
                $('#eventColor').val('#007bff');
                $('.color-option').removeClass('selected');
                $('.color-option').first().addClass('selected');
                $('#deleteBtn').hide();
            }
            
            $('#eventModal').show();
        }
        
        function closeModal() {
            $('#eventModal').hide();
        }
        
        function saveEvent() {
            const formData = $('#eventForm').serialize();
            const action = $('#eventId').val() ? 'update_event' : 'add_event';
            
            $.ajax({
                url: '<?=G5_THEME_URL?>/calendar_ajax.php',
                method: 'POST',
                data: formData + '&action=' + action,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        closeModal();
                        loadEvents();
                        alert('일정이 저장되었습니다.');
                    } else {
                        alert('오류가 발생했습니다: ' + response.message);
                    }
                }
            });
        }
        
        function editEvent(eventId) {
            $.ajax({
                url: '<?=G5_THEME_URL?>/calendar_ajax.php',
                method: 'POST',
                data: { action: 'get_event', id: eventId },
                dataType: 'json',
                success: function(event) {
                    if (event) {
                        openModal(event);
                    }
                }
            });
        }
        
        function deleteEvent(eventId) {
            $.ajax({
                url: '<?=G5_THEME_URL?>/calendar_ajax.php',
                method: 'POST',
                data: { action: 'delete_event', id: eventId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        closeModal();
                        loadEvents();
                        alert('일정이 삭제되었습니다.');
                    } else {
                        alert('삭제 중 오류가 발생했습니다.');
                    }
                }
            });
        }
        
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
    </script>
<?php
include_once(G5_THEME_PATH.'/tail.php');
