# Rinha de backend

Implementação em PHP da [rinha de backend 2023 Q3](https://github.com/zanfranceschi/rinha-de-backend-2023-q3).

## Objetivo

Implementar a rinha em PHP usando o Laravel, e sem fazer nenuma adição de cache e batch insert.

## Implementações testadas

1. otimizar o autoloader
2. adicionar nginx e PHP-FPM em cada um dos apps
    - piorou
    - eu também tenho uma teoria do motivo da piora, apenas com o "Built-in server do PHP", que roda com apenas 1 de concorrência, o container já topa os 0.5% de CPU, então aumentar a concorrência não faz sentido se não tiver recursos
3. remover nginx e php-fpm

## Conclusões

Não sei se estou comentendo algum erro muito grande em PHP (nunca programei antes), mas a performance é MUITO pior que o Ruby, e nem se compara com Elixir ou Golang.

Além de que por algumas limitações do ORM do Laravel, algumas coisas bem pesadas tiveram de ser feitas para atingir todos os objetivos da rinha.

## Resultados

### Laptop

|CPU|RAM|
|---|---|
|Ryzen 4750U|16GB|

#### Duas instâncias (com nginx)

##### Resultado do gatling navegador

![resultado gatling navegador part 1](./images/laptop/two/gatling-browser-1.png)
![resultado gatling navegador part 2](./images/laptop/two/gatling-browser-2.png)

##### Resultado do gatling console

```
Simulation RinhaBackendSimulation completed in 239 seconds
Parsing log file(s)...
Parsing log file(s) done
Generating reports...

================================================================================
---- Global Information --------------------------------------------------------
> request count                                      70488 (OK=3323   KO=67165 )
> min response time                                      0 (OK=48     KO=0     )
> max response time                                  60001 (OK=56651  KO=60001 )
> mean response time                                   693 (OK=14508  KO=9     )
> std deviation                                       3573 (OK=7723   KO=732   )
> response time 50th percentile                          0 (OK=17180  KO=0     )
> response time 75th percentile                          1 (OK=17935  KO=1     )
> response time 95th percentile                          1 (OK=18955  KO=1     )
> response time 99th percentile                      18013 (OK=49826  KO=1     )
> mean requests/sec                                  293.7 (OK=13.846 KO=279.854)
---- Response Time Distribution ------------------------------------------------
> t < 800 ms                                           353 (  1%)
> 800 ms <= t < 1200 ms                                 16 (  0%)
> t >= 1200 ms                                        2954 (  4%)
> failed                                             67165 ( 95%)
---- Errors --------------------------------------------------------------------
> j.i.IOException: Premature close                                67155 (99.99%)
> Request timeout to localhost/127.0.0.1:9999 after 60000 ms         10 ( 0.01%)
================================================================================
A contagem de pessoas é: 2078
```

##### Recusos do docker durante a parte mais pesada do teste

![Recusos do docker durante a parte mais pesada do teste](./images/laptop/two/docker-stats.png)
