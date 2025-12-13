pipeline {
  agent any
  options { timestamps() }

  environment {
    CI_IMAGE    = "laravel-ci-image:latest"
    JENKINS_VOL = "jenkins-laravel_jenkins_home"
    WS          = "/var/jenkins_home/workspace/laravel-ci"
  }

  stages {

    stage('Checkout') {
      steps {
        checkout scm
        sh 'pwd'
        sh 'ls -la'
        sh 'test -f docker-compose.app.yml && echo "✅ docker-compose.app.yml var" || (echo "❌ yok" && exit 1)'
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
            STATUS=$(docker inspect -f '{{.State.Health.Status}}' laravel_db 2>/dev/null || true)
            echo "DB status: $STATUS"
            [ "$STATUS" = "healthy" ] && break
            sleep 5
          done

          echo "⏳ APP healthy bekleniyor..."
          for i in $(seq 1 60); do
            RUNNING=$(docker inspect -f '{{.State.Running}}' laravel_app 2>/dev/null || echo false)
            HEALTH=$(docker inspect -f '{{.State.Health.Status}}' laravel_app 2>/dev/null || echo starting)
            echo "APP running=$RUNNING | health=$HEALTH ($i/60)"

            if [ "$RUNNING" = "true" ] && [ "$HEALTH" = "healthy" ]; then
              echo "✅ APP healthy"
              break
            fi

            if [ "$i" -eq 15 ]; then
              echo "📌 laravel_app logs (tail 80)"
              docker logs laravel_app --tail=80 || true
            fi

            sleep 5
          done

          FINAL=$(docker inspect -f '{{.State.Health.Status}}' laravel_app 2>/dev/null || echo fail)
          [ "$FINAL" = "healthy" ] || exit 1

          echo "✅ Docker ortamı hazır"
        '''
      }
    }

    stage('Integration Tests (Feature + JUnit)') {
      steps {
        sh '''
          docker exec laravel_app sh -lc "
            cd /var/jenkins_home/workspace/laravel-ci
            mkdir -p storage/test-results
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
          docker exec laravel_app sh -lc "wget -qO- http://127.0.0.1:8000/ >/dev/null"
          docker exec laravel_app sh -lc "wget -qO- http://127.0.0.1:8000/login >/dev/null"
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
