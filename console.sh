# Detengo todos los contenedores
docker stop $(docker ps -aq)

# Elimino todos los conternedores
docker rm $(docker ps -aq)  

# Elimino los volumenes
docker volume prune     

# Elimino los volumenes del sistema
docker system prune --all --volumes --force