// membership_admin.js - 회비관리 프론트엔드 JavaScript

$(document).ready(function() {
    loadMembershipList();
    loadStatistics();
    checkExpiringMemberships(); // 페이지 로드시 만료 예정 회비 자동 체크
    
    // 전체선택 체크박스 이벤트
    $('#checkAll').change(function() {
        $('input[type="checkbox"][value]').prop('checked', this.checked);
    });
    
    // 개별 체크박스 이벤트
    $(document).on('change', 'input[type="checkbox"][value]', function() {
        const totalCheckboxes = $('input[type="checkbox"][value]').length;
        const checkedCheckboxes = $('input[type="checkbox"][value]:checked').length;
        $('#checkAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
    
    // 모달 외부 클릭시 닫기
	/**
    $(window).click(function(event) {
        if (event.target == $('#membershipModal')[0]) {
            closeModal();
        }
        if (event.target == $('#generationModal')[0]) {
            closeGenerationModal();
        }
    });
	*/
    
    // Enter 키 검색
    $('#searchMemberId').keypress(function(e) {
        if (e.which === 13) {
            searchMembership();
        }
    });
    
    // 회비종류 변경시 기본값 설정
    $('#modalType').change(function() {
        const type = $(this).val();
        const year = $('#modalYear').val() || new Date().getFullYear();
        
        if (type === 'annual') {
            $('#modalContent').val(year + '년 연회비');
            $('#modalStartDate').val(year + '-01-01');
            $('#modalEndDate').val(year + '-12-31');
            $('#modalAmount').val('50000');
        } else if (type === 'entrance') {
            $('#modalContent').val('입회비');
            $('#modalStartDate').val('');
            $('#modalEndDate').val('9999-12-31');
            $('#modalAmount').val('30000');
        }
    });
    
    // 회원 검색 자동완성
    $('#modalMemberId').on('input', function() {
        const memberId = $(this).val().trim();
        if (memberId.length >= 2) {
            $.ajax({
                url: './membership_api.php',
                type: 'GET',
                data: { get_member: 1, member_id: memberId },
                dataType: 'json',
                success: function(response) {
                    if (response.mb_name) {
                        $('#modalMemberId').attr('title', response.mb_name + ' (' + response.mb_email + ')');
                    } else {
                        $('#modalMemberId').removeAttr('title');
                    }
                }
            });
        }
    });
    
    // 만료 예정 회비 전체선택 이벤트
    $('#checkAllExpiring').change(function() {
        $('.expiring-checkbox').prop('checked', this.checked);
    });
});

// 검색 기능
function searchMembership() {
    loadMembershipList();
}

// 회비 목록 로드
function loadMembershipList(page = 1) {
    const searchData = {
        action: 'list',
        page: page,
        member_id: $('#searchMemberId').val(),
        type: $('#filterType').val(),
        status: $('#filterStatus').val(),
        year: $('#filterYear').val()
    };
    
    $.ajax({
        url: './membership_api.php',
        type: 'POST',
        data: searchData,
        dataType: 'json',
        beforeSend: function() {
            $('#membershipTable').html('<tr><td colspan="10" style="text-align: center; padding: 50px;">데이터를 불러오는 중...</td></tr>');
        },
        success: function(response) {
            if (response.success) {
                renderMembershipTable(response.data);
                renderPagination(response.page, response.total_count, response.per_page);
            } else {
                alert('데이터 로드 중 오류가 발생했습니다.');
            }
        },
        error: function() {
            alert('서버 통신 오류가 발생했습니다.');
            $('#membershipTable').html('<tr><td colspan="10" style="text-align: center; padding: 50px; color: #e74c3c;">데이터 로드 실패</td></tr>');
        }
    });
}

// 테이블 렌더링
function renderMembershipTable(data) {
    let html = '';
    
    if (data.length === 0) {
        html = '<tr><td colspan="11" style="text-align: center; padding: 50px; color: #7f8c8d;">검색 결과가 없습니다.</td></tr>';
    } else {
        data.forEach(function(item) {
			console.log(item.mb_status);
            const overdueClass = item.is_overdue ? 'overdue' : '';
            const statusClass = 'status-' + item.mb_status;
            
            html += `
                <tr>
                    <td><input type="checkbox" value="${item.mb_id}"></td>
                    <td>${item.mb_member_id}</td>
                    <td>${item.mb_name || '-'}</td>
                    <td>${item.mb_type_text}</td>
                    <td>${item.mb_content}</td>
                    <td class="amount">₩${item.mb_amount_formatted}</td>
                    <td>${item.validity_period}</td>
                    <td class="${overdueClass}">${item.mb_due_date}</td>
					<td>${item.mb_payment_method}</td>
                    <td><span class="status-badge ${statusClass}">${item.mb_status_text}</span></td>
                    <td>${item.mb_reg_date_formatted}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editMembership(${item.mb_id})">수정</button>`;
						if(item.mb_status === 'pending'){ 
                            html +=`<button class="btn btn-sm btn-success" onclick="approveMembership(${item.mb_id})">승인</button>`;
						}else{
                            html +=`<button class="btn btn-sm btn-warning" onclick="refundMembership(${item.mb_id})">환불</button>`;
                        }
			html += `<button class="btn btn-sm btn-danger" onclick="deleteMembership(${item.mb_id})">삭제</button>
                    </td>
                </tr>
				`;
        });
    }
    
    $('#membershipTable').html(html);
}

// 페이지네이션 렌더링
function renderPagination(currentPage, totalCount, perPage) {
    const totalPages = Math.ceil(totalCount / perPage);
    let html = '';
    
    if (totalPages <= 1) {
        $('#pagination').html('');
        return;
    }
    
    // 이전 페이지
    if (currentPage > 1) {
        html += `<a href="javascript:loadMembershipList(${currentPage - 1})">이전</a>`;
    }
    
    // 페이지 번호
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        const activeClass = i === currentPage ? 'active' : '';
        html += `<a href="javascript:loadMembershipList(${i})" class="${activeClass}">${i}</a>`;
    }
    
    // 다음 페이지
    if (currentPage < totalPages) {
        html += `<a href="javascript:loadMembershipList(${currentPage + 1})">다음</a>`;
    }
    
    $('#pagination').html(html);
}

// 통계 로드
function loadStatistics() {
    $.ajax({
        url: './membership_api.php',
        type: 'POST',
        data: { action: 'stats' },
        dataType: 'json',
        success: function(response) {
            $('#totalAmount').text('₩' + response.total_amount);
            $('#completedCount').text(response.completed_count);
            $('#pendingCount').text(response.pending_count);
            $('#overdueCount').text(response.overdue_count);
        },
        error: function() {
            console.log('통계 로드 실패');
        }
    });
}

// 모달 열기
function openAddModal() {
    $('#membershipModal').show();
    $('#membershipForm')[0].reset();
    $('#modalMbId').val('');
    $('.modal-title').text('새 회비 등록');
    
    // 기본값 설정
    const currentYear = new Date().getFullYear();
    $('#modalYear').val(currentYear);
    $('#modalStatus').val('pending');
    $('#modalStartDate').val(currentYear + '-01-01');
}

function closeModal() {
    $('#membershipModal').hide();
}

// 회비 수정
function editMembership(id) {
    $.ajax({
        url: './membership_api.php',
        type: 'POST',
        data: { action: 'get_detail', mb_id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                
                $('#membershipModal').show();
                $('.modal-title').text('회비 정보 수정');
                
                $('#modalMbId').val(data.mb_id);
                $('#modalMemberId').val(data.mb_member_id);
                $('#modalType').val(data.mb_type);
                $('#modalContent').val(data.mb_content);
                $('#modalAmount').val(data.mb_amount);
                $('#modalYear').val(data.mb_year);
                $('#modalStartDate').val(data.mb_start_date);
                $('#modalEndDate').val(data.mb_end_date);
                $('#modalDueDate').val(data.mb_due_date);
                $('#modalStatus').val(data.mb_status);
                $('#modalPaymentMethod').val(data.mb_payment_method);
                $('#modalPaymentInfo').val(data.mb_payment_info);
                $('#modalAdminMemo').val(data.mb_admin_memo);
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('데이터 로드 중 오류가 발생했습니다.');
        }
    });
}

// 회비 승인
function approveMembership(id) {
    if (confirm('해당 회비를 승인하시겠습니까?')) {
        $.ajax({
            url: './membership_api.php',
            type: 'POST',
            data: { action: 'approve', mb_id: id },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    loadMembershipList();
                    loadStatistics();
                }
            },
            error: function() {
                alert('승인 처리 중 오류가 발생했습니다.');
            }
        });
    }
}

