pipeline {
    agent any;

    environment {
        PHP_CONTAINER_NAME= 'eu.gcr.io/mythic-producer-212107/football-api-php'
        NGINX_CONTAINER_NAME= 'eu.gcr.io/mythic-producer-212107/football-api-web'
        MASTER_BRANCH_NAME='master'
        RELEASE_BRANCH_NAME='release'
        CONTAINER_VERSION="$BUILD_ID"
        K8S_TAG_RELEASE='release-*'
        DOCKER_TAG_NAME="$GIT_BRANCH-$BUILD_NUMBER"
    }


    stages {
        stage('Build') {
            steps {
                script {
                    sh 'composer clearcache'
                    sh 'composer install --no-interaction'
                    sh 'composer dump-autoload --classmap-authoritative'
                    sh 'touch .env'
                    sh 'composer generateSwagger'
                }
            }
        }

        stage('Test') {
            steps {
                script {
                    sh 'php ./vendor/bin/phpunit tests/Unit/'
                }
            }
        }

        stage('Build no dev') {
            steps {
                script {
                    sh 'rm -rf vendor/'
                    sh 'composer clearcache'
                    sh 'composer install --no-dev --no-interaction'
                    sh 'composer dump-autoload --classmap-authoritative'
                }
            }
        }

        stage('Dockerize') {
            when {
                anyOf {
                    branch "$MASTER_BRANCH_NAME";
                    branch "$RELEASE_BRANCH_NAME";
                    tag "$K8S_TAG_RELEASE";
                }
            }
            steps {
                script {
                    // Use Amazon registry
                    docker.withRegistry("https://eu.gcr.io",'gcr:mythic-producer-212107') {

                        //  Build and push PHP container
                        def phpImage = docker.build("${env.PHP_CONTAINER_NAME}:${DOCKER_TAG_NAME}", "-f Dockerfile.php .")
                        docker.build("${env.PHP_CONTAINER_NAME}:latest", "-f Dockerfile.php .")
                        phpImage.push()
                        phpImage.push('latest')

                        //  Build and push NGINX container
                        def nginxImage = docker.build("${env.NGINX_CONTAINER_NAME}:${DOCKER_TAG_NAME}", "-f Dockerfile.nginx .")
                        docker.build("${env.NGINX_CONTAINER_NAME}:latest", "-f Dockerfile.nginx .")
                        nginxImage.push()
                        nginxImage.push('latest')
                    }

                    // Clean up build node
                    sh "docker rmi ${env.PHP_CONTAINER_NAME}:${DOCKER_TAG_NAME}"
                    sh "docker rmi ${env.NGINX_CONTAINER_NAME}:${DOCKER_TAG_NAME}"
                    sh "docker image prune -f"
                }
            }
        }

        stage('Deploy integration') {
            when {
                branch "$MASTER_BRANCH_NAME"
            }
            steps {
                sh 'envsubst < ./deployment/k8s/integration/deployment-public.tmpl | kubectl apply -f -'
                sh 'envsubst < ./deployment/k8s/integration/deployment-queue.tmpl | kubectl apply -f -'
            }
        }

        stage('Deploy development') {
            when {
                branch "$RELEASE_BRANCH_NAME"
            }
            steps {
                sh 'envsubst < ./deployment/k8s/development/deployment-public.tmpl | kubectl apply -f -'
            }
        }

        stage('Deploy staging') {
            when {
                branch "$RELEASE_BRANCH_NAME"
            }
            steps {
                sh 'envsubst < ./deployment/k8s/staging/deployment-public.tmpl | kubectl apply -f -'
                sh 'envsubst < ./deployment/k8s/staging/deployment-queue.tmpl | kubectl apply -f -'
            }
        }

        stage('Deploy Sandbox') {
            when {
                tag "$K8S_TAG_RELEASE"
            }
            steps {
                sh 'envsubst < ./deployment/k8s/sandbox/deployment-public.tmpl | kubectl apply -f -'
                sh 'envsubst < ./deployment/k8s/sandbox/deployment-queue.tmpl | kubectl apply -f -'
            }
        }

        stage('Deploy Production') {
            when {
                tag "$K8S_TAG_RELEASE"
            }
            steps {
                sh 'envsubst < ./deployment/k8s/production/deployment-public.tmpl | kubectl apply -f -'
                sh 'envsubst < ./deployment/k8s/production/deployment-queue.tmpl | kubectl apply -f -'
            }
        }
    }
    post { 
        always { 
            cleanWs()
        }
    }
}
