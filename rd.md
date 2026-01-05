# Proje Kurulum ve Docker/Jenkins İş akışı

Bu rehberde Docker Compose ile Laravel uygulamasını ayağa kaldırma, Vite bundle üretme ve Jenkins’i yerel olarak çalıştırma için temel adımlar verilmiştir. Her komutu proje kökünde (`/Users/ugurcandogan/Desktop/ytmOdev kopyası/ytmOdevJenkisTest`) çalıştır.

## 1. Docker Compose servislerini başlat
1. (İstersen eski konteynerleri sil:)  
   ```bash
   docker compose -f docker-compose.app.yml down -v
   ```
2. Yeni imajı oluşturup `app` ile `db` servislerini ayağa kaldır:  
   ```bash
   docker compose -f docker-compose.app.yml up --build -d
   ```
3. Servis durumunu kontrol et:  
   ```bash
   docker compose -f docker-compose.app.yml ps
   ```

## 2. Frontend bağımlılıklarını ve Vite build’ini konteyner içinde çalıştır
1. Uygulama konteynerine girip Node paketlerini yükle ve üretim dosyalarını oluştur:  
   ```bash
   docker compose -f docker-compose.app.yml exec app npm install
   docker compose -f docker-compose.app.yml exec app npm run build
   ```
2. Laravel’in cache’lerini temizle (güncel konfigürasyon ve view’ler için):  
   ```bash
   docker compose -f docker-compose.app.yml exec app php artisan config:clear
   docker compose -f docker-compose.app.yml exec app php artisan route:clear
   docker compose -f docker-compose.app.yml exec app php artisan view:clear
   ```

## 3. Veritabanı ve key yönetimi
1. Migration’ları çalıştırarak oturum/sayfa tablolarını oluştur:  
   ```bash
   docker compose -f docker-compose.app.yml exec app php artisan migrate --force
   ```
2. Henüz ayarlanmadıysa uygulama anahtarını üret (bir kez yeterli):  
   ```bash
   docker compose -f docker-compose.app.yml exec app php artisan key:generate
   ```

## 4. Uygulamayı doğrula
1. `http://localhost:8000` adresini tarayıcıdan aç veya terminalden hızlıca kontrol et:  
   ```bash
   curl -I http://localhost:8000
   ```
2. Laravel loglarını takip etmek istersen:  
   ```bash
   docker compose -f docker-compose.app.yml logs -f app
   ```

## 5. Jenkins’i yerel makinede ayağa kaldırma (opsiyonel)
1. Jenkins Docker konteynerini başlat (kendi ortamına göre volume ayarlarını değiştirebilirsin):  
   ```bash
   docker run -d --name jenkins \
     -p 8080:8080 -p 50000:50000 \
     -v jenkins_home:/var/jenkins_home \
     -v /var/run/docker.sock:/var/run/docker.sock \
     jenkins/jenkins:lts
   ```
2. İlk kurulum şifresini oku:  
   ```bash
   docker exec jenkins cat /var/jenkins_home/secrets/initialAdminPassword
   ```
   (Genellikle `a918d80b805f49c38610b574b1493831` değerini verir.)
3. `http://localhost:8080` üzerinden Jenkins’e erişip temel kurulum adımlarını tamamla.
4. Yeni pipeline oluştururken bu repo URL’sini kullan ve `Jenkinsfile` içeriğini deploy adımı olarak kullan.

## 6. Kısa Komut Özeti
| Amaç | Komut |
| --- | --- |
| Servisleri başlat | `docker compose -f docker-compose.app.yml up --build -d` |
| Frontend build | `docker compose -f docker-compose.app.yml exec app npm run build` |
| Migrasyon | `docker compose -f docker-compose.app.yml exec app php artisan migrate --force` |
| Cache temizle | `docker compose -f docker-compose.app.yml exec app php artisan config:clear` |
| Jenkins şifresi | `docker exec jenkins cat /var/jenkins_home/secrets/initialAdminPassword` |

## 7. Jenkins pipeline için ön koşullar
1. Jenkins ajanı ya da konteynerinde `docker` CLI’nin kurulmuş ve yol üzerinde olduğundan emin ol; yoksa aşağıdaki gibi basit bir doğrulama çalıştır:  
   ```bash
   docker --version
   docker run --rm hello-world
   ```
   Eğer loglarda `docker: not found` veya `Cannot connect to the Docker daemon` görüyorsan, Jenkins profilindeki agent’a `/usr/bin/docker` çalıştırılabilirini bağla ve `/var/run/docker.sock` dosyasına erişim ver (örneğin `docker` daemon’un kurulu olduğu hosttan mount ederek).
2. Pipeline loglarında `Selected Git installation does not exist` mesajı alındıysa, Jenkins yönetici panelinde “Global Tool Configuration” altından Git kurulumu belirt ve pipeline’da o ismi seç (ya da `git` komutuna `PATH` üzerinden erişimi sağla).
3. Hem Docker CLI hem de Git hazır olduğunda pipeline’ı yeniden tetikleyerek adım adım: imaj oluşturma, composer/npm işlemleri, docker-compose başlatma ve testleri çalıştırma aşamalarının sorunsuz tamamlandığını doğrula.
Bu adımları takip ederek uygulamayı Docker ile çalıştırabilir, Jenkins pipeline’ında aynı komutları koşarak CI akışını yeniden oluşturarısın.