// 회비 환불
function refundMembership(id) {
    const refundAmount = prompt('환불 금액을 입력하세요:');
    if (refundAmount && refundAmount > 0 && confirm(Number(refundAmount).toLocaleString() + '원을 환불하시겠습니까?')) {
        $.ajax({
            url: './membership_api.php',
            type: 'POST',
            data: { action: 'refund', mb_id: id, refund_amount: refundAmount },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadMembershipList();
                    loadStatistics();
                }
            },
            error: function() {
                alert('환불 처리 중 오류가 발생했습니다.');
            }
        });
    }
}

// 회비 삭제
function deleteMembership(id) {
    if (confirm('정말 삭제하시겠습니까?\n삭제된 데이터는 복구할 수 없습니다.')) {
        $.ajax({
            url: './membership_api.php',
            type: 'POST',
            data: { action: 'delete', mb_id: id },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    loadMembershipList();
                    loadStatistics();
                }
            },
            error: function() {
                alert('삭제 중 오류가 발생했습니다.');
            }
        });
    }
}

// 회비 저장
function saveMembership() {
    const formData = {
        action: 'save',
        mb_id: $('#modalMbId').val(),
        mb_member_id: $('#modalMemberId').val().trim(),
        mb_type: $('#modalType').val(),
        mb_content: $('#modalContent').val().trim(),
        mb_amount: $('#modalAmount').val(),
        mb_year: $('#modalYear').val(),
        mb_start_date: $('#modalStartDate').val(),
        mb_end_date: $('#modalEndDate').val(),
        mb_due_date: $('#modalDueDate').val(),
        mb_status: $('#modalStatus').val(),
        mb_payment_method: $('#modalPaymentMethod').val(),
        mb_payment_info: $('#modalPaymentInfo').val().trim(),
        mb_admin_memo: $('#modalAdminMemo').val().trim()
    };

    // 필수 항목 체크
    if (!formData.mb_member_id) {
        alert('회원ID를 입력해주세요.');
        $('#modalMemberId').focus();
        return;
    }
    
    if (!formData.mb_type) {
        alert('회비종류를 선택해주세요.');
        $('#modalType').focus();
        return;
    }
    
    if (!formData.mb_content) {
        alert('회비내용을 입력해주세요.');
        $('#modalContent').focus();
        return;
    }
    
    if (!formData.mb_amount || formData.mb_amount <= 0) {
        alert('올바른 금액을 입력해주세요.');
        $('#modalAmount').focus();
        return;
    }
    
    if (!formData.mb_due_date) {
        alert('납부예정일을 입력해주세요.');
        $('#modalDueDate').focus();
        return;
    }

    $.ajax({
        url: './membership_api.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
            $('button').prop('disabled', true);
        },
        success: function(response) {
            alert(response.message);
            if (response.success) {
                closeModal();
                loadMembershipList();
                loadStatistics();
            }
        },
        error: function() {
            alert('저장 중 오류가 발생했습니다.');
        },
        complete: function() {
            $('button').prop('disabled', false);
        }
    });
}

