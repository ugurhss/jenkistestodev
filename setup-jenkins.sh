#!/bin/bash

set -e

echo "🚀 Jenkins CI/CD Pipeline Setup Başlanıyor..."
echo ""

# Docker'ın çalışıp çalışmadığını kontrol et
echo "✅ Docker Kontrol Ediliyor..."
if ! command -v docker &> /dev/null; then
    echo "❌ Docker kurulu değil. Lütfen Docker'ı kurunuz."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose kurulu değil. Lütfen Docker Compose'u kurunuz."
    exit 1
fi

echo "✅ Docker ve Docker Compose kurulu"
echo ""

# Docker socket grup ID'sini al (macOS ve Linux uyumlu)
if [ -S /var/run/docker.sock ]; then
    DOCKER_GID=$(stat -f "%g" /var/run/docker.sock 2>/dev/null || stat -c "%g" /var/run/docker.sock)
    export DOCKER_GID
    echo "✅ Docker socket group id: ${DOCKER_GID}"
else
    echo "⚠️  /var/run/docker.sock bulunamadı; DOCKER_GID ayarlanmadı"
fi

# Jenkins container'ını başlat
echo "📦 Jenkins Container'ı Başlatılıyor..."
docker-compose -f docker-compose.jenkins.yml up -d

echo "⏳ Jenkins'in başlaması bekleniyor (60 saniye)..."
sleep 60

# Jenkins'in çalışıp çalışmadığını kontrol et
echo "✅ Jenkins Durumu Kontrol Ediliyor..."
if docker-compose -f docker-compose.jenkins.yml ps | grep -q "jenkins.*Up"; then
    echo "✅ Jenkins başarıyla başlatıldı!"
else
    echo "❌ Jenkins başlatılamadı"
    docker-compose -f docker-compose.jenkins.yml logs jenkins
    exit 1
fi

echo ""
echo "🔐 Jenkins Initial Password:"
echo "=================================================="
docker-compose -f docker-compose.jenkins.yml exec -T jenkins cat /var/jenkins_home/secrets/initialAdminPassword
echo "=================================================="
echo ""

echo "🌐 Jenkins URL: http://localhost:8080"
echo ""

echo "📝 Sonraki Adımlar:"
echo "1. http://localhost:8080 adresine gidiniz"
echo "2. Yukarıdaki password'u kopyalayıp yapıştırınız"
echo "3. Jenkins eklentilerini yükleyiniz (Install suggested plugins)"
echo "4. Admin kullanıcı oluşturunuz"
echo "5. New Item -> Pipeline seçerek job oluşturunuz"
echo "6. Pipeline script from SCM seçerek:"
echo "   - Repository URL: https://github.com/ugurhss/jenkistestodev.git"
echo "   - Branch: main"
echo "   - Script Path: Jenkinsfile"
echo "7. GitHub Settings'ten webhook ekleyiniz:"
echo "   - Payload URL: http://your-jenkins-domain/github-webhook/"
echo "   - Content type: application/json"
echo "   - Events: Push events"
echo ""

echo "✅ Setup Tamamlandı!"
