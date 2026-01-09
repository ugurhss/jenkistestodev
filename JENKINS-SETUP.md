# 🚀 Jenkins CI/CD Pipeline Setup Kılavuzu

Bu proje otomatik olarak aşağıdaki aşamaları gerçekleştir:

## 📋 Pipeline Aşamaları

1. **✅ GitHub'dan kodlar çekilecek** (5 puan)
   - Repository otomatik olarak clone edilir

2. **✅ Kodlar build edilecek** (5 puan)
   - Composer bağımlılıkları yüklenir
   - NPM bağımlılıkları yüklenir
   - Frontend build edilir

3. **✅ Birim Testleri çalıştırılacak** (15 puan)
   - `tests/Unit/` dizinindeki tüm testler çalışır
   - JUnit raporu oluşturulur
   - Test sonuçları Jenkins'te görüntülenir

4. **✅ Entegrasyon Testleri çalıştırılacak** (15 puan)
   - `tests/Feature/` dizinindeki tüm testler çalışır
   - Database ile test edilir
   - JUnit raporu oluşturulur

5. **✅ Sistem Docker container'lar üzerinde çalıştırılacak** (5 puan)
   - PHP-FPM container başlatılır
   - MySQL database container başlatılır
   - Health check yapılır

6. **✅ 3 E2E Test Senaryosu çalıştırılacak** (55 puan)
   - Test 1: `/api/health` - API sağlık kontrolü
   - Test 2: `/api/status` - Uygulama durumu kontrolü
   - Test 3: `/api/db-status` - Veritabanı bağlantısı kontrolü

---

## 🔧 Jenkins'i Docker'da Ayağa Kaldırma

### 1. Setup Script'i Çalıştırın

```bash
chmod +x setup-jenkins.sh
./setup-jenkins.sh
```

Bu script:
- Docker container'ları başlatır
- Jenkins'i 8080 portunda ayağa kaldırır
- Initial admin password'u gösterir

### 2. Jenkins'e Gidin

```
🌐 http://localhost:8080
```

### 3. Initial Setup

1. Yukarıda gösterilen password'u kopyalayın
2. **Install suggested plugins** seçin
3. Admin kullanıcı oluşturun
4. Jenkins Dashboard'a girin

### 4. Pipeline Job Oluşturun

1. **New Item** → Job adı girin → **Pipeline** seçin
2. **Pipeline** sekmesinde:
   - **Definition**: Pipeline script from SCM
   - **SCM**: Git
   - **Repository URL**: `https://github.com/ugurhss/jenkistestodev.git`
   - **Branch**: `main`
   - **Script Path**: `Jenkinsfile`
3. **Save** yapın

### 5. GitHub Webhook Ayarı (Opsiyonel - Otomatik Trigger)

```bash
python3 setup-webhook.py
```

Bu script istenirse:
- GitHub Personal Access Token (Settings → Developer settings → Personal access tokens)
- Repository bilgilerinizi soracaktır
- Webhook otomatik olarak GitHub'a eklenecektir

#### Manuel Webhook Ekleme

GitHub → Repository Settings → Webhooks → Add webhook:
- **Payload URL**: `http://your-jenkins-url/github-webhook/`
- **Content type**: `application/json`
- **Events**: Push events
- **Active**: ✓

---

## 🧪 Test Senaryoları

### Unit Tests
```bash
php artisan test --testsuite=Unit
```

- CalculatorTest.php - Temel math işlemleri
- ExampleUnitTest.php - String ve array işlemleri
- GroupServiceTest.php - Group service testleri

### Feature Tests
```bash
php artisan test --testsuite=Feature
```

- ApiHealthCheckTest.php - API sağlık kontrolleri
- DatabaseConnectionTest.php - DB bağlantı testleri
- GroupStoreTest.php - Group oluşturma
- GroupUpdateTest.php - Group güncelleme
- GroupAuthTest.php - Yetkilendirme testleri

### E2E Tests (Jenkins'te otomatik çalışır)
- `/api/health` - Sağlık kontrolü
- `/api/status` - Uygulama durumu
- `/api/db-status` - Veritabanı durumu

---

## 🚀 Pipeline Tetikleme

### Otomatik Tetikleme (Webhook ile)
```bash
git push origin main
# Jenkins otomatik olarak çalışacak!
```

### Manuel Tetikleme
1. Jenkins Job sayfasına gidin
2. **Build Now** butonuna tıklayın
3. **Build History**'de ilerlemeyi izleyin

---

## 📊 Jenkins'te Sonuçları Görme

### Console Output
- **Build History** → Build numarasını tıklayın → **Console Output**

### Test Reports
- **Build** sayfasında **Test Results** görülür
- Unit ve Integration test sonuçları burada

### Aşama Detayları
- Pipeline stages görselleştirilir
- Her aşamanın duruşu kontrolünü yapabilirsiniz

---

## 🐳 Docker Komutları

### Container Durumunu Kontrol Et
```bash
docker-compose -f docker-compose.jenkins.yml ps
```

### Jenkins Loglarını Göster
```bash
docker-compose -f docker-compose.jenkins.yml logs jenkins -f
```

### Tüm Container'ları Durdur
```bash
docker-compose -f docker-compose.jenkins.yml down
```

### Tüm Container'ları Başlat
```bash
docker-compose -f docker-compose.jenkins.yml up -d
```

---

## 🔐 Güvenlik Notları

- Jenkins password'u `.env` dosyasında saklanmaz
- GitHub token'ı sadece setup sırasında kullanılır
- Jenkins home directory Docker volume'de depolanır

---

## ❓ Sorun Giderme

### Jenkins başlamıyor
```bash
docker-compose -f docker-compose.jenkins.yml logs jenkins
```

### Pipeline job çalışmıyor
1. Pipeline job konfigürasyonunu kontrol edin
2. GitHub repository erişimini kontrol edin
3. Jenkins logs'ları kontrol edin

### Webhook çalışmıyor
1. GitHub repository → Settings → Webhooks
2. Son deliveryx'i kontrol edin
3. Jenkins URL'i public olması gerekebilir

---

## 📝 Proje Yapısı

```
.
├── Jenkinsfile                    # Pipeline tanımı
├── docker-compose.jenkins.yml     # Jenkins Docker compose
├── docker-compose.app.yml         # App + DB Docker compose
├── Dockerfile                     # Production image
├── ci/
│   └── Dockerfile.ci             # CI image
├── routes/
│   └── api.php                   # API endpoints
├── tests/
│   ├── Unit/                     # Unit testleri
│   └── Feature/                  # Feature testleri
└── setup-jenkins.sh              # Jenkins setup script
```

---

## ✨ Özellikler

✅ **Tam Otomatik** - Push → Jenkins çalışır
✅ **Detaylı Raporlar** - Test sonuçları Jenkins'te
✅ **E2E Tests** - 3 API senaryosu
✅ **Docker Tabanlı** - Hiçbir local kurulum gerekmiyor
✅ **Kolay Setup** - Bir komutla başlatın

---

## 📞 Destek

Sorularınız için:
1. Jenkins logs'ları kontrol edin
2. GitHub Actions'dan esinlenebilirsiniz
3. Docker logs'ları kontrol edin

**Happy Testing! 🎉**