// 엑셀 다운로드
function exportExcel() {
    if (confirm('회비 데이터를 엑셀 파일로 다운로드하시겠습니까?')) {
        const form = $('<form>').attr({
            method: 'POST',
            action: './membership_api.php'
        }).append(
            $('<input>').attr({ type: 'hidden', name: 'action', value: 'export' })
        );
        
        $('body').append(form);
        form.submit();
        form.remove();
    }
}

// 일괄 처리
function bulkAction(action) {
    const checkedItems = $('input[type="checkbox"][value]:checked').map(function() {
        return this.value;
    }).get();

    if (checkedItems.length === 0) {
        alert('처리할 항목을 선택해주세요.');
        return;
    }

    const actionText = action === 'approve' ? '승인' : '삭제';
    if (confirm('선택한 ' + checkedItems.length + '개 항목을 ' + actionText + '하시겠습니까?')) {
        $.ajax({
            url: './membership_api.php',
            type: 'POST',
            data: { 
                action: 'bulk_' + action, 
                ids: checkedItems 
            },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    loadMembershipList();
                    loadStatistics();
                    $('#checkAll').prop('checked', false);
                }
            },
            error: function() {
                alert('처리 중 오류가 발생했습니다.');
            }
        });
    }
}

// 전체선택/해제 함수
function selectAll() {
    $('input[type="checkbox"]').prop('checked', true);
}

