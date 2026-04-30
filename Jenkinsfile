pipeline {
    agent any

    stages {
        stage('Deploy to XAMPP') {
            steps {
                echo '🚀 Deploying to XAMPP htdocs...'

                bat '''
                echo Creating folder if not exists...
                if not exist "C:\\xampp\\htdocs\\student portfolio" (
                    mkdir "C:\\xampp\\htdocs\\student portfolio"
                )

                echo Copying files...
                xcopy /E /I /Y "%WORKSPACE%\\*" "C:\\xampp\\htdocs\\student portfolio"

                echo Deployment Done!
                '''
            }
        }
    }
}
