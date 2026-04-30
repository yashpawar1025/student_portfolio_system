pipeline {
    agent any

    stages {
        stage('Deploy') {
            steps {
                echo '🚀 Deploying to local server...'

                bat '''
                echo Closing any process using folder...
                
                taskkill /f /im httpd.exe 2>nul
                taskkill /f /im chrome.exe 2>nul
                
                timeout /t 2
                
                if exist C:\\jenkins-deploy (
                    rmdir /s /q C:\\jenkins-deploy || echo Folder in use, retrying...
                )
                
                mkdir C:\\jenkins-deploy
                xcopy /E /I /Y * C:\\jenkins-deploy
                
                echo Deployment completed!
                '''
            }
        }
    }
}
