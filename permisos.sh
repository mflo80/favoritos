#!/bin/bash
echo
echo "------------------------------------------------------"
echo "------------------------------------------------------"
echo "                ACTUALIZANDO PERMISOS                 "
echo "------------------------------------------------------"
echo "------------------------------------------------------"
sudo chown -R www-data.mflorio-adm favoritos-frontend &&
sudo chown -R www-data.mflorio-adm favoritos-backend &&
cd favoritos-frontend &&
sudo find . -type d -exec chmod 775 {} \; &&
sudo find . -type f -exec chmod 664 {} \; &&
cd .. &&
cd favoritos-backend &&
sudo find . -type d -exec chmod 775 {} \; &&
sudo find . -type f -exec chmod 664 {} \; &&
echo "------------------------------------------------------"
echo "------------------------------------------------------"
echo "                     FINALIZADO                       "
echo "------------------------------------------------------"
echo "------------------------------------------------------"