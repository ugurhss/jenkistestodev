pipeline {
  agent any
  options { timestamps() }

  environment {
    CI_IMAGE    = "laravel-ci-image:latest"
    JENKINS_VOL = "jenkins-laravel_jenkins_home"
    WS          = "/var/jenkins_home/workspace/laravel-ci"
    APP_URL     = "http://localhost:8000"
  }

  stages {
    stage('Checkout') {
      steps {
        checkout scm
        sh 'pwd'
        sh 'ls -la'
        sh 'test -f composer.json && echo "✅ composer.json var" || (echo "❌ composer.json yok" && exit 1)'
        sh 'test -f docker-compose.app.yml && echo "✅ docker-compose.app.yml var" || (echo "❌ docker-compose.app.yml yok" && exit 1)'
      }
    }

    stage('Build CI Image') {
      steps {
        sh 'docker build -t ${CI_IMAGE} -f ci/Dockerfile.ci .'
      }
    }

    stage('Composer Install') {
      steps {
        sh '''
          docker run --rm \
            -v ${JENKINS_VOL}:/var/jenkins_home \
            -w ${WS} \
            ${CI_IMAGE} \
            composer install --no-interaction --prefer-dist
        '''
      }
    }

    stage('NPM Install & Build') {
      steps {
        sh '''
          docker run --rm \
            -v ${JENKINS_VOL}:/var/jenkins_home \
            -w ${WS} \
            node:20-alpine \
            sh -lc "npm ci && npm run build"
        '''
      }
    }

    stage('Unit Tests (JUnit)') {
      steps {
        sh '''
          docker run --rm \
            -v ${JENKINS_VOL}:/var/jenkins_home \
            -w ${WS} \
            ${CI_IMAGE} \
            sh -lc "
              cp -n .env.example .env || true
              php artisan key:generate --force
              mkdir -p storage/test-results
              php artisan test --testsuite=Unit --log-junit storage/test-results/junit-unit.xml
            "
        '''
      }
      post {
        always {
          junit 'storage/test-results/junit-unit.xml'
        }
      }
    }

    stage('Docker Up (App+DB)') {
      steps {
        sh '''
          # App+DB ayağa kaldır
          docker-compose -f docker-compose.app.yml up -d

          echo "⏳ DB healthcheck bekleniyor..."
          # db container healthy olana kadar bekle (max ~150sn)
          for i in $(seq 1 30); do
            STATUS=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}}' laravel_db 2>/dev/null || true)
            echo "DB status: $STATUS"
            if [ "$STATUS" = "healthy" ]; then
              echo "✅ DB healthy"
              break
            fi
            sleep 5
          done

          echo "⏳ APP ayağa kalkması için kısa bekleme..."
          sleep 5

          docker ps
        '''
      }
    }

    stage('Integration Tests (Feature + JUnit)') {
      steps {
        sh '''
          # Feature testleri app container içinde koşuyoruz
          docker exec laravel_app sh -lc "
            mkdir -p storage/test-results &&
            php artisan test --testsuite=Feature --log-junit storage/test-results/junit-feature.xml
          "
        '''
      }
      post {
        always {
          junit 'storage/test-results/junit-feature.xml'
        }
      }
    }

    stage('E2E Scenarios (3 HTTP checks)') {
      steps {
        sh '''
          echo "🌐 APP_URL: ${APP_URL}"

          echo "1) Ana sayfa erişim (200/302 kabul)"
          curl -s -o /dev/null -w "%{http_code}" "${APP_URL}/" | grep -E "200|302"

          echo "2) Login sayfası erişim (200/302 kabul)"
          curl -s -o /dev/null -w "%{http_code}" "${APP_URL}/login" | grep -E "200|302"

          echo "3) Groups index (auth yoksa 302 olur, bazen 200/401/403 olabilir)"
          curl -s -o /dev/null -w "%{http_code}" "${APP_URL}/groups" | grep -E "200|302|401|403"
        '''
      }
    }
  }

  post {
    always {
      sh 'docker-compose -f docker-compose.app.yml down -v || true'
    }
  }
}
