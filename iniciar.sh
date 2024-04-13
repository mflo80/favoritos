#!/bin/bash
FILE=docker-compose.yml

favoritos_uno() {
	echo "------------------------------------------------------"
	echo "       GENERANDO KEY PARA FAVORITOS-BACKEND           "
	echo "------------------------------------------------------"
	docker exec -ti favoritosbackend php artisan key:generate
	docker exec -ti favoritosbackend php artisan migrate:fresh --seed
	echo "------------------------------------------------------"
	echo "------------------------------------------------------"
	echo "------------------------------------------------------"
	echo "    FINALIZADA CONFIGURACIÓN DE FAVORITOS-BACKEND     "
	echo "------------------------------------------------------"
	echo "------------------------------------------------------"
	echo "------------------------------------------------------"
}

favoritos_dos() {
	echo "------------------------------------------------------"
	echo "       GENERANDO KEY PARA FAVORITOS-FRONTEND          "
	echo "------------------------------------------------------"
	docker exec -ti favoritosfrontend php artisan key:generate
	echo "------------------------------------------------------"
	echo "------------------------------------------------------"
	echo "------------------------------------------------------"
	echo "    FINALIZADA CONFIGURACIÓN DE FAVORITOS-FRONTEND    "
	echo "------------------------------------------------------"
	echo "------------------------------------------------------"
	echo "------------------------------------------------------"
}

echo "------------------------------------------------------"
echo "             INICIANDO PROYECTO FAVORITOS             "
echo "------------------------------------------------------"

echo "------------------------------------------------------"
echo "        VERIFICANDO SI YA EXISTE RED FAVORITOS        "
echo "------------------------------------------------------"

if ping -c 1 -t 100 192.168.12.1; then
	echo La RED FAVORITOSNET ya se encuentra creada
else
	echo "------------------------------------------------------"
	echo "                CREANDO RED HOMENET                   "
	echo "------------------------------------------------------"
	docker network create --driver=bridge --subnet=192.168.12.0/24 --gateway=192.168.12.1 favoritosnet
fi

echo "------------------------------------------------------"
echo "               CREANDO DIRECTORIOS                    "
echo "------------------------------------------------------"

if [ ! -d favoritos-backend ]; then
	echo "------------------------------------------------------"
	echo "               CREANDO HOME-WEB                       "
	echo "------------------------------------------------------"
	composer create-project --prefer-dist laravel/laravel:^10.0 favoritos-backend
	cp -f .env .env-bkp
	wait
fi

if [ ! -d favoritos-frontend ]; then
	echo "------------------------------------------------------"
	echo "               CREANDO HOME-WEB                       "
	echo "------------------------------------------------------"
	composer create-project --prefer-dist laravel/laravel:^10.0 favoritos-frontend
	cp -f .env .env-bkp
	wait
fi

if [ -f "$FILE" ]; then
	echo "------------------------------------------------------"
	echo "                INICIANDO CONTENEDORES                "
	echo "------------------------------------------------------"
    	docker compose up -d
	wait
	if ping -c 1 -t 100 192.168.12.4; then
		echo "------------------------------------------------------"
		echo "          CONFIGURANDO FAVORITOS-BACKEND              "
		echo "------------------------------------------------------"
		echo Cambiando a directorio favoritos-backend
		cd favoritos-backend
		composer update
		echo Creando .env
		cp -f .env.example .env
		echo Ejecutando funciones
		favoritos_uno
		echo Cambiando a directorio raíz
		cd ..
	else
		echo ¡¡¡FAVORITOS-BACKEND NO SE ENCUENTRA ACTIVA!!!
	fi
	
	if ping -c 1 -t 100 192.168.12.5; then
		echo "------------------------------------------------------"
		echo "           CONFIGURANDO FAVORITOS-FRONTEND            "
		echo "------------------------------------------------------"
		echo Cambiando a directorio favoritos-frontend
		cd favoritos-frontend
		composer update
		echo Creando .env
		cp -f .env.example .env
		echo Ejecutando funciones
		favoritos_dos
		echo Cambiando a directorio raíz
		cd ..
	else
		echo ¡¡¡FAVORITOS-FRONTEND NO SE ENCUENTRA ACTIVA!!!
	fi
fi
echo
echo "------------------------------------------------------"
echo "------------------------------------------------------"
echo "       GRACIAS POR USAR PROYECTO FAVORITOS WEB        "
echo "------------------------------------------------------"
echo "------------------------------------------------------"