function deselectAll() {
    $('input[type="checkbox"]').prop('checked', false);
}

// 만료 예정 회비 체크
function checkExpiringMemberships() {
    $.ajax({
        url: './membership_api.php',
        type: 'POST',
        data: { action: 'check_expiring' },
        dataType: 'json',
        beforeSend: function() {
            // 로딩 표시
            $('#expiringMembersSection').html('<div style="text-align: center; padding: 20px;">만료 예정 회비를 확인하고 자동 생성 중...</div>').show();
        },
        success: function(response) {
			console.log(response);
            if (response.success) {
                if (response.count > 0) {
                    displayExpiringMembers(response.data, response.count, response.next_year);
                    $('#expiringCount').text(response.count + '건 만료 예정').show();
                    
                    // 자동 생성 결과 알림
                    if (response.auto_generated_count > 0) {
                        showAutoGenerationAlert(response.auto_generated_count, response.message);
                        // 목록 새로고침 (새로 생성된 회비 반영)
                        setTimeout(function() {
                            loadMembershipList();
                            loadStatistics();
                        }, 1000);
                    }
                } else {
                    $('#expiringMembersSection').hide();
                    $('#expiringCount').hide();
                    showInfoMessage('현재 만료 예정인 회비가 없습니다.');
                }
            } else {
                $('#expiringMembersSection').hide();
                showErrorMessage('만료 예정 회비 체크 실패: ' + response.message);
            }
        },
        error: function() {
            $('#expiringMembersSection').hide();
            showErrorMessage('만료 예정 회비 체크 중 서버 오류가 발생했습니다.');
        }
    });
}

