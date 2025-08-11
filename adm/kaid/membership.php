<?php
$sub_menu = "600100";
include_once('./_common.php');

// 관리자 권한 체크
if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    goto_url(G5_URL);
    exit;
}

$g5['title'] = '회비관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Malgun Gothic', sans-serif; background: #f5f5f5; }
.container { max-width: 1400px; margin: 0 auto; padding: 20px; }
.header { background: #2c3e50; color: white; padding: 15px 20px; margin-bottom: 20px; border-radius: 8px; }
.header h1 { font-size: 24px; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
.stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
.stat-card h3 { color: #34495e; margin-bottom: 10px; }
.stat-card .number { font-size: 32px; font-weight: bold; color: #3498db; }
.stat-card .label { color: #7f8c8d; font-size: 14px; }
.controls { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.controls-row { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; }
.form-group { display: flex; flex-direction: column; }
.form-group label { margin-bottom: 5px; font-weight: 500; color: #34495e; }
.form-group input, .form-group select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; transition: all 0.3s; }
.btn-primary { background: #3498db; color: white; }
.btn-primary:hover { background: #2980b9; }
.btn-success { background: #27ae60; color: white; }
.btn-success:hover { background: #219a52; }
.btn-warning { background: #f39c12; color: white; }
.btn-warning:hover { background: #e67e22; }
.btn-danger { background: #e74c3c; color: white; }
.btn-danger:hover { background: #c0392b; }
.btn-sm { padding: 6px 12px; font-size: 12px; }
.table-container { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.table { width: 100%; border-collapse: collapse; }
.table th { background: #34495e; color: white; padding: 15px 10px; text-align: left; font-weight: 500; }
.table td { padding: 12px 10px; border-bottom: 1px solid #ecf0f1; }
.table tr:hover { background: #f8f9fa; }
.status-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
.status-pending { background: #fff3cd; color: #856404; }
.status-completed { background: #d4edda; color: #155724; }
.status-cancelled { background: #f8d7da; color: #721c24; }
.pagination { display: flex; justify-content: center; gap: 5px; margin-top: 20px; }
.pagination a { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #007bff; border-radius: 4px; }
.pagination a:hover { background: #e9ecef; }
.pagination .active { background: #007bff; color: white; }
.modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
.modal-content { background: white; margin: 5% auto; padding: 20px; width: 90%; max-width: 600px; border-radius: 8px; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.modal-title { font-size: 18px; font-weight: 600; }
.close { font-size: 28px; cursor: pointer; color: #aaa; }
.close:hover { color: #000; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
.form-grid .full-width { grid-column: 1 / -1; }
.quick-actions { display: flex; gap: 10px; margin-left: auto; }
.export-btn { background: #17a2b8; color: white; }
.export-btn:hover { background: #138496; }
.amount { font-weight: 600; color: #27ae60; }
.overdue { color: #e74c3c; font-weight: 500; }
.bulk-actions { margin-bottom: 15px; }
.bulk-actions button { margin-right: 10px; }
.auto-check-section { background: #e8f4fd; border: 1px solid #bee5eb; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
.auto-check-title { color: #2980b9; font-weight: 600; margin-bottom: 15px; font-size: 16px; }
.auto-check-controls { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 15px; }
.badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
.badge-warning { background: #fff3cd; color: #856404; }
.badge-danger { background: #f8d7da; color: #721c24; }
.badge-success { background: #d4edda; color: #155724; }
.expiring-table { margin-top: 15px; }
.expiring-table th { background: #5dade2; }
.generation-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; }
.generation-modal-content { background: white; margin: 3% auto; padding: 20px; width: 90%; max-width: 800px; border-radius: 8px; max-height: 80vh; overflow-y: auto; }
.alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; }
.alert-info { background: #e8f4fd; color: #2980b9; border: 1px solid #bee5eb; }
.alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
@keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
</style>

<div class="container">
    <!-- 헤더 -->
    <div class="header">
        <h1>회비관리 시스템</h1>
    </div>

    <!-- 통계 카드 -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>총 회비</h3>
            <div class="number" id="totalAmount">₩0</div>
            <div class="label">전체 납부금액</div>
        </div>
        <div class="stat-card">
            <h3>완료</h3>
            <div class="number" id="completedCount">0</div>
            <div class="label">납부완료 건수</div>
        </div>
        <div class="stat-card">
            <h3>미납</h3>
            <div class="number" id="pendingCount">0</div>
            <div class="label">납부예정 건수</div>
        </div>
        <div class="stat-card">
            <h3>연체</h3>
            <div class="number" id="overdueCount">0</div>
            <div class="label">납부연체 건수</div>
        </div>
    </div>

    <!-- 자동 회비 생성 체크 섹션 -->
    <div class="auto-check-section">
        <div class="auto-check-title">🔔 회비 만료 알림 및 자동 생성</div>
        <div class="auto-check-controls">
            <button class="btn btn-primary btn-sm" onclick="checkExpiringMemberships()">만료 예정 회비 체크 및 자동 생성</button>
            <span id="expiringCount" class="badge badge-warning" style="display: none;">0건 만료 예정</span>
            <span class="help-text">* 만료 1개월 전 회비를 자동으로 체크하고 <strong>다음 연도 회비를 자동 생성</strong>합니다</span>
        </div>
        
        <div class="alert" style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; margin-top: 15px; padding: 12px; border-radius: 6px;">
            <strong>📋 자동 생성 규칙:</strong><br>
            • <strong>유효기간:</strong> 현재 회비 만료일 다음날 ~ 1년 후<br>
            • <strong>납부예정일:</strong> 현재 회비 만료일과 동일<br>
            • <strong>금액:</strong> 현재 회비와 동일한 금액<br>
            • <strong>상태:</strong> 납부예정으로 자동 설정
        </div>
        
        <div id="expiringMembersSection" style="display: none;">
            <table class="table expiring-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAllExpiring"></th>
                        <th>회원ID</th>
                        <th>회원명</th>
                        <th>현재 금액</th>
                        <th>만료일</th>
                        <th>남은 일수</th>
                        <th>상태</th>
                    </tr>
                </thead>
                <tbody id="expiringMembersTable">
                </tbody>
            </table>
            
            <div style="text-align: center; margin-top: 15px;">
                <button class="btn btn-success btn-sm" onclick="openGenerationModal()">선택 회원 수동 회비 생성</button>
                <span style="margin-left: 10px; color: #6c757d; font-size: 12px;">* 자동 생성된 회원은 체크박스가 비활성화됩니다</span>
            </div>
        </div>
    </div>

    <!-- 검색 및 필터 -->
    <div class="controls">
        <div class="controls-row">
            <div class="form-group">
                <label>회원ID</label>
                <input type="text" id="searchMemberId" placeholder="회원ID 검색">
            </div>
            <div class="form-group">
                <label>회비종류</label>
                <select id="filterType">
                    <option value="">전체</option>
                    <option value="annual">연회비</option>
                    <option value="entrance">입회비</option>
                </select>
            </div>
            <div class="form-group">
                <label>납부상태</label>
                <select id="filterStatus">
                    <option value="">전체</option>
                    <option value="pending">납부예정</option>
                    <option value="completed">완료</option>
                    <option value="cancelled">취소</option>
                </select>
            </div>
            <div class="form-group">
                <label>연도</label>
                <select id="filterYear">
                    <option value="">전체</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                </select>
            </div>
            <div class="form-group">
                <label>&nbsp;</label>
                <button class="btn btn-primary" onclick="searchMembership()">검색</button>
            </div>
            <div class="quick-actions">
                <button class="btn btn-success" onclick="openAddModal()">새 회비 등록</button>
                <button class="btn export-btn" onclick="exportExcel()">엑셀 다운로드</button>
            </div>
        </div>
    </div>

    <!-- 일괄 처리 버튼 -->
    <div class="bulk-actions">
        <button class="btn btn-success btn-sm" onclick="bulkAction('approve')">선택항목 승인</button>
        <button class="btn btn-danger btn-sm" onclick="bulkAction('delete')">선택항목 삭제</button>
        <button class="btn btn-sm" onclick="selectAll()">전체선택</button>
        <button class="btn btn-sm" onclick="deselectAll()">선택해제</button>
    </div>

    <!-- 회비 목록 테이블 -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>회원ID</th>
                    <th>회원명</th>
                    <th>회비종류</th>
                    <th>내용</th>
                    <th>금액</th>
                    <th>유효기간</th>
                    <th>납부예정일</th>
                    <th>납부방법</th>
                    <th>상태</th>
                    <th>등록일</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody id="membershipTable">
                <tr>
                    <td colspan="11" style="text-align: center; padding: 50px;">데이터를 불러오는 중...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 페이지네이션 -->
    <div class="pagination" id="pagination"></div>
</div>

<!-- 회비 등록/수정 모달 -->
<div id="membershipModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">회비 정보</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <form id="membershipForm">
            <input type="hidden" id="modalMbId">
            <div class="form-grid">
                <div class="form-group">
                    <label>회원ID *</label>
                    <input type="text" id="modalMemberId" required placeholder="회원ID를 입력하세요">
                </div>
                <div class="form-group">
                    <label>회비종류 *</label>
                    <select id="modalType" required>
                        <option value="">선택하세요</option>
                        <option value="annual">연회비</option>
                        <option value="entrance">입회비</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>회비내용 *</label>
                    <input type="text" id="modalContent" required placeholder="예: 2025년 연회비">
                </div>
                <div class="form-group">
                    <label>금액 *</label>
                    <input type="number" id="modalAmount" required placeholder="원">
                </div>
                <div class="form-group">
                    <label>연도</label>
                    <input type="number" id="modalYear" value="2025">
                </div>
                <div class="form-group">
                    <label>유효기간 시작일</label>
                    <input type="date" id="modalStartDate">
                </div>
                <div class="form-group">
                    <label>유효기간 만료일</label>
                    <input type="date" id="modalEndDate">
                </div>
                <div class="form-group">
                    <label>납부예정일 *</label>
                    <input type="date" id="modalDueDate" required>
                </div>
                <div class="form-group">
                    <label>납부상태</label>
                    <select id="modalStatus">
                        <option value="pending">납부예정</option>
                        <option value="completed">완료</option>
                        <option value="cancelled">취소</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>결제방법</label>
                    <select id="modalPaymentMethod">
                        <option value="">선택하세요</option>
                        <option value="카드">카드</option>
                        <option value="무통장">무통장</option>
                        <option value="현금">현금</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>결제정보</label>
                    <input type="text" id="modalPaymentInfo" placeholder="거래번호, 계좌정보 등">
                </div>
                <div class="form-group full-width">
                    <label>관리자 메모</label>
                    <textarea id="modalAdminMemo" rows="3" placeholder="관리자 전용 메모"></textarea>
                </div>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <button type="button" class="btn btn-success" onclick="saveMembership()">저장</button>
                <button type="button" class="btn" onclick="closeModal()">취소</button>
            </div>
        </form>
    </div>
</div>

<!-- 회비 자동 생성 모달 -->
<div id="generationModal" class="generation-modal" style="display: none;">
    <div class="generation-modal-content">
        <div class="modal-header">
            <h2 class="modal-title">다음 연도 회비 자동 생성</h2>
            <span class="close" onclick="closeGenerationModal()">&times;</span>
        </div>
        
        <div style="margin-bottom: 20px;">
            <h4>생성 설정</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label>기본 금액 (원)</label>
                    <input type="number" id="autoGenAmount" placeholder="비어두면 현재 금액과 동일">
                </div>
                <div class="form-group">
                    <label>납부 예정일</label>
                    <input type="date" id="autoGenDueDate" value="<?php echo (date('Y') + 1) . '-03-31'; ?>">
                </div>
            </div>
            <button class="btn btn-primary" onclick="previewAutoGeneration()">생성 미리보기</button>
        </div>
        
        <div id="generationPreview" style="display: none;">
            <h4>생성 예정 회비 미리보기</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>회원ID</th>
                        <th>회원명</th>
                        <th>현재 금액</th>
                        <th>신규 내용</th>
                        <th>신규 금액</th>
                        <th>유효기간</th>
                        <th>납부예정일</th>
                    </tr>
                </thead>
                <tbody id="generationPreviewTable">
                </tbody>
            </table>
            
            <div style="text-align: center; margin-top: 20px;">
                <button class="btn btn-success" onclick="executeAutoGeneration()">회비 생성 실행</button>
                <button class="btn" onclick="closeGenerationModal()">취소</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script src="./membership_admin.js"></script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>