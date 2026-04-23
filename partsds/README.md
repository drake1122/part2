# 파츠디에스 (PartsDS) 차종 기반 부품 검색 시스템

## 개요
그누보드5 + 이윰빌더(eyoom builder) 기반의 자동차 수입 부품 쇼핑몰(파츠디에스)을 위한 확장 시스템입니다.

---

## 기능

### 1. 차종 카테고리 3단계 구조 (브랜드 > 시리즈 > 모델)
- DB 테이블: `g5_car_brand`, `g5_car_series`, `g5_car_model`, `g5_item_car`
- 관리 페이지: `/partsds/admin/car_manage.php`

### 2. 메인 화면 차종 선택 UI
- 브랜드 이미지 캐러셀
- 3단계 선택 박스 (브랜드 → 시리즈 → 모델)
- 선택 후 해당 차종 부품 목록으로 이동
- 파일: `/partsds/brand_selector.php`

### 3. 회원가입 시 차종 저장
- 회원가입/정보수정 폼에 차종 선택 필드 추가
- 이윰빌더 테마 완벽 호환 (eyoom 스타일 레이아웃)
- mb_1~mb_3: 브랜드명, 시리즈명, 모델명 (텍스트)
- mb_4~mb_6: brand_id, series_id, model_id (숫자 ID)
- 로그인 시 저장된 차종 자동 선택

---

## 설치 방법

### 1단계: DB 테이블 설치
브라우저에서 접속:
```
https://[사이트주소]/partsds/install_car_tables.php
```
> 설치 완료 후 이 파일을 삭제하세요.

### 2단계: 차종 데이터 입력
```
https://[사이트주소]/partsds/admin/car_manage.php
```
- 브랜드 탭: 벤츠, BMW 등 추가 (기본 14개 자동 삽입됨)
- 시리즈 탭: E-Class, 3시리즈 등 추가
- 모델 탭: E220d, 320i 등 추가

### 3단계: 상품-차종 연결
```
https://[사이트주소]/partsds/admin/item_car_manage.php
```

---

## 파일 구조

```
/partsds/
  install_car_tables.php    ← DB 설치 스크립트 (설치 후 삭제)
  car_api.php               ← AJAX API (브랜드/시리즈/모델 조회, 회원 차종 저장)
  brand_selector.php        ← 쇼핑몰 메인 브랜드 선택 위젯
  car_list_filter.php       ← 상품 목록 차종 필터 처리
  register_car_field.php    ← 회원가입 폼 차종 선택 필드
  css/brand_selector.css    ← 스타일시트
  admin/
    car_manage.php          ← 차종 관리 (브랜드/시리즈/모델)
    item_car_manage.php     ← 상품-차종 연결 관리

/extend/
  partsds.extend.php        ← 그누보드 이벤트 훅 (자동 로드)

/shop/
  list.php                  ← 차종 필터 기능 추가됨 (pds_brand, pds_series, pds_model)

/theme/eb4_basic/shop/
  index.html.php            ← 차종 선택 위젯 삽입됨

/theme/eb4_basic/skin/member/basic/
  register_form.skin.html.php  ← 차종 선택 필드 삽입됨

/eyoom/user/member/
  register_form.skin.php    ← 이윰빌더 user hook (자동 include)
```

---

## 그누보드 회원 필드 활용
관리자 > 환경설정 > 회원 관련 설정 > 회원 항목에서 아래와 같이 설명 입력:
- mb_1: 차량 브랜드명 (예: 벤츠)
- mb_2: 차량 시리즈명 (예: E-Class)
- mb_3: 차량 모델명 (예: E220d)
- mb_4: 브랜드 ID (숫자)
- mb_5: 시리즈 ID (숫자)
- mb_6: 모델 ID (숫자)

---

## API 엔드포인트

| 요청 | 설명 |
|------|------|
| `GET /partsds/car_api.php?action=brands` | 전체 브랜드 목록 |
| `GET /partsds/car_api.php?action=series&brand_id=1` | 브랜드별 시리즈 |
| `GET /partsds/car_api.php?action=models&series_id=1` | 시리즈별 모델 |
| `GET /partsds/car_api.php?action=member_car` | 로그인 회원 저장 차종 |
| `POST /partsds/car_api.php?action=save_member_car` | 회원 차종 저장 |

---

## 상품 필터 URL 예시
```
/shop/list.php?ca_id=xx&pds_brand=1               # 브랜드 필터
/shop/list.php?ca_id=xx&pds_brand=1&pds_series=2  # 시리즈까지 필터
/shop/list.php?pds_brand=1&pds_series=2&pds_model=3  # 모델까지 필터
```
