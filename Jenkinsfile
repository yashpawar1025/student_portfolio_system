pipeline {
    agent any

    stages {
        stage('Deploy to XAMPP') {
            steps {
                echo '🚀 Deploying to XAMPP htdocs...'

                bat '''
                echo Creating project folder if not exists...
                if not exist C:\\xampp\\htdocs\\student_portfolio_system (
                    mkdir C:\\xampp\\htdocs\\student_portfolio_system
                )

                echo Copying latest files from Jenkins workspace...
                xcopy /E /I /Y %WORKSPACE%\\* C:\\xampp\\htdocs\\student_portfolio_system

                echo Deployment Done!
                '''
            }
        }
    }
}
