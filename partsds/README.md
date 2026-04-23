# 파츠디에스 커스텀 기능 가이드

## 개요
그누보드5 + 이윰빌더 기반 파츠디에스 쇼핑몰의 차종 기반 부품 검색 시스템

---

## 📁 파일 구조

```
/partsds/
├── install_car_tables.php    ← ① 최초 1회 실행 (DB 테이블 생성)
├── car_api.php               ← AJAX API (브랜드/시리즈/모델 목록)
├── brand_selector.php        ← 메인 페이지 브랜드 선택 위젯
├── car_list_filter.php       ← 상품 목록 차종 필터 함수
├── register_car_field.php    ← 회원가입 차종 선택 필드
├── css/
│   └── brand_selector.css   ← 브랜드 선택기 스타일
└── admin/
    ├── car_manage.php         ← 브랜드/시리즈/모델 관리
    └── item_car_manage.php    ← 상품-차종 매핑 관리

/extend/
└── partsds.extend.php        ← 그누보드 이벤트 훅 (자동 로드)
```

---

## 🚀 설치 순서

### 1단계: DB 테이블 생성
관리자 로그인 후 브라우저에서 접근:
```
https://사이트주소/partsds/install_car_tables.php
```
- `g5_car_brand`, `g5_car_series`, `g5_car_model`, `g5_item_car` 테이블 생성
- 기본 브랜드 14개 자동 삽입 (벤츠, BMW, 아우디 등)
- 설치 완료 후 파일 삭제 권장

### 2단계: 차종 데이터 입력
관리자 페이지에서 접근:
```
https://사이트주소/partsds/admin/car_manage.php
```
- **브랜드 탭**: 브랜드 확인/추가/삭제
- **시리즈 탭**: 브랜드별 시리즈 추가 (예: 벤츠 > E-Class)
- **모델 탭**: 시리즈별 모델 추가 (예: E-Class > E220d 2019-2023)

### 3단계: 상품-차종 매핑
```
https://사이트주소/partsds/admin/item_car_manage.php
```
- 상품코드와 차종을 연결
- 브랜드만 연결 or 브랜드+시리즈 or 브랜드+시리즈+모델 세 가지 레벨 지원

---

## 🔧 주요 기능

### 1. 메인 페이지 브랜드 선택기
- 브랜드 로고 캐러셀 (좌우 버튼으로 스크롤)
- 3단계 연동 셀렉트박스: 브랜드 → 시리즈 → 모델
- [부품 검색] 버튼 클릭 시 `/shop/list.php?pds_brand=1&pds_series=2&pds_model=3`으로 이동
- 로그인 회원의 저장된 차종 자동 선택

### 2. 상품 목록 차종 필터
- `pds_brand`, `pds_series`, `pds_model` 파라미터 지원
- 상단에 "현재 차종 필터" 배너 표시
- `item_car` 테이블에 매핑된 상품만 노출

### 3. 회원가입 차종 저장
- 회원가입/정보수정 폼에 차종 선택 드롭다운 추가
- 저장 필드:
  - `mb_1` = 브랜드명
  - `mb_2` = 시리즈명
  - `mb_3` = 모델명
  - `mb_4` = brand_id (숫자)
  - `mb_5` = series_id (숫자)
  - `mb_6` = model_id (숫자)

### 4. 로그인 시 차종 자동 선택
- 로그인한 회원의 저장된 차종이 메인 페이지 브랜드 선택기에 자동 반영
- `/partsds/car_api.php?action=member_car` API로 실시간 확인 가능
- 마이페이지에서 언제든 차종 변경 가능

---

## 🗄️ DB 테이블 구조

| 테이블 | 설명 |
|--------|------|
| `g5_car_brand` | 브랜드 (벤츠, BMW 등) |
| `g5_car_series` | 시리즈 (E-Class, 3시리즈 등) |
| `g5_car_model` | 모델 (E220d, 320i 등) |
| `g5_item_car` | 상품-차종 매핑 |

---

## 📝 그누보드 설정 필요사항

### 회원 항목 레이블 설정 (관리자 > 회원관리 > 회원항목)
| 항목 | 설정값 |
|------|--------|
| 회원항목1 (mb_1) | 차량브랜드 |
| 회원항목2 (mb_2) | 차량시리즈 |
| 회원항목3 (mb_3) | 차량모델 |
| 회원항목4 (mb_4) | 브랜드ID |
| 회원항목5 (mb_5) | 시리즈ID |
| 회원항목6 (mb_6) | 모델ID |

---

## ⚠️ 주의사항

1. `install_car_tables.php`는 설치 후 즉시 삭제하세요
2. `partsds/admin/` 디렉토리는 관리자만 접근 가능 (내부에서 `$is_admin` 체크)
3. 브랜드 로고 이미지는 관리자 페이지에서 추가 가능 (향후 개발)
4. `item_car` 매핑이 없는 상품은 차종 필터 검색 결과에 나타나지 않음
