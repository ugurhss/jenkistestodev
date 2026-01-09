#!/bin/bash

# Jenkins CI/CD Pipeline - Hızlı Başlangıç

echo "════════════════════════════════════════════════"
echo "   🚀 Jenkins CI/CD Pipeline - Hızlı Başlangıç"
echo "════════════════════════════════════════════════"
echo ""

echo "📋 Bu script şunları yapar:"
echo "1. Jenkins Docker container'ını başlatır"
echo "2. Pipeline job oluşturur"
echo "3. GitHub webhook'u ayarlar"
echo ""

# Step 1: Jenkins'i Başlat
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "ADIM 1/3: Jenkins'i Docker'da Başlatılıyor..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if ./setup-jenkins.sh; then
    echo "✅ Jenkins başarıyla başlatıldı!"
else
    echo "❌ Jenkins başlatılamadı"
    exit 1
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "ADIM 2/3: Jenkins'te Pipeline Job Oluşturuluyor..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

read -p "Pipeline job'u otomatik oluşturmak istiyor musunuz? (Y/n): " choice
if [[ $choice == "Y" || $choice == "y" || -z $choice ]]; then
    chmod +x create-jenkins-job.sh
    ./create-jenkins-job.sh
else
    echo "⚠️  Manuel olarak Jenkins UI'dan job oluşturun:"
    echo "   1. New Item → Pipeline"
    echo "   2. Pipeline script from SCM seçin"
    echo "   3. Repository: https://github.com/ugurhss/jenkistestodev.git"
    echo "   4. Branch: main"
    echo "   5. Script Path: Jenkinsfile"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "ADIM 3/3: GitHub Webhook Ayarlanıyor (Opsiyonel)..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

read -p "GitHub webhook'u otomatik ayarlamak istiyor musunuz? (y/N): " choice
if [[ $choice == "Y" || $choice == "y" ]]; then
    chmod +x setup-webhook.py
    python3 setup-webhook.py
else
    echo "⚠️  Manuel olarak GitHub Settings → Webhooks'dan ekleyebilirsiniz"
fi

echo ""
echo "════════════════════════════════════════════════"
echo "✅ SETUP TAMAMLANDI!"
echo "════════════════════════════════════════════════"
echo ""

echo "🌐 Jenkins URL: http://localhost:8080"
echo ""

echo "📝 Sonraki Adımlar:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "1. Jenkins'e Gidin:"
echo "   📱 http://localhost:8080"
echo ""
echo "2. Test Edin:"
echo "   Bir push yapın veya Jenkins'te 'Build Now' tıklayın"
echo ""
echo "3. Sonuçları İzleyin:"
echo "   - Console Output: Build progress"
echo "   - Test Results: Unit ve Feature test raporu"
echo "   - Build History: Geçmiş build'ler"
echo ""
echo "4. Docker Komutları:"
echo "   - Durumu kontrol et: docker-compose -f docker-compose.jenkins.yml ps"
echo "   - Logları göster: docker-compose -f docker-compose.jenkins.yml logs jenkins -f"
echo "   - Durdur: docker-compose -f docker-compose.jenkins.yml down"
echo ""

echo "🎉 Pipeline aşamaları:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ 1. Checkout (5 puan) - GitHub'dan kod çekme"
echo "✅ 2. Build (5 puan) - Composer + NPM build"
echo "✅ 3. Unit Tests (15 puan) - Unit test raporu"
echo "✅ 4. Feature Tests (15 puan) - Integration test raporu"
echo "✅ 5. Docker Up (5 puan) - Container başlatma"
echo "✅ 6. E2E Tests (55 puan) - 3 API test senaryosu"
echo ""

echo "❓ Sorun mu yaşadınız?"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "JENKINS-SETUP.md dosyasını okuyunuz"
echo ""

echo "🚀 Hazırsınız! Happy Coding! 🎉"
