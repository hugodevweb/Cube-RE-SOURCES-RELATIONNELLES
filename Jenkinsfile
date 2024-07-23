pipeline {
    agent any
    stages {
        stage('PHP Version') {
            steps {
                script {
                    sh 'php -v'
                    echo 'PHP Version étape OK'
                }
            }
        }
        stage('Preparation') {
            steps {
                script {
                    sh 'pwd'
                    sh 'composer install'
                    echo 'Everything is installed. Ready to start !'
                }
            }
        }
        stage('Initialization') {
            steps {
                script {
                    sh 'pwd'
                    sh 'php bin/console doctrine:migrations:status'
                    echo 'Database status: OK'
                }
            }
        }
        stage('Test') {
            steps {
                script {
                    sh 'php bin/phpunit'
                    echo 'Test étape OK'
                }
            }
        }
        stage('END') {
            steps {
                script {

                    echo 'Finished'
                }
            }
        }
    }
}