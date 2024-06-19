pipeline {
    agent any

    environment {
        // Chemin vers PHP et Composer, ajustez selon votre configuration
        PHP_PATH = '/usr/local/bin/php'
        COMPOSER_PATH = '/usr/local/bin/composer'
        // Chemin vers Symfony, ajustez selon votre configuration
        SYMFONY_PATH = '/usr/local/bin/symfony'
    }

    stages {
        stage('Checkout') {
            steps {
                script {
                    // Vérifiez le code source depuis le dépôt Git
                    checkout scm
                }
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    // Installez les dépendances via Composer
                    sh "${COMPOSER_PATH} install --no-interaction --prefer-dist"
                }
            }
        }

        stage('Lint Code') {
            steps {
                script {
                    // Lint le code PHP pour vérifier les erreurs de syntaxe
                    sh "${PHP_PATH} bin/console lint:twig templates"
                    sh "${PHP_PATH} bin/console lint:yaml config"
                    sh "${PHP_PATH} bin/console lint:container"
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    // Exécutez les tests PHPUnit
                    sh "${PHP_PATH} bin/phpunit"
                }
            }
        }

        stage('Build and Package') {
            steps {
                script {
                    // Commandes pour construire l'application, par exemple pour créer un package ou un artefact
                    sh 'tar czf my-symfony-app.tar.gz .'
                }
            }
        }

        stage('Deploy to Staging') {
            when {
                branch 'main' // Déployez seulement si c'est la branche principale
            }
            steps {
                script {
                    // Commandes pour déployer l'application sur l'environnement de staging
                    sh 'scp my-symfony-app.tar.gz user@staging-server:/path/to/deploy/'
                }
            }
        }

        stage('Deploy to Production') {
            when {
                branch 'production' // Déployez seulement si c'est la branche de production
            }
            steps {
                script {
                    // Commandes pour déployer l'application sur l'environnement de production
                    sh 'scp my-symfony-app.tar.gz user@production-server:/path/to/deploy/'
                }
            }
        }
    }

    post {
        always {
            // Action toujours exécutée, que la build soit réussie ou échouée
            cleanWs() // Nettoie l'espace de travail Jenkins
        }
        success {
            // Actions à exécuter en cas de succès, comme envoyer une notification
            echo 'Build succeeded!'
        }
        failure {
            // Actions à exécuter en cas d'échec, comme envoyer une alerte
            echo 'Build failed!'
        }
    }
}
