[Documentação](https://abdalazard-api.com/)


* Como utilizar os endpoints:
    Copie e cole a URL da documentação e acrescente o prefixo "/api" no fim seguido pela rota, por exemplo: https://abdalazard-api.com/api/ + rota ). Lembre-se de utilizar o verbo HTTP correto, de acordo com a documentação.

- Necessário criar arquivo .env.

- Caso queira usar os testes unitários, deve-se criar o .env e replicar o banco de dados vazio.

- Execute o comando "php artisan key:generate".

- Execute o comando "php artisan migrate" para importar o banco de dados.
