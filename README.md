# Laboratório Digital da Água

## 1. Sobre o projeto

O Laboratório Digital da Água é um projeto desenvolvido em PHP para avaliar qualidade da água e simular a eficiência de um biofiltro.

O projeto interliga conhecimentos de programação, Química e Biologia, com foco no estudo da qualidade da água e o Objetivo de Desenvolvimento Sustentável 6 (ODS 6), que trata de água potável e saneamento.

## 2. Funcionalidades

- Calculadora de pH a partir da concentração de íons H+.
- Classificação de parâmetros de qualidade da água.
- Avaliação simplificada dos indicadores informados.
- Simulação da redução da turbidez após a aplicação de um biofiltro.
- Leitura de dados de relatórios da Embasa.
- Testes automatizados com PHPUnit.

## 3. Tecnologias utilizadas

- PHP 8.4 ou superior.
- HTML5.
- CSS3.
- PHPUnit.
- Composer.
- Git e GitHub.

## 4. Organização do projeto

- `src/`: classes responsáveis pelos cálculos e regras.
- `tests/`: testes automatizados.
- `dataset/`: conjunto de dados utilizado pelo projeto.
- `templates/css/`: estilos da interface.
- `index.php`: página principal.
- `phpunit.xml`: configuração do PHPUnit.
- `composer.json`: dependências do projeto.

## 5. Como executar

1. Instale o PHP 8.4 ou superior e o Composer.
2. Abra a pasta do projeto no terminal.
3. Instale as dependências:

   ```bash
   composer install
   ```

4. Inicie o servidor PHP:

   ```bash
   php -S localhost:3000
   ```

5. Acesse `http://localhost:3000` no navegador.

Se estiver utilizando o Laravel Herd, também é possível executar o projeto pelo ambiente local configurado.

## 6. Como executar os testes

No terminal, execute:

```bash
vendor/bin/phpunit
```

Para verificar a cobertura de código:

```bash
vendor/bin/phpunit --coverage-text
```

A cobertura depende das classes e dos caminhos executados pelos testes.

## 7. Cálculos utilizados

### Cálculo do pH

O pH é calculado a partir da concentração de íons hidrogênio:

**pH = -log10[H+]**

A concentração informada deve ser maior que zero.

### Simulação do biofiltro

A turbidez estimada após a filtragem é calculada por:

**Turbidez final = Turbidez inicial × (1 − eficiência / 100)**

A eficiência deve estar entre 0% e 100%.

## 8. Dados utilizados

O conjunto de dados está armazenado em `dataset/RelatorioEmbasa.csv` e foi obtido de um relatório da Embasa.

## 9. Limitações

A avaliação apresentada é com cunho educacional. A classificação depende dos parâmetros implementados no projeto e não substitui uma análise laboratorial nem uma declaração oficial de potabilidade, como da empresa Embasa.

## 10. Testes e validação

O projeto possui testes automatizados para verificar os cálculos, as classificações e o comportamento das classes em diferentes situações.

## 11. Autoria

Ana Clara Marques
Anna Clara Vieira

Projeto acadêmico desenvolvido para integrar conhecimentos de programação, Química e Biologia.
