pipeline {
    agent any

    environment {
        REPO_URL    = 'https://github.com/yashpawar1025/student_portfolio_system.git'
        DEPLOY_DIR  = 'C:\\jenkins-deploy'
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
                echo '🔨 Building project...'
                bat 'dir'
                echo '✅ Build complete!'
            }
        }

        stage('Test') {
            steps {
                echo '🧪 Testing project files...'
                bat 'if exist index.html (echo index.html found) else (exit 1)'
                echo '✅ Test passed!'
            }
        }

        stage('Deploy') {
            steps {
                echo '🚀 Deploying to local server...'

                // Clean deploy folder
                bat 'if exist C:\\jenkins-deploy rmdir /s /q C:\\jenkins-deploy'
                bat 'mkdir C:\\jenkins-deploy'

                // Copy all project files to deploy folder
                bat 'xcopy /s /e /y * C:\\jenkins-deploy\\'

                echo '✅ Files copied to C:\\jenkins-deploy'
                echo '🌐 Website deployed successfully!'
            }
        }
    }

    post {
        success {
            echo '✅ Pipeline completed! Website is updated!'
        }
        failure {
            echo '❌ Pipeline failed! Check Console Output.'
        }
    }
}
