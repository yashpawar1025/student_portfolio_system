pipeline {
    agent any

    environment {
        REPO_URL = 'https://github.com/yashpawar1025/student_portfolio_system.git'
    }

    stages {

        stage('Clone Repository') {
            steps {
                echo '🔄 Cloning student_portfolio_system...'
                git branch: 'main',
                    credentialsId: 'github-creds',
                    url: 'https://github.com/yashpawar1025/student_portfolio_system.git'
                echo '✅ Repository cloned successfully!'
            }
        }

        stage('Build') {
            steps {
                echo '🔨 Building Student Portfolio System...'
                echo 'Checking project files...'
                bat 'dir'
                echo '✅ Build stage complete!'
            }
        }

        stage('Test') {
            steps {
                echo '🧪 Running Tests...'
                bat 'dir index.html'
                echo '✅ Test stage complete!'
            }
        }

        stage('Deploy') {
            steps {
                echo '🚀 Deploying Student Portfolio System...'
                echo '✅ Deployment complete!'
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline completed successfully for student_portfolio_system!'
        }
        failure {
            echo '❌ Pipeline failed! Check Console Output for errors.'
        }
    }
}
