# 🚀 Jenkins CI/CD Setup - Hızlı Başlangıç

## ⚡ 3 Adımda Jenkins'i Başlatın

### 1️⃣ Jenkins'i Başlat
```bash
cd /Users/ugurcandogan/Desktop/adsız\ klasör\ 12/ytmOdevJenkisTest
./start.sh
```

Bu script:
✅ Jenkins'i Docker'da başlatır
✅ Pipeline job oluşturur (opsiyonel)
✅ GitHub webhook ayarlar (opsiyonel)

---

## 📋 Pipeline Aşamaları (1-6)

### ✅ **1. Checkout** (5 puan)
- GitHub'dan kodlar otomatik çekilir
- Repository clone edilir

### ✅ **2. Build** (5 puan)
- Composer bağımlılıkları yüklenir
- NPM bağımlılıkları yüklenir
- Frontend build edilir (Vite)

### ✅ **3. Unit Tests** (15 puan)
- Tests çalıştırılır: `tests/Unit/*.php`
- Sonuçlar JUnit raporu olarak kaydedilir
- Jenkins'te görüntülenir

### ✅ **4. Feature/Integration Tests** (15 puan)
- Tests çalıştırılır: `tests/Feature/*.php`
- Database ile test edilir
- JUnit raporu oluşturulur

### ✅ **5. Docker Containers** (5 puan)
- PHP-FPM container başlatılır
- MySQL database container başlatılır
- Health checks yapılır

### ✅ **6. E2E Test Senaryoları** (55 puan)
3 test senaryosu otomatik çalışır:
- **Test 1**: `/api/health` - API sağlık kontrolü
- **Test 2**: `/api/status` - Uygulama durumu
- **Test 3**: `/api/db-status` - Database bağlantısı

---

## 🌐 Jenkins'e Erişim

```
🔗 http://localhost:8080
```

**İlk Giriş:**
1. Console output'ta gösterilen password'u kopyalayın
2. Suggested plugins'i yükleyin
3. Admin kullanıcı oluşturun

---

## 🚀 Pipeline'ı Tetikle

### Otomatik (Push ile)
```bash
git push origin main
# Jenkins otomatik çalışacak!
```

### Manuel
Jenkins Dashboard → Job seçin → **Build Now**

---

## 📊 Sonuçları İzle

1. **Build Progress**: Console Output
2. **Test Results**: Sağ panelde Test Results
3. **Pipeline Stages**: Pipeline View
4. **Logs**: Build History'den

---

## 🐳 Docker Komutları

```bash
# Container durumu
docker-compose -f docker-compose.jenkins.yml ps

# Jenkins logları
docker-compose -f docker-compose.jenkins.yml logs jenkins -f

# Tümünü durdur
docker-compose -f docker-compose.jenkins.yml down
```

---

## ❓ İlk Kurulum Sonrası

1. **Jenkins Eklentileri Yükleyin**:
   - Manage Jenkins → Plugin Manager
   - Gerekli: Git, Pipeline, GitHub Integration
   - Optional: Blue Ocean, Email, Slack

2. **Credentials Ekleyin**:
   - Manage Jenkins → Manage Credentials
   - GitHub token ekleyin (webhook için)

3. **Job Oluşturun**:
   - New Item → Pipeline
   - SCM: Git
   - Repository: `https://github.com/ugurhss/jenkistestodev.git`
   - Script: `Jenkinsfile`

4. **Webhook Ayarlayın**:
   ```bash
   python3 setup-webhook.py
   ```

---

## 📁 Dosyalar

| Dosya | Açıklama |
|-------|----------|
| `Jenkinsfile` | Pipeline tanımı (7 aşama) |
| `docker-compose.jenkins.yml` | Jenkins Docker setup |
| `docker-compose.app.yml` | App + DB Docker setup |
| `routes/api.php` | E2E testler için API endpoints |
| `tests/Unit/` | Unit testleri |
| `tests/Feature/` | Feature testleri |
| `start.sh` | Hızlı başlangıç script'i |
| `setup-jenkins.sh` | Jenkins kurulum script'i |
| `setup-webhook.py` | GitHub webhook otomasyonu |
| `JENKINS-SETUP.md` | Detaylı dokümantasyon |

---

## 🎯 Hedefler

| Hedef | Puan | Durum |
|-------|------|-------|
| GitHub Checkout | 5 | ✅ |
| Build | 5 | ✅ |
| Unit Tests | 15 | ✅ |
| Feature Tests | 15 | ✅ |
| Docker Containers | 5 | ✅ |
| E2E Tests (3 senaryo) | 55 | ✅ |
| **TOPLAM** | **100** | ✅ |

---

## 🔒 Güvenlik

- Jenkins password Docker logs'ta gösterilir (kurulum sırasında)
- GitHub token sadece webhook setup'da kullanılır
- Jenkins home directory encrypted volume'de depolanır

---

## ✨ Özet

```
1. ./start.sh çalıştır
2. http://localhost:8080 aç
3. Jenkins setup'ı tamamla
4. Job oluştur / Webhook ayarla
5. Push yap - Jenkins otomatik çalışır!
```

---

**Happy Testing! 🎉**

Sorular? Bkz: [JENKINS-SETUP.md](JENKINS-SETUP.md)