// 만료 예정 회원 목록 표시 (자동 생성 결과 포함)
function displayExpiringMembers(data, count, nextYear) {
    let html = '';
    let autoGeneratedCount = 0;
    let failedCount = 0;
    
    data.forEach(function(member) {
        const statusClass = member.days_until_expiry <= 7 ? 'overdue' : 'status-soon';
        const statusText = member.days_until_expiry <= 7 ? '긴급' : '예정';
        
        // 자동 생성 결과 표시
        let autoStatusHtml = '';
        if (member.auto_generated === true) {
            autoStatusHtml = '<span class="badge badge-success" style="background: #d4edda; color: #155724; margin-left: 5px;">자동생성완료</span>';
            autoGeneratedCount++;
        } else if (member.auto_generated === false) {
            autoStatusHtml = '<span class="badge badge-danger" style="background: #f8d7da; color: #721c24; margin-left: 5px;" title="' + member.auto_error + '">자동생성실패</span>';
            failedCount++;
        }
        
        html += `
            <tr ${member.auto_generated ? 'style="background-color: #f0fff0;"' : ''}>
                <td><input type="checkbox" class="expiring-checkbox" value="${member.mb_member_id}" ${member.auto_generated ? 'disabled' : ''}></td>
                <td>${member.mb_member_id}</td>
                <td>${member.mb_name}</td>
                <td>₩${Number(member.mb_amount).toLocaleString()}</td>
                <td>${member.mb_end_date}</td>
                <td class="${statusClass}">${member.days_until_expiry}일</td>
                <td>
                    <span class="badge ${member.days_until_expiry <= 7 ? 'badge-danger' : 'badge-warning'}">${statusText}</span>
                    ${autoStatusHtml}
                </td>
            </tr>
        `;
    });
    
    $('#expiringMembersTable').html(html);
    $('#expiringMembersSection').show();
    
    // 자동 생성 결과 요약 표시
    if (autoGeneratedCount > 0 || failedCount > 0) {
        let summaryHtml = '<div class="auto-generation-summary" style="margin-top: 15px; padding: 10px; background: #e8f4fd; border-radius: 5px;">';
        summaryHtml += '<strong>자동 생성 결과:</strong> ';
        if (autoGeneratedCount > 0) {
            summaryHtml += `<span style="color: #155724;">${autoGeneratedCount}건 성공</span>`;
        }
        if (failedCount > 0) {
            if (autoGeneratedCount > 0) summaryHtml += ', ';
            summaryHtml += `<span style="color: #721c24;">${failedCount}건 실패</span>`;
        }
        summaryHtml += '</div>';
        
        $('#expiringMembersSection').append(summaryHtml);
    }
}

