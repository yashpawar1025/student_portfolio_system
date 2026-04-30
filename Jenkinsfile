pipeline {
    agent any

    stages {
        stage('Deploy') {
            steps {
                echo '🚀 Deploying to XAMPP...'

                bat '''
                echo Stopping Apache...
                taskkill /f /im httpd.exe 2>nul

                timeout /t 2

                echo Removing old files...
                rmdir /s /q C:\\xampp\\htdocs\\student_portfolio_system 2>nul

                echo Copying new files...
                xcopy /E /I /Y %WORKSPACE%\\* C:\\xampp\\htdocs\\student_portfolio_system

                echo Starting Apache...
                start "" "C:\\xampp\\apache\\bin\\httpd.exe"

                echo Deployment completed!
                '''
            }
        }
    }
}
