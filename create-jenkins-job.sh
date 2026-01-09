#!/bin/bash

# Jenkins Pipeline Job Otomatik Oluşturma Scripti

set -e

JENKINS_URL="http://localhost:8080"
JENKINS_USER="admin"
REPO_URL="https://github.com/ugurhss/jenkistestodev.git"
JOB_NAME="laravel-ci"

echo "🚀 Jenkins Pipeline Job Oluşturuluyor..."
echo ""

# Jenkins username ve password iste
read -p "Jenkins username'i girin (varsayılan: admin): " JENKINS_USER
JENKINS_USER=${JENKINS_USER:-admin}

read -sp "Jenkins password'u girin: " JENKINS_PASS
echo ""

echo "⏳ Job oluşturuluyor..."

# Job XML template
JOB_XML=$(cat <<'EOF'
<?xml version='1.1' encoding='UTF-8'?>
<org.jenkinsci.plugins.workflow.job.WorkflowJob plugin="workflow-job@1298.v38be8eb_c2c26">
  <actions/>
  <description>Laravel CI/CD Pipeline - Otomatik build, test ve deploy</description>
  <keepDependencies>false</keepDependencies>
  <properties>
    <com.coralogix.jenkins.github_integration.GithubIntegrationProperty plugin="github-integration@1.36.1">
      <githubToken>
        <secret>${GITHUB_TOKEN}</secret>
      </githubToken>
      <githubUrl>https://github.com/ugurhss/jenkistestodev</githubUrl>
    </com.coralogix.jenkins.github_integration.GithubIntegrationProperty>
  </properties>
  <definition class="org.jenkinsci.plugins.workflow.cps.CpsScmFlowDefinition" plugin="workflow-cps@2975.v7b_fe46b_9266e">
    <scm class="hudson.plugins.git.GitSCM" plugin="git@5.2.0">
      <configVersion>2</configVersion>
      <userRemoteConfigs>
        <hudson.plugins.git.UserRemoteConfig>
          <url>REPO_URL_PLACEHOLDER</url>
        </hudson.plugins.git.UserRemoteConfig>
      </userRemoteConfigs>
      <branches>
        <hudson.plugins.git.BranchSpec>
          <name>*/main</name>
        </hudson.plugins.git.BranchSpec>
      </branches>
      <doGenerateSubmoduleConfigurations>false</doGenerateSubmoduleConfigurations>
      <submoduleCfg class="java.util.ArrayList"/>
      <extensions/>
    </scm>
    <scriptPath>Jenkinsfile</scriptPath>
    <lightweight>true</lightweight>
  </definition>
  <triggers>
    <com.cloudbees.jenkins.GitHubPushTrigger plugin="github@1.37.3">
      <spec></spec>
    </com.cloudbees.jenkins.GitHubPushTrigger>
  </triggers>
  <disabled>false</disabled>
</org.jenkinsci.plugins.workflow.job.WorkflowJob>
EOF
)

# REPO_URL'yi değiştir
JOB_XML="${JOB_XML//REPO_URL_PLACEHOLDER/$REPO_URL}"

# Jenkins API'ye job gönder
RESPONSE=$(curl -s -w "\n%{http_code}" \
  -u "$JENKINS_USER:$JENKINS_PASS" \
  -X POST \
  -H "Content-Type: application/xml" \
  -d "$JOB_XML" \
  "$JENKINS_URL/createItem?name=$JOB_NAME")

HTTP_CODE=$(echo "$RESPONSE" | tail -n 1)
RESPONSE_BODY=$(echo "$RESPONSE" | head -n -1)

if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "201" ]; then
    echo "✅ Job başarıyla oluşturuldu!"
    echo ""
    echo "🔗 Job URL: $JENKINS_URL/job/$JOB_NAME/"
    echo ""
    echo "📝 Sonraki adımlar:"
    echo "1. Jenkins'te job konfigürasyonunu açın"
    echo "2. Gerekli ayarları yapın"
    echo "3. Build Now'a tıklayarak test edin"
else
    echo "❌ Job oluşturulamadı (HTTP $HTTP_CODE)"
    echo "Response: $RESPONSE_BODY"
    echo ""
    echo "⚠️  Manuel olarak Jenkins UI'da job oluşturun:"
    echo "1. New Item → Pipeline"
    echo "2. Pipeline script from SCM"
    echo "3. Git repository: $REPO_URL"
    echo "4. Branch: main"
    echo "5. Script Path: Jenkinsfile"
fi
