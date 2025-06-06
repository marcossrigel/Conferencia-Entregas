FROM php:8.2-cli

# Instala extensões que você precisar, como MySQL
RUN docker-php-ext-install mysqli

# Copia os arquivos da sua aplicação para dentro da imagem
COPY . /app
WORKDIR /app

# Expõe a porta usada pelo Render
EXPOSE 10000

# Comando para rodar o servidor embutido do PHP
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
