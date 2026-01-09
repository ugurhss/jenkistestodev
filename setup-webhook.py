#!/usr/bin/env python3

"""
GitHub Webhook Configurator for Jenkins
Jenkins otomatik trigger ayarı yapar
"""

import json
import requests
import sys
from urllib.parse import urljoin

def setup_github_webhook(token, repo_owner, repo_name, jenkins_url, webhook_path="/github-webhook/"):
    """
    GitHub repository'ye webhook ekler
    """
    headers = {
        "Accept": "application/vnd.github+json",
        "Authorization": f"token {token}",
        "X-GitHub-Api-Version": "2022-11-28"
    }

    webhook_data = {
        "name": "web",
        "active": True,
        "events": ["push", "pull_request"],
        "config": {
            "url": urljoin(jenkins_url, webhook_path),
            "content_type": "json",
            "insecure_ssl": "0"
        }
    }

    url = f"https://api.github.com/repos/{repo_owner}/{repo_name}/hooks"

    try:
        response = requests.post(url, headers=headers, json=webhook_data, timeout=10)

        if response.status_code == 201:
            print("✅ GitHub webhook başarıyla oluşturuldu!")
            print(f"   Repo: {repo_owner}/{repo_name}")
            print(f"   Jenkins URL: {jenkins_url}")
            return True
        elif response.status_code == 422:
            # Webhook zaten var olabilir
            print("⚠️  Webhook zaten var veya hata oluştu")
            print(f"   Response: {response.json()}")
            return False
        else:
            print(f"❌ Hata: {response.status_code}")
            print(f"   {response.text}")
            return False

    except Exception as e:
        print(f"❌ Bağlantı hatası: {str(e)}")
        return False

def main():
    print("🔗 GitHub Webhook Configuration")
    print("=" * 50)
    print("")

    # User input
    token = input("GitHub Personal Access Token girin: ").strip()
    repo_owner = input("Repository sahibi (örn: ugurhss): ").strip()
    repo_name = input("Repository adı (örn: jenkistestodev): ").strip()
    jenkins_url = input("Jenkins URL (örn: http://localhost:8080): ").strip()

    if not all([token, repo_owner, repo_name, jenkins_url]):
        print("❌ Tüm alanlar zorunludur!")
        sys.exit(1)

    print("")
    print("⏳ Webhook eklemesi yapılıyor...")

    success = setup_github_webhook(token, repo_owner, repo_name, jenkins_url)

    if success:
        print("")
        print("✅ Setup tamamlandı!")
        print("")
        print("🚀 Sonraki adımlar:")
        print("1. Jenkins'te Pipeline Job oluşturun")
        print("2. Pipeline script from SCM seçin")
        print("3. GitHub repository'yi bağlayın")
        print("4. Bir push yapın - Jenkins otomatik çalışacak!")
    else:
        print("")
        print("⚠️  Webhook setup başarısız oldu")
        print("Manuel olarak GitHub Settings > Webhooks'dan ekleyebilirsiniz")

if __name__ == "__main__":
    main()
