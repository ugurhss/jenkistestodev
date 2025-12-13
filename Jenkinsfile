pipeline {
  agent any
  options { timestamps() }

  environment {
    CI_IMAGE     = "laravel-ci-image:latest"
    JENKINS_VOL  = "jenkins-laravel_jenkins_home"
    WS           = "/var/jenkins_home/workspace/laravel-ci"
  }

  stages {

    stage('Checkout') {
      steps {
        checkout scm
        sh 'pwd'
        sh 'ls -la'
        sh 'test -f composer.json && echo "✅ composer.json var" || (echo "❌ composer.json yok" && exit 1)'
        sh 'test -f docker-compose.app.yml && echo "✅ docker-compose.app.yml var" || (echo "❌ docker-compose.app.yml yok" && exit 1)'
        sh 'test -d ci && echo "✅ ci klasörü var" || (echo "❌ ci klasörü yok" && exit 1)'
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
              php artisan key:generate --force || true
              mkdir -p storage/test-results
              php artisan test --testsuite=Unit --log-junit storage/test-results/junit-unit.xml
            "
        '''
      }
      post {
        always {
          junit allowEmptyResults: true, testResults: 'storage/test-results/junit-unit.xml'
        }
      }
    }

    stage('Docker Up (App+DB)') {
      steps {
        sh '''
          docker-compose -f docker-compose.app.yml up -d

          echo "⏳ DB healthy bekleniyor..."
          for i in $(seq 1 30); do
            STATUS=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}}' laravel_db 2>/dev/null || true)
            echo "DB status: $STATUS"
            if [ "$STATUS" = "healthy" ]; then
              echo "✅ DB healthy"
              break
            fi
            sleep 5
          done

          echo "⏳ APP running + healthy bekleniyor..."
          for i in $(seq 1 30); do
            RUNNING=$(docker inspect -f '{{.State.Running}}' laravel_app 2>/dev/null || echo "false")
            HEALTH=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}}' laravel_app 2>/dev/null || echo "no")
            echo "APP running: $RUNNING | health: $HEALTH"
            if [ "$RUNNING" = "true" ] && { [ "$HEALTH" = "healthy" ] || [ "$HEALTH" = "no-healthcheck" ]; }; then
              echo "✅ APP ayakta"
              break
            fi
            sleep 5
          done

          echo "📌 docker ps"
          docker ps

          echo "📌 docker-compose ps"
          docker-compose -f docker-compose.app.yml ps

          # Eğer app ayakta değilse: log bas ve pipeline fail
          if ! docker ps --format '{{.Names}}' | grep -q '^laravel_app$'; then
            echo "❌ laravel_app ayakta değil. Loglar:"
            docker logs laravel_app --tail=200 || true
            exit 1
          fi

          echo "✅ Docker ortamı hazır"
        '''
      }
    }

    stage('Integration Tests (Feature + JUnit)') {
      steps {
        sh '''
          docker exec laravel_app sh -lc "
            mkdir -p storage/test-results &&
            php artisan test --testsuite=Feature --log-junit storage/test-results/junit-feature.xml
          "
        '''
      }
      post {
        always {
          junit allowEmptyResults: true, testResults: 'storage/test-results/junit-feature.xml'
        }
      }
    }

    stage('E2E Scenarios (3 HTTP checks)') {
      steps {
        sh '''
          echo "⏳ HTTP hazır mı?"
          for i in $(seq 1 30); do
            if docker exec laravel_app sh -lc "wget -qO- http://127.0.0.1:8000/ >/dev/null 2>&1"; then
              echo "✅ APP HTTP cevap veriyor"
              break
            fi
            echo "bekleniyor..."
            sleep 3
          done

          echo "✅ Senaryo 1: Home page 200"
          docker exec laravel_app sh -lc "wget -qO- http://127.0.0.1:8000/ >/dev/null"

          echo "✅ Senaryo 2: Login sayfası erişilebilir mi? (Breeze varsa /login)"
          docker exec laravel_app sh -lc "wget -qO- http://127.0.0.1:8000/login >/dev/null"

          echo "✅ Senaryo 3: Groups index (auth gerekebilir) - burada sadece route var mı kontrol edelim"
          # Eğer /groups auth istiyorsa 302 döner; wget yine de HTML alır.
          docker exec laravel_app sh -lc "wget -qO- http://127.0.0.1:8000/groups >/dev/null"
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
