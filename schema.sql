CREATE TABLE analises_agua (
    id INT AUTO_INCREMENT PRIMARY KEY,
    concentracao_h DOUBLE NOT NULL,
    ph DECIMAL(4,2) NOT NULL,
    turbidez DECIMAL(6,2) NOT NULL,
    temperatura DECIMAL(5,2) NOT NULL,
    cloro_residual DECIMAL(5,2) NOT NULL,
    dureza DECIMAL(7,2) NOT NULL,
    classificacao VARCHAR(100) NOT NULL,
);