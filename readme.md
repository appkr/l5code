## 예제 프로젝트 5.5 업그레이드

### 1. 달라진 점

[5.4 브랜치](https://github.com/appkr/l5code/tree/laravel54) 대비 크게 달라진 점은 없습니다.

#### 1.1. 모델 팩토리 비활성화

라라벨 5.5부터 모델당 모델 팩토리를 하나씩 쓸 수 있도록 바뀌었습니다. 물론 기존처럼 통합된 `ModelFactory` 도 쓸 수 있습니다. 충돌을 피하기 위해 5.5 버전에 추가된 `database\factories\UserFactory.php` 의 모든 내용을 주석처리했습니다. 
