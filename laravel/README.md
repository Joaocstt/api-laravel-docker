<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


# Instruções para rodar o projeto com Docker

## Pré-requisitos 
Antes de rodar o projeto, você precisará ter o Docker instalado na sua máquina.

## Passos para rodar o projeto

**1** - Clone o repositório: git clone https://github.com/seu-usuario/seu-repositorio.git

**2** - Acesse o pasta laravel e clone .env.example do projeto: `` cp .env.example .env ``

**3** - Construir os containers: `` docker-compose up -d --build ``

**4** - Acesse o container do Laravel para instalar as dependências do Composer e Instalar as dependências do NPM (Vite):

- `` docker exec -it laravel-app /bin/bash ``


- `` composer install ``


- `` npm install ``

**5** - Ainda dentro do container do Laravel, gere a chave da aplicação com o seguinte comando:

- `` php artisan key:generate ``

**6** - Configure o banco de dados: 

`` php artisan migrate ``

- Caso não tenha criado o database.sqlite, confirme com "yes".

**7** - Configure o JWT

Para gerar a chave secreta do JWT, rode o comando abaixo:

`` php artisan jwt:secret ``

- Isso atualizará seu arquivo .env para algo como **JWT_SECRET=key**

**8** Configure o ambiente de teste

`` cp .env.testing.example .env.testing ``

Dentro do .env.testing, coloque:

```
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

JWT_SECRET=sua_key_gerada_jwt
```
Após isso, você pode rodar o comando: `` php artisan migrate --env=testing ``

**9** - Configuração de envio de Email


Você pode optar por usar o driver `log` ou o serviço do Mailtrap para testar o envio de e-mails.

### Usando o driver `log`:


- No arquivo `.env`, configure o envio de e-mail para `log`:
   ```env
   MAIL_MAILER=log


2. Todos os e-mails enviados pela aplicação serão registrados no arquivo de log localizado em:
storage/logs/laravel.log


caso queira utilizar o mailtrap, informe as credenciais SMTP obtidas pelo site.


Usando o Mailtrap:

1. Crie uma conta no Mailtrap.


2. Obtenha as credenciais do SMTP fornecidas pelo Mailtrap.


3. Configure o arquivo .env com as credenciais:

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_username_mailtrap
MAIL_PASSWORD=sua_senha_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@seuprojeto.com
MAIL_FROM_NAME="${APP_NAME}"






**9** - Saia do container e, acesse a raiz onde o arquivo docker-compose.yml está localizado, ajuste as permissões da pasta do projeto Laravel.

```
# sudo chown $USER:www-data -R laravel
# sudo chgrp -R www-data laravel/storage laravel/bootstrap/cache
# sudo chmod -R ug+rwx laravel/storage laravel/bootstrap/cache
# sudo chmod -R 777 laravel/database/database.sqlite 
```
**10** - Rodar a aplicação

- O servidor backend do Laravel estará disponível na porta 8080, e o servidor frontend do Vite estará disponível na porta 5173


- `` npm run dev ``


- Após acessar a página inicial da aplicação, estará listado as rotas disponíveis na API.