// 자동 생성 성공 알림
function showAutoGenerationAlert(count, message) {
    // 성공 알림 모달 또는 토스트 메시지
    const alertHtml = `
        <div class="auto-generation-alert" style="
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            background: #d4edda; border: 1px solid #c3e6cb; color: #155724;
            padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 400px; animation: slideInRight 0.3s ease-out;
        ">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 20px;">✅</span>
                <div>
                    <strong>자동 회비 생성 완료!</strong><br>
                    <small>${message}</small>
                </div>
                <button onclick="closeAutoAlert()" style="
                    background: none; border: none; color: #155724; 
                    font-size: 18px; cursor: pointer; margin-left: auto;
                ">&times;</button>
            </div>
        </div>
    `;
    
    $('body').append(alertHtml);
    
    // 5초 후 자동 삭제
    setTimeout(function() {
        $('.auto-generation-alert').fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);
}

// 정보 메시지 표시
function showInfoMessage(message) {
    const alertHtml = `
        <div class="info-alert" style="
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            background: #cce7ff; border: 1px solid #9fcfff; color: #0056b3;
            padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 400px;
        ">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 20px;">ℹ️</span>
                <div>${message}</div>
                <button onclick="closeInfoAlert()" style="
                    background: none; border: none; color: #0056b3; 
                    font-size: 18px; cursor: pointer; margin-left: auto;
                ">&times;</button>
            </div>
        </div>
    `;
    
    $('body').append(alertHtml);
    
    setTimeout(function() {
        $('.info-alert').fadeOut(300, function() {
            $(this).remove();
        });
    }, 3000);
}

// 에러 메시지 표시
function showErrorMessage(message) {
    const alertHtml = `
        <div class="error-alert" style="
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;
            padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            max-width: 400px;
        ">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 20px;">❌</span>
                <div>${message}</div>
                <button onclick="closeErrorAlert()" style="
                    background: none; border: none; color: #721c24; 
                    font-size: 18px; cursor: pointer; margin-left: auto;
                ">&times;</button>
            </div>
        </div>
    `;
    
    $('body').append(alertHtml);
    
    setTimeout(function() {
        $('.error-alert').fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);
}

// 알림 닫기 함수들
function closeAutoAlert() {
    $('.auto-generation-alert').fadeOut(300, function() {
        $(this).remove();
    });
}

function closeInfoAlert() {
    $('.info-alert').fadeOut(300, function() {
        $(this).remove();
    });
}

function closeErrorAlert() {
    $('.error-alert').fadeOut(300, function() {
        $(this).remove();
    });
}

// 자동 생성 모달 열기
function openGenerationModal() {
    const selectedExpiring = $('.expiring-checkbox:checked').map(function() {
        return this.value;
    }).get();
    
    if (selectedExpiring.length === 0) {
        alert('생성할 회원을 선택해주세요.');
        return;
    }
    
    window.selectedExpiringMembers = selectedExpiring;
    $('#generationModal').show();
    $('#generationPreview').hide();
}

// 자동 생성 모달 닫기
function closeGenerationModal() {
    $('#generationModal').hide();
    $('#generationPreview').hide();
}

// 회비 자동 생성 미리보기
function previewAutoGeneration() {
    if (!window.selectedExpiringMembers || window.selectedExpiringMembers.length === 0) {
        alert('선택된 회원이 없습니다.');
        return;
    }
    
    const customAmount = $('#autoGenAmount').val();
    const customDueDate = $('#autoGenDueDate').val();
    
    $.ajax({
        url: './membership_api.php',
        type: 'POST',
        data: { 
            action: 'preview_generation',
            member_ids: window.selectedExpiringMembers,
            custom_amount: customAmount,
            custom_due_date: customDueDate
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayGenerationPreview(response.data, response.next_year);
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('미리보기 생성 중 오류가 발생했습니다.');
        }
    });
}

// 생성 미리보기 표시
function displayGenerationPreview(data, nextYear) {
    let html = '';
    
    data.forEach(function(item) {
        html += `
            <tr>
                <td>${item.mb_member_id}</td>
                <td>${item.mb_name}</td>
                <td>₩${Number(item.current_amount).toLocaleString()}</td>
                <td>${item.new_content}</td>
                <td>₩${Number(item.new_amount).toLocaleString()}</td>
                <td>${item.new_start_date} ~ ${item.new_end_date}</td>
                <td>${item.new_due_date}</td>
            </tr>
        `;
    });
    
    $('#generationPreviewTable').html(html);
    $('#generationPreview').show();
}

// 회비 자동 생성 실행
function executeAutoGeneration() {
    if (!confirm(`${window.selectedExpiringMembers.length}명의 다음 연도 회비를 생성하시겠습니까?`)) {
        return;
    }
    
    const customAmount = $('#autoGenAmount').val();
    const customDueDate = $('#autoGenDueDate').val();
    
    $.ajax({
        url: './membership_api.php',
        type: 'POST',
        data: { 
            action: 'auto_generate',
            member_ids: window.selectedExpiringMembers,
            custom_amount: customAmount,
            custom_due_date: customDueDate
        },
        dataType: 'json',
        beforeSend: function() {
            $('button').prop('disabled', true);
        },
        success: function(response) {
            if (response.success) {
                let message = `${response.success_count}건 성공`;
                if (response.error_count > 0) {
                    message += `, ${response.error_count}건 실패`;
                    message += '\n\n실패 상세:\n' + response.error_list.join('\n');
                }
                
                alert(message);
                closeGenerationModal();
                
                // 목록 새로고침
                loadMembershipList();
                loadStatistics();
                checkExpiringMemberships();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('회비 생성 중 오류가 발생했습니다.');
        },
        complete: function() {
            $('button').prop('disabled', false);
        }
    });
}