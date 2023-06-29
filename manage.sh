#!/bin/bash

export USER_ID=$(id -u)
export HOME_DIR=$HOME/docker-home
export LOCAL_DOCKER_HOME=$HOME_DIR
export USER_DIR=$USER
export DEFAULT_CONTAINER=php
export COMMAND="${*:3}"

if [ ! -d "$HOME_DIR" ]; then
	mkdir $HOME_DIR
fi


start() 
{
	docker-compose -f docker-compose-local.yaml pull
	docker-compose -f docker-compose-local.yaml up --remove-orphans -d 
}

stop()
{
	docker-compose -f docker-compose-local.yaml down
}

list()
{
	docker-compose -f docker-compose-local.yaml ps
}

console()
{
        COMMAND="${*:2}"
	if [ ! -z "$1" ]; then
	   if [ ! -z "$COMMAND" ]; then
		docker-compose -f docker-compose-local.yaml exec $1 $COMMAND
	   else
		docker-compose -f docker-compose-local.yaml exec $1 bash
	   fi

   	   exit
	fi

	docker-compose -f docker-compose-local.yaml exec ${DEFAULT_CONTAINER} bash
}

usage()
{
	printf "Usage: manage.sh OPTION"
	printf "\n\t start - run docker containers"
	printf "\n\t stop  - stop docker containers"
	printf "\n\t -l|--list  - list containers"
	printf "\n\t -e|exec [CONTAINER_NAME] - exec into the container - defautlt : ${DEFAULT_CONTAINER}"
	printf "\n\t -h | --help  - this message \n"
}

case $1 in
        start )           	shift
                                start
								exit
                                ;;
        stop )   			 	stop
								exit
                                ;;
		-l | --list )   		list
								exit
                                ;;
		-e | exec )   		console $2 $COMMAND
								exit
                                ;;
        -h | --help )           usage
                                exit
                                ;;
        * )                     usage
                                exit 1
    esac
    shift
