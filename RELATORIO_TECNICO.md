# Relatório Técnico — Laboratório Digital da Água

## 1. Introdução

O Laboratório Digital da Água é uma aplicação desenvolvida em PHP, HTML e CSS para avaliar parâmetros da qualidade da água e simular a eficiência de um biofiltro. O projeto relaciona conhecimentos de Física, Química, Biologia e Programação, contribuindo para o estudo do ODS 6, relacionado à água potável e ao saneamento.

## 2. Funcionalidades

- Cálculo do pH pela concentração de íons hidrogênio.
- Classificação de parâmetros como pH, turbidez, cloro residual e dureza.
- Simulação da turbidez antes e depois da filtragem.
- Leitura de dados de um relatório da Embasa.
- Testes automatizados com PHPUnit.

## 3. Algoritmos utilizados

O pH é calculado pela fórmula:

\[
pH=-\log_{10}[H^+]
\]

A turbidez final estimada pelo biofiltro é calculada por:

\[
T_f=T_i\left(1-\frac{E}{100}\right)
\]

Em que \(T_i\) é a turbidez inicial e \(E\) é a eficiência de remoção em porcentagem.

Os limites utilizados na classificação são simplificados e não substituem uma análise completa de potabilidade.

## 4. Dados e testes

Os dados utilizados estão em `dataset/RelatorioEmbasa.csv` e foram obtidos de um relatório da Embasa. A referência completa do documento original deve ser informada para permitir a verificação da fonte.

Na última execução registrada, o PHPUnit apresentou **41 testes e 49 verificações aprovados**, com aproximadamente **96,93% de cobertura de linhas**.

## 5. Conclusão

O projeto demonstra a aplicação de algoritmos na avaliação educacional da qualidade da água. A simulação do biofiltro permite compreender a redução estimada da turbidez, enquanto os testes automatizados verificam o funcionamento das principais operações.

A aplicação é educativa e não comprova, sozinha, que uma amostra de água é potável.

## 6. Referências

- EMBASA — relatório utilizado como fonte dos dados. Completar com título, ano e link original.
- Ministério da Saúde — [Portaria GM/MS nº 888/2021](https://bvsms.saude.gov.br/bvs/saudelegis/gm/2021/prt0888_24_05_2021_rep.html).
- ONU — [Objetivo de Desenvolvimento Sustentável 6](https://sdgs.un.org/goals/goal6).
