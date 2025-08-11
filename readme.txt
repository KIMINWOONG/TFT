회비관라, 학술대회신청/결제,

회비납부
g5_membership
회비종류(연회비, 입회비)
회비내용
금액
유효기간시작일
유효기간만료일
납부예정일
승인일자
납부상태(납부예정, 완료, 취소)
회원ID
등록일

CREATE TABLE `g5_membership` (
  `mb_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '회비납부 고유ID',
  `mb_type` varchar(20) NOT NULL DEFAULT '' COMMENT '회비종류 (annual:연회비, entrance:입회비)',
  `mb_content` varchar(255) NOT NULL DEFAULT '' COMMENT '회비내용',
  `mb_amount` int(11) NOT NULL DEFAULT '0' COMMENT '금액',
  `mb_start_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '유효기간시작일',
  `mb_end_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '유효기간만료일',
  `mb_due_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '납부예정일',
  `mb_approve_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '승인일자',
  `mb_status` varchar(20) NOT NULL DEFAULT 'pending' COMMENT '납부상태 (pending:납부예정, completed:완료, cancelled:취소)',
  `mb_member_id` varchar(20) NOT NULL DEFAULT '' COMMENT '회원ID',
  `mb_reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '등록일',
  `mb_payment_method` varchar(20) NOT NULL DEFAULT '' COMMENT '결제방법 (card:카드, bank:계좌이체, cash:현금)',
  `mb_payment_info` text COMMENT '결제정보 (거래번호, 계좌정보 등)',
  `mb_receipt_number` varchar(50) NOT NULL DEFAULT '' COMMENT '영수증번호',
  `mb_note` text COMMENT '비고',
  `mb_admin_memo` text COMMENT '관리자메모',
  `mb_year` year(4) NOT NULL DEFAULT '0000' COMMENT '회비연도',
  `mb_is_refund` tinyint(1) NOT NULL DEFAULT '0' COMMENT '환불여부 (0:미환불, 1:환불)',
  `mb_refund_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '환불일자',
  `mb_refund_amount` int(11) NOT NULL DEFAULT '0' COMMENT '환불금액',
  `mb_update_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
  PRIMARY KEY (`mb_id`),
  KEY `idx_member_id` (`mb_member_id`),
  KEY `idx_status` (`mb_status`),
  KEY `idx_type` (`mb_type`),
  KEY `idx_year` (`mb_year`),
  KEY `idx_due_date` (`mb_due_date`),
  KEY `idx_reg_date` (`mb_reg_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='회비납부관리';


CREATE TABLE `g5_conference_summary` (
  `as_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '초록 제출 ID',
  `as_sy_id` int(11) NOT NULL COMMENT '집담회 ID',
  `as_mb_id` varchar(20) DEFAULT '' COMMENT '회원ID',
  `as_cr_id` int(11) DEFAULT NULL COMMENT '등록 ID (conference_registration과 연결)',
  
  -- 논문 메타정보
  `as_submitter` varchar(100) NOT NULL DEFAULT '' COMMENT '제출자',
  `as_title_kor` varchar(500) NOT NULL DEFAULT '' COMMENT '논문제목(국문)',
  `as_title_eng` varchar(500) DEFAULT '' COMMENT '논문제목(영문)',
  `as_institution` varchar(200) NOT NULL DEFAULT '' COMMENT '소속기관',
  `as_language` varchar(20) NOT NULL DEFAULT '한국어' COMMENT '언어',
  `as_abstract_kor` longtext NOT NULL COMMENT '초록(국문)',
  `as_abstract_eng` longtext DEFAULT NULL COMMENT '초록(영문)',
  `as_file_path` varchar(500) DEFAULT '' COMMENT '첨부파일 경로',
  `as_file_name` varchar(255) DEFAULT '' COMMENT '원본 파일명',
  `as_file_size` int(11) DEFAULT 0 COMMENT '파일 크기',
  
  -- 발표정보
  `as_presentation_field` varchar(50) NOT NULL DEFAULT '' COMMENT '발표분야 (구강, 예방, 치주, 소아치과, 치과)',
  `as_presentation_type` varchar(50) NOT NULL DEFAULT '' COMMENT '발표유형 (구연, 포스터)',
  
  -- 발표자 정보
  `as_presenter_name` varchar(100) NOT NULL DEFAULT '' COMMENT '발표자명',
  `as_presenter_institution` varchar(200) NOT NULL DEFAULT '' COMMENT '발표자 소속기관',
  `as_presenter_home_phone1` varchar(4) DEFAULT '' COMMENT '자택전화1',
  `as_presenter_home_phone2` varchar(4) DEFAULT '' COMMENT '자택전화2',
  `as_presenter_home_phone3` varchar(4) DEFAULT '' COMMENT '자택전화3',
  `as_presenter_mobile_carrier` varchar(10) DEFAULT '' COMMENT '휴대전화 통신사',
  `as_presenter_mobile1` varchar(4) DEFAULT '' COMMENT '휴대전화1',
  `as_presenter_mobile2` varchar(4) DEFAULT '' COMMENT '휴대전화2',
  `as_presenter_email` varchar(100) DEFAULT '' COMMENT '발표자 이메일',
  
  -- 상태 및 관리
  `as_status` varchar(20) NOT NULL DEFAULT 'submitted' COMMENT '상태 (submitted:제출, reviewed:심사중, accepted:승인, rejected:반려, withdrawn:철회)',
  `as_review_comments` text DEFAULT NULL COMMENT '심사 의견',
  `as_admin_memo` text DEFAULT NULL COMMENT '관리자 메모',
  `as_submit_date` datetime NOT NULL DEFAULT current_timestamp() COMMENT '제출일시',
  `as_update_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '수정일시',
  `as_review_date` datetime DEFAULT NULL COMMENT '심사완료일시',
  
  PRIMARY KEY (`as_id`),
  KEY `as_sy_id` (`as_sy_id`),
  KEY `as_mb_id` (`as_mb_id`),
  KEY `as_cr_id` (`as_cr_id`),
  KEY `as_status` (`as_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='초록 제출';

-- 저자 정보 테이블 (다중 저자 지원)
CREATE TABLE `g5_conference_summary_authors` (
  `aa_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '저자 ID',
  `aa_as_id` int(11) NOT NULL COMMENT '초록 제출 ID',
  `aa_name` varchar(100) NOT NULL DEFAULT '' COMMENT '저자명',
  `aa_role` varchar(50) NOT NULL DEFAULT '' COMMENT '저자구분 (제1저자, 공동저자, 교신저자, 책임저자, 참여저자, 공동제1저자, 공동교신저자)',
  `aa_is_presenter` tinyint(1) NOT NULL DEFAULT 0 COMMENT '발표자와 동일 여부',
  `aa_order` int(11) NOT NULL DEFAULT 1 COMMENT '저자 순서',
  `aa_reg_date` datetime NOT NULL DEFAULT current_timestamp() COMMENT '등록일시',
  
  PRIMARY KEY (`aa_id`),
  KEY `aa_as_id` (`aa_as_id`),
  KEY `aa_order` (`aa_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='초록 저자정보';