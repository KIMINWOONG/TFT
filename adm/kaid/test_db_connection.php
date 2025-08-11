<?php
$sub_menu = "600210";
require_once './_common.php';

// 데이터베이스 연결 테스트
echo "<h2>데이터베이스 연결 테스트</h2>";

// 1. 기본 테이블 존재 확인
$tables_to_check = ['g5_conference_registration', 'g5_conference', 'g5_symposium', 'g5_member'];

foreach ($tables_to_check as $table) {
    $sql = "SHOW TABLES LIKE '{$table}'";
    $result = sql_query($sql);
    $exists = sql_num_rows($result) > 0;
    echo "<p>테이블 {$table}: " . ($exists ? "<span style='color:green'>존재함</span>" : "<span style='color:red'>존재하지 않음</span>") . "</p>";
    
    // 테이블이 존재하면 구조 확인
    if ($exists) {
        echo "<details><summary>{$table} 테이블 구조</summary>";
        $desc_sql = "DESCRIBE {$table}";
        $desc_result = sql_query($desc_sql);
        if ($desc_result) {
            echo "<table border='1' style='margin:10px;'><tr><th>필드명</th><th>타입</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($desc_row = sql_fetch_array($desc_result)) {
                echo "<tr><td>{$desc_row['Field']}</td><td>{$desc_row['Type']}</td><td>{$desc_row['Null']}</td><td>{$desc_row['Key']}</td><td>{$desc_row['Default']}</td></tr>";
            }
            echo "</table>";
        }
        echo "</details>";
    }
}

// 2. g5_conference_registration 테이블 데이터 확인
echo "<h3>g5_conference_registration 테이블 데이터</h3>";
$sql = "SELECT COUNT(*) as total FROM g5_conference_registration";
$result = sql_query($sql);
if ($result) {
    $row = sql_fetch_array($result);
    echo "<p>총 등록 데이터 수: {$row['total']}개</p>";
    
    if ($row['total'] > 0) {
        // 최근 5개 데이터 조회
        $sql = "SELECT cr_id, cr_name, cr_mb_id, cr_datetime FROM g5_conference_registration ORDER BY cr_id DESC LIMIT 5";
        $result = sql_query($sql);
        echo "<h4>최근 등록 데이터 (최대 5개)</h4>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>이름</th><th>회원ID</th><th>등록일시</th></tr>";
        while ($data = sql_fetch_array($result)) {
            echo "<tr>";
            echo "<td>{$data['cr_id']}</td>";
            echo "<td>{$data['cr_name']}</td>";
            echo "<td>{$data['cr_mb_id']}</td>";
            echo "<td>{$data['cr_datetime']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p>쿼리 실행 오류</p>";
}

// 3. 조인 쿼리 테스트
echo "<h3>조인 쿼리 테스트</h3>";

// 먼저 간단한 쿼리부터 테스트
echo "<h4>1단계: g5_conference_registration만 조회</h4>";
$simple_sql = "SELECT cr_id, cr_name_kor, cr_nonemb_name, cr_mb_id, cr_reg_date FROM g5_conference_registration ORDER BY cr_id DESC LIMIT 3";
$simple_result = sql_query($simple_sql);
if ($simple_result && sql_num_rows($simple_result) > 0) {
    echo "<table border='1'><tr><th>등록ID</th><th>한글명</th><th>비회원명</th><th>회원ID</th><th>등록일시</th></tr>";
    while ($data = sql_fetch_array($simple_result)) {
        echo "<tr><td>{$data['cr_id']}</td><td>{$data['cr_name_kor']}</td><td>{$data['cr_nonemb_name']}</td><td>{$data['cr_mb_id']}</td><td>{$data['cr_reg_date']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>기본 테이블 조회 실패</p>";
}

echo "<h4>2단계: 회원 테이블과 조인</h4>";
$join_sql = "SELECT 
            cr.cr_id,
            cr.cr_name_kor,
            cr.cr_nonemb_name,
            cr.cr_mb_id,
            m.mb_name,
            cr.cr_reg_date
        FROM g5_conference_registration cr 
        LEFT JOIN g5_member m ON (cr.cr_mb_id = m.mb_id AND cr.cr_mb_id IS NOT NULL AND cr.cr_mb_id != '')
        ORDER BY cr.cr_id DESC 
        LIMIT 3";
        
echo "<p><strong>실행 쿼리:</strong><br><code>" . htmlspecialchars($join_sql) . "</code></p>";

$result = sql_query($join_sql);
if ($result && sql_num_rows($result) > 0) {
    echo "<table border='1'>";
    echo "<tr><th>등록ID</th><th>한글명</th><th>비회원명</th><th>회원ID</th><th>회원명</th><th>등록일시</th></tr>";
    while ($data = sql_fetch_array($result)) {
        echo "<tr>";
        echo "<td>{$data['cr_id']}</td>";
        echo "<td>{$data['cr_name_kor']}</td>";
        echo "<td>{$data['cr_nonemb_name']}</td>";
        echo "<td>{$data['cr_mb_id']}</td>";
        echo "<td>{$data['mb_name']}</td>";
        echo "<td>{$data['cr_reg_date']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>조인 쿼리 결과 없음 또는 오류</p>";
    // 오류 정보 출력
    if (function_exists('sql_error')) {
        $error = sql_error();
        if ($error) {
            echo "<p><strong>SQL 오류:</strong> " . htmlspecialchars($error) . "</p>";
        }
    }
}

// 4. 전체 테이블 목록 확인
echo "<h3>데이터베이스 내 모든 테이블 목록</h3>";
$all_tables_sql = "SHOW TABLES";
$all_tables_result = sql_query($all_tables_sql);
if ($all_tables_result) {
    echo "<ul>";
    while ($table_row = sql_fetch_array($all_tables_result)) {
        $table_name = array_values($table_row)[0];
        echo "<li>{$table_name}</li>";
    }
    echo "</ul>";
}

echo "<p><a href='conference_registration_list.php'>등록자 목록으로 돌아가기</a></p>";
?>