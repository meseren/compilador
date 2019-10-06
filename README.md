# Compiladores
## Ciência da Computação

## Parte 1: Analisador Léxico
- Extração e classificação dos tokens
- Eliminação de delimitadores e comentários
- Conversão Numérica *(usaremos somente números inteiros)*.
- ~~Converte todas as letras para maiúsculas~~
- Eliminar todos os caracteres desnecessários *(por ex. comentários e/ou espaços em branco)*
- ~~Numerar o arquivo para melhor procura de erros~~
- Analisar todos os caracteres especiais
- ~~Separar todas as palavras reservadas em símbolos (ex. IF = “Comando IF))~~

## Parte 2: Analisador Sintático

Considerando a gramática abaixo, elabore o projeto para realizar o reconhecimento sintático das possíveis cadeias produzidas pela gramática. Neste momento, ainda não realizaremos o tratamento dos erros. Caso uma cadeia não seja reconhecida, bastará emitir mensagem de “ERRO”.  O projeto poderá ser implementado usando uma das abordagens apresentadas em aula (ASD Preditiva Recursiva) ou (ASD Preditiva não recursiva). 

Gramática para reconhecimento de expressões aritméticas.

**- E  ->  TS**
**- T -> FG**
**- S -> + TS | - TS | λ**
**- G -> * FG | / FG | λ**
**-F ->  id |num | (E)**

*Obs. Para os tokens de entrada, permitir a leitura de um arquivo e/ou um string pelo teclado contendo a cadeia a ser analisada.
